<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'vehicle_id' => $this->id,
            'matricula_real' => $this->matricula,
            'matricula' => "P-". strtoupper($this->matricula),
            'type_vehicle_id' => $this->type_vehicle_id,
            'type_vehicle' => $this->name,
            'employee_name' => "$this->first_name $this->last_name"
        ];
    }
}
