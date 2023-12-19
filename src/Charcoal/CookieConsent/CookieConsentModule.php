<?php

namespace Charcoal\CookieConsent;

use Charcoal\App\Module\AbstractModule;
use Charcoal\App\Module\ModuleInterface;

/**
 * Charcoal Module: Cookie Consent.
 *
 * Handles cookie management through Charcoal cms.
 */
class CookieConsentModule extends AbstractModule implements ModuleInterface
{
    public const ADMIN_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-cookie-consent/config/admin.json';
    public const APP_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-cookie-consent/config/config.json';

    /**
     * @return $this
     */
    public function setUp(): CookieConsentModule
    {
        $container = $this->app()->getContainer();

        $container['translator/config']->addPaths([
            'vendor/locomotivemtl/charcoal-contrib-cookie-consent/translations/'
        ]);

        $serviceProvider = new ConsentServiceProvider();
        $container->register($serviceProvider);

        return $this;
    }
}
