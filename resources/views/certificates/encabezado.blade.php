<!DOCTYPE html>
<html>
<head>
    <title>Encabezado</title>
    <style>
        .header {
            width: 100%;
            position: relative; /* Necesario para posicionar elementos dentro */
        }
        .left-text {
            position: absolute;
            top: 10px;
            left: 10px;
            text-align: left;
            font-size: 12px;
        }
        .center-text {
            text-align: center;
            padding: 10px;
            width: 100%;
            font-size: 16px;
        }
        .right-image {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 100px; /* Ajusta el ancho según tu imagen */
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="left-text">
            Soy un ser especial<br>
            Dios no comete errores<br>
            Él me ama así
        </div>
        <div class="center-text">
            FUNDACION DE AYUDA MUTUA SAN JORGE
        </div>
        <img src="{{ public_path('images/Logo_Funcacion.jpg') }}" alt="Imagen" class="right-image">
    </div>
</body>
</html>