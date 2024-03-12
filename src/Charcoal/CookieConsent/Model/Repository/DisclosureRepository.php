<?php

namespace Charcoal\CookieConsent\Model\Repository;

use Charcoal\CookieConsent\Exception\ModelNotFoundException;
use Charcoal\CookieConsent\Model\Disclosure;
use Charcoal\Model\Collection;
use Charcoal\Loader\CollectionLoader;

/**
 * Cookie Disclosure Repository Decorator
 *
 * Retrieves the active Disclosure models.
 */
class DisclosureRepository
{
    /** @var CollectionLoader<Disclosure> */
    protected CollectionLoader $collectionLoader;

    /**
     * @param  CollectionLoader<Disclosure> $collectionLoader
     * @return static
     */
    public static function create(
        CollectionLoader $collectionLoader
    ) {
        $repository = new static();
        $repository->collectionLoader = $collectionLoader;

        return $repository;
    }

    /**
     * @param  int|string|null $id
     * @throws ModelNotFoundException
     */
    public function getDisclosure($id = null): Disclosure
    {
        $this->collectionLoader
            ->reset()
            ->addFilter('active', true)
            ->addOrder('position', 'asc')
            ->setNumPerPage(1);

        if ($id) {
            $this->collectionLoader->addFilter('id', $id);
        }

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
            $this->collectionLoader->modelClass()
        );
    }

    private function __construct()
    {
    }
}
