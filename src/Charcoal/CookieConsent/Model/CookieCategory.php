<?php

namespace Charcoal\CookieConsent\Model;

use Charcoal\Object\Content;
use Charcoal\Translator\TranslatableValue;
use Charcoal\Translator\Translation;

/**
 * The cookie category model.
 * Defines the different cookie categories.
 */
class CookieCategory extends Content
{
    private ?Translation $title = null;
    private ?TranslatableValue $cookies = null;

    public function setCookies($cookies)
    {
        $this->cookies = new TranslatableValue($cookies);

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
}
