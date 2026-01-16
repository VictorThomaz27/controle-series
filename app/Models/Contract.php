<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'type','client_id','driver_id','route_id','monthly_value','tolerance_minutes','vacation_policy','anticorruption_acceptance','payer_name','payer_cnpj','payer_address','start_date','status','content'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function route()
    {
        return $this->belongsTo(RoutePlan::class, 'route_id');
    }
}
