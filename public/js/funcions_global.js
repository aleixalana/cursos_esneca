

// AFEGIR TOTAL HORES: Afegeix Fila a la Taula de cursos
function afegirFilaTotal(total) {
    const taulaCursos = document.querySelector('#taula-cursos tbody');
    if (!taulaCursos) return;

    // Localitzar y eliminar fila de total hores si ja havíem executat el procés
    const filaAnterior = document.getElementById('fila-total-hores');
    if (filaAnterior) {
        filaAnterior.remove();
    }

    // Crear fila
    const tr = document.createElement('tr');
    tr.id = 'fila-total-hores';
    tr.style.fontWeight = 'bold';
    tr.style.backgroundColor = '#f8f9fa';
    tr.innerHTML = `
        <td colspan="5" style="text-align:right;">Total hores:</td>
        <td colspan="3">${total}</td>
    `;

    // Afegir Fila
    taulaCursos.appendChild(tr);
}


// MODAL 
function confirmarSweet(pregunta, ruta) {
    Swal.fire({
        title: pregunta,
        icon: 'error',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Si',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'NO'
    }).then((result) => {

        if (result.isConfirmed) {
            window.location = ruta;
        }
    })
}

