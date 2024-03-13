<?php

namespace Charcoal\CookieConsent\Model\Repository;

use Charcoal\CookieConsent\Exception\ModelNotFoundException;
use Charcoal\Model\Collection;
use Charcoal\Loader\CollectionLoader;
use Charcoal\Model\ModelInterface;

/**
 * Link Model Repository Decorator
 *
 * Retrieves a model from a model identifier for a link.
 */
final class LinkRelationRepository
{
    /** @var CollectionLoader<ModelInterface> */
    protected CollectionLoader $collectionLoader;

    /**
     * @param  CollectionLoader<Modelinterface> $collectionLoader
     * @return static
     */
    public static function create(
        CollectionLoader $collectionLoader
    ) {
        $modelRepository = new static();
        $modelRepository->collectionLoader = $collectionLoader;

        return $modelRepository;
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
    public function getModel($id): Modelinterface
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

    private function __construct()
    {
    }
}
