<?php

namespace Charcoal\CookieConsent\Model\Structure;

use Charcoal\CookieConsent\Model\Repository\LinkRelationRepository;
use Charcoal\Model\Modelinterface;
use Charcoal\Property\Structure\StructureModel;
use Charcoal\Translator\TranslatorAwareTrait;
use Charcoal\Translator\Translation;
use Charcoal\Validator\ValidatorInterface;
use Pimple\Container;

/**
 * Structure Model: Link
 */
class Link extends StructureModel
{
    use TranslatorAwareTrait;

    public const TYPE_FILE    = 'file';
    public const TYPE_MODEL   = 'model';
    public const TYPE_URL     = 'url';
    public const DEFAULT_TYPE = self::TYPE_MODEL;

    public const TARGET_BLANK = '_blank';

    private bool $active = false;
    private ?Translation $label = null;
    private string $type = self::DEFAULT_TYPE;
    private ?string $target = null;

    private ?Translation $filePath = null;
    /** @var int|string|null */
    private $modelId = null;
    private ?Translation $url = null;

    private ?LinkRelationRepository $modelRepository = null;

    public function getActive(): bool
    {
        return $this->active;
    }

    public function isActive(): bool
    {
        return $this->getActive();
    }

    /**
     * @param mixed $active The active toggle.
     */
    public function setActive($active): self
    {
        $this->active = (bool)$active;

        return $this;
    }

    public function getLabel(): ?Translation
    {
        return $this->label;
    }

