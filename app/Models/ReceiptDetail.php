<?php

namespace App\Models;

use App\Enums\PriceFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property integer $id
 * @property integer $receipt_id
 * @property integer $product_id
 * @property integer $unit_id
 * @property string $name
 * @property string $code
 * @property integer $price
 * @property string $memo
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $quantity
 */
class ReceiptDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receipt_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'receipt_id',
        'product_id',
        'unit_id',
        'price',
        'quantity',
        'memo',
        'name',
        'code',
        'sort',
    ];

    /**
     * Relationship with products
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Relationship with products
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Relationship with products
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    /**
     * Get price of receipt unit by code
     *
     * @return mixed
     */
    public function getPriceFmAttribute()
    {
        return number_format($this->price);
    }

    /**
     * Get amount
     *
     * @return int
     */
    protected function getAmountAttribute()
    {
        $amount = $this->price * $this->quantity;
        $rounding = $this->receipt->agent->fraction_rounding_code;
        return $this->roundingAmount($amount, $rounding);
    }

    /**
     * Format name for receipt
    */
    public function getNameForReceiptAttribute()
    {
        $dots = strlen($this->name) > 100 ? '...' : null;
        return mb_substr($this->name, 0, 12, "UTF-8") . $dots;
    }

    /**
     * Rounding amount the receipt
     *
     * @return int
     */
    public function roundingAmount($amount, $roundingType)
    {
        switch ($roundingType) {
            case PriceFormat::TRUNCATION():
                return (int)$amount;
            case PriceFormat::ROUNDING_UP():
                return !is_integer($amount) ? ((int)$amount) + 1 : (int)$amount;
            default:
                return (int)round($amount);
        }
    }
}
