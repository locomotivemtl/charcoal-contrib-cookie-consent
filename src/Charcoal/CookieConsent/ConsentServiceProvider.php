<?php

namespace Charcoal\CookieConsent;

use Charcoal\CookieConsent\Model;
use Charcoal\CookieConsent\Transformer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tinify\Exception;

/**
 * Service Provider: Cookie Consent.
 */
class ConsentServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container Pimple DI container.
     * @return void
     */
    public function register(Container $container)
    {
        /**
         * @param Container $container
         * @return ConsentConfig
         */
        $container['cookie-consent/config'] = function (Container $container) {
            $config = $container['config']->get('modules.charcoal/cookie-consent/cookie-consent');

            return ConsentConfig::create($config);
        };

        $container['cookie-consent'] = function (Container $container) {
            return new ConsentService(
                $container['cookie-consent/config'],
                $container['cookie-consent/consent'],
                $container['cookie-consent/transformers']['consent']
            );
        };

        $container['cookie-consent/consent'] = function (Container $container) {
            return $container['model/factory']->create(Model\Consent::class);
        };

        $container['cookie-consent/transformers'] = function (Container $container) {

            $transformers = new Container();

            $transformers['consent'] = function () use ($container) {
                return new Transformer\Consent(
                    $container['translator'],
                    $container['cookie-consent/transformers'],
                    $container['model/collection/loader']
                );
            };

            $transformers['consentModal'] = function () use ($container) {
                return new Transformer\Structure\Consent\ConsentModal(
                    $container['translator']
                );
            };

            $transformers['preferencesModal'] = function () use ($container) {
                return new Transformer\Structure\Consent\PreferencesModal(
                    $container['cookie-consent/transformers'],
                    $container['translator']
                );
            };

            $transformers['preferenceSection'] = function () {
                return new Transformer\Structure\Consent\PreferenceSection();
            };

            $transformers['preferenceSectionWithCategory'] = function () use ($container) {
                return new Transformer\Structure\Consent\PreferenceSectionWithCategory(
                    $container['model/factory'],
                    $container['translator']
                );
            };

            return $transformers;
        };
    }
}
