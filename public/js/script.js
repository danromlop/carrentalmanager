//funcion para ordenar tabla según campos
function ordenarTabla(indiceColumna) {
    const tabla = document.getElementById("tabla");
  
    const filas = Array.from(tabla.rows).slice(1);  
    let filasOrdenadas;

    //comprobar si la columna es de texto o numérica
    const esNumero = !isNaN(filas[0].cells[indiceColumna].innerText);

    if (esNumero) {
        
        filasOrdenadas = filas.sort((a, b) => {
            return parseFloat(a.cells[indiceColumna].innerText) - parseFloat(b.cells[indiceColumna].innerText);
        });
    } else {
        filasOrdenadas = filas.sort((a, b) => {
            return a.cells[indiceColumna].innerText.localeCompare(b.cells[indiceColumna].innerText);
        });
    }

    //pinchando se alterna entre ascendente y descendente
    const ordenActual = tabla.getAttribute("data-order") === "asc" ? "desc" : "asc";
    tabla.setAttribute("data-order", ordenActual);

    if (ordenActual === "desc") {
        filasOrdenadas.reverse();
    }

    //se devuelven las filas a la tabla con la nueva ordenación
    for (const fila of filasOrdenadas) {
        tabla.tBodies[0].appendChild(fila);
    }
}


/* Botones confirmacion cancelacion */
let idEliminar = null;

function confirmarEliminar(index) {
    idEliminar = index;
    document.getElementById('confirmEliminar').style.display = 'block';
    
}

//cerrar modal
document.getElementById('cancelarBoton').onclick = function() {
    document.getElementById('confirmEliminar').style.display = 'none';
    idEliminar = null;
}

//eliminar registro
document.getElementById('confirmarBoton').onclick = function() {
    if (idEliminar !== null) {
      
        const form = document.getElementById('formEliminar' + idEliminar)
        
        if(form){
           form.submit();
        }
        //ocultar el modal al terminar
        document.getElementById('confirmEliminar').style.display = 'none';
    }
}