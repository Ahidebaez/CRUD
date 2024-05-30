<?php
// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CONEXIÓN A LA BASE DE DATOS
$servername = "localhost";
$username = "root";
$password = "";
$database = "CRUD";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    // ELIMINAR USUARIO
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Usuario eliminado correctamente</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
    // Redirigir a la página principal después de eliminar
    header("Location: index.php");
    exit();
} else {
    echo "<p style='color: red;'>ID de usuario no especificado</p>";
    exit();
}

$conn->close();

?>
