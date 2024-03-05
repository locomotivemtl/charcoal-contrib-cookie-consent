<?php

namespace Charcoal\CookieConsent\Transformer\Structure\Consent;

use Charcoal\CookieConsent\Model;
use Charcoal\CookieConsent\Transformer\StructureAwareTrait;
use Charcoal\Translator\Translator;
use Charcoal\Translator\TranslatorAwareTrait;
use Pimple\Container;

/**
 * Transformer: Preferences Modal
 */
class PreferencesModal
{
    use TranslatorAwareTrait;
    use StructureAwareTrait;

    private array $sectionsList = [
        [
            'ident'       => 'body',
            'transformer' => 'preferenceSection',
        ],
        [
            'ident'       => 'necessary',
            'transformer' => 'preferenceSectionWithCategory',
        ],
        [
            'ident'       => 'functionality',
            'transformer' => 'preferenceSectionWithCategory',
        ],
        [
            'ident'       => 'performance',
            'transformer' => 'preferenceSectionWithCategory',
        ],
        [
            'ident'       => 'targeting',
            'transformer' => 'preferenceSectionWithCategory',
        ],
        [
            'ident'       => 'footer',
            'transformer' => 'preferenceSection',
        ],
    ];

    public function __construct(Container $transformers, Translator $translator)
    {
        $this->transformers = $transformers;
        $this->setTranslator($translator);
    }

    /**
     * @return array<string, mixed>
     */
    public function transform(Model\Structure\Consent\PreferencesModal $preferencesModal): ?array
    {
        return [
            'title'              => (string)$preferencesModal['title'],
            'acceptAllBtn'       => $this->translator()->trans('cookie-consent.modal.preferences.accept-all'),
            'acceptNecessaryBtn' => $this->translator()->trans('cookie-consent.modal.preferences.accept-necessary'),
            'savePreferencesBtn' => $this->translator()->trans('cookie-consent.modal.preferences.save-preferences'),
            'closeIconLabel'     => $this->translator()->trans('cookie-consent.modal.close-icon-label'),
            'sections'           => $this->parseSections($preferencesModal),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function parseSections(Model\Structure\Consent\PreferencesModal $preferencesModal): array
    {
        $parseSection = function ($sectionData) use ($preferencesModal) {
            return $this->parseStructure(
                $preferencesModal,
                $sectionData['ident'],
                $sectionData['transformer']
            );
        };

        $sections = array_map($parseSection, $this->sectionsList);
        return array_values(array_filter($sections, fn($section) => !!$section['active']));
    }
}
