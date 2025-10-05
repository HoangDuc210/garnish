<?php

namespace App\Models;

use App\Enums\PriceFormat;
use App\Helpers\Facades\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $name_kana
 * @property integer $unit_id
 * @property integer $price
 * @property integer $quantity
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Product extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['code', 'name', 'name_kana', 'unit_id', 'price', 'quantity', 'status'];

    /**
     * Relationship with unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    /**
     * Format price
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, ROUNDING_DECIMAL);
    }

    /**
     * Query builder search by code
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCode($builder, $code)
    {
        if ($code) {
            return $builder->where('code', $code);
        }
    }

    /**
     * Query builder search by name
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeName($builder, $name)
    {
        if ($name) {
            return $builder->where('name', $name);
        }
    }

    /**
     * Format name for receipt
    */
    public function getNameForReceiptAttribute()
    {
        $dots = strlen($this->name) > 100 ? '...' : null;
        return mb_substr($this->name, 0, 12, "UTF-8") . $dots;
    }
}
