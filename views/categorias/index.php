<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Categorias - Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/categorias.css">
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
                <h2>Gestion de Categorias</h2>
                <div>
                    <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                        <a href="index.php?action=categorias&method=crear" class="accion-btn primario">
                            <i class="btn-icon fas fa-plus"></i>
                            Nueva Categoria
                        </a>
                    <?php endif; ?>
                    <a href="index.php?action=categorias&method=exportar_csv" class="accion-btn secundario">
                        <i class="btn-icon fas fa-download"></i>
                        Exportar CSV
                    </a>
                </div>
            </div>

           

            <!-- Mensajes -->
            <?php if (!empty($message)): ?>
                <div class="alerta alerta-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>


            <!-- Lista de Categorias -->
            <div class="container-categorias">
                <?php if (!empty($categorias)): ?>
                    <div class="categorias-grid">
                        <?php foreach ($categorias as $categoria): ?>
                            <div class="categorias-carta">
                                <div class="categoria-cabecera">
                                    <h3><?php echo htmlspecialchars($categoria['nombre']); ?></h3>
                                    <div class="categoria-acciones">
                                    <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                        <a href="index.php?action=categorias&method=actualizar&id=<?php echo $categoria['id']; ?>" 
                                           class="btn-editar" title="Editar">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        
                                            <a href="index.php?action=categorias&method=eliminar&id=<?php echo $categoria['id']; ?>" 
                                               class="btn-eliminar" title="Eliminar"
                                               onclick="return confirm('¿Estas seguro de que quieres eliminar esta categoria?')">
                                               <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="categoria-info">
                                    <p>ID: <?php echo $categoria['id']; ?></p>
                                    <p>Creada: <?php echo date('d/m/Y', strtotime($categoria['created_at'] ?? 'now')); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Resumen de Categorias -->
                    <div class="categorias-resumen">
                        <p>Total de categorias: <strong><?php echo count($categorias); ?></strong></p>
                    </div>
                <?php else: ?>
                    <div class="campo-vacio">
                        <div class="icon-vacio"> </div>
                        <h3>No hay categorias</h3>
                        <p>Aún no se han creado categorias en el sistema.</p>
                          <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?> 
                            <a href="index.php?action=categorias&method=crear" class="btn-primario">
                                Crear categoria
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

</body>
</html>

           