<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Usuarios - Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/usuarios.css">
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
            <a href="index.php?action=movimientos" class="nav-item">
                <i class="nav-icon fas fa-exchange-alt"></i>
                Movimientos
            </a>
            <a href="index.php?action=usuarios" class="nav-item activo">
                <i class="nav-icon fas fa-users"></i>
                Usuarios
            </a>
        </nav>

        <!-- Contenido Principal -->
        <main class="contenido-principal">
            <!-- Page Header -->
            <div class="cabecera-pagina">
                <h2>Gestion de Usuarios</h2>
                <div class="cabecera-acciones">
                    <a href="index.php?action=usuarios&method=exportar_csv" class="accion-btn secundario">
                        <i class="fas fa-download"></i>
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

            <!-- Tabla de Usuarios -->
            <div class="container-tabla">
                <?php if (!empty($usuarios)): ?>
                    <table class="datos-tabla">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo $usuario['id']; ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $usuario['rol'] === 'admin' ? 'admin' : 'worker'; ?>">
                                            <?php echo ucfirst($usuario['rol']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                                            <?php if ($usuario['id'] == 1): ?>
                                                <span class="protegido-label" title="No se puede modificar el administrador principal">🔒 Admin Principal</span>
                                            <?php else: ?>
                                                <form method="POST" action="index.php?action=usuarios&method=cambiarRol" class="form-cambiar-rol">
                                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                                    <select name="nuevo_rol" class="select-rol" onchange="this.form.submit()">
                                                        <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                        <option value="trabajador" <?php echo $usuario['rol'] === 'trabajador' ? 'selected' : ''; ?>>Trabajador</option>
                                                    </select>
                                                </form>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Usuario actual</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="campo-vacio">No hay usuarios registrados</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>

