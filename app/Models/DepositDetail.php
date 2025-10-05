<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $deposit_id
 * @property integer $billing_agent_id
 * @property string $type_code
 * @property string $payment_date
 * @property integer $amount
 * @property string $memo
 * @property string $deleted_at
 */
class DepositDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deposits_detail';

    public $timestamps = false;

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
     * Relationship with deposit agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
