<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Movimiento | Sistema de Inventario</title>
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
            <a href="index.php?action=categorias" class="nav-item">
                <i class="nav-icon fas fa-tags"></i>
                Categorias
            </a>
            <a href="index.php?action=movimientos" class="nav-item activo">
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
            <!-- Header pagina -->
            <div class="cabecera-pagina">
                <h2>Registrar Movimiento</h2>
                <a href="index.php?action=movimientos" class="btn-secundario">
                    <span class="btn-icon"><-</span>
                    Volver a Movimientos
                </a>
            </div>

            <!-- Mensajes -->
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alerta alerta-<?php echo $_SESSION['message_type']; ?>">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                </div>
                <?php 
                unset($_SESSION['message'], $_SESSION['message_type']); 
                ?>
            <?php endif; ?>

            <!-- Formulario -->
            <div class="container-formulario">
                <form method="POST" action="index.php?action=movimientos&method=crear" class="formularios">
                    <div class="grupo-formulario">
                        <label for="producto_id">Producto *</label>
                        <select id="producto_id" name="producto_id" required>
                            <option value="">Seleccionar producto...</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto['id']; ?>" 
                                        <?php echo ($productoId ?? '') == $producto['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($producto['nombre']); ?> 
                                    (Stock: <?php echo $producto['stock']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grupo-formulario">
                        <label for="tipo_movimiento">Tipo de Movimiento *</label>
                        <select id="tipo_movimiento" name="tipo_movimiento" required>
                            <option value="">Seleccionar tipo...</option>
                            <option value="entrada" <?php echo ($tipoMovimiento ?? '') == 'entrada' ? 'selected' : ''; ?>>Entrada</option>
                            <option value="salida" <?php echo ($tipoMovimiento ?? '') == 'salida' ? 'selected' : ''; ?>>Salida</option>
                            <option value="ajuste" <?php echo ($tipoMovimiento ?? '') == 'ajuste' ? 'selected' : ''; ?>>Ajuste</option>
                        </select>
                    </div>

                    <div class="grupo-formulario">
                        <label for="cantidad">Cantidad *</label>
                        <input type="number" id="cantidad" name="cantidad" 
                               value="<?php echo htmlspecialchars($cantidad ?? ''); ?>" 
                               min="1" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="referencia">Referencia</label>
                        <input type="text" id="referencia" name="referencia" 
                               value="<?php echo htmlspecialchars($referencia ?? ''); ?>" 
                               placeholder="Ej: Factura #123, Orden de compra...">
                    </div>

                    <div class="grupo-formulario">
                        <label for="notas">Notas</label>
                        <textarea id="notas" name="notas" rows="3" 
                                  placeholder="Observaciones adicionales..."><?php echo htmlspecialchars($notas ?? ''); ?></textarea>
                    </div>

                    <div class="acciones-formulario">
                        <button type="submit" class="btn-primario">
                            <span class="btn-icon"> </span>
                            Registrar Movimiento
                        </button>
                        <a href="index.php?action=movimientos" class="btn-secundario">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="assets/js/forms_minimal.js"></script>
</body>
</html>
