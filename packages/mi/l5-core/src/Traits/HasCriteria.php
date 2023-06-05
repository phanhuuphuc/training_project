<?php

namespace Mi\L5Core\Traits;

use Illuminate\Support\Collection;
use Mi\L5Core\Contracts\CriteriaInterface;
use Mi\L5Core\Exceptions\RepositoryException;

trait HasCriteria
{
    /**
     * @var boolean
     */
    protected $skipCriteria = false;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $criteria;

    /**
     * Push new criteria to the stack
     *
     * @param mixed $criteria
     */
    public function pushCriteria($criteria)
    {
        if (is_string($criteria)) {
            $criteria = new $criteria;
        }

        if (! $criteria instanceof CriteriaInterface) {
            throw RepositoryException::invalidMethod();
        }

        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * Pop criteria to the stack
     *
     * @param mixed $criteria
     * @return self
     */
    public function popCriteria($criteria)
    {
        $this->criteria = $this->criteria->reject(function ($item) use ($criteria) {
            if (is_object($item) && is_string($criteria)) {
                return get_class($item) === $criteria;
            }

            if (is_string($item) && is_object($criteria)) {
                return $item === get_class($criteria);
            }

            return get_class($item) === get_class($criteria);
        });

        return $this;
    }

    /**
     * Get list of criteria
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Reset the list of criteria
     *
     * @return self
     */
    public function resetCriteria()
    {
        $this->criteria = new Collection();

        return $this;
    }

    /**
     * Apply the criteria to handler
     *
     * @return self
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria) {
            return $this;
        }

        $cr = $this->getCriteria();

        foreach ($cr as $c) {
            $this->model = $c->apply($this->model, $this);
        }

        return $this;
    }
}
