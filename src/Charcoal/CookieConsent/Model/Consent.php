<?php

namespace Charcoal\CookieConsent\Model;

use App\Model\Content\Page;
use Charcoal\Cms\Section;
use Charcoal\Object\Content;
use Pimple\Container;

/**
 * The cookie consent modal editable options.
 */
class Consent extends Content
{
    private ?string $policyPageObjType = null;
    private ?string $policyPageId;
    private ?Content $policyPage;
    private array $consentModal = [];
    private array $preferencesModal = [];

    /**
     * @param mixed  $propertyMetadata The property metadata array.
     * @param string $propertyIdent    The Property Ident being filtered.
     * @return mixed
     */
    public function filterPropertyMetadata($propertyMetadata, $propertyIdent)
    {
        if ($propertyIdent === 'policyPageId') {
            $propertyMetadata['obj_type'] = $this->getPolicyPageObjType();
        }

        return $propertyMetadata;
    }

    public function setPolicyPageId(?string $policyPageId): Consent
    {
        $this->policyPageId = $policyPageId;
        return $this;
    }

    public function getPolicyPageId(): ?string
    {
        return $this->policyPageId;
    }

    /**
     * Fetch the Policy Page and returns it.
     *
     * @return Content|null
     */
    public function getPolicyPage(): ?Content
    {
        /** Return the policy page early if already loaded. */
        if (isset($this->policyPage)) {
            return $this->policyPage;
        }

        if (!$this->getPolicyPageId()) {
            $this->policyPage = null;
            return $this->policyPage;
        }

        $loader = $this->modelFactory()->create($this->getPolicyPageObjType());
        $this->policyPage = $loader->load($this->getPolicyPageId());

        return $this->policyPage;
    }

    /**
     * @param Container $container Pimple DI container.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->setPolicyPageObjType($container['cookie-consent/config']->getPolicyPageObjType());
    }

    public function getPolicyPageObjType(): string
    {
        return $this->policyPageObjType;
    }

    public function setPolicyPageObjType(string $policyPageObjType): void
    {
        $this->policyPageObjType = $policyPageObjType;
    }

    public function getConsentModal(): array
    {
        return $this->consentModal;
    }

    public function setConsentModal($consentModal): Consent
    {
        $this->consentModal = $this->p('consentModal')->parseVal($consentModal);

        return $this;
    }

    public function getPreferencesModal(): array
    {
        return $this->preferencesModal;
    }

    public function setPreferencesModal($preferencesModal): Consent
    {
        $this->preferencesModal = $this->p('preferencesModal')->parseVal($preferencesModal);

        return $this;
    }
}
