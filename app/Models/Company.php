<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property integer $id
 * @property string $name
 * @property string $name_kana
 * @property string $post_code
 * @property string $address
 * @property string $address_more
 * @property string $tel
 * @property string $fax
 * @property string $bank_account
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Company extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'address_more',
        'post_code',
        'tel',
        'fax',
        'bank_account',
        'regis_number'
    ];
}
