<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos de Inventario - Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/movimientos.css">
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
            <!-- Page Header -->
            <div class="cabecera-pagina">
                <h2>Gestion de Movimientos</h2>
                <div class="cabecera-acciones">
                        <a href="index.php?action=movimientos&method=exportar_csv<?php 
                            $parametrosArreglo = [];
                            if (!empty($filters['fecha_movimiento'])) $parametrosArreglo[] = 'fecha_movimiento=' . urlencode($filters['fecha_movimiento']);
                            if (!empty($filters['producto_id'])) $parametrosArreglo[] = 'producto_id=' . urlencode($filters['producto_id']);
                            if (!empty($filters['tipo_movimiento'])) $parametrosArreglo[] = 'tipo_movimiento=' . urlencode($filters['tipo_movimiento']);
                            echo !empty($parametrosArreglo) ? '&' . implode('&', $parametrosArreglo) : '';
                        ?>" class="accion-btn secundario">
                            <i class="btn-icon fas fa-download"></i>
                            Exportar CSV
                        </a>
                        <a href="index.php?action=movimientos&method=crear" class="accion-btn primario">
                            <i class="btn-icon fas fa-plus"></i>
                            Registrar Movimiento
                        </a>
                    </div>
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

            <!-- Estadisticas -->
            <div class="estadisticas-grid">
                <div class="estadisticas-carta">
                    <div class="estadisticas-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="estadisticas-info">
                        <h3><?php echo number_format($estadisticas['total_movimientos']); ?></h3>
                        <p>Total Movimientos</p>
                    </div>
                </div>
                
                <div class="estadisticas-carta">
                    <div class="estadisticas-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="estadisticas-info">
                        <h3><?php echo number_format($estadisticas['total_entradas']); ?></h3>
                        <p># Entradas</p>
                    </div>
                </div>
                
                <div class="estadisticas-carta">
                    <div class="estadisticas-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="estadisticas-info">
                        <h3><?php echo number_format($estadisticas['total_salidas']); ?></h3>
                        <p># Salidas</p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rapidas -->
        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
            <div class="acciones-rapidas">
                <h2>Acciones Rapidas</h2>
                <div class="">
                    <a href="index.php?action=productos&method=crear" class="accion-btn primario">
                        <span class="btn-icon"> </span>
                        Agregar Producto
                    </a>
                    <a href="index.php?action=categorias&method=crear" class="accion-btn secundario">
                        <span class="btn-icon"> </span>
                        Agregar Categoria
                    </a>
                    <a href="index.php?action=movimientos&method=crear" class="accion-btn terciario">
                        <span class="btn-icon"> </span>
                        Agregar Movimiento
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Filtros -->
            <div class="seccion-filtros">
                <h3>Filtros de Búsqueda</h3>
                <form method="GET" action="index.php" class="formularios-filtros">
                    <input type="hidden" name="action" value="movimientos">
                    <input type="hidden" name="method" value="index">
                    
                    <div class="fila-filtro">
                        <div class="grupo-filtro">
                            <label for="fecha_movimiento">Fecha del Movimiento</label>
                            <input type="date" id="fecha_movimiento" name="fecha_movimiento" 
                                   value="<?php echo htmlspecialchars($filtros['fecha_movimiento'] ?? ''); ?>">
                        </div>
                        
                        <div class="grupo-filtro">
                            <label for="producto_id">Producto</label>
                            <select id="producto_id" name="producto_id">
                                <option value="">Todos los productos</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto['id']; ?>" 
                                            <?php echo ($filtros['producto_id'] ?? '') == $producto['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($producto['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="grupo-filtro">
                            <label for="tipo_movimiento">Tipo de Movimiento</label>
                            <select id="tipo_movimiento" name="tipo_movimiento">
                                <option value="">Todos los tipos</option>
                                <option value="entrada" <?php echo ($filtros['tipo_movimiento'] ?? '') == 'entrada' ? 'selected' : ''; ?>>Entrada</option>
                                <option value="salida" <?php echo ($filtros['tipo_movimiento'] ?? '') == 'salida' ? 'selected' : ''; ?>>Salida</option>
                                <option value="ajuste" <?php echo ($filtros['tipo_movimiento'] ?? '') == 'ajuste' ? 'selected' : ''; ?>>Ajuste</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="acciones-filtro">
                        <button type="submit" class="btn-primario">
                            <span class="btn-icon"> </span>
                            Filtrar
                        </button>
                        <a href="index.php?action=movimientos" class="btn-secundario">
                            <span class="btn-icon"> </span>
                            Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>

            <!-- Movimientos Recientes -->
            <div class="dashboard-carta">
                <div class="cabecera-carta">
                    <h3>Movimientos 
                        <?php if (!empty($filtros['fecha_movimiento'])): ?>
                            (<?php echo date('d/m/Y', strtotime($filtros['fecha_movimiento'])); ?>)
                        <?php else: ?>
                            (Ultimos 30 dias)
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="contenido-carta">
                    <?php if (!empty($movimientos)): ?>
                        <div class="resultados-info">
                            <p>Mostrando <?php echo count($movimientos); ?> movimiento(s) encontrado(s)</p>
                        </div>
                        <div class="tabla-responsive">
                            <table class="tabla-info">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th>Usuario</th>
                                        <th>Referencia</th>
                                        <th>Notas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($movimientos, 0, 20) as $movimiento): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($movimiento['fecha_movimiento'])); ?></td>
                                            <td><?php echo htmlspecialchars($movimiento['producto_nombre'] ?? 'Producto no encontrado'); ?></td>
                                            <td>
                                                <span class="tipo-movimiento <?php echo $movimiento['tipo_movimiento']; ?>">
                                                    <?php 
                                                    $tipos = [
                                                        'entrada' => 'Entrada',
                                                        'salida' => 'Salida',
                                                        'ajuste' => 'Ajuste'
                                                    ];
                                                    echo $tipos[$movimiento['tipo_movimiento']] ?? $movimiento['tipo_movimiento'];
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo number_format($movimiento['cantidad']); ?></td>
                                            <td>$<?php echo number_format($movimiento['precio_total'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($movimiento['nombre_usuario']); ?></td>
                                            <td><?php echo htmlspecialchars($movimiento['referencia'] ?: '-'); ?></td>
                                            <td><?php echo htmlspecialchars($movimiento['notas'] ?: '-'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="campo-vacio">
                            <div class="icon-vacio"> </div>
                            <h3>No hay movimientos</h3>
                            <p>No se han registrado movimientos en los últimos 30 días.</p>
                            <a href="index.php?action=movimientos&method=crear" class="btn-primario">
                                Registrar movimiento
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/reports_minimal.js"></script>
</body>
</html>
