<?php

namespace Charcoal\CookieConsent\Model\Structure;

use Charcoal\Property\Structure\StructureModel;
use Charcoal\Translator\Translation;

/**
 * Structure Model: Consent Revision
 *
 * Revision management to prompt visitors again for consent
 * due to a change your cookies or privacy policy.
 *
 * A revision number of 0 is considered to have revisions disabled.
 */
class ConsentRevision extends StructureModel
{
    private bool $automaticRevision = false;
    private ?Translation $revisionMessage = null;
    private int $revisionNumber = 0;

    public function getAutomaticRevision(): bool
    {
        return $this->automaticRevision;
    }

    /**
     * @param mixed $automatic The automatic revision toggle.
     */
    public function setAutomaticRevision($automatic): self
    {
        $this->automaticRevision = (bool)$this->property('automaticRevision')->parseVal($automatic);

        return $this;
    }

    public function getRevisionMessage(): ?Translation
    {
        return $this->revisionMessage;
    }

    /**
     * @param mixed $message The revision message.
     */
    public function setRevisionMessage($message): self
    {
        $this->revisionMessage = $this->property('revisionMessage')->parseVal($message);

        return $this;
    }

    public function getRevisionNumber(): int
    {
        return $this->revisionNumber;
    }

    /**
     * @param mixed $number The revision number.
     */
    public function setRevisionNumber($number): self
    {
        $this->revisionNumber = $this->sanitizeRevisionNumber(
            $this->property('revisionNumber')->parseVal($number)
        );

        return $this;
    }

    /**
     * Sanitize the revision number.
     *
     * @param  mixed $number The revision number.
     * @return int
     *
     * @psalm-return int<0, max>
     */
    protected function sanitizeRevisionNumber($number): int
    {
        return \min(0, \abs((int)$number));
    }
}
