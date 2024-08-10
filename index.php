<?php
$url = $_SERVER['REQUEST_URI']; // Esto incluye el path y los parámetros de consulta

// Usa parse_url para obtener solo el path
$path = parse_url($url, PHP_URL_PATH);

// Divide el path en partes usando explode
$pathParts = explode("/", trim($path, "/"));

// Extrae la parte deseada
$getNameFolder = isset($pathParts[0]) ? $pathParts[0] : ''; //esto se deberá cambiar a 1 si se trabaja en entorno local, por favor consierar
$carpetaRuta = "./descarga/" . $getNameFolder;

try {
    if (!file_exists($carpetaRuta)) {
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$getNameFolder' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$getNameFolder' ya existe.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['archivo'])) {
            $archivos = $_FILES['archivo'];
            foreach ($archivos['tmp_name'] as $key => $tmpName) {
                $nombreOriginal = $archivos['name'][$key];
                $nombreNuevo = formatearNombreArchivo($nombreOriginal);

                if (move_uploaded_file($tmpName, $carpetaRuta . '/' . $nombreNuevo)) {
                    $mensaje = "Archivo '$nombreNuevo' subido con éxito.";
                } else {
                    throw new Exception("Error al subir el archivo '$nombreNuevo'.");
                }
            }
        }
    }

    if (isset($_POST['eliminarArchivo'])) {
        $archivoAEliminar = $_POST['eliminarArchivo'];
        $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

        if (file_exists($archivoRutaAEliminar)) {
            if (unlink($archivoRutaAEliminar)) {
                $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
            } else {
                throw new Exception("Error al eliminar el archivo.");
            }
        } else {
            throw new Exception("El archivo '$archivoAEliminar' no existe.");
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}

// Función para formatear el nombre del archivo
function formatearNombreArchivo($nombre) {
    // Obtiene el nombre del archivo sin extensión
    $nombreSinExtension = pathinfo($nombre, PATHINFO_FILENAME);
    // Obtiene la extensión del archivo
    $extension = pathinfo($nombre, PATHINFO_EXTENSION);

    // Reemplaza espacios y otros caracteres no deseados en el nombre del archivo
    $nombreFormateado = str_replace([' ', '-', '_'], '_', $nombreSinExtension);
    if ($extension) {
        $nombreFormateado .= '.' . $extension;
    }

    return $nombreFormateado;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir archivos</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="parametro.js"></script>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <section class="home">             
        <div class="content">
            <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
                <h3>Sube tus archivos y comparte este enlace temporal: <span>nicoproject.online/<?php echo $getNameFolder;?></span></h3>
                <div class="container">
                    <div class="drop-area" id="drop-area">
                        <form action="" id="form" method="POST" enctype="multipart/form-data">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" style="fill:#0730c5;transform: ;msFilter:;"><path d="M13 19v-4h3l-4-5-4 5h3v4z"></path><path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path></svg> <br>
                            <input type="file" class="file-input" name="archivo[]" id="archivo" multiple onchange="document.getElementById('form').submit()">
                            <label for="file-input"> Arrastra tus archivos aquí<br>o</label>
                            <p><b>Abre el explorador</b></p> 
                            
                        </form>
                    </div>

                    <div class="container2">
                        <div id="file-list" class="pila">
                            <?php
                            $targetDir = $carpetaRuta;

                            $files = scandir($targetDir);
                            $files = array_diff($files, array('.', '..'));

                            if (count($files) > 0) {
                                echo "<h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";
                            
                                foreach ($files as $file) {
                                    $nombreMostrar = formatearNombreArchivo($file);
                                    echo "<div class='archivos'>
                                        <a href='$carpetaRuta/$file' download class='boton-descargar'>$nombreMostrar</a>
                                        <form action='' method='POST' style='display:inline;'>
                                            <input type='hidden' name='eliminarArchivo' value='$file'>
                                            <button type='submit' class='btn_delete'>
                                                <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-trash' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                                    <path d='M4 7l16 0' />
                                                    <path d='M10 11l0 6' />
                                                    <path d='M14 11l0 6' />
                                                    <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                                    <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>";
                                }
                            } else {
                                echo "No se han subido archivos.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
    <!-- <script src="parametro.js"></script> -->
    </section>
</body>

</html>
