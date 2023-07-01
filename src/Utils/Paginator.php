<?php

namespace App\Utils;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;

/**
 * Class Paginator
 *
 * A utility class for paginating query results.
 */
class Paginator
{
    /**
     * @var int The total number of items
     */
    private $total;

    /**
     * @var int The last page number
     */
    private $lastPage;

    /**
     * @var mixed The paginated items
     */
    private $items;

    /**
     * Paginate the given query or query builder.
     *
     * @param Query|QueryBuilder $query The query or query builder to paginate
     * @param int                $page  The current page number
     * @param int                $limit The number of items per page
     *
     * @return Paginator The Paginator instance
     */
    public function paginate(Query|QueryBuilder $query, int $page = 1, int $limit = 10): Paginator
    {
        $paginator = new OrmPaginator($query);

        $paginator
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $this->total = $paginator->count();
        $this->lastPage = (int) ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
        $this->items = $paginator;

        return $this;
    }

    /**
     * Get the total number of items.
     *
     * @return int The total number of items
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Get the last page number.
     *
     * @return int The last page number
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    /**
     * Get the paginated items.
     *
     * @return mixed The paginated items
     */
    public function getItems()
    {
        return $this->items;
    }
}