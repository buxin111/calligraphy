<?php

namespace App\Foundation\Eloquent\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 一对多的时候，默认生成的是      $query->where($this->foreignKey, '=', $this->getParentKey());
 * 当字段是 数组的时候 应该得生成  $query->whereIn($this->foreignKey, $this->getParentKey());
 * @author wangzh
 * @date 2022-03-16
 * @package App\Foundation\Eloquent\Relations
 */
class HasManyByArrayField extends HasMany
{
    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        if (static::$constraints) {
            $query = $this->getRelationQuery();

            $query->whereIn($this->foreignKey, $this->getParentKey());

            $query->whereNotNull($this->foreignKey);
        }
    }
}
