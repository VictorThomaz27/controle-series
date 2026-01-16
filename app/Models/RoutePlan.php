<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutePlan extends Model
{
    use HasFactory;

    protected $table = 'routes';

    protected $fillable = [
        'origin_address','destination_address','start_time','end_time','days','distance_km','driver_id'
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'route_id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'route_client', 'route_id', 'client_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'route_company', 'route_id', 'company_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
