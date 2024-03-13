<?php

namespace Charcoal\CookieConsent\Config;

use Charcoal\Config\AbstractConfig;

/**
 * Plugin Configuration Options.
 *
 * {@link https://cookieconsent.orestbida.com/reference/configuration-reference.html Configuration options for vanilla-cookieconsent.}
 *
 * @psalm-type CookieTableRow = array{
 *     name: string,
 *     service: string,
 *     duration: string,
 *     purpose: string,
 * }
 *
 * @psalm-type CookieAutoClear = array{
 *     name: string,
 *     path?: string,
 *     domain?: string,
 * }
 *
 * @psalm-type AutoClear = array{
 *     cookies?: list<CookieAutoclear>,
 *     reloadPage?: bool,
 * }
 *
 * @psalm-type CookieCategory = array{
 *     enabled: bool,
 *     readOnly: bool,
 *     autoClear: AutoClear,
 * }
 *
 * @psalm-type Language = array{
 *     default?: string,
 *     autoDetect?: string,
 *     ...<string, mixed>
 * }
 *
 * @psalm-type ModalOptions = array{
 *     layout?: string,
 *     position?: string,
 *     flipButtons?: bool,
 *     equalWeightButtons?: bool,
 * }
 *
 * @psalm-type GuiOptions = array{
 *     consentModal?: ModalOptions,
 *     preferencesModal?: ModalOptions,
 * }
 *
 * @psalm-type PluginOptions = array{
 *     guiOptions?: GuiOptions,
 *     language?: Language,
 *     ...<string, mixed>
 * }
 */
class PluginConfig extends AbstractConfig
{
    /**
     * Retrieve the default plugin options.
     *
     * @return array<string, mixed>
     *
     * @psalm-return PluginOptions
     */
    public function defaults()
    {
        return [
            'language' => [
                'autoDetect' => 'document',
            ],
            'guiOptions' => [
                'consentModal' => [
                    'layout'   => 'box',
                    'position' => 'bottom right',
                ],
            ],
        ];
    }
}
