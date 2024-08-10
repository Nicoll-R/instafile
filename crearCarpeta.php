<?php
// Nombre de la carpeta a crear (obtenido del parámetro)
$url = $_SERVER['REQUEST_URI']; // Esto incluye el path y los parámetros de consulta

// Usa parse_url para obtener solo el path
$path = parse_url($url, PHP_URL_PATH);

// Divide el path en partes usando explode
$pathParts = explode("/", trim($path, "/"));

// Extrae la parte deseada
$getNameFolder = isset($pathParts[1]) ? $pathParts[1] : ''; // 2 en local

// Ruta donde deseas crear la carpeta (por ejemplo, en la carpeta 'descarga')
$carpetaRuta = "./descarga/" . $getNameFolder;

// Verifica si la carpeta ya existe antes de crearla
if (!file_exists($carpetaRuta)) {
    // Crea la carpeta con permisos adecuados (por ejemplo, 0755)
    if (mkdir($carpetaRuta, 0755, true)) {
        $mensaje = "Carpeta '$getNameFolder' creada con éxito.";
    } else {
        echo "Error al crear la carpeta. Verifica los permisos y la ruta.";
        exit;
    }
} else {
    $mensaje = "La carpeta '$getNameFolder' ya existe. ";
}

// Imprime el mensaje sobre la creación de la carpeta
echo $mensaje;

// Luego, cuando se procese un archivo, guárdalo en la carpeta creada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivo'];

        // Sanitizar el nombre del archivo
        $nombreArchivo = htmlspecialchars($archivo['name']);
        $rutaArchivo = $carpetaRuta . '/' . $nombreArchivo;

        // Mover el archivo al destino
        if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
            echo "Archivo '$nombreArchivo' subido con éxito.";
        } else {
            echo "Error al mover el archivo '$nombreArchivo'.";
        }
    } else {
        echo "No se ha subido ningún archivo o hubo un error en la subida.";
    }
}
?>
