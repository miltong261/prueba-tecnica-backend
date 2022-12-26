<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula',
        'type_vehicle_id'
    ];

    public function type_vehicle()
    {
        return $this->belongsTo(TypeVehicle::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
