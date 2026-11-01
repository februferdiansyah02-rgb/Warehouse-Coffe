<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseProfile extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'logo',
        'address',
        'phone',
        'email',
        'pic',
        'description',
    ];
}