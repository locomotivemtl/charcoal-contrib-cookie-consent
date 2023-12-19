<?php

namespace Charcoal\CookieConsent;

use Charcoal\Config\ConfigInterface;
use Charcoal\CookieConsent\Model;
use Charcoal\CookieConsent\Transformer;

/**
 * Cookie Consent Service.
 *
 * Handle code generation for Cookie Consent functionalities.
 */
class ConsentService
{
    private ConfigInterface $config;
    private Model\Consent $consent;
    private Transformer\Consent $transformer;

    public function __construct(
        ConfigInterface $config,
        Model\Consent $consent,
        Transformer\Consent $transformer
    ) {
        $this->config = $config;
        $this->consent = $consent;
        $this->transformer = $transformer;
    }

    /**
     * @return string
     */
    public function getPluginSettingsAsJson(): string
    {
        return json_encode($this->getPluginSettings(), (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array
     */
    public function getPluginSettings(): array
    {
        return array_replace(
            (array)$this->config,
            $this->getConsentData()
        );
    }

    /**
     * @return array
     */
    public function getConsentData(): array
    {
        if ($this->consent->source()->tableExists()) {
            $this->consent->load(1);
        }

        return $this->transformer->transform($this->consent);
    }
}
