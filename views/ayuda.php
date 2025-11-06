<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda - Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .help-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .help-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4a90e2;
        }
        
        .help-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .help-header p {
            color: #666;
            font-size: 16px;
        }
        
        .qr-container {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #4a90e2;
        }
        
        .qr-placeholder {
            max-width: 300px;
            width: 100%;
            height: 300px;
            margin: 0 auto;
            background: #fff;
            border: 3px solid #4a90e2;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .qr-placeholder img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
        }
        
        .qr-placeholder .qr-text {
            color: #666;
            font-size: 14px;
            padding: 20px;
            text-align: center;
        }
        
        .qr-instructions {
            margin-top: 20px;
            color: #555;
            font-size: 16px;
        }
        
        .qr-instructions i {
            color: #4a90e2;
            margin-right: 8px;
        }
        
        .btn-volver {
            display: inline-block;
            padding: 12px 24px;
            background: #4a90e2;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.3s;
            margin-top: 20px;
        }
        
        .btn-volver:hover {
            background: #357abd;
        }
        
        .help-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="cabecera">
            <div class="logo">
                <h1>ScaleUp</h1>
                <span>Sistema de Inventario</span>
            </div>
            <div class="usuario-info">
                <a href="samathluxe/index.php" class="volver-web-btn" style="margin-right: 15px; padding: 8px 16px; background: #d4af37; color: #000; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">
                    ← Volver al Sitio Web
                </a>
                <a href="index.php?action=ayuda" class="help-btn" style="margin-right: 15px; padding: 8px 16px; background: #17a2b8; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">
                    <i class="fas fa-question-circle"></i> Ayuda
                </a>
                <span>Bienvenido(a), <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span>
                <a href="index.php?action=logout" class="logout-btn">Cerrar Sesion</a>
            </div>
        </header>

        <!-- Nav -->
        <nav class="nav-principal">
            <a href="index.php?action=dashboard" class="nav-item">
                <i class="nav-icon fas fa-th-large"></i>
                Dashboard
            </a>
            <a href="index.php?action=productos" class="nav-item">
                <i class="nav-icon fas fa-cube"></i>
                Productos
            </a>
            <a href="index.php?action=categorias" class="nav-item">
                <i class="nav-icon fas fa-tags"></i>
                Categorias
            </a>
            <a href="index.php?action=movimientos" class="nav-item">
                <i class="nav-icon fas fa-exchange-alt"></i>
                Movimientos
            </a>
            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
            <a href="index.php?action=usuarios" class="nav-item">
                <i class="nav-icon fas fa-users"></i>
                Usuarios
            </a>
            <?php endif; ?>
        </nav>

        <!-- Contenido de Ayuda -->
        <main class="contenido-principal">
            <div class="help-container">
                <div class="help-header">
                    <h1><i class="fas fa-question-circle" style="color: #4a90e2; margin-right: 10px;"></i>Centro de Ayuda</h1>
                    <p>Escanea el código QR para ver el tutorial completo</p>
                </div>

                <!-- Contenedor de Código QR -->
                <div class="qr-container">
                    <h2 style="color: #4a90e2; margin-bottom: 20px;">
                        <i class="fas fa-qrcode"></i> Tutorial en YouTube
                    </h2>
                    <div class="qr-placeholder">
                        <img src="assets/images/qr-tutorial.png" alt="Código QR Tutorial YouTube">
                    </div>
                    <div class="qr-instructions">
                        <p><i class="fas fa-info-circle"></i> Escanea este código con la cámara de tu dispositivo móvil para acceder al video tutorial completo del sistema.</p>
                    </div>
                </div>

                <div class="help-footer">
                    <a href="index.php?action=dashboard" class="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                    </a>
                    <p style="margin-top: 20px;">
                        Sistema de Gestión de Inventario ScaleUp © <?php echo date('Y'); ?>
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
