<?php
// Conectar a la base de datos SQLite
$db = new SQLite3('/var/www/html/database.db');

// Función para crear un nuevo usuario
if (isset($_POST['create'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Prevenir inyección SQL con prepared statements
    $stmt = $db->prepare('INSERT INTO usuarios (nombre, apellido, correo, contraseña) VALUES (:nombre, :apellido, :correo, :contraseña)');
    $stmt->bindValue(':nombre', $nombre, SQLITE3_TEXT);
    $stmt->bindValue(':apellido', $apellido, SQLITE3_TEXT);
    $stmt->bindValue(':correo', $correo, SQLITE3_TEXT);
    $stmt->bindValue(':contraseña', $contraseña, SQLITE3_TEXT);

    // Ejecutar y verificar si hubo error
    if ($stmt->execute()) {
        echo "Usuario creado exitosamente.";
    } else {
        echo "Error al crear el usuario: " . $db->lastErrorMsg();
    }
}

// Función para eliminar un usuario
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Usar prepared statements para eliminar el usuario de manera segura
    $stmt = $db->prepare('DELETE FROM usuarios WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    // Ejecutar y verificar si hubo error
    if ($stmt->execute()) {
        echo "Usuario eliminado exitosamente.";
    } else {
        echo "Error al eliminar el usuario: " . $db->lastErrorMsg();
    }
}

// Función para obtener todos los usuarios
$result = $db->query('SELECT * FROM usuarios');

?>

<!-- Formulario para crear un nuevo usuario -->
<h1>Crear nuevo usuario</h1>
<form method="POST" action="">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label for="apellido">Apellido:</label>
    <input type="text" name="apellido" required><br>

    <label for="correo">Correo:</label>
    <input type="email" name="correo" required><br>

    <label for="contraseña">Contraseña:</label>
    <input type="password" name="contraseña" required><br>

    <input type="submit" name="create" value="Crear Usuario">
</form>

<!-- Mostrar los usuarios existentes -->
<h1>Usuarios</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Correo</th>
        <th>Contraseña</th>
        <th>Acciones</th>
    </tr>

    <?php
    // Mostrar los usuarios de la base de datos
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['apellido'] . "</td>";
        echo "<td>" . $row['correo'] . "</td>";
        echo "<td>" . $row['contraseña'] . "</td>";
        echo "<td><a href='?delete=" . $row['id'] . "'>Eliminar</a></td>";
        echo "</tr>";
    }
    ?>
</table>

<?php
// Cerrar la conexión
$db->close();
?>
