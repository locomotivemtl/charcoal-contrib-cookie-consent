<?php

namespace Charcoal\CookieConsent;

use Charcoal\App\Module\AbstractModule;
use Charcoal\App\Module\ModuleInterface;
use Psr\Container\ContainerInterface;

/**
 * Charcoal Module: Cookie Consent.
 *
 * Handles cookie management through Charcoal cms.
 */
class CookieConsentModule extends AbstractModule implements ModuleInterface
{
    public const ADMIN_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-cookie-consent/config/admin.json';
    public const APP_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-cookie-consent/config/config.json';

    public function setUp(): self
    {
        /** @var ContainerInterface */
        $container = $this->app()->getContainer();
        (new CookieConsentServiceProvider())->register($container);

        return $this;
    }
}
