<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name','cpf_cnpj','email','phone','vehicle_model','vehicle_plate','vehicle_capacity'
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
