<!DOCTYPE html>
<html>
<head>
    <title>Certificacion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            width: 100%;
            height: 100%;
            margin: 0px;
            padding: 20px;
            background-color: #fff;
            box-shadow: none;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .header {
            /* width: 100%;
            position: relative;
            margin-bottom: 20px; */
            width: 100%;
            position: fixed; /* Cambia 'relative' a 'fixed' */
            top: 0; /* Coloca el encabezado en la parte superior de la página */
            left: 0; /* Asegura que el encabezado ocupe todo el ancho */
            background-color: #fff; /* Opcional: añade un fondo blanco para mayor claridad */
            z-index: 1000; /* Asegura que el encabezado esté encima de otros elementos */
            padding: 10px 0; /* Opcional: añade un poco de padding */
        }
        .left-text {
            position: absolute;
            top: 60px;
            right: 10px;
            text-align: right;
            /* font-style: italic; */
            font-family: 'lucida-calligraphy';
            font-size: 12px;
        }
        .center-text {
            text-align: center;
            padding: 10px;
            width: 100%;
            font-size: 20px;
        }
        .right-image {
            position: absolute;
            top: 0px;
            left: 10px;
            width: 110px;
        }
        .content {
            margin-top: 120px;
            font-size: 18px;
            line-height: 1.6;
        }
        .content p {
            margin: 10px 0;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
            font-size: 18px;

            position: absolute; /* Fija la posición de .signature */
            bottom: 50px; /* Coloca .signature a 20px del fondo */
            /* text-align: center; Centra el texto dentro de .signature */
            width: 100%; /* Ocupa todo el ancho disponible */
        }

        .signature p {
            margin: 0;
        }


        .signature {
        margin-top: 50px;
        text-align: right;
        font-size: 18px;
        width: 400px; /* Ajusta el ancho según sea necesario */
        display: inline-block; /* O display: flex; si prefieres flexbox */
    }
    .signature p {
        margin: 0;
        text-align: left; /* Alinea el texto a la izquierda dentro de cada párrafo */
    }

    .signature .name {
        font-weight: bold;
        text-align: left;
    }

    .footer {
        position: absolute;
        text-align: center;
        margin-top: 40px;
        font-size: 16px;
        color: #777;
        left: 50%; /* Centra .signature horizontalmente */
        transform: translateX(-50%);
        width: 100%;
    }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="left-text">
                Soy un ser especial<br>
                Dios no comete errores<br>
                Él me ama así
            </div>
            <div class="center-text">
                <strong>FUNDACION DE AYUDA MUTUA SAN JORGE</strong></p>
            </div>
            <img src="{{ public_path('images/Logo_Fundacion.png') }}" alt="Imagen" class="right-image">
        </div>
        <div class="content">
            <p>Por medio del presente, se certifica que <strong>{{ $shift['patient_name'] }}</strong>, identificado con [Número de Identificación], ha asistido a sesiones de terapia en <strong>[Nombre de la Institución/Centro de Terapia]</strong>.</p>
            <p><strong>Tipo de Terapia:</strong> {{ $shift['therapy_type'] }}</p>
            <p><strong>Terapeuta a Cargo:</strong> {{ $shift['doctor_name'] }}</p>
            <p><strong>Período de Asistencia:</strong> Desde {{ $shift['start_time'] }} hasta {{ $shift['end_time'] }}</p>
            <p>Este certificado se expide a petición del interesado para los fines que estime convenientes.</p>
        </div>
        <div class="signature">
            <p> Ing. Gabriela Briones Giler </p>
            <p><strong>COORDINADORA GENERAL</strong></p>
            <p><strong>FUNDACIÓN DE AYUDA MUTUA SAN JORGE</strong></p>
        </div>

        <div class="footer">
            <p>famsanjorge@hotmail.com</p>
        </div>
    </div>
</body>
</html>