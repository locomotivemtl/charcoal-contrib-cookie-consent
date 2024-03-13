<?php

namespace Charcoal\CookieConsent\Exception;

/**
 * @psalm-template TModel of \Charcoal\Model\Modelinterface
 */
final class ModelNotFoundException extends \RuntimeException implements ExceptionInterface
{
    /** @vpsalm-var class-string<TModel> */
    protected ?string $model = null;

    /** @psalm-var list<int|string> */
    protected array $ids = [];

    /**
     * @param  (int|string)[] $ids
     * @return static
     *
     * @psalm-param  class-string<TModel> $model
     * @psalm-return list<int|string>     $ids
     */
    public static function create(string $model, array $ids = [])
    {
        $exception = new static();
        $exception->setModel($model, $ids);

        return $exception;
    }

    /**
     * @param  (int|string)[] $ids
     * @return $this
     *
     * @psalm-param  class-string<TModel> $model
     * @psalm-return list<int|string>     $ids
     */
    public function setModel(string $model, array $ids = [])
    {
        $this->model = $model;
        $this->ids   = $ids;

        $this->message = "No results for model [{$model}]";

        if (\count($this->ids) > 0) {
            $this->message .= ': ' . \implode(', ', $this->ids);
        }

        return $this;
    }

    /**
     * @psalm-return class-string<TModel>
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @return (int|string)[]
     *
     * @psalm-return list<int|string>
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
