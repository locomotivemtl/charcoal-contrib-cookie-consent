<?php

namespace Charcoal\CookieConsent;

use Charcoal\CookieConsent\Model;
use Charcoal\CookieConsent\Model\Repository;
use Psr\Container\ContainerInterface;

/**
 * Service Provider: Cookie Consent.
 */
class CookieConsentServiceProvider
{
    public function register(ContainerInterface $container)
    {
        /**
         * @return array<string, class-string> The map of classes.
         */
        $container->set('cookie-consent/class-map', function (): array {
            return [
                'config/cookie-consent' => Config\CookieConsentConfig::class,
                'config/plugin'         => Config\PluginConfig::class,
                'model/category'        => Model\Category::class,
                'model/disclosure'      => Model\Disclosure::class,
            ];
        });

        /**
         * @return Repository\DisclosureRepository<Model\Disclosure>
         */
        $container->set('cookie-consent/repository/disclosure', function (ContainerInterface $container) {
            $collectionLoader = $container->get('model/collection/loader');
            $collectionLoader->setModel($container->get('cookie-consent/class-map')['model/disclosure']);
            $collectionLoader->setCollectionClass('array');

            return new Repository\DisclosureRepository($collectionLoader);
        });

        /**
         * @return Repository\CategoryRepository<Model\Category>
         */
        $container->set('cookie-consent/repository/category', function (ContainerInterface $container) {
            $collectionLoader = $container->get('model/collection/loader');
            $collectionLoader->setModel($container->get('cookie-consent/class-map')['model/category']);
            $collectionLoader->setCollectionClass('array');

            return new Repository\CategoryRepository($collectionLoader);
        });

        /**
         * @return Repository\LinkRelationRepository<\Charcoal\Model\Modelinterface>
         */
        $container->set('cookie-consent/repository/link-relation', function (ContainerInterface $container) {
            $collectionLoader = $container->get('model/collection/loader');
            $collectionLoader->setModel($container->get('cookie-consent/config')->getPrivacyPolicyObjType());
            $collectionLoader->setCollectionClass('array');

            return new Repository\LinkRelationRepository($collectionLoader);
        });

        $container->set('cookie-consent/config', function (ContainerInterface $container) {
            $appConfig    = $container->get('config');
            $cookieClass  = $container->get('cookie-consent/class-map')['config/cookie-consent'];
            $cookieConfig = new $cookieClass();

            $appOptions = $appConfig['cookie_consent'];
            if ($appOptions) {
                $cookieConfig->merge($appOptions);
            }

            $moduleOptions = $appConfig->get(
                'modules.charcoal/cookie-consent/cookie-consent'
            );
            if ($moduleOptions) {
                $cookieConfig->merge($moduleOptions);
            }

            return $cookieConfig;
        });

        $container->set('cookie-consent', function (ContainerInterface $container) {
            return new CookieConsentManager(
                $container->get('cookie-consent/config')->getPluginConfig(),
                $container->get('cookie-consent/repository/disclosure'),
                $container->get('translator')
            );
        });
    }
}
