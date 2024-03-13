<?php

namespace Charcoal\CookieConsent\Model;

use Charcoal\CookieConsent\Model\Repository\CategoryRepository;
use Charcoal\Object\Content;
use Pimple\Container;

/**
 * Model: Cookies Disclosure
 *
 * Stores editable options and modal texts.
 */
class Disclosure extends Content
{
    /** @var ?array<string, mixed> {@see \Charcoal\CookieConsent\Model\Structure\ConsentModal}. */
    private ?array $consentModal = null;
    /** @var ?array<string, mixed> {@see \Charcoal\CookieConsent\Model\Structure\ConsentRevision}. */
    private ?array $consentRevision = null;
    /** @var ?array<string, mixed> {@see \Charcoal\CookieConsent\Model\Structure\PreferencesModal}. */
    private ?array $preferencesModal = null;
    /** @var ?array<string, mixed> {@see \Charcoal\CookieConsent\Model\Structure\PrivacyPolicyLink}. */
    private ?array $privacyPolicyLink = null;
    /** @var ?(int|string)[] */
    private array $categoryIds = [];

    private ?CategoryRepository $categoryRepository = null;

    public function getConsentModal(): ?array
    {
        return $this->consentModal;
    }

    /**
     * @param mixed $consentModal The consent modal dataset.
     */
    public function setConsentModal($consentModal): self
    {
        $this->consentModal = $this->sanitizeStructureProperty(
            $this->p('consentModal')->parseVal($consentModal)
        );

        return $this;
    }

    public function getConsentRevision(): ?array
    {
        return $this->consentRevision;
    }

    /**
     * @param mixed $consentRevision The consent modal dataset.
     */
    public function setConsentRevision($consentRevision): self
    {
        $this->consentRevision = $this->sanitizeStructureProperty(
            $this->p('consentRevision')->parseVal($consentRevision)
        );

        return $this;
    }

    public function getPreferencesModal(): ?array
    {
        return $this->preferencesModal;
    }

    /**
     * @param mixed $preferencesModal The preferences modal dataset.
     */
    public function setPreferencesModal($preferencesModal): self
    {
        $this->preferencesModal = $this->sanitizeStructureProperty(
            $this->p('preferencesModal')->parseVal($preferencesModal)
        );

        return $this;
    }

    public function getPrivacyPolicyLink(): ?array
    {
        return $this->privacyPolicyLink;
    }

    /**
     * @param mixed $link The privacy policy link dataset.
     */
    public function setPrivacyPolicyLink($link): self
    {
        $this->privacyPolicyLink = $this->sanitizeStructureProperty(
            $this->p('privacyPolicyLink')->parseVal($link)
        );

        return $this;
    }

    public function getCategories(): array
    {
        return $this->getCategoryRepository()->findCategories(
            $this->getCategoryIds()
        );
    }

    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    /**
     * @param mixed $categoryIds A list of Category object IDs.
     */
    public function setCategoryIds($categoryIds): self
    {
        $this->categoryIds = (array)$this->p('categoryIds')->parseVal($categoryIds);

        return $this;
    }

    /**
     * Sanitize the dataset.
     *
     * For example, a false/positive of an empty array.
     *
     * @param mixed $data The dataset to filter.
     */
    protected function sanitizeStructureProperty($data): ?array
    {
        if (\is_array($data) && $data) {
            return $data;
        }

        return null;
    }

    protected function getCategoryRepository(): CategoryRepository
    {
        return $this->categoryRepository;
    }

    protected function setCategoryRepository(CategoryRepository $repository): void
    {
        $this->categoryRepository = $repository;
    }

    protected function setDependencies(Container $container)
    {
        $this->setCategoryRepository($container['cookie-consent/repository/category']);
        parent::setDependencies($container);
    }
}
