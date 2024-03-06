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
    /** @var CollectionLoader<Category> */
    protected CollectionLoader $collectionLoader;

    /**
     * @param  CollectionLoader<Category> $collectionLoader
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

    private function __construct()
    {
    }
}
