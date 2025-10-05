<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $billing_agent_id
 * @property string $calculate_date
 * @property integer $last_billed_amount
 * @property integer $deposit_amount
 * @property integer $receipt_amount
 * @property integer $collection_amount
 * @property integer $tax_amount
 * @property integer $carried_forward_amount
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Billing extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'billings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'billing_agent_id',
        'calculate_date',
        'last_billed_amount',
        'deposit_amount',
        'receipt_amount',
        'carried_forward_amount',
        'collection_amount',
        'tax_amount',
    ];

    /**
     * Get billing_amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function billingAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                (
                    $attributes['carried_forward_amount'] +
                    $attributes['receipt_amount'] +
                    $attributes['collection_amount'] +
                    $attributes['tax_amount']
                ),
        );
    }

    /**
     * Get final_receipt_amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function finalReceiptAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                $attributes['receipt_amount'] + $attributes['collection_amount']
        );
    }


    /**
     * Relationship with billing agent
     */
    public function billingAgent()
    {
        return $this->belongsTo(Agent::class, 'billing_agent_id');
    }
}
