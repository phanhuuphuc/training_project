<?php

namespace Mi\L5Core\Criteria;

use Illuminate\Support\Str;
use Mi\L5Core\Contracts\CriteriaInterface;
use Mi\L5Core\Contracts\RepositoryInterface;

/**
 * Class NewOrderCriteriaCriteria.
 *
 * @package Mi\L5Core\Criteria;
 */
class OrderCriteria implements CriteriaInterface
{
    /**
     * @var array $order
     */
    protected $orders;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * Instance of Order2Criteria
     *
     * @param array|string $input
     */
    public function __construct($input, $options = [])
    {
        $this->orders = array_filter(is_array($input) ? $input : explode(',', $input));
        $this->options = $options;
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
        if (! method_exists($repository, 'getOrderableFields')) {
            return $model;
        }

        $orderableFields = $repository->getOrderableFields();

        if (! empty($this->options)) {
            $orderableFields = array_merge($this->options, $orderableFields);
        }

        if (empty($this->orders)) {
            return $model->orderByDesc($orderableFields[0]);
        }

        foreach ($this->orders as $o) {
            $desc  = $o[0] === '-';
            $field = $desc ? substr($o, 1) : $o;

            if (! in_array($field, $orderableFields)) {
                continue;
            }

            // order by relation
            if (preg_match('/^(\w+)\.(\w+)$/', $field, $found)) {
                $relationName = Str::camel($found[1]);
                $relation     = $model->getModel()->$relationName();
                $field        = $relation->getModel()->getTable() . '_' . $found[2];

                $model = $model->select()
                    ->selectSub(function ($query) use ($relation, $found) {
                        $relatedAlias = Str::snake(class_basename($relation->getRelated()));

                        return $query->select($relatedAlias . '.' . $found[2])
                            ->fromRaw($relation->getModel()->getTable() . ' as ' . $relatedAlias)
                            ->whereColumn($relatedAlias . '.' . $relation->getOwnerKeyName(), $relation->getQualifiedForeignKeyName());
                    }, $field);
            }

            // order by normal field
            // $model = $this->orderBy($repository, $model, $field, $desc);
            $model = $desc ? $model->orderBy($field, 'DESC') : $model->orderBy($field);
        }

        return $model;
    }

    private function orderBy($repository, $model, $field, $desc = false)
    {
        $allowSortUtf8 = [];
        if (method_exists($repository, 'allowSortUtf8')) {
            $allowSortUtf8 = $repository->allowSortUtf8();
        }

        if (in_array($field, $allowSortUtf8)) {
            return $desc ? $model->orderByRaw($field . ' COLLATE "C" DESC NULLS LAST')
                : $model->orderByRaw($field . ' COLLATE "C" ASC');
        }

        return $desc ? $model->orderByRaw($field . ' DESC NULLS LAST') : $model->orderBy($field);
    }
}
