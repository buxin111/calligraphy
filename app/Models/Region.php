<?php

namespace App\Models;

/**
 * 区域信息
 * @author bzxx
 * @date 2023-01-28
 * @package App\Models
 *
 * @property integer id
 * @property string name
 * @property integer level
 * @property integer parent_id
 * @property-read Region[] children
 *
 * @method static \Illuminate\Database\Query\Builder|static province()
 * @method static \Illuminate\Database\Query\Builder|static city($province)
 * @method static \Illuminate\Database\Query\Builder|static district($city)
 */
class Region extends Model
{
    /*** @var string */
    protected $table = 'region';

    public const LEVEL_PROVINCE = 1;
    public const LEVEL_CITY = 2;     // city
    public const LEVEL_DISTRICT = 3; //district

    /**
     * @author wangzh
     * @date 2022-02-25
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * 查询 省份
     * @author wangzh
     * @date 2022-02-25
     * @return mixed
     */
    public function scopeProvince($query)
    {
        return $query->where('level', self::LEVEL_PROVINCE);
    }

    /**
     * 城市
     * @author wangzh
     * @date 2022-02-25
     * @return mixed
     */
    public function scopeCity($query, $province)
    {
        return $query->where('level', self::LEVEL_CITY)->where('parent_id', $province);
    }

    /**
     * 地区
     * @author wangzh
     * @date 2022-02-25
     * @return mixed
     */
    public function scopeDistrict($query, $city)
    {
        return $query->where('level', self::LEVEL_DISTRICT)->where('parent_id', $city);
    }
}
