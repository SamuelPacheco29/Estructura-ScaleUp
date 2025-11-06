<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScaleUp | Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #4a90e2;
            align-items: center;
            justify-content: center;
            display: flex;
            min-height: 100vh;
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

        .container-formulario {
            background: #ffffff;
            padding: 70px;
            justify-content: left;
            display: column;
            width: 90%;
            max-width: 500px;
            border-radius: .7rem;
        }

        .titulo-formulario {
            font-size: 45px;
        }

        .subtitulo-formulario {
            margin-bottom: 40px;
            color: #6c757d;
            font-size: 16px;
        }

        .grupo-formulario {
            margin-bottom: 20px;
        }

        .grupo-formulario label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        .grupo-formulario input {
            width: 100%;
            padding: 12px;
            border: 2px solid rgb(209, 213, 216);
            font-size: 16px;
        }

        .grupo-formulario input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            transition: .3s ease;
        }

        .btn {
            width: 40%;
            padding: 15px;
            border-radius: 1.7rem;
            margin-top: 20px;
            color: #ffffff;
            background: #4a90e2;
            border: none;
            border-color: #4a90e2;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(74, 144, 226, 0.35);
            transition: .3s ease;
        }

        .link-formulario {
            margin-top: 20px; 
        }

        .link-formulario a {
            color: #4a90e2;
        }

        .error {
            color: #ff6b6b;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
            padding: 10px;
            background: #ffe6e6;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <img src="assets/images/logo.png" alt="ScaleUp Logo" class="logo-formulario">
    <div class="container-formulario">
        <h2 class="titulo-formulario">Iniciar Sesion</h2>
        <h6 class="subtitulo-formulario">Accede a tu cuenta para continuar</h6>

        <?php if  (isset($error) && !empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?> </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login">
            <div class="grupo-formulario">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                        value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="grupo-formulario">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="contrasena" required>
            </div>
            <button type="submit" class="btn">Iniciar Sesion</button>
        </form>

        <div class="link-formulario">
            <a href="index.php?action=registrar">¿No tienes cuenta? Registrate</a>
        </div>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="samathluxe/index.php" style="display: inline-block; padding: 10px 20px; background: #d4af37; color: #000; text-decoration: none; border-radius: 4px; font-weight: 600;">
                ← Volver al Sitio Web
            </a>
        </div>
    </div>
</body>
</html>