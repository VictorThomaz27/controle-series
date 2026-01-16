<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name','cpf','email','phone','address_line','address_number','city','state','zip_code','lat','lon'
    ];

    protected $casts = [
        'lat' => 'float',
        'lon' => 'float',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
