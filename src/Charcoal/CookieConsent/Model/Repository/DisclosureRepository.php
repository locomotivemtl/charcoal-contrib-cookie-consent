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
final class DisclosureRepository
{
    protected CollectionLoader $collectionLoader;

    public function __construct(
        CollectionLoader $collectionLoader
    ) {
        $this->collectionLoader = $collectionLoader;
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
}
