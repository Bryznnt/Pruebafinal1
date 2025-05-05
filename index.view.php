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

  <!-- Formulario para agregar un nuevo usuario -->
  <form action="index.php" method="POST">
    <input type="text" name="nombre" placeholder="Nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
    <input type="email" name="email" placeholder="Correo Electrónico" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    <button type="submit">Agregar Usuario</button>
  </form>
  
  <!-- Mostrar mensaje de error si existe -->
  <?php if (!empty($mensaje_error)): ?>
    <div class="error"> <?php echo $mensaje_error; ?></div>
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
          <a href="#">Editar</a> | 
          <a href="#">Eliminar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
      </tr>
    <?php endif; ?>
  </table>
</body>
</html>