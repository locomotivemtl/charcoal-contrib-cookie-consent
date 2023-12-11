<?php

namespace Charcoal\CookieConsent\Transformer;

use Charcoal\CookieConsent\Model;
use Charcoal\Model\ModelInterface;
use Charcoal\Property\StructureProperty;
use Charcoal\Translator\Translator;
use InvalidArgumentException;
use Pimple\Container;
use Traversable;

/**
 * Transformer: Consent
 */
class Consent
{
    private Translator $translator;
    private Container $transformers;

    public function __construct(
        Translator $translator,
        Container  $transformers
    ) {
        $this->translator = $translator;
        $this->transformers = $transformers;
    }

    protected function parseStructure(ModelInterface $model, string $propertyIdent): array
    {
        $structure = $this->getStructure($model, $propertyIdent);
        if (!$structure) {
            return [];
        }
        return $this->transformers[$propertyIdent]->transform($structure);
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

    public function transform(Model\Consent $consent): ?array
    {
        $consentModal = $this->parseStructure($consent, 'consentModal');
        // Early exit if no modal data.
        if (!$consentModal) {
            return [];
        }

        $preferencesModal = $this->parseStructure($consent, 'preferencesModal');

        $policyPage = $consent->getPolicyPage();
        $revision = 0;
        if ($policyPage && $policyPage->getLastModified()) {
            $revision = $policyPage->getLastModified()->format('YmdHis');
        }

        $locale = $this->translator->getLocale();

        return [
            'revision'   => $revision,
            'language'   => [
                'default'      => $locale,
                'translations' => [
                    $locale => [
                        'consentModal'     => $consentModal,
                        'preferencesModal' => $preferencesModal,
                    ]
                ]
            ],
            'categories' => [

            ],
        ];
    }
}
