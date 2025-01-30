<?php
if (isset($_POST['qr_data'])) {
    $qrData = $_POST['qr_data'];

    // Aquí puedes procesar el QR, por ejemplo, buscar el producto en la base de datos.
    echo "<h2>Código QR detectado:</h2>";
    echo "<p>$qrData</p>";

    // Conexión a la base de datos (ejemplo con MySQL)
    // $conn = new mysqli("localhost", "usuario", "contraseña", "basedatos");
    // $query = "SELECT * FROM productos WHERE codigo_qr = '$qrData'";
    // $result = $conn->query($query);
    
    // if ($result->num_rows > 0) {
    //     $producto = $result->fetch_assoc();
    //     echo "<p>Producto encontrado: " . $producto['nombre'] . "</p>";
    // } else {
    //     echo "<p>Producto no encontrado en la base de datos.</p>";
    // }
}
?>
