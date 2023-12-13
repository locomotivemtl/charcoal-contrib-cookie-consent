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
            'transformer' => 'preferenceSection'
        ],
        [
            'ident'       => 'necessary',
            'transformer' => 'preferenceSectionWithCategory'
        ],
        [
            'ident'       => 'functionality',
            'transformer' => 'preferenceSectionWithCategory'
        ],
        [
            'ident'       => 'performance',
            'transformer' => 'preferenceSectionWithCategory'
        ],
        [
            'ident'       => 'targeting',
            'transformer' => 'preferenceSectionWithCategory'
        ],
        [
            'ident'       => 'footer',
            'transformer' => 'preferenceSection'
        ],
    ];

    public function __construct(Container $transformers, Translator $translator)
    {
        $this->transformers = $transformers;
        $this->setTranslator($translator);
    }

    public function transform(Model\structure\consent\preferencesModal $preferencesModal): ?array
    {
        return [
            'title'              => (string)$preferencesModal['title'],
            'acceptAllBtn'       => $this->translator()->trans('consent.preferences.btn.accept'),
            'acceptNecessaryBtn' => $this->translator()->trans('consent.preferences.btn.reject'),
            'savePreferencesBtn' => $this->translator()->trans('consent.preferences.btn.accept.selection'),
            'closeIconLabel'     => $this->translator()->trans('consent.preferences.btn.close'),
            'sections'           => $this->parseSections($preferencesModal),
        ];
    }

    private function parseSections(Model\Structure\Consent\PreferencesModal $preferencesModal)
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
