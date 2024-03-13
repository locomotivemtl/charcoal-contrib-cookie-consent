<?php

namespace Charcoal\CookieConsent\Model\Repository;

use Charcoal\CookieConsent\Exception\ModelNotFoundException;
use Charcoal\Model\Collection;
use Charcoal\Model\ModelInterface;
use Charcoal\Loader\CollectionLoader;

/**
 * Link Model Repository Decorator
 *
 * Retrieves a model from a model identifier for a link.
 */
class LinkRelationRepository
{
    protected CollectionLoader $collectionLoader;

    public function __construct(
        CollectionLoader $collectionLoader
    ) {
        $this->collectionLoader = $collectionLoader;
    }

    /**
     * @psalm-return class-string
     */
    public function getModelType(): string
    {
        return $this->collectionLoader->modelClass();
    }

    /**
     * @param  int|string $id
     * @throws ModelNotFoundException
     */
    public function getModel($id): ModelInterface
    {
        $this->collectionLoader
            ->reset()
            ->addFilter('active', true)
            ->addFilter('id', $id)
            ->setNumPerPage(1);

        $results = $this->collectionLoader->load();
        if ($results) {
            if (\is_array($results)) {
                return $results[0];
            }

            if ($results instanceof Collection) {
                return $results->first();
            }

            foreach ($results as $result) {
                return $result;
            }
        }

        throw ModelNotFoundException::create(
            $this->collectionLoader->modelClass(),
            [ $id ]
        );
    }
}
