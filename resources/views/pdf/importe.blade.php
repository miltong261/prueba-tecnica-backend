
<!DOCTYPE html>
<html>
<head>
    <title>Hi</title>
</head>
<body>
    <h1>Reporte Estacionamiento ORCAapplicants</h1>
  <table>
    <thead>
      <tr>
        <th>No. Placa</th>
        <th>Tiempo Estacionado (min)</th>
        <th>Tarifa</th>
        <th>Cantidad a Pagar</th>
      </tr>
    </thead>
    <tbody>  
      <tr>
        <td style="width:150px; text-align:center">{{ $matricula   }}</td>
        <td style="width:150px; text-align:center">{{ $minutes   }}</td>
        <td style="width:150px; text-align:center">{{ $rate   }}</td>
        <td style="width:150px; text-align:center">{{ $amount   }}</td>
      </tr>
    </tbody>
  </table>
  
</body>
</html>