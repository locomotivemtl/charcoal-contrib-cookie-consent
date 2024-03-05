<?php

namespace Charcoal\CookieConsent\Transformer\Structure\Consent;

use Charcoal\CookieConsent\Model;
use Charcoal\Translator\Translator;
use Charcoal\Translator\TranslatorAwareTrait;

/**
 * Transformer: Consent Modal
 */
class ConsentModal
{
    use TranslatorAwareTrait;

    public function __construct(Translator $translator)
    {
        $this->setTranslator($translator);
    }

    public function transform(Model\Structure\Consent\ConsentModal $consentModal): array
    {
        return [
            'title'              => (string)$consentModal['title'],
            'description'        => (string)$consentModal['description'],
            'revisionMessage'    => (string)$consentModal['revisionMessage'],
            'acceptAllBtn'       => $this->translator()->trans('cookie-consent.modal.consent.accept-all'),
            'acceptNecessaryBtn' => $this->translator()->trans('cookie-consent.modal.consent.accept-necessary'),
            'showPreferencesBtn' => $this->translator()->trans('cookie-consent.modal.consent.show-preferences'),
            'closeIconLabel'     => $this->translator()->trans('cookie-consent.modal.close-icon-label'),
        ];
    }
}
