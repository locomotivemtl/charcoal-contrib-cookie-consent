<?php

namespace Charcoal\CookieConsent\Transformer;

use Charcoal\Model\ModelInterface;
use Charcoal\Property\StructureProperty;
use InvalidArgumentException;
use Pimple\Container;

trait StructureAwareTrait
{
    protected Container $transformers;

    protected function parseStructure(
        ModelInterface $model,
        string $propertyIdent,
        string $transformerIdent = null
    ): array {
        $structure = $this->getStructure($model, $propertyIdent);
        if (!$structure) {
            return [];
        }
        $transformerIdent ??= $propertyIdent;
        return $this->transformers[$transformerIdent]->transform($structure);
    }

    protected function getStructure(ModelInterface $model, string $propertyIdent): ?ModelInterface
    {
        if (!$model->hasProperty($propertyIdent)) {
            throw new InvalidArgumentException(
                sprintf('Property ident %s does not exist for model %s', $propertyIdent, get_class($model))
            );
        }

        if (!$model->p($propertyIdent) instanceof StructureProperty) {
            throw new InvalidArgumentException(
                sprintf(
                    'Model property from ident %s must be a StructureProperty, %s received',
                    $propertyIdent,
                    get_class($model->p($propertyIdent))
                )
            );
        }

        if (!$model[$propertyIdent]) {
            return null;
        }

        $pModel = $model->p($propertyIdent)->toModel();
        $modelData = $model[$propertyIdent];
        return $pModel->setData($modelData);
    }
}
