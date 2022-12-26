<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\VehicleRequest;
use App\Http\Resources\Api\V1\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Retornar lista del recurso (vehículos).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehiculos = Vehicle::select('vehicles.id', 'vehicles.matricula', 'vehicles.type_vehicle_id', 'type_vehicles.name', 'employees.first_name', 'employees.last_name')->join('type_vehicles', 'type_vehicles.id', '=', 'vehicles.type_vehicle_id')->join('transactions', 'transactions.vehicle_id', '=', 'vehicles.id')->join('employees', 'employees.id', '=', 'transactions.employee_id')->get();

        return VehicleResource::collection($vehiculos)->response()->setStatusCode(200);
    }

    /**
     * Actualizar un recurso específico en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VehicleRequest $request, $id)
    {
        try {
            $vehicle = Vehicle::where('id', $id)->update($request->only(
                'matricula'
            ));

            if (!$vehicle) {
                return response()->json([
                    'message' => '¡Matricula no encontrada!'
                ])->setStatusCode(404);
            }
    
            return response()->json([
                'message' => "¡La matricula del vehículo fue actualizada correctamente!"
            ])->setStatusCode(200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }
}
