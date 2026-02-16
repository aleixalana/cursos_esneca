document.getElementById('btn-refrescar-llistat').addEventListener('click', function(e) {
    e.preventDefault();

    fetch(this.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('seccio-llistat-cursos').innerHTML = html;
    })
    .catch(() => alert('Error al refrescar llistat de cursos'));
});

document.getElementById('btn-suma-hores-cursos').addEventListener('click', function(e) {
    e.preventDefault();

    fetch(this.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        let totalDuracio = parseFloat(data.totalDuracio) || 0;

        // convertir hores i minuts (0.5 = 30 min)
        let hores = Math.floor(totalDuracio);
        let minuts = (totalDuracio - hores === 0.5) ? '30' : '00';

        // Funcio per insertar valor a una nova fila (Borra si ja existeix)
        // Arxiu public/js/funcions_global.js
        afegirFilaTotal(totalDuracio);
    })
    .catch(() => alert('Error al calcular hores dels cursos'));
});