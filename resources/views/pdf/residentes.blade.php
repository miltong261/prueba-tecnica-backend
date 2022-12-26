
<!DOCTYPE html>
<html>
<head>
    <title>Hi</title>
</head>
<body>
    <h1>Reporte Estacionamiento BlueMedical</h1>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>No. Placa</th>
        <th>Tiempo Estacionado (min)</th>
        <th>Tarifa</th>
        <th>Cantidad a Pagar</th>
      </tr>
    </thead>
    <tbody>
      @php
        $index = 1;
      @endphp
      @foreach ($resident_vehicles as $resident_vehicle)
      <tr>
        <td style="width:100px; text-align:center">{{ $index }}</td>
        <td style="width:100px; text-align:center">P-{{ strtoupper($resident_vehicle->matricula)   }}</td>
        <td style="width:100px; text-align:center">{{ $resident_vehicle->minutes   }}</td>
        <td style="width:100px; text-align:center">Q{{ $resident_vehicle->rate   }}/minuto</td>
        <td style="width:100px; text-align:center">Q{{ $resident_vehicle->total   }}</td>
      </tr>
      @php
        $index++
      @endphp
      @endforeach
    </tbody>
  </table>
  
</body>
</html>