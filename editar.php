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
    $id = intval($_GET["id"]); // Asegúrate de que el ID es un entero
    // OBTENER DATOS DEL USUARIO A EDITAR
    $stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre = htmlspecialchars($row["nombre"]);
        $email = htmlspecialchars($row["email"]);
    } else {
        echo "<p style='color: red;'>Usuario no encontrado</p>";
        $stmt->close();
        $conn->close();
        exit;
    }
    
    $stmt->close();
} else {
    echo "<p style='color: red;'>ID de usuario no especificado</p>";
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #F8E6FF; /* Color de fondo morado pastel */
            color: #333;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        form {
            background-color: #E6E6FA; /* Color morado claro */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        input[type="text"], input[type="email"] {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            box-sizing: border-box;
            background-color: #F0E6FF; /* Fondo de los campos de texto */
            border: 1px solid #D8BFD8;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #DDA0DD; /* Fondo de los botones */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #BA55D3; /* Fondo más oscuro al pasar el cursor */
        }
        h2 {
            color: #4B0082;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>EDITAR USUARIO</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        Nombre: <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>
        Email: <input type="email" name="email" value="<?php echo $email; ?>" required>
        <input type="submit" name="editar" value="Actualizar Usuario">
    </form>
</div>
</body>
</html>
