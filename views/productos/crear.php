<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto - Sistema de Inventario</title>
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
            <a href="index.php?action=productos" class="nav-item activo">
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

        <!-- Contenido Principal -->
        <main class="contenido-principal">
            <!-- Header de la pagina -->
            <div class="cabecera-pagina">
                <h2>Nuevo Producto</h2>
                <a href="index.php?action=productos" class="btn-secundario">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Volver a Productos
                </a>
            </div>

            <!-- Formulario -->
            <div class="container-formulario">
                <form method="POST" action="index.php?action=productos&method=crear" class="formularios">
                    <div class="grupo-formulario">
                        <label for="nombre">Nombre del Producto *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: Anillo de plata con piedra">
                    </div>

                    <div class="grupo-formulario">
                        <label for="categoria_id">Categoria *</label>
                        <select id="categoria_id" name="categoria_id" required>
                            <option value="">Seleccionar categoria</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['id']; ?>">
                                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small>Requerido - Puedes crear categorias desde la seccion de Categorias</small>
                    </div>

                    <div class="fila-formulario">
                        <div class="grupo-formulario">
                            <label for="stock">Stock *</label>
                            <input type="number" id="stock" name="stock" min="0" required 
                                   placeholder="0" value="0">
                        </div>

                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                        <div class="grupo-formulario">
                            <label for="precio">Precio *</label>
                            <div class="precio-input">
                                <span class="moneda">$</span>
                                <input type="number" id="precio" name="precio" min="0" step="0.01" required 
                                       placeholder="0.00" value="0.00">
                            </div>
                        </div>
                        <?php else: ?>
                        <input type="hidden" name="precio" value="0.00">
                        <?php endif; ?>
                    </div>

                    <div class="acciones-formulario">
                        <button type="submit" class="btn-primario">
                            <i class="fas fa-save"></i>
                            Guardar Producto
                        </button>
                        <a href="index.php?action=productos" class="btn-terciario">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

</body>
</html>
