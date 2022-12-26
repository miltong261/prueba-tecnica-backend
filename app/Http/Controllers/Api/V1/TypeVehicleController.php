<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TypeVehicleRequest;
use App\Http\Resources\Api\V1\TypeVehicleResource;
use App\Models\TypeVehicle;
use Illuminate\Http\Request;

class TypeVehicleController extends Controller
{
    /**
     * Retornar lista del recurso (tipo de vehiculo).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TypeVehicleResource::collection(
            TypeVehicle::all()
        )->response()->setStatusCode(200);
    }

    /**
     * Registar el recurso en la base de datos (tipo de vehículo).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeVehicleRequest $request)
    {
        try {
            TypeVehicle::create($request->only(
                'name', 'rate'
            ));

            return response()->json([
                'message' => "¡Tipo de vehiculo agregado exitosamente!"
            ])->setStatusCode(201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualizar un recurso específico en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TypeVehicleRequest $request, $id)
    {
        try {
            $type_vehicle = TypeVehicle::where('id', $id)->update($request->only(
                'rate'
            ));

            if (!$type_vehicle) {
                return response()->json([
                    'message' => '¡Tipo de vehiculo no encontrado!'
                ])->setStatusCode(404);
            }
    
            return response()->json([
                'message' => "¡La tarifa para el tipo de vehiculo $request->nombre fue actualizado correctamente!"
            ])->setStatusCode(200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }
}
