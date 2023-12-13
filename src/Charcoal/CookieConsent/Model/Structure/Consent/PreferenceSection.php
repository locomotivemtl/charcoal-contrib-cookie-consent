<?php

namespace Charcoal\CookieConsent\Model\Structure\Consent;

use Charcoal\Property\Structure\StructureModel;
use Charcoal\Translator\Translation;

/**
 * Structure Model: Preference Section
 *
 * Section of content displayed in the Preferences Modal.
 * Can be linked with a Cookie Category to enable control over the said category and display the list of cookies related
 * to it.
 *
 *
 * {@see https://cookieconsent.orestbida.com/reference/configuration-reference.html#translation-preferencesmodal-sections}
 */
class PreferenceSection extends StructureModel
{
    /** @var boolean default to true */
    protected bool $active = true;
    private ?Translation $title = null;
    private ?Translation $description = null;

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

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

    public function setDescription($description): self
    {
        $this->description = $this->property('description')->parseVal($description);

        return $this;
    }
}
