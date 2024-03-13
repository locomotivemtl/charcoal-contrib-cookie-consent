<?php

namespace Charcoal\CookieConsent\Model\Structure;

use Charcoal\Translator\Translation;

/**
 * Structure Model: Consent Modal
 *
 * This is the first modal that appears when loading the page that
 * allows the user to accept or decline cookies and services.
 */
class ConsentModal extends Section
{
    private ?Translation $footer = null;

    public function getFooter(): ?Translation
    {
        return $this->footer;
    }

    /**
     * @param mixed $footer The section footer.
     */
    public function setFooter($footer): self
    {
        $this->footer = $this->property('footer')->parseVal($footer);

        return $this;
    }
}
