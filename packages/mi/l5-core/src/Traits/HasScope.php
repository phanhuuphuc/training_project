<?php

namespace Mi\L5Core\Traits;

trait HasScope
{
    /**
     * @var \Closure|null
     */
    protected $scopeQuery = null;

    /**
     * Add custom scope to handler
     *
     * @param \Closure $scope
     * @return self
     */
    public function scopeQuery(\Closure $scope)
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Reset handler scope
     *
     * @return self
     */
    public function resetScope()
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply scope in current Query
     *
     * @return $this
     */
    public function applyScope()
    {
        if (isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
            $callback = $this->scopeQuery;
            $this->model = $callback($this->model);
        }

        return $this;
    }
}
