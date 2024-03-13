<?php

namespace Charcoal\CookieConsent\Model\Repository;

use Charcoal\CookieConsent\Model\Category;
use Charcoal\Model\Collection;
use Charcoal\Loader\CollectionLoader;

/**
 * Cookie Category Repository Decorator
 *
 * Retrieves the active Category models.
 */
class CategoryRepository
{
    protected CollectionLoader $collectionLoader;

    public function __construct(
        CollectionLoader $collectionLoader
    ) {
        $this->collectionLoader = $collectionLoader;
    }

    /**
     * @param  (int|string)[] $ids
     * @return Category[]
     */
    public function findCategories(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $this->collectionLoader
            ->reset()
            ->addFilter('active', true)
            ->addFilter([
                'property' => 'id',
                'operator' => 'IN',
                'value'    => $ids,
            ]);

        $results = $this->collectionLoader->load();

        if (\is_array($results)) {
            return $results;
        }

        if ($results instanceof Collection) {
            return $results->all();
        }

        $categories = [];
        foreach ($results as $result) {
            $categories[] = $result;
        }

        return $categories;
    }
}
