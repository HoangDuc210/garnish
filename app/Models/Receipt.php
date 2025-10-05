<?php

namespace App\Models;

use App\Enums\PriceFormat;
use App\Enums\PrintStatus;
use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property integer $id
 * @property integer $code
 * @property string $type_code
 * @property integer $agent_id
 * @property string $transaction_date
 * @property boolean $print_status
 * @property string $memo
 * @property boolean $tax
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Receipt extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receipts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type_code',
        'agent_id',
        'transaction_date',
        'print_status',
        'tax',
        'memo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'transaction_date'  => 'date:Y/m/d',
    ];

    /**
     * formatted_transaction_date
     *
     * @return string
     */
    public function getFormattedTransactionDateAttribute()
    {
        return Carbon::parse($this->transaction_date)->format('Y/m/d');
    }

    /**
     * Get total_amount
     *
     * @return int
     */
    protected function getTotalAmountAttribute()
    {
        return $this->receiptDetails->sum('amount');
    }

    /**
     * Get billing_agent_id
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function billingAgentId(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->agent->billing_agent_id,
        );
    }

    /**
     * Get billing_agent
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function billingAgent(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->agent->billingAgent,
        );
    }

    /**
     * Get deposits
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function deposits(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
            $this->agent->billingAgent->deposits
                ->where('payment_date', $attributes['transaction_date'])->values()
        );
    }

    /**
     * Get deposit_amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function depositAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->deposits->sum('amount'),
        );
    }

    /**
     * Relationship with agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id')->withTrashed();
    }

    /**
     * Relationship with receiptDetails
     */
    public function receiptDetails()
    {
        return $this->hasMany(ReceiptDetail::class)->orderBy('sort', 'asc');
    }

    /**
     * Relationship with products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'receipt_details', 'receipt_id', 'product_id')->withPivot('name', 'code', 'product_id','quantity', 'price', 'unit_id', 'memo');
    }

    /**
     * Get status print
     *
     * @return string
     */
    public function getPrintAttribute()
    {
        return PrintStatus::tryFrom($this->print_status)->label();
    }

    /**
     * Format date transaction_date
     *
     * @return string
     */
    public function getTransactionDateFmAttribute()
    {
        return Carbon::parse($this->transaction_date)->format('Y/m/d');
    }

    /**
     * Format created_at
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d');
    }

    /**
     * Get consumption tax attribute
     */
    public function getConsumptionTaxAttribute()
    {
        $consumption = $this->price_total_product * $this->tax / 100;

        return $this->roundingAmount($consumption, $this->agent->tax_fraction_rounding_code);
    }

    /**
     * Get total receipt
     */
    public function getTotalReceiptAmountAttribute()
    {
        $total = $this->price_total_product + $this->consumption_tax;

        return $this->roundingAmount($total, $this->agent->fraction_rounding_code);
    }

    /**
     * Get total price product
     *
     * @return int
     */
    public function getPriceTotalProductAttribute()
    {
        return $this->receiptDetails->sum('amount');
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

    /**
     * Format transaction date with day
     *
     * @return string
     */
    public function getFormattedTransactionDateWithDayAttribute()
    {
        return Carbon::parse($this->transaction_date)->format('d');
    }

    /**
     * Rounding total receipt amount
     *
     * @return string
     */
    public function getRoundTotalReceiptAmountAttribute()
    {
        return $this->roundingAmount($this->total_receipt_amount, $this->agent->fraction_rounding_code);
    }
}
