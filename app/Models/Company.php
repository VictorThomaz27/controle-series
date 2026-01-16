<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','phone','address_line','address_number','city','state','zip_code','lat','lon'
    ];

    protected $casts = [
        'lat' => 'float',
        'lon' => 'float',
    ];

    public function routes()
    {
        return $this->belongsToMany(RoutePlan::class, 'route_company', 'company_id', 'route_id');
    }
}
