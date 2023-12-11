<?php

namespace Charcoal\CookieConsent\Transformer\Structure\Consent;

use Charcoal\CookieConsent\Model;

/**
 * Transformer: Preferences Modal
 */
class PreferencesModal
{
    public function __construct() {}

    public function transform( Model\structure\consent\preferencesModal $preferencesModal): ?array
    {
        return [
            'title' => (string)$preferencesModal['title'],
            'description' => (string)$preferencesModal['description'],
        ];
    }
}
