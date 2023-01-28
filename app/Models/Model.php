<?php

namespace App\Models;

# Laravel 自带的。
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Foundation\Eloquent\Relations\HasManyByArrayField;

/**
 * 模型基类
 * @author bzxx
 * @date 2023-01-28
 * @package App\Models
 *
 * @method \Illuminate\Pagination\LengthAwarePaginator autoSizePaginate(string $perPageName = "pageSize", array $columns = ['*'], string $pageName = 'page', int $page = null)
 */
class Model extends \Illuminate\Database\Eloquent\Model
{


    /**
     * 自定义静态分页
     * @author kingofzihua
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int|null $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     *
     */
    public function scopeAutoSizePaginate($builder, string $perPageName = "pageSize", array $columns = ['*'], string $pageName = 'page', int $page = null)
    {
        // 当前页数
        $perPage = (int)request($perPageName) ?: $builder->getModel()->getPerPage();

        return $builder->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * 一对多 字段是一个数组的时候
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasManyByArrayField($related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasManyByArrayField(
            $instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey
        );
    }

    /**
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function newHasManyByArrayField(Builder $query, \Illuminate\Database\Eloquent\Model $parent, $foreignKey, $localKey)
    {
        return new HasManyByArrayField($query, $parent, $foreignKey, $localKey);
    }
}
