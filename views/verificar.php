<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificacion de Email</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .logo-formulario {
            width: 100px;
            height: 100px;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 9999;
            margin: 20px 20px 0px 20px;
        }

        .verificacion-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }

        .verificacion-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .verificacion-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .verificacion-header p {
            color: #666;
            font-size: 14px;
        }

        .email-info {
            background: #f0f8ff;
            border-left: 4px solid #4a90e2;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }

        .email-info strong {
            color: #4a90e2;
        }

        .expiracion- {
            background: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
            color: #856404;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .codigo-input {
            width: 100%;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .codigo-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .btn-verificar {
            width: 100%;
            padding: 15px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-verificar:hover {
            background: #357abd;
        }

        .error-mensaje {
            background: #ffe6e6;
            border-left: 4px solid #ff4444;
            color: #cc0000;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .exito-mensaje {
            background: #e6ffe6;
            border-left: 4px solid #44ff44;
            color: #00cc00;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .info-box ul {
            margin-left: 20px;
            margin-top: 10px;
        }

        .info-box li {
            margin-bottom: 5px;
        }

        .link-registro {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .link-registro a {
            color: #4a90e2;
            text-decoration: none;
        }

        .link-registro a:hover {
            text-decoration: underline;
        }

        .btn-reenviar {
            width: 100%;
            padding: 12px;
            background: white;
            color: #4a90e2;
            border: 2px solid #4a90e2;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .btn-reenviar:hover {
            background: #4a90e2;
            color: white;
        }
    </style>
</head>
<body>
    <img src="assets/images/logo.png" alt="ScaleUp Logo" class="logo-formulario">
    <div class="verificacion-container">
        <div class="verificacion-header">
            <h1>Verificacion de Email</h1>
            <p>Hemos enviado un codigo de verificacion a tu correo</p>
        </div>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="error-mensaje">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success) && !empty($success)): ?>
            <div class="exito-mensaje">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div class="email-info">
            Email: <strong><?php echo htmlspecialchars($_GET['email'] ?? ''); ?></strong>
        </div>

        <form method="POST" action="index.php?action=verificar">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
            <div class="grupo-formulario">
                <label for="codigo">Codigo de verificacion (6 digitos)</label>
                <input type="text" name="codigo" id="codigo" class="codigo-input" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required autofocus>
            </div>

            <button type="submit" name="action" value="verificar" class="btn-verificar">
            Verificar Email
            </button>
        </form>

        <form method="POST" action="index.php?action=reenviarCodigo">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
            <button type="submit" class="btn-reenviar">
                Reenviar codigo
            </button>
        </form>

        <div class="link-formulario">
            <a href="index.php?action=registrar">Volver a Registro</a>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="samathluxe/index.php" style="display: inline-block; padding: 10px 20px; background: #d4af37; color: #000; text-decoration: none; border-radius: 4px; font-weight: 600;">
                ← Volver al Sitio Web
            </a>
        </div>
    </div>
</body>
</html>