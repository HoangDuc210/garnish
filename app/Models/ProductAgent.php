<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $agent_id
 * @property integer $product_id
 * @property integer $unit_id
 * @property integer $price
 * @property string $created_at
 * @property string $updated_at
 */
class ProductAgent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_agent';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'agent_id',
        'product_id',
        'unit_id',
        'price'
    ];

    /**
     * Relationship with products
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with products
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Relationship with products
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Query builder search by agent
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAgent($builder, $agentId)
    {
        return $builder->where('agent_id', $agentId);
    }

    public function scopeUnit($builder, $unitId)
    {
        return $builder->where('unit_id', $unitId);
    }
}
