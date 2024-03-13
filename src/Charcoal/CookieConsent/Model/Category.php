<?php

namespace Charcoal\CookieConsent\Model;

use Charcoal\Object\Content;
use Charcoal\Translator\TranslatableValue;
use Charcoal\Translator\Translation;
use Transliterator;

/**
 * Model: Cookie Category
 */
class Category extends Content
{
    private ?string $handle = null;
    private ?Translation $name = null;
    private ?Translation $description = null;
    private bool $enabled = false;
    private bool $readOnly = false;
    private bool $reloadPage = false;
    /** @var TranslatableValue<(array<string, mixed>)[]> */
    private ?TranslatableValue $cookiesTable = null;
    /** @var (array<string, mixed>)[] */
    private ?array $cookiesAutoClear = null;

    public function getHandle(): ?string
    {
        if (\is_null($this->handle) && $this->name) {
            $this->setHandle((string)$this->name);
        }

        return $this->handle;
    }

    /**
     * @param mixed $handle The unique key of the category.
     */
    public function setHandle($handle): self
    {
        $this->handle = $this->sanitizeKey(
            $this->property('handle')->parseVal($handle)
        );

        return $this;
    }

    public function getName(): ?Translation
    {
        return $this->name;
    }

    /**
     * @param mixed $name The name of the category.
     */
    public function setName($name): self
    {
        $this->name = $this->property('name')->parseVal($name);

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

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled The enabled by default toggle.
     */
    public function setEnabled($enabled): self
    {
        $this->enabled = (bool)$enabled;

        return $this;
    }

    public function getReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * @param mixed $readOnly The read only/always enabled toggle.
     */
    public function setReadOnly($readOnly): self
    {
        $this->readOnly = (bool)$readOnly;

        return $this;
    }

    public function getReloadPage(): bool
    {
        return $this->reloadPage;
    }

    /**
     * @param mixed $reloadPage The reload page when clearing cookies toggle.
     */
    public function setReloadPage($reloadPage): self
    {
        $this->reloadPage = (bool)$reloadPage;

        return $this;
    }

    public function getCookiesTable(): ?TranslatableValue
    {
        return $this->cookiesTable;
    }

    /**
     * @param mixed $cookies The list of cookies.
     */
    public function setCookiesTable($cookies): self
    {
        $this->cookiesTable = $this->p('cookiesTable')->parseVal($cookies);
        $this->cookiesTable->each(
            fn($translation) => $this->sanitizeStructureProperty($translation)
        );

        return $this;
    }

    public function getCookiesAutoClear(): ?array
    {
        return $this->cookiesAutoClear;
    }

    /**
     * @param mixed $cookies The list of cookies.
     */
    public function setCookiesAutoClear($cookies): self
    {
        $this->cookiesAutoClear = $this->sanitizeStructureProperty(
            $this->p('cookiesAutoClear')->parseVal($cookies)
        );

        return $this;
    }

    /**
     * Sanitize the key.
     *
     * @param mixed $key The dataset to filter.
     */
    protected function sanitizeKey($key): ?string
    {
        $transliterator = Transliterator::createFromRules(
            ':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;',
            Transliterator::FORWARD
        );
        $key = $transliterator->transliterate((string)$key);
        if (!$key) {
            return null;
        }

        $key = \preg_replace(
            [
                '/\s+/',
                '/[^a-z\-_]/',
                '/([-_])[-_]+/',
            ],
            [
                '-',
                '',
                '$1',
            ],
            \trim(\mb_strtolower($key))
        );
        if (!$key) {
            return null;
        }

        return $key;
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
}
