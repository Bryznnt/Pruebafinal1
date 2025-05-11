<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Gestión de Usuarios</title>
</head>
<body>
  <h1>Gestión de Usuarios</h1>

  <?php if ($editando): ?>
  <!-- Formulario para editar usuario -->
  <h2>Editar Usuario</h2>
  <form action="index.php" method="POST">
    <input type="hidden" name="accion" value="actualizar">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_editar); ?>">
    <input type="text" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($nombre_editar); ?>">
    <input type="email" name="email" placeholder="Correo Electrónico" value="<?php echo htmlspecialchars($email_editar); ?>">
    <button type="submit">Actualizar Usuario</button>
    <a href="index.php?cancelar=1" class="btn-cancelar">Cancelar</a>
  </form>
  <?php else: ?>
  <!-- Formulario para agregar un nuevo usuario -->
  <h2>Agregar Nuevo Usuario</h2>
  <form action="index.php" method="POST">
    <!-- Asegurarnos de que estos campos estén siempre vacíos cuando no estamos editando -->
    <input type="text" name="nombre" placeholder="Nombre" value="">
    <input type="email" name="email" placeholder="Correo Electrónico" value="">
    <button type="submit">Agregar Usuario</button>
  </form>
  <?php endif; ?>
  
  <!-- Mostrar mensaje de error si existe -->
  <?php if (!empty($mensaje_error)): ?>
    <div class="error"><?php echo $mensaje_error; ?></div>
  <?php endif; ?>
  
  <!-- Mostrar mensaje de éxito si existe -->
  <?php if (!empty($mensaje_exito)): ?>
    <div class="exito"><?php echo $mensaje_exito; ?></div>
  <?php endif; ?>

  <h2>Lista de Usuarios</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Correo</th>
      <th>Acciones</th>
    </tr>
    
    <?php if (!empty($usuarios)): ?>
      <?php foreach ($usuarios as $usuario): ?>
      <tr>
        <td><?php echo htmlspecialchars($usuario['id']); ?></td>
        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
        <td>
          <a href="index.php?editar=<?php echo $usuario['id']; ?>" class="btn-editar">Editar</a> | 
          <a href="index.php?eliminar=<?php echo $usuario['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Está seguro de que desea eliminar este usuario?');">Eliminar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="4">No hay usuarios registrados</td>
      </tr>
    <?php endif; ?>
  </table>
</body>
</html>