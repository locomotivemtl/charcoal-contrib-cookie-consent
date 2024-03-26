<?php

namespace Charcoal\CookieConsent;

use Charcoal\CookieConsent\Exception\InvalidArgumentException;
use Charcoal\CookieConsent\Model;
use Charcoal\CookieConsent\Model\Repository\DisclosureRepository;
use Charcoal\CookieConsent\Config\PluginConfig;
use Charcoal\Model\ModelInterface;
use Charcoal\Property\ModelStructureProperty;
use Charcoal\Translator\Translator;

/**
 * Cookie Consent Manager
 *
 * Generates the vanilla-cookieconsent configuration options.
 *
 * @psalm-import-type CookieCategoryAutoClear from PluginConfig
 * @psalm-import-type CookieTableRow from PluginConfig
 * @psalm-import-type CookieAutoClear from PluginConfig
 * @psalm-import-type CookieCategory from PluginConfig
 * @psalm-import-type PluginOptions from PluginConfig
 */
class CookieConsentManager
{
    /**
     * @var ?array<string, mixed>
     * @psalm-var ?PluginOptions
     */
    protected ?array $pluginOptions = null;
    protected PluginConfig $pluginConfig;
    protected DisclosureRepository $disclosureRepository;
    protected Translator $translator;

    public function __construct(
        PluginConfig $pluginConfig,
        DisclosureRepository $disclosureRepository,
        Translator $translator
    ) {
        $this->pluginConfig = $pluginConfig;
        $this->disclosureRepository = $disclosureRepository;
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    public function getPluginOptionsAsJson(): string
    {
        return json_encode($this->getPluginOptions(), (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array<string, mixed>
     *
     * @psalm-return PluginOptions
     */
    public function getPluginOptions(): array
    {
        return $this->pluginOptions ??= $this->createPluginOptions();
    }

    /**
     * @return array<string, mixed>
     *
     * @psalm-return PluginOptions
     */
    public function createPluginOptions(): array
    {
        $disclosureInstance = $this->disclosureRepository->getDisclosure();
        if (!$disclosureInstance) {
            return [];
        }

        $categoryInstances = $disclosureInstance->getCategories();
        if (!$categoryInstances) {
            return [];
        }

        $requiredLabel       = $this->translator->trans('cookie-consent.modal.category.required');
        $cookieTableTemplate = [
            'headers' => [
                'name'     => $this->translator->trans('cookie-consent.cookie-table.header.name'),
                'service'  => $this->translator->trans('cookie-consent.cookie-table.header.service'),
                'duration' => $this->translator->trans('cookie-consent.cookie-table.header.duration'),
                'purpose'  => $this->translator->trans('cookie-consent.cookie-table.header.purpose'),
            ],
            'body' => [],
        ];

        $acceptAllLabel       = $this->translator->trans('cookie-consent.modal.consent.accept-all');
        $acceptNecessaryLabel = $this->translator->trans('cookie-consent.modal.consent.accept-necessary');
        $showPreferencesLabel = $this->translator->trans('cookie-consent.modal.consent.show-preferences');
        $savePreferencesLabel = $this->translator->trans('cookie-consent.modal.preferences.save-preferences');

        $showPreferencesTemplate = '<button class="cc__link" data-cookie-consent="show-preferences">%s</button>';

        /** @var Model\Structure\PrivacyPolicyLink */
        $privacyPolicyInstance = $this->getStructureModel($disclosureInstance, 'privacyPolicyLink');
        $privacyPolicyTemplate = null;
        if ($privacyPolicyInstance) {
            $href = $privacyPolicyInstance->getHref();
            if ($href) {
                $linkAttributes = $privacyPolicyInstance->getAttributes();
                $privacyPolicyTemplate = \sprintf(
                    '<a href="%s" target="%s" rel="%s" data-cookie-consent="hide">%s</a>',
                    $href . '?hide-cookie-consent',
                    ($linkAttributes['target'] ?? '_self'),
                    ($linkAttributes['rel'] ?? ''),
                    '%s'
                );
            }
        }

        /** @var Model\Structure\PreferencesModal */
        $preferencesInstance = $this->getStructureModel($disclosureInstance, 'preferencesModal');

        $preferencesSections = [];
        $preferencesDescription = \trim($preferencesInstance->getDescription());
        if ($preferencesDescription) {
            $preferencesSections[] = [
                'description' => $this->parsePlaceholders($this->p2br($preferencesDescription), [
                    'privacyPolicyLink' => $privacyPolicyTemplate,
                ]),
            ];
        }

        $cookieCategories  = [];
        foreach ($categoryInstances as $category) {
            $categoryName = $category->getHandle();
            $categoryDescription = \trim((string)$category->getDescription());
            $categoryDescription = $categoryDescription
                ? $this->p2br($categoryDescription)
                : null;

            $section = [
                'title'          => (string)$category->getName(),
                'description'    => $categoryDescription,
                'linkedCategory' => $categoryName,
            ];

            if ($category->getReadOnly()) {
                $section['title'] .= '<span class="pm__badge">' . $requiredLabel . '</span>';
            }

            $cookies = $category->getCookiesTable();
            if ($cookies) {
                $cookies = $cookies->trans($this->translator);
                if ($cookies) {
                    $cookieTable = $cookieTableTemplate;
                    $cookieTable['body'] = $cookies;

                    $section['cookieTable'] = $cookieTable;
                }
            }

            $preferencesSections[] = $section;

            if ($categoryName) {
                $cookieCategories[$categoryName] = $this->parseCookieCategory($category);
            }
        } // end foreach

        $preferencesModal = [
            'title'              => (string)$preferencesInstance->getTitle(),
            'acceptAllBtn'       => $acceptAllLabel,
            'acceptNecessaryBtn' => $acceptNecessaryLabel,
            'savePreferencesBtn' => $savePreferencesLabel,
            'closeIconLabel'     => $acceptNecessaryLabel,
            'sections'           => $preferencesSections,
        ];

        /** @var Model\Structure\ConsentModal */
        $consentInstance = $this->getStructureModel($disclosureInstance, 'consentModal');
        $consentDescription = \trim($consentInstance->getDescription());
        $consentDescription = $consentDescription
            ? $this->parsePlaceholders($this->p2br($consentDescription), [
                'privacyPolicyLink'    => $privacyPolicyTemplate,
                'showPreferencesModal' => $showPreferencesTemplate,
            ], $placeholderCounts)
            : null;

        $consentFooter = \trim($consentInstance->getFooter());
        $consentFooter = $consentFooter
            ? $this->parsePlaceholders($this->p2br($consentFooter), [
                'privacyPolicyLink'    => $privacyPolicyTemplate,
                'showPreferencesModal' => $showPreferencesTemplate,
            ], $placeholderCounts)
            : null;

        $consentModal = [
            'title'              => (string)$consentInstance->getTitle(),
            'description'        => $consentDescription,
            'revisionMessage'    => null,
            'footer'             => $consentFooter,
            'acceptAllBtn'       => $acceptAllLabel,
            'acceptNecessaryBtn' => $acceptNecessaryLabel,
            'showPreferencesBtn' => (
                isset($placeholderCounts['showPreferencesModal'])
                ? null
                : $showPreferencesLabel
            ),
        ];

        /** @var Model\Structure\ConsentRevision */
        $revisionInstance = $this->getStructureModel($disclosureInstance, 'consentRevision');

        $revisionNumber = 1;
        if ($revisionInstance) {
            $revisionNumber = \min($revisionNumber, \max($revisionNumber, $revisionInstance->getRevisionNumber()));

            if ($privacyPolicyInstance && $revisionInstance->getAutomaticRevision()) {
                $time = $privacyPolicyInstance->findTime();
                if ($time) {
                    $revisionNumber = $time;
                }
            }

            $revisionMessage = \trim((string)$revisionInstance->getRevisionMessage());
            if ($revisionMessage) {
                if (
                    $consentModal['description'] &&
                    \strpos($consentModal['description'], '{{revisionMessage}}') === false
                ) {
                    $consentModal['description'] .= '{{revisionMessage}}';
                }
                $consentModal['revisionMessage'] = '<br><br>' . $this->parsePlaceholders(
                    $this->p2br($revisionMessage),
                    [
                        'privacyPolicyLink'    => $privacyPolicyTemplate,
                        'showPreferencesModal' => $showPreferencesTemplate,
                    ]
                );
            }
        }

        $translations = [
            'consentModal'     => \array_filter($consentModal),
            'preferencesModal' => \array_filter($preferencesModal),
        ];

        $locale = $this->translator->getLocale();

        $pluginOptions = clone $this->pluginConfig;
        $pluginOptions->merge([
            'revision'   => $revisionNumber,
            'language'   => [
                'default'      => $locale,
                'translations' => [
                    $locale => $translations,
                ],
            ],
            'categories' => $cookieCategories,
        ]);

        return $pluginOptions->data();
    }

    /**
     * @return ModelInterface[]|ModelInterface|null
     */
    protected function getStructureModel(ModelInterface $model, string $propertyIdent)
    {
        if (!$model->hasProperty($propertyIdent)) {
            throw new InvalidArgumentException(sprintf(
                'Property [%s] does not exist for model [%s]',
                $propertyIdent,
                get_class($model)
            ));
        }

        $property = $model->property($propertyIdent);
        if (!($property instanceof ModelStructureProperty)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Model property [%s] must be a ModelStructureProperty, [%s] received',
                    $propertyIdent,
                    get_class($property)
                )
            );
        }

        $data = $model[$propertyIdent];
        if (!$data) {
            return null;
        }

        return $property->structureVal($data);
    }

    /**
     * Replaces HTML paragraphs with HTML line breaks in a string.
     *
     * @param  string $string The input string.
     * @return ?string The altered string.
     */
    protected function p2br(string $string): ?string
    {
        return \preg_replace(
            [
                '~<\/p>\s*<p[^>]*>~i',
                '~<\/?p[^>]*>~i',
            ],
            [
                '<br><br>',
                '',
            ],
            $string
        );
    }

    /**
     * @return array<string, mixed>
     *
     * @psalm-return CookieCategory
     */
    protected function parseCookieCategory(Model\Category $category): array
    {
        $data = \array_filter([
            'enabled'   => $category->getEnabled(),
            'readOnly'  => $category->getReadOnly(),
            'autoClear' => $this->parseAutoClear($category),
        ]);

        return $data;
    }

    /**
     * @return array<string, mixed>
     *
     * @psalm-return CookieCategoryAutoClear
     */
    protected function parseAutoClear(Model\Category $category): ?array
    {
        $cookies = $category->getCookiesAutoClear();
        if ($cookies) {
            $cookies = $this->parseAutoClearCookies($cookies);
        }

        $data = \array_filter([
            'cookies'    => $cookies,
            'reloadPage' => $category->getReloadPage(),
        ]);

        if ($data) {
            return $data;
        }

        return null;
    }

    /**
     * @return array<string, mixed>[]
     *
     * @psalm-param  CookieAutoClear[]
     * @psalm-return CookieAutoClear[]
     */
    protected function parseAutoClearCookies(array $cookies): array
    {
        if (!$cookies) {
            return [];
        }

        $cookies = \array_map(
            fn(array $cookie): ?array => $this->parseAutoClearCookie($cookie),
            $cookies
        );
        return \array_values(\array_filter($cookies));
    }

    /**
     * @param  array<string, mixed> $cookie
     * @return ?array<string, mixed>
     *
     * @psalm-param  CookieAutoClear $cookie
     * @psalm-return ?CookieAutoClear
     */
    protected function parseAutoClearCookie(array $cookie): ?array
    {
        if (!$cookie['name']) {
            return null;
        }

        return \array_filter($cookie);
    }

    /**
     * @param array<string, mixed> $placeholders The placeholders.
     * @param array<string, int>   $counts       The counts.
     */
    protected function parsePlaceholders(string $text, array $placeholders = [], ?array &$counts = []): string
    {
        if (\preg_match_all('/\{\{ *(\w+?) *: *(.+?) *\}\}/', $text, $results, \PREG_SET_ORDER)) {
            foreach ($results as $matches) {
                if (isset($placeholders[$matches[1]])) {
                    $counts[$matches[1]] ??= 0;
                    $counts[$matches[1]]++;

                    $text = \str_replace(
                        $matches[0],
                        \sprintf(
                            $placeholders[$matches[1]],
                            $matches[2]
                        ),
                        $text
                    );
                }
            }
        }

        $text = preg_replace(
            '/\{\{ *revisionMessage *\}\}/',
            '{{revisionMessage}}',
            $text,
            -1,
            $count
        );

        if ($count) {
            $counts['revisionMessage'] = $count;
        }

        return $text;
    }
}
