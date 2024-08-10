// Obtén la URL actual
const urlActual = window.location.href;

// Verifica si el parámetro 'nombre' ya está presente en la URL
const parametros = new URLSearchParams(window.location.search);
// let carpetaNombre = parametros.get("nombre");
let getNameFolder = (window.location.pathname).split("/")[1] // cambiar a 1 en producción y 2 en local
let cadenaAleatoria
//console.log("parametros", parametros);
if (!getNameFolder) {
    // Si 'nombre' no está presente, genera un número aleatorio
    getNameFolder = generarCadenaAleatoria();
    // Agrega el parámetro 'nombre' a la URL
    const urlConParametro = urlActual.includes("/") ? `${urlActual}${cadenaAleatoria}` : `${urlActual}${cadenaAleatoria}`;
    // Redirige a la nueva URL con el parámetro 'nombre'
    window.location.href  = urlConParametro;
} else {
    // Llama a la función para crear la carpeta con el nombre obtenido
    crearCarpeta(getNameFolder);
}

// Función para generar una cadena aleatoria
function generarCadenaAleatoria() {
    const caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789';
    cadenaAleatoria = '';
    for (let i = 0; i < 3; i++) {
        const caracterAleatorio = caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        cadenaAleatoria += caracterAleatorio;
    }
    return cadenaAleatoria;
}


// Función para crear la carpeta
function crearCarpeta(getNameFolder) {
    $.ajax({
        url: 'crearCarpeta.php', // Verifica que la ruta sea correcta
        type: 'POST',
        data: { nombreCarpeta: getNameFolder },
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
        },
        error: function(xhr, status, error) {
            console.error('Error en la llamada AJAX:', error);
        }
    });
}


// Manejo de la URL y la creación de la carpeta
document.addEventListener('DOMContentLoaded', () => {
    // Obtén la URL actual
    const urlActual = window.location.hostname;

    // Verifica si el parámetro 'nombre' ya está presente en la URL
    // const parametros = new URLSearchParams(window.location.search);
    // let carpetaNombre = parametros.get("nombre");

    if (!getNameFolder) {
        // Si 'nombre' no está presente, genera una cadena aleatoria
        getNameFolder = generarCadenaAleatoria();
        // Agrega el parámetro 'nombre' a la URL
        //const urlConParametro = urlActual.includes("?") ? `${urlActual}&nombre=${carpetaNombre}` : `${urlActual}?nombre=${carpetaNombre}`;
        // Redirige a la nueva URL con el parámetro 'nombre'
        //window.location.hostname = `${window.location.origin}/${carpetaNombre}`;
        // window.location.href  = `${window.location.hostname}/${carpetaNombre}`;
        window.location.href  = urlConParametro;
    } else {
        // Llama a la función para crear la carpeta con el nombre obtenido
        crearCarpeta(getNameFolder);
    }

    // Obtén el formulario y maneja el evento de envío
    const form = document.getElementById('form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const fileInput = form.querySelector('#archivo');
            const file = fileInput.files[0];
            if (file) {
                // Puedes enviar el archivo al servidor para su procesamiento aquí
                console.log('Subir archivo:', file.name);
            } else {
                alert('Por favor, seleccione un archivo primero.');
            }
        });
    } else {
        console.error('No se encontró el formulario con ID "form".');
    }

    // Obtén la zona de arrastre y maneja los eventos de arrastre
    const dropArea = document.getElementById('drop-area');

    // Función para manejar el archivo seleccionado
    function handleFile(file) {
        for (const file of files) {
            console.log('Archivo:', file.name);
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('drag-over');
        });
    
        dropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('drag-over');
        });
    
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            handleFile(file);
        });
        
        // También puedes realizar otras acciones, como subir el archivo al servidor
        // Puedes agregar aquí el código para subir el archivo si lo deseas

   }
   }

});


