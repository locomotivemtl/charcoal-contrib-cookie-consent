<?php

namespace Charcoal\CookieConsent\Transformer\Structure\Consent;

use Charcoal\CookieConsent\Model;
use Charcoal\Factory\FactoryInterface;
use Charcoal\Model\ModelFactoryTrait;
use Charcoal\Translator\TranslatableValue;
use Charcoal\Translator\Translator;
use Charcoal\Translator\TranslatorAwareTrait;

/**
 * Transformer: Preference Section With Category
 */
class PreferenceSectionWithCategory extends PreferenceSection
{
    use TranslatorAwareTrait;
    use ModelFactoryTrait;

    public function __construct(FactoryInterface $factory, Translator $translator)
    {
        $this->setModelFactory($factory);
        $this->setTranslator($translator);
    }

    public function transform(Model\Structure\Consent\PreferenceSection $preferenceSection)
    {
        /** @var Model\CookieCategory $categoryModel */
        $categoryModel = $this->modelFactory()->create(Model\CookieCategory::class);
        $category = $categoryModel->load($preferenceSection['linkedCategory']);
        $cookies = $category['cookies'];

        if ($cookies instanceof TranslatableValue) {
            $cookies = $cookies->trans($this->translator());
        }

        $out = [
            'linkedCategory' => $preferenceSection['linkedCategory'],
            'cookieTable'    => [
                'headers' => [
                    'name'     => $this->translator()->trans('consent.cookie-table.header.name'),
                    'service'  => $this->translator()->trans('consent.cookie-table.header.service'),
                    'purpose'  => $this->translator()->trans('consent.cookie-table.header.purpose'),
                    'duration' => $this->translator()->trans('consent.cookie-table.header.duration')
                ],
                'body'    => json_decode($cookies, JSON_PRETTY_PRINT)
            ]
        ];

        return array_merge(
            parent::transform($preferenceSection),
            $out
        );
    }
}
