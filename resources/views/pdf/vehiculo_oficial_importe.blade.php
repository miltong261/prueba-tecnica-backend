
<!DOCTYPE html>
<html>
<head>
    <title>Hi</title>
</head>
<body>
  <h1>Reporte Estacionamiento BlueMedical</h1>
  <h1>Placa No. P-{{ strtoupper($transactions[0]->matricula) }}</h1>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Fecha y hora de entrada</th>
        <th>Fecha y hora de salida</th>
      </tr>
    </thead>
    <tbody>
      @php
        $index = 1;
      @endphp
      @foreach ($transactions as $transaction)
      <tr>
        <td style="width:200px; text-align:center">{{ $index }}</td>
        <td style="width:200px; text-align:center">{{ $transaction->date_time_start   }}</td>
        <td style="width:200px; text-align:center">Q{{ $transaction->date_time_end   }}</td>
      </tr>
      @php
          $index++
      @endphp
      @endforeach
    </tbody>
  </table>
  
</body>
</html>