<?php

namespace Charcoal\CookieConsent;

use Psr\Container\ContainerInterface;

/**
 * Provides templating tools for cookie consent integration
 */
trait HasCookieConsentTrait
{
    protected CookieConsentManager $cookieConsent;

    /**
     * @return string
     */
    public function cookieConsentConfigAsJson(): string
    {
        return $this->cookieConsent->getPluginOptionsAsJson();
    }

    public function hasCookieConsent(): bool
    {
        return $this->cookieConsent->isCookieConsentActive();
    }

    public function cookieConsentScriptTag(): string
    {
        if (!$this->hasCookieConsent()) {
            return 'type=text/javascript';
        }

        return 'type=text/plain';
    }

    /**
     * twig syntax
     *
     * @return string
     */
    public function getCookieConsentConfigAsJson(): string
    {
        return $this->cookieConsentConfigAsJson();
    }

    /**
     * twig syntax
     *
     * @return string
     */
    public function getCookieConsentScriptTag(): string
    {
        return $this->cookieConsentScriptTag();
    }

    /**
     * twig syntax
     *
     * @return string
     */
    public function getHasCookieConsent(): bool
    {
        return $this->hasCookieConsent();
    }

    /**
     * @param ContainerInterface|CookieConsentManager $cookieConsent
     * @return void
     */
    public function setCookieConsent($cookieConsent)
    {
        $this->cookieConsent = $cookieConsent instanceof ContainerInterface ? $cookieConsent['cookie-consent'] : $cookieConsent;
    }
}
