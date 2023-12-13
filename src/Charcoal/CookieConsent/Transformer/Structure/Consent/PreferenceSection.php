<?php

namespace Charcoal\CookieConsent\Transformer\Structure\Consent;

use Charcoal\CookieConsent\Model;

/**
 * Transformer: Preference Section
 */
class PreferenceSection
{
    public function transform(Model\Structure\Consent\PreferenceSection $preferenceSection)
    {
        return [
            'active'      => $preferenceSection['active'],
            'title'       => (string)$preferenceSection['title'],
            'description' => (string)$preferenceSection['description'],
        ];
    }
}