    /**
     * @param mixed $label The link label.
     */
    public function setLabel($label): self
    {
        $this->label = $this->translator()->translation($label);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param mixed $type The {@see self::TYPE_* link type}.
     */
    public function setType($type): self
    {
        if (!$this->isSupportedType($type)) {
            $type = $this->getDefaultType();
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     *
     * @psalm-return self::TYPE_*
     */
    public function getDefaultType(): string
    {
        return self::DEFAULT_TYPE;
    }

    /**
     * @return string[]
     *
     * @psalm-return list<self::TYPE_*>
     */
    public function getSuportedTypes(): array
    {
        return [
            self::TYPE_FILE,
            self::TYPE_MODEL,
            self::TYPE_URL,
        ];
    }

    /**
     * @param mixed $type A {@see self::TYPE_* link type}.
     */
    public function isSupportedType($type): bool
    {
        return \in_array($type, $this->getSuportedTypes(), true);
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * @param mixed $target The {@see self::TARGET_* link target}.
     */
    public function setTarget($target): self
    {
        if (!$this->isSupportedTarget($target)) {
            $target = $this->getDefaultTarget();
        }

        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultTarget(): ?string
    {
        return null;
    }

    /**
     * @return string[]
     *
     * @psalm-return list<self::TARGET_*>
     */
    public function getSuportedTargets(): array
    {
        return [
            self::TARGET_BLANK,
        ];
    }

    /**
     * @param mixed $target A {@see self::TARGET_* link target}.
     */
    public function isSupportedTarget($target): bool
    {
        return \in_array($target, $this->getSuportedTargets(), true);
    }

    /**
     * @return Translation|string|null
     */
    public function getHref()
    {
        switch ($this->getType()) {
            case self::TYPE_FILE: {
                return $this->getFilePath();
            }

            case self::TYPE_MODEL: {
                return $this->getModel()['url'];
            }

            case self::TYPE_URL: {
                return $this->getUrl();
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        $attributes = [];

        $href = $this->getHref();
        if ($href instanceof Translation) {
            $locale = $this->translator()->getLocale();
            if ($locale && (!isset($href[$locale]) || $href[$locale] === '')) {
                foreach ($href->data() as $locale => $message) {
                    if ($message !== '') {
                        $attributes['hreflang'] = $locale;
                        break;
                    }
                }
            }
        }

        $target = $this->getTarget();
        if ($target) {
            $attributes['target'] = $target;
            $attributes['rel']    = 'noopener noreferrer';
        }

        if ($this->getType() === self::TYPE_URL) {
            $attributes['rel'] = 'noopener noreferrer';
        }

        return $attributes;
    }

    public function getFilePath(): ?Translation
    {
        return $this->filePath;
    }

    /**
     * @param mixed $path A file path.
     */
    public function setFilePath($path): self
    {
        $this->filePath = $this->translator()->translation($path);

        return $this;
    }

    public function getModel(): ModelInterface
    {
        $modelId = $this->getModelId();
        if (!$modelId) {
            return null;
        }

        return $this->getModelRepository()->getModel($modelId);
    }

    /**
     * @return int|string|null
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param mixed $modelId A model object ID.
     */
    public function setModelId($modelId): self
    {
        $modelId = $this->property('modelId')->parseVal($modelId);
        if (\is_string($modelId) && \ctype_digit($modelId)) {
            $modelId = (int)$modelId;
        }

        $this->modelId = $modelId;

        return $this;
    }

    public function getUrl(): ?Translation
    {
        return $this->url;
    }

    /**
     * @param mixed $url A URL.
     */
    public function setUrl($url): self
    {
        $this->url = $this->translator()->translation($url);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ValidatorInterface &$v = null)
    {
        return parent::validate($v) &&
            $this->validateFilePath() &&
            $this->validateModelId() &&
            $this->validateUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function filterPropertyMetadata($propertyMetadata, $propertyIdent)
    {
        if ($propertyIdent === 'modelId') {
            $propertyMetadata['obj_type'] = $this->getModelRepository()->getModelType();
        }

        return $propertyMetadata;
    }

    /**
     * Determines whether a variable is empty.
     *
     * @param mixed $value The value to check.
     */
    protected function isEmpty($value): bool
    {
        if (\is_null($value)) {
            return true;
        }

        if (\is_string($value)) {
            return (\trim($value) === '');
        }

        if ($value instanceof Translation) {
            foreach ($value->data() as $translation) {
                if (!static::isEmpty($translation)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    protected function getModelRepository(): LinkRelationRepository
    {
        return $this->modelRepository;
    }

    protected function setModelRepository(LinkRelationRepository $repository): void
    {
        $this->modelRepository = $repository;
    }

    protected function setDependencies(Container $container)
    {
        $this->setTranslator($container['translator']);
        $this->setModelRepository($container['cookie-consent/repository/link-relation']);
        parent::setDependencies($container);
    }

    /**
     * {@inheritdoc}
     *
     * Ensure important properties are set first.
     */
    protected function setIdFromData(array $data)
    {
        $key = 'type';
        if (\array_key_exists($key, $data)) {
            $this->setType($data[$key]);
            unset($data[$key]);
        }

        return parent::setIdFromData($data);
    }

    protected function validateFilePath(): bool
    {
        if ($this->getType() !== self::TYPE_FILE) {
            return true;
        }

        $path = $this->getFilePath();
        if (!$this->isEmpty($path)) {
            return true;
        }

        $this->validator()->error(
            $this->translator()->trans('form.validation.value_missing_file')
        );
        return false;
    }

    protected function validateModelId(): bool
    {
        if ($this->getType() !== self::TYPE_MODEL) {
            return true;
        }

        $modelId = $this->getModelId();
        if (!$modelId) {
            $this->validator()->error(
                $this->translator()->trans('cookie-consent.admin.form.link-model.error.missing')
            );
            return false;
        }

        /**
         * This is a dirty hack to ensure the user does not
         * accidentally chose to redirect the parent model
         * to itself.
         */
        $objType = ($_POST['obj_type'] ?? null);
        $objId   = ($_POST['obj_id'] ?? null);
        if (\is_string($objId) && \ctype_digit($objId)) {
            $objId = (int)$objId;
        }

        $modelType = $this->getModelRepository()->getModelType();
        if ($objType === $modelType && $objId === $modelId) {
            $this->validator()->error(
                $this->translator()->trans('cookie-consent.admin.form.link-model.error.recursion')
            );
            return false;
        }

        return true;
    }

    protected function validateUrl(): bool
    {
        if ($this->getType() !== self::TYPE_URL) {
            return true;
        }

        $url = $this->getUrl();
        if (!$this->isEmpty($url)) {
            return true;
        }

        $this->validator()->error(
            $this->translator()->trans('form.validation.value_missing')
        );
        return false;
    }
}
