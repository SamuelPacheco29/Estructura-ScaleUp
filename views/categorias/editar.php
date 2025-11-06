<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria - Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/forms.css">
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
                <span>Bienvenido(a), <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
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
            <a href="index.php?action=categorias" class="nav-item activo">
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

        <!-- Contenido Principal -->
        <main class="contenido-principal">
            <!-- Header -->
            <div class="cabecera-pagina">
                <h2>Editar Categoria</h2>
                <a href="index.php?action=categorias" class="btn-secundario">
                    <span class="btn-icon"><-</span>
                    Volver a Categorias
                </a>
            </div>

            <!-- Formulario -->
            <div class="container-formulario">
                <form method="POST" action="index.php?action=categorias&method=actualizar&id=<?php echo $categorias['id']; ?>" class="formulario">
                    <div class="grupo-formulario">
                        <label for="nombre">Nombre de la Categoria *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               value="<?php echo htmlspecialchars($categorias['nombre']); ?>"
                               placeholder="Ej: Anillos, Aretes, Pulseras...">
                        <small>Usa nombres descriptivos para organizar mejor tus productos</small>
                    </div>

                    <div class="acciones-formulario">
                        <button type="submit" class="btn-primario">
                            <span class="btn-icon"> </span> 
                            Actualizar Categoria
                        </button>
                        <a href="index.php?action=categorias" class="btn-terciario">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    
</body>
</html>
