<?php

namespace Mi\L5Core\Contracts;

interface HasCriteriaInterface
{
    /**
     * Push new criteria to the stack
     *
     * @param mixed $criteria
     * @return self
     */
    public function pushCriteria($criteria);

    /**
     * Pop criteria to the stack
     *
     * @param mixed $criteria
     * @return self
     */
    public function popCriteria($criteria);

    /**
     * Get list of criteria
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCriteria();

    /**
     * Reset the list of criteria
     *
     * @return self
     */
    public function resetCriteria();

    /**
     * Apply the criteria to handler
     *
     * @return self
     */
    public function applyCriteria();
}
