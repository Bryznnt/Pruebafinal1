<?php
// Variable para almacenar mensajes de error
$mensaje_error = '';

// Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=localhost;dbname=prueba_final1", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $error) {
    echo "Error de conexión: " . $error->getMessage();
    die();
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que los campos nombre y email no estén vacíos
    if (empty($_POST['nombre'])) {
        $mensaje_error = 'Por favor, complete el campo, nombre';
    } elseif (empty($_POST['email'])) {
        $mensaje_error = 'Por favor, complete el campo, correo electronico';
    } else {
        try {
            $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
            $stmt = $conexion->prepare($sql);
            
            // Vincular parámetros
            $stmt->bindParam(':nombre', $_POST['nombre']);
            $stmt->bindParam(':email', $_POST['email']);
            
            // Ejecutar la consulta
            $stmt->execute();
            
            // Redireccionar para evitar reenvío del formulario
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } catch(PDOException $error) {
            $mensaje_error = "Error al agregar usuario: " . $error->getMessage();
        }
    }
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