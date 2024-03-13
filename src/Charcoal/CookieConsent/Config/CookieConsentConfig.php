<?php

namespace Charcoal\CookieConsent\Config;

use Charcoal\Config\AbstractConfig;
use Charcoal\Factory\GenericResolver as ClassResolver;

/**
 * Cookie Consent Package Configuration Options
 */
class CookieConsentConfig extends AbstractConfig
{
    private int $delay = 0;
    private ?PluginConfig $pluginConfig = null;
    private ?string $privacyPolicyObjType = null;

    public function getDelay(): int
    {
        return $this->delay;
    }

    public function setDelay(int $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Retrieve the plugin's options.
     */
    public function getPluginConfig(): PluginConfig
    {
        return $this->pluginConfig ??= $this->createPluginConfig();
    }

    /**
     * Set the plugin's options.
     *
     * @param mixed $options The plugin options.
     */
    public function setPluginConfig($options): self
    {
        $this->pluginConfig = $this->createPluginConfig($options);

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function getPrivacyPolicyObjType(): string
    {
        if (!$this->privacyPolicyObjType) {
            throw new \Exception(
                'Expected a cookie consent privacy policy object type'
            );
        }

        return $this->privacyPolicyObjType;
    }

    /**
     * @psalm-param class-string $objType
     */
    public function setPrivacyPolicyObjType(string $objType): self
    {
        $classResolver = new ClassResolver();

        $this->privacyPolicyObjType = $classResolver->resolve($objType);

        return $this;
    }

    /**
     * Create a new plugin configuration instance.
     *
     * @param mixed $data The plugin options.
     */
    protected function createPluginConfig($data = null): PluginConfig
    {
        return new PluginConfig($data);
    }
}
