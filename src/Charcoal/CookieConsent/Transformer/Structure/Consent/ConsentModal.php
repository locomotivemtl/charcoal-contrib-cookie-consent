<?php

namespace Charcoal\CookieConsent\Transformer\Structure\Consent;

use Charcoal\CookieConsent\Model;

/**
 * Transformer: Consent Modal
 */
class ConsentModal
{
    public function __construct()
    {
    }

    public function transform( Model\structure\consent\ConsentModal $consentModal): ?array
    {
        return [
            'title' => (string)$consentModal['title'],
            'description' => (string)$consentModal['description'],
        ];
    }
}
