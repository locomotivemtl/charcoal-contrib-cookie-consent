<?php

namespace Charcoal\CookieConsent\Model\Structure\Consent;

use Charcoal\Property\Structure\StructureModel;
use Charcoal\Translator\Translation;

/**
 * Structure Model: Consent Modal
 *
 * This is the first modal that appears when loading the page that allows the user to accept or decline
 * cookies and services.
 */
class ConsentModal extends StructureModel
{
    private ?Translation $title = null;
    private ?Translation $description = null;
    private ?Translation $revisionMessage = null;

    /**
     * @param mixed $title Translation string.
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

    public function setDescription($description): ConsentModal
    {
        $this->description = $this->property('description')->parseVal($description);

        return $this;
    }

    public function setRevisionMessage($message): ConsentModal
    {
        $this->revisionMessage = $this->property('revisionMessage')->parseVal($message);

        return $this;
    }
}
