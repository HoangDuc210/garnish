<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer $id
 * @property string $name
 * @property string $name_kana
 * @property string $code
 * @property string $post_code
 * @property string $address
 * @property string $address_more
 * @property string $tel
 * @property string $fax
 * @property integer $closing_date
 * @property string $fraction_rounding_code
 * @property string $tax_type_code
 * @property string $tax_fraction_rounding_code
 * @property string $tax_taxation_method_code
 * @property integer $billing_agent_id
 * @property string $billing_name
 * @property string $billing_address
 * @property string $billing_address_more
 * @property string $billing_tel
 * @property string $billing_fax
 * @property string $billing_source_company_id
 * @property float $tax_rate
 * @property float $collection_rate
 * @property integer $print_type
 * @property string $memo
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Agent extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'code',
        'name',
        'name_kana',
        'post_code',
        'address',
        'address_more',
        'tel',
        'fax',
        'closing_date',
        'fraction_rounding_code',
        'tax_type_code',
        'tax_fraction_rounding_code',
        'tax_taxation_method_code',
        'billing_agent_id',
        'billing_name',
        'billing_address',
        'billing_address_more',
        'billing_tel',
        'billing_fax',
        'billing_source_company_id',
        'memo',
        'collection_rate',
        'print_type',
        'tax_rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trading_date' => 'date:Y/m/d',
        'collection_rate' => 'float',
        'tax_rate' => 'float',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

     /**
     * Relationship with  parent agent
     */
    public function billingAgent()
    {
        return $this->belongsTo(Agent::class, 'billing_agent_id');
    }

    /**
     * Relationship with  children agent
     */
    public function childAgents()
    {
        return $this->hasMany(Agent::class, 'billing_agent_id');
    }


    /**
     * Relationship with deposits
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'billing_agent_id', 'id');
    }

    /**
     * Relationship with  receipts
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'agent_id');
    }

    /**
     * Relationship with  parent agent
     */
    public function receipt()
    {
        return $this->hasOne(Receipt::class, 'agent_id');
    }

    /**
     * Formatted id
     *
     * @return string
     */
    public function getFormattedIdAttribute()
    {
        return sprintf('%02d',  $this->id);
    }
}
