<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Productos - Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/productos.css">
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
            <!-- Page Header -->
            <div class="cabecera-pagina">
                <h2>Gestion de Productos</h2>
                <div class="cabecera-acciones">
                    <a href="index.php?action=productos&method=exportar_csv" class="accion-btn secundario">
                        <i class="fas fa-download"></i>
                        Exportar CSV
                    </a>
                    <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                        <a href="index.php?action=productos&method=crear" class="accion-btn primario">
                            <i class="fas fa-plus"></i>
                            Nuevo Producto
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mensajes -->
            <?php if (!empty($message)): ?>
                <div class="alerta alerta-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Filtros -->
            <div class="seccion-filtros">
                <form method="GET" action="index.php" class="formularios-filtros">
                    <input type="hidden" name="action" value="productos">
                    
                    <div class="grupo-filtro">
                        <label for="buscar">Buscar:</label>
                        <input type="text" id="buscar" name="buscar" 
                               value="<?php echo htmlspecialchars($buscar ?? ''); ?>" 
                               placeholder="Nombre del producto...">
                    </div>
                    
                    <div class="grupo-filtro">
                        <label for="categoria_id">Categoria:</label require>
                        <select id="categoria_id" name="categoria_id">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['id']; ?>" 
                                        <?php echo ($categoriaId ?? '') == $categoria['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="acciones-filtro">
                        <button type="submit" class="btn-secundario">Filtrar</button>
                        <a href="index.php?action=productos" class="btn-terciario">Limpiar</a>
                    </div>
                </form>
            </div>

            <!-- Tabla de Productos -->
            <div class="container-tabla">
                <?php if (!empty($productos)): ?>
                    <table class="datos-tabla">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Stock</th>
                                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                <th>Precio</th>
                                <th>Valor Total</th>
                                <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr class="<?php echo $producto['stock'] <= 10 ? 'stock-bajo' : ''; ?>">
                                    <td>
                                        <div class="nombre-producto">
                                            <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="insignia-categoria">
                                            <?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoria'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="insignia-stock <?php echo $producto['stock'] <= 10 ? 'critico' : ($producto['stock'] <= 25 ? 'medio' : 'bien'); ?>">
                                            <?php echo $producto['stock']; ?>
                                        </span>
                                    </td>
                                    <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                    <td>
                                        <span class="precio"><?php echo number_format($producto['precio'], 2); ?></span>
                                    </td>
                                    <td>
                                        <span class="precio-total">$<?php echo number_format($producto['precio'] * $producto['stock'], 2); ?></span>
                                    </td>
                                    <?php endif; ?>
                                    <td>
                                        <div class="btn-acciones">
                                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                            <a href="index.php?action=productos&method=actualizar&id=<?php echo $producto['id']; ?>" 
                                               class="btn-actualizar" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                                <a href="index.php?action=productos&method=eliminar&id=<?php echo $producto['id']; ?>" 
                                                   class="btn-eliminar" title="Eliminar"
                                                   onclick="return confirm('¿Estas seguro de que quieres eliminar este producto?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Resumen de Productos -->
                    <div class="resumen-tabla">
                        <p>Total de productos: <strong><?php echo count($productos); ?></strong></p>
                        <?php 
                        $precioTotal = array_sum(array_map(function($p) { 
                            return $p['precio'] * $p['stock']; 
                        }, $productos));
                        ?>
                        <p>Valor total del inventario: <strong>$<?php echo number_format($precioTotal, 2); ?></strong></p>
                    </div>
                <?php else: ?>
                    <div class="campo-vacio">
                        <div class="icon-vacio"> </div>
                        <h3>No hay productos</h3>
                        <p>No se encontraron productos con los filtros aplicados.</p>
                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                            <a href="index.php?action=productos&method=crear" class="btn-primario">
                                Agregar producto
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

</body>
</html>
