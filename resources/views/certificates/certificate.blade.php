<!DOCTYPE html>
<html>
<head>
    <title>Certificado de Cita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            margin-top: 50px;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
        }
        .details {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Certificado de Cita</div>
        <div class="details">
            <p>Paciente: {{ $shift->patient->name }}</p>
            <p>Doctor: {{ $shift->doctor->name }}</p>
            <p>Terapia: {{ $shift->therapy->therapy_type }}</p>
            <p>Fecha y Hora: {{ $shift->start_time }} - {{ $shift->end_time }}</p>
            <p>Notas: {{ $shift->notes }}</p>
        </div>
    </div>
</body>
</html>