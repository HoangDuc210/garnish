<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\Deposit\Type;
use Illuminate\Support\Carbon;

/**
 * @property integer $id
 * @property integer $billing_agent_id
 * @property string $type_code
 * @property string $payment_date
 * @property integer $amount
 * @property string $memo
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Deposit extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deposits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'billing_agent_id',
        'type_code',
        'payment_date',
        'amount',
        'memo',
    ];


    /**
     * formatted_payment_date
     *
     * @return string
     */
    public function getFormattedPaymentDateAttribute()
    {
        return Carbon::parse($this->payment_date)->format('Y/m/d');
    }

    /**
     * Get billing_agent_code
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function billingAgentCode(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->billingAgent?->code,
        );
    }

    /**
     * Relationship with billing agent
     */
    public function billingAgent()
    {
        return $this->belongsTo(Agent::class, 'billing_agent_id');
    }

    /**
     * Get type_label
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Type::fromValue($attributes['type_code'])->description,
        );
    }

}
