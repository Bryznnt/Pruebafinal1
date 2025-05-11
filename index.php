<?php
// Variable para almacenar mensajes de error y éxito
$mensaje_error = '';
$mensaje_exito = '';

// Variables para el formulario de edición
$editando = false;
$id_editar = '';
$nombre_editar = '';
$email_editar = '';

// Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=localhost;dbname=prueba_final1", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $error) {
    echo "Error de conexión: " . $error->getMessage();
    die();
}

// Verificar si se está solicitando eliminar un usuario
if (isset($_GET['eliminar']) && !empty($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    
    try {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id_eliminar);
        $stmt->execute();
        $mensaje_exito = "Usuario eliminado correctamente";
    } catch(PDOException $error) {
        $mensaje_error = "Error al eliminar usuario: " . $error->getMessage();
    }
}

// Verificar si se está solicitando editar un usuario
if (isset($_GET['editar']) && !empty($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $editando = true;
    
    try {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id_editar);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            $nombre_editar = $usuario['nombre'];
            $email_editar = $usuario['email'];
        } else {
            $mensaje_error = "Usuario no encontrado";
            $editando = false;
        }
    } catch(PDOException $error) {
        $mensaje_error = "Error al recuperar usuario: " . $error->getMessage();
        $editando = false;
    }
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si estamos actualizando o agregando
    if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
        // Actualizar usuario existente
        if (empty($_POST['nombre'])) {
            $mensaje_error = 'Por favor, complete el campo nombre';
            $editando = true;
            $id_editar = $_POST['id'];
            $nombre_editar = $_POST['nombre'];
            $email_editar = $_POST['email'];
        } elseif (empty($_POST['email'])) {
            $mensaje_error = 'Por favor, complete el campo correo electrónico';
            $editando = true;
            $id_editar = $_POST['id'];
            $nombre_editar = $_POST['nombre'];
            $email_editar = $_POST['email'];
        } else {
            try {
                $sql = "UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id";
                $stmt = $conexion->prepare($sql);
                
                // Vincular parámetros
                $stmt->bindParam(':nombre', $_POST['nombre']);
                $stmt->bindParam(':email', $_POST['email']);
                $stmt->bindParam(':id', $_POST['id']);
                
                // Ejecutar la consulta
                $stmt->execute();
                
                $mensaje_exito = "Usuario actualizado correctamente";
                $editando = false;
                
                // Redireccionar para limpiar el formulario después de actualizar
                header('Location: ' . $_SERVER['PHP_SELF'] . '?actualizado=1');
                exit;
            } catch(PDOException $error) {
                $mensaje_error = "Error al actualizar usuario: " . $error->getMessage();
                $editando = true;
                $id_editar = $_POST['id'];
                $nombre_editar = $_POST['nombre'];
                $email_editar = $_POST['email'];
            }
        }
    } else {
        // Agregar nuevo usuario
        if (empty($_POST['nombre'])) {
            $mensaje_error = 'Por favor, complete el campo nombre';
        } elseif (empty($_POST['email'])) {
            $mensaje_error = 'Por favor, complete el campo correo electrónico';
        } else {
            try {
                $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
                $stmt = $conexion->prepare($sql);
                
                // Vincular parámetros
                $stmt->bindParam(':nombre', $_POST['nombre']);
                $stmt->bindParam(':email', $_POST['email']);
                
                // Ejecutar la consulta
                $stmt->execute();
                
                $mensaje_exito = "Usuario agregado correctamente";
                
                // Redireccionar para evitar reenvío del formulario
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } catch(PDOException $error) {
                $mensaje_error = "Error al agregar usuario: " . $error->getMessage();
            }
        }
    }
}

// Cancelar edición
if (isset($_GET['cancelar'])) {
    $editando = false;
    // Redireccionar a una URL limpia
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Obtener todos los usuarios
try {
    // Consulta para mostrar todos los usuarios ordenados por ID
    $sql = "SELECT * FROM usuarios ORDER BY id ASC";
    $stmt = $conexion->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Para depuración: ver cuántos usuarios se recuperaron
    // echo "Número de usuarios recuperados: " . count($usuarios);
} catch(PDOException $error) {
    echo "Error al recuperar usuarios: " . $error->getMessage();
    $usuarios = [];
}

// Incluir el archivo de vista
require_once 'index.view.php';
?>