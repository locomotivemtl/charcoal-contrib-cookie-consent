<?php

namespace Charcoal\CookieConsent;

use Charcoal\Config\AbstractConfig;

/**
 * Default Config for Cookie Consent module.
 */
class ConsentConfig extends AbstractConfig
{
    private int $delay;
    private bool $page_scripts;
    private string $auto_language;
    private array $gui_options;
    private string $policyPageObjType;

    /**
     * The default data is defined in a JSON file.
     *
     * @return array
     */
    public function defaults(): array
    {
        $baseDir = rtrim(realpath(__DIR__.'/../../../'), '/');
        $confDir = $baseDir.'/config';

        return $this->loadFile($confDir.'/cookie-consent.json');
    }

    /**
     * @param $data
     * @return static
     */
    public static function create($data)
    {
        $config = new static($data);

        $config->getPolicyPageObjType();

        return $config;
    }

    public function getDelay(): int
    {
        return $this->delay;
    }

    public function setDelay(int $delay):void
    {
        $this->delay = $delay;
    }

    public function isPageScripts(): bool
    {
        return $this->page_scripts;
    }

    public function setPageScripts(bool $page_scripts):void
    {
        $this->page_scripts = $page_scripts;
    }

    public function getAutoLanguage(): string
    {
        return $this->auto_language;
    }

    public function setAutoLanguage(string $auto_language):void
    {
        $this->auto_language = $auto_language;
    }

    public function getGuiOptions(): array
    {
        return $this->gui_options;
    }

    public function setGuiOptions(array $gui_options):void
    {
        $this->gui_options = $gui_options;
    }

    /**
     * @throws \Exception
     */
    public function getPolicyPageObjType(): string
    {
        if (!isset($this->policyPageObjType)) {
            throw new \Exception('No Consent page object type defined. Please provide a `policyPageObjType` option when declaring the Cookie Consent module.');
        }

        return $this->policyPageObjType;
    }

    public function setPolicyPageObjType(string $policyPageObjType):void
    {
        $this->policyPageObjType = $policyPageObjType;
    }
}
