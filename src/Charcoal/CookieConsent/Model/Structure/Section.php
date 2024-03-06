<?php

namespace Charcoal\CookieConsent\Model\Structure;

use Charcoal\Property\Structure\StructureModel;
use Charcoal\Translator\Translation;

/**
 * Structure Model: Section
 */
class Section extends StructureModel
{
    private ?Translation $title = null;
    private ?Translation $description = null;

    public function getTitle(): ?Translation
    {
        return $this->title;
    }

    /**
     * @param mixed $title The section title.
     */
    public function setTitle($title): self
    {
        $this->title = $this->property('title')->parseVal($title);

        return $this;
    }

    public function getDescription(): ?Translation
    {
        return $this->description;
    }

    /**
     * @param mixed $description The section description.
     */
    public function setDescription($description): self
    {
        $this->description = $this->property('description')->parseVal($description);

        return $this;
    }
}
