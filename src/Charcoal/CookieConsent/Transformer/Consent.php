<?php

namespace Charcoal\CookieConsent\Transformer;

use Charcoal\CookieConsent\Model;
use Charcoal\Loader\CollectionLoader;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Charcoal\Translator\Translator;
use Pimple\Container;

/**
 * Transformer: Consent
 */
class Consent
{
    use StructureAwareTrait;
    use CollectionLoaderAwareTrait;

    private Translator $translator;

    public function __construct(
        Translator $translator,
        Container $transformers,
        CollectionLoader $collectionLoader
    ) {
        $this->translator = $translator;
        $this->transformers = $transformers;
        $this->setCollectionLoader($collectionLoader);
    }

    public function transform(Model\Consent $consent): ?array
    {
        $consentModal = $this->parseStructure($consent, 'consentModal');
        // Early exit if no modal data.
        if (!$consentModal) {
            return [];
        }

        $preferencesModal = $this->parseStructure($consent, 'preferencesModal');

        $policyPage = $consent->getPolicyPage();
        $revision = 0;
        if ($policyPage && $policyPage->getLastModified()) {
            $revision = $policyPage->getLastModified()->format('YmdHis');
        }

        $locale = $this->translator->getLocale();

        return [
            'revision'   => $revision,
            'language'   => [
                'default'      => $locale,
                'translations' => [
                    $locale => [
                        'consentModal'     => $consentModal,
                        'preferencesModal' => $preferencesModal,
                    ],
                ],
            ],
            'categories' => $this->fetchCategories(),
        ];
    }

    private function fetchCategories(): array
    {
        $categories = $this->collectionLoader()
            ->setModel(Model\CookieCategory::class)
            ->addFilter(['property' => 'active', 'value' => true])
            ->load()
            ->values();


        $formattedCategories = array_map([$this, 'formatCategory'], $categories);

        return array_combine(array_column($formattedCategories, 'ident'), $formattedCategories);
    }

    protected function formatCategory(Model\CookieCategory $category): array
    {
        return [
            'ident'    => $category['id'],
            'name'     => (string)$category['title'],
            'readOnly' => (string)$category['readOnly'],
        ];
    }
}
