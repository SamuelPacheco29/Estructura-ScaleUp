<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Inventario - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
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
            <a href="index.php?action=dashboard" class="nav-item activo">
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

        
        <main class="contenido-principal">
            <div class="estadisticas-grid">
                <div class="estadisticas-carta">
                    <div class="estadisticas-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div class="estadisticas-info">
                        <h3><?php echo number_format($estadisticas['total_productos'] ?? 0) ?></h3>
                        <p>Total Productos</p>
                    </div>
                </div>

                <div class="estadisticas-carta">
                    <div class="estadisticas-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="estadisticas-info">
                        <h3><?php echo number_format($estadisticas['total_stock'] ?? 0); ?></h3>
                        <p>Stock Total</p>
                    </div>
                </div>

                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                <div class="estadisticas-carta">
                    <div class="estadisticas-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="estadisticas-info">
                        <h3><?php echo number_format($estadisticas['precio_total'] ?? 0, 2); ?></h3>
                        <p>Precio Total</p>
                    </div>
                </div>

                <div class="estadisticas-carta">
                    <div class="estadisticas-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="estadisticas-info">
                        <h3><?php echo number_format($estadisticas['precio_promedio'] ?? 0) ?></h3>
                        <p>Precio Promedio</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        <!-- Acciones Rapidas -->
        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
            <div class="acciones-btn">
                <h2>Acciones Rapidas</h2>
                <div class="">
                    <a href="index.php?action=productos&method=crear" class="accion-btn primario">
                        <i class="btn-icon fas fa-plus"></i>
                        Agregar Producto
                    </a>
                    <a href="index.php?action=categorias&method=crear" class="accion-btn secundario">
                        <i class="btn-icon fas fa-plus"></i>
                        Agregar Categoria
                    </a>
                    <a href="index.php?action=movimientos&method=crear" class="accion-btn terciario">
                        <i class="btn-icon fas fa-plus"></i>
                        Agregar Movimiento
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Productos Recientes / Bajo Stock -->
            <div class="dashboard-grid">
                <!-- Prodcutos Recientes -->
                <div class="dashboard-carta">
                    <div class="cabecera-carta">
                        <h3>Productos Recientes</h3>
                        <a href="index.php?action=productos" class="ver-todo">Ver todos</a>
                    </div>
                    <div class="contenido-carta">
                        <?php if (!empty($productosRecientes)): ?>
                            <div class="lista-productos">
                                <?php foreach (array_slice($productosRecientes, 0, 5) as $producto): ?>
                                    <div class="item-producto">
                                        <div class="producto-info">
                                            <h4><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                                            <p class="producto-categoria"><?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoria'); ?></p>
                                        </div>
                                        <div class="estadisticas-productos">
                                            <span class="stock <?php echo $producto['stock'] <= 10 ? 'bajo' : ''; ?>">
                                                Stock: <?php echo $producto['stock']; ?>
                                            </span>
                                            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                            <span class="precio">$<?php echo number_format($producto['precio'], 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="campo-vacio">No hay productos registrados</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Alerta de Stock Bajo -->
                <div class="dashboard-carta">
                    <div class="cabecera-carta">
                        <h3>Stock Bajo</h3>
                        <span class="alerta-monto"><?php echo count($productosStockBajo); ?></span>
                    </div>
                    <div class="contenido-carta">
                        <?php if (!empty($productosStockBajo)): ?>
                            <div class="alerta-lista">
                                <?php foreach (array_slice($productosStockBajo, 0, 5) as $producto): ?>
                                    <div class="alerta-item">
                                        <div class="alerta-info">
                                            <h4><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                                            <p class="alerta-categoria"><?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoria'); ?></p>
                                        </div>
                                        <div class="alerta-stock">
                                            <span class="stock-critico"><?php echo $producto['stock']; ?> unidades</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="campo-vacio correcto">Todo el stock esta en buen nivel</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>