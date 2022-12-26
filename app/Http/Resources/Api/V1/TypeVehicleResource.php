<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeVehicleResource extends JsonResource
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
            'type_vehicle_id' => $this->id,
            'name' => $this->name,
            'rate_real' => $this->rate,
            'rate' => "Q{$this->rate}/minuto"
        ];
    }
}
