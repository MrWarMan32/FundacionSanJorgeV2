<!DOCTYPE html>
<html>
<head>
    <title>Certificado de Asistencia a Terapia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .content {
            margin-top: 20px;
            font-size: 18px;
            line-height: 1.6;
        }
        .content p {
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 16px;
            color: #777;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
            font-size: 18px;
        }
        .signature p {
            margin: 0;
        }
        .signature .name {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('path/to/logo.png') }}" alt="Logo">
            <h1>Certificado de Asistencia a Terapia</h1>
        </div>
        <div class="content">
            <p>Por medio del presente, se certifica que <strong>{{ $shift['patient_name'] }}</strong>, identificado con [Número de Identificación], ha asistido a sesiones de terapia en <strong>[Nombre de la Institución/Centro de Terapia]</strong>.</p>
            <p><strong>Tipo de Terapia:</strong> {{ $shift['therapy_type'] }}</p>
            <p><strong>Terapeuta a Cargo:</strong> {{ $shift['doctor_name'] }}</p>
            <p><strong>Período de Asistencia:</strong> Desde {{ $shift['start_time'] }} hasta {{ $shift['end_time'] }}</p>
            <p>Este certificado se expide a petición del interesado para los fines que estime convenientes.</p>
        </div>
        <div class="footer">
            <p>Fecha de Emisión: {{ now()->format('d/m/Y') }}</p>
        </div>
        <div class="signature">
            <p class="name">{{ $shift['doctor_name'] }}</p>
            <p>cargo terapeuta</p>
            <p>[Sello de la Institución/Centro de Terapia (opcional)]</p>
        </div>
    </div>
</body>
</html>