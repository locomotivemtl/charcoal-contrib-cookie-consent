<?php

namespace Charcoal\CookieConsent;

use Charcoal\CookieConsent\Model;
use Charcoal\CookieConsent\Model\Repository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Service Provider: Cookie Consent.
 */
class CookieConsentServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        /**
         * @return array<string, class-string> The map of classes.
         */
        $container['cookie-consent/class-map'] = function (): array {
            return [
                'config/cookie-consent' => Config\CookieConsentConfig::class,
                'config/plugin'         => Config\PluginConfig::class,
                'model/category'        => Model\Category::class,
                'model/disclosure'      => Model\Disclosure::class,
            ];
        };

        /**
         * @return Repository\DisclosureRepository<Model\Disclosure>
         */
        $container['cookie-consent/repository/disclosure'] = function (Container $container) {
            $collectionLoader = $container['model/collection/loader'];
            $collectionLoader->setModel($container['cookie-consent/class-map']['model/disclosure']);
            $collectionLoader->setCollectionClass('array');

            return new Repository\DisclosureRepository($collectionLoader);
        };

        /**
         * @return Repository\CategoryRepository<Model\Category>
         */
        $container['cookie-consent/repository/category'] = function (Container $container) {
            $collectionLoader = $container['model/collection/loader'];
            $collectionLoader->setModel($container['cookie-consent/class-map']['model/category']);
            $collectionLoader->setCollectionClass('array');

            return new Repository\CategoryRepository($collectionLoader);
        };

        /**
         * @return Repository\LinkRelationRepository<\Charcoal\Model\Modelinterface>
         */
        $container['cookie-consent/repository/link-relation'] = function (Container $container) {
            $collectionLoader = $container['model/collection/loader'];
            $collectionLoader->setModel($container['cookie-consent/config']->getPrivacyPolicyObjType());
            $collectionLoader->setCollectionClass('array');

            return new Repository\LinkRelationRepository($collectionLoader);
        };

        $container['cookie-consent/config'] = function (Container $container) {
            $appConfig    = $container['config'];
            $cookieClass  = $container['cookie-consent/class-map']['config/cookie-consent'];
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
        };

        $container['cookie-consent'] = function (Container $container) {
            return new CookieConsentManager(
                $container['cookie-consent/config']->getPluginConfig(),
                $container['cookie-consent/repository/disclosure'],
                $container['translator']
            );
        };
    }
}
