<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TransactionRequest;
use App\Models\Vehicle;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use DateTime;
use PDF;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Registra el vehiculo y la hora de entrada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start_time(TransactionRequest $request)
    {
        try {
            $extra = "";

            $vehicle = Vehicle::where('matricula', $request->matricula)->first();

            //Sí no encuentra el vehículo con la matrícula dada lo creamos
            if (!$vehicle) {
                $vehicle = Vehicle::create($request->only(
                    'matricula',
                    'type_vehicle_id'
                ));

                $extra = "El vehículo con matrícula $request->matricula y ";
            }

            //Buscamos si el vehículo se encuentra en el estacionamiento (si la hora de salida está en null, es decir, no ha salido)
            $is_vehicle = Transaction::where('vehicle_id', $vehicle->id)->whereNull('date_time_end')->first();

            if ($is_vehicle) {
                return response()->json([
                    'message' => "El vehículo con matrícula $request->matricula ya se encuentra en el estacionamiento"
                ])->setStatusCode(200);
            }

            
            $transaction = array(
                'vehicle_id' => $vehicle->id,
                'date_time_start' => now(),
                'employee_id' => $request->employee_id
            );

            Transaction::create($transaction);
    
            return response()->json([
                'message' => "¡$extra La hora de entrada fue registrada con exito!"
            ])->setStatusCode(201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualiza información de la tabla transacciones (estacionamiento - registra la hora de salida).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function end_time(TransactionRequest $request)
    {   
        try {
            $type = 0;
            $extra = "";
        
            //Buscamos si el vehículo ya no está en el estacionamiento (si la hora de salida no está en null, es decir, ya ha salido)
            $vehicle = Transaction::select('type_vehicles.name as type_vehicle', 'type_vehicles.rate', 'vehicles.id as vehicle_id', 'transactions.id', 'transactions.date_time_start')->join('vehicles','vehicles.id', '=', 'transactions.vehicle_id')->join('type_vehicles','type_vehicles.id', '=', 'vehicles.type_vehicle_id')->where('vehicles.matricula', $request->matricula)->whereNull('transactions.date_time_end')->first();

            if (!$vehicle) {
                return response()->json([
                    'message' => "¡El vehículo con matrícula $request->matricula ya se le ha marcado la salida!"
                ])->setStatusCode(200);
            }

            //Realizamos la operación para obtener los minutos que estuvo parqueado (tomando en cuenta la hora de entrada y salida)
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vehicle->date_time_start);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());
            $minutes = $to->diffInMinutes($from);

            $amount = $vehicle->rate * $minutes;

            $transaction = array(
                'vehicle_id' => $vehicle->vehicle_id,
                'date_time_end' => now(),
                'amount' => $amount,
                'status' => 0
            );

            //Enviamos el tipo (oficial - listado de fechas y horas de estacionamiento o no residente - para el reporte de importe a pagar)
            if($vehicle->type_vehicle == 'Oficial') {
                $type = 1;
            } elseif ($vehicle->type_vehicle == 'No Residente') {
                $type = 3;
            }  

            Transaction::where('id', $vehicle->id)->update($transaction);

            return response()->json([
                'message' => "¡La hora de salida fue registrada con exito $extra!",
                'type' => $type
            ])->setStatusCode(200);   
        } catch (\Throwable $th) {
            return response()->josn([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualiza la fecha y hora de salida que aún no han salido
     * Actualiza el estado para todos los vehículos
     */ 
    public function start_month()
    {   
        try {
            Transaction::where('status', 1)->whereNull('date_time_end')->update(['date_time_end' => now()]);
            Transaction::where('status', 0)->update(['status' => 1]);

            return response()->json([
                'message' => "¡Se ha restablecido el mes!"
            ])->setStatusCode(200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Retorna el reporte del importe a pagar dada la matrícula de un vehículo no residente
     */ 
    public function amount_import(Request $request, $matricula)
    {
        $transaction = Transaction::select('transactions.id', 'transactions.date_time_start', 'transactions.date_time_end', 'transactions.amount', 'vehicles.matricula', 'type_vehicles.rate')->join('vehicles', 'vehicles.id', '=', 'transactions.vehicle_id')->join('type_vehicles', 'type_vehicles.id', '=', 'vehicles.type_vehicle_id')->where('matricula', $matricula)->orderBy('id', 'desc')->limit(1)->get();

        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction[0]["date_time_start"]);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction[0]["date_time_end"]);
        $minutes = $to->diffInMinutes($from);

        $data = [
            'matricula' => "P-" . strtoupper($transaction[0]["matricula"]),
            'minutes' => $minutes . " minutos",
            'rate' => "Q" . $transaction[0]["rate"] . "/minuto",
            'amount' => "Q{$transaction[0]["amount"]}"
        ];

        $pdf = PDF::loadView('pdf.importe', $data);
    
        return $pdf->download(date('Y-m-d_H:i:s'). "_importe" . ".pdf");
    }

    /**
     * Retorna el reporte de las fechas y horas dada la matrícula de un vehículo oficial
     */ 
    public function oficial_vehicle_import(Request $request, $matricula)
    {
        $transactions = Transaction::select('transactions.id', 'transactions.date_time_start', 'transactions.date_time_end', 'vehicles.matricula')->join('vehicles', 'vehicles.id', '=', 'transactions.vehicle_id')->where('matricula', $matricula)->where('status', 0)->whereNotNull('date_time_end')->get();

        $pdf = PDF::loadView('pdf.vehiculo_oficial_importe', compact('transactions'));
    
        return $pdf->download(date('Y-m-d_H:i:s'). "_vehiculo_oficial_importe" . ".pdf");
    }

    /**
     * Retorna el reporte con el nombre dado con los minutos y monto a pagar del mes de los vehículos residentes
     */ 
    public function report(Request $request, $name_pdf)
    {
        $resident_vehicles = DB::table('transactions')->join('vehicles','vehicles.id', '=', 'transactions.vehicle_id')->join('type_vehicles','type_vehicles.id', '=', 'vehicles.type_vehicle_id')->select(
            'vehicles.matricula', 
            'type_vehicles.rate',
            DB::raw('SUM(ABS(TIMESTAMPDIFF(MINUTE,transactions.date_time_start,transactions.date_time_end))) as minutes'),
            DB::raw('SUM(transactions.amount) as total'),
        )->groupBy(
            'transactions.vehicle_id'
        )->where('type_vehicles.name', 'Residente')->where('status', 0)->get();

        $pdf = PDF::loadView('pdf.residentes', compact('resident_vehicles'));
    
        return $pdf->download(date('Y-m-d_H:i:s'). "_" . $name_pdf . ".pdf");
    }
}
