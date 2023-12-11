<?php

namespace Charcoal\CookieConsent\Model\Structure\Consent;

use Charcoal\Property\Structure\StructureModel;
use Charcoal\Translator\Translation;

/**
 * Structure Model: Preferences Modal
 *
 * This is the Modal that the user accesses when clicking the show preference btn in the consent modal.
 * Allows the user to fine tune the cookie and service consents
 */
class PreferencesModal extends StructureModel
{
    private ?Translation $title = null;
    private ?Translation $description = null;

    /**
     * @param  mixed $title Translation string.
     */
    public function setTitle($title): self
    {
        $this->title = $this->property('title')->parseVal($title);

        return $this;
    }

    public function getTitle(): ?Translation
    {
        return $this->title;
    }

    public function getDescription(): ?Translation
    {
        return $this->description;
    }

    public function setDescription($description): PreferencesModal
    {
        $this->description = $this->property('description')->parseVal($description);

        return $this;
    }
}
