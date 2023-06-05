<?php

namespace Mi\L5Core\Criteria;

use Mi\L5Core\Contracts\CriteriaInterface;
use Mi\L5Core\Contracts\RepositoryInterface;
use Illuminate\Support\Str;

/**
 * Class FilterCriteria.
 *
 * @package Mi\L5Core\Criteria;
 */
class FilterCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    protected $input;

    /**
     * List of allowable fiters
     *
     * @var array
     */
    protected $allows;

    /**
     * Instance of FilterCriteria
     *
     * @param array $input
     * @param array $allows
     */
    public function __construct(array $input = [], array $allows = [])
    {
        $this->input  = $input;
        $this->allows = $allows;
    }

    /**
     * Apply criteria in query repository
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        foreach ($this->allows as $key => $value) {
            $filterName = is_string($key) ? $key : $value;
            $filter = is_string($key) ? $value : $this->getFilter($value);

            // TODO: add logic to filter by relation
            // for filtering by more than one value at the same time,
            // likes: store_name, store_address
            if (isset($filterName)
                && isset($this->input[$filterName])
                && $this->isValidFilter($filter)
            ) {
                $model = $filter::apply($model, $this->input[$filterName]);
            }
        }

        return $model;
    }

    private function getFilter($filterName)
    {
        return 'App\\Filters\\' . Str::studly($filterName);
    }

    private function isValidFilter($filter)
    {
        return class_exists($filter);
    }
}
