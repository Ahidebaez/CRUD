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

// Procesar formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $id = intval($_POST["id"]);
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $email = $conn->real_escape_string($_POST["email"]);

    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nombre, $email, $id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Usuario actualizado correctamente</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Procesar formulario de nuevo usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar"])) {
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $email = $conn->real_escape_string($_POST["email"]);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $email);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Usuario agregado correctamente</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Mostrar lista de usuarios
$sql = "SELECT id, nombre, email FROM usuarios";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #F8E6FF; /* Color de fondo morado pastel */
            color: #333;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 60%;
            background-color: #E6E6FA; /* Color morado pastel */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #D8BFD8; /* Color morado más oscuro para el encabezado */
        }
        tr:hover {background-color: #f5f5f5;}
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"] {
            padding: 10px;
            margin: 5px 0;
            width: 30%;  /* Ajustar el ancho de los campos de texto */
            box-sizing: border-box;
            background-color: #F0E6FF; /* Fondo de los campos de texto */
            border: 1px solid #D8BFD8;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #DDA0DD; /* Fondo de los botones */
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #BA55D3; /* Fondo más oscuro al pasar el cursor */
        }
        a {
            text-decoration: none;
            color: #2196F3;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<h2>Usuarios</h2>

<!-- Formulario para agregar un nuevo usuario -->
<h3>Agregar Nuevo Usuario</h3>
<form action="index.php" method="post">
    Nombre: <input type="text" name="nombre" required>
    Email: <input type="email" name="email" required>
    <input type="submit" name="agregar" value="Agregar Usuario">
</form>

<!-- Lista de usuarios existentes -->
<h3>Lista de Usuarios</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Acciones</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td><a href='editar.php?id=" . $row["id"] . "'>Editar</a> | <a href='eliminar.php?id=" . $row["id"] . "'>Eliminar</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No hay usuarios registrados.</td></tr>";
    }
    ?>
</table>
</body>
</html>