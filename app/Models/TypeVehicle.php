<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate'
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
