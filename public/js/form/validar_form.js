

// REGLES FORMULARIS de CREAR / EDITAR
$("#form_afegir").validate({
    rules: {
        "cursos_form[codi]": {
            required: true,
        },
        "cursos_form[nom]": {
            required: true,
            minlength: 3
        },
        "cursos_form[data_inici]": {
            required: true,
            date: true,
            dataMajorQueActual: "#cursos_form_data_inici"
        },
        "cursos_form[data_fi]": {
            required: true,
            dataMajorQueInici: {
                "data_inici" : "#cursos_form_data_inici",
                "data_fi" : "#cursos_form_data_fi"
            }
        },
        "cursos_form[duracio]": {
            required: true,
            number: true, 
            duracioMultiple05: "#cursos_form_duracio"
        },
        "cursos_form[preu]": {
            required: true,
            number: true, 
            preuMultiple01: "#cursos_form_preu"
        }
    },
    messages: {
        "cursos_form[codi]": {
            required: "El codi és obligatori",
        },
        "cursos_form[nom]": {
            required: "El nom és obligatori",
            minlength: "El nom ha de tenir almenys 3 caràcters"
        },
        "cursos_form[data_inici]": {
            required: "La data d'inici és obligatoria"
        },
        "cursos_form[data_fi]": {
            required: "La data de finalització és obligatoria"
        },
        "cursos_form[duracio]": {
            required: "La duració és obligatoria",
            number: "La duració ha de ser un número",
        },
        "cursos_form[preu]": {
            required: "El preu és obligatori",
            number: "El preu ha de ser un número amb decimals",
        },
    }
});



/* FUNCIONS VALIDACIONS - Personalitzades */
$.validator.addMethod("dataMajorQueActual", function(value, element, idObjecte) {
    
    var dataCamp = $(idObjecte).val();
    var fechaActual = new Date();
    fechaActual.setHours(0,0,0,0); // EVITA ERROR per horari si s'elegeix el mateix dia
    console.log("data ara: " + Date.parse(fechaActual) + ". DataMiliCamp: " + Date.parse(dataCamp) + ". dataCamp:" + dataCamp);
    
    return Date.parse(dataCamp) >= Date.parse(fechaActual);
}, "La data actual té que ser major a l'actual");

$.validator.addMethod("dataMajorQueInici", function(value, element, idObjectes) {
    
    var dataCampInici = $(idObjectes['data_inici']).val();
    var dataCampFi = $(idObjectes['data_fi']).val();
    var fechaActual = new Date();
    console.log("data inici: " + Date.parse(dataCampInici) + ". DataFi: " + Date.parse(dataCampFi));
    
    return Date.parse(dataCampFi) >= Date.parse(dataCampInici);
}, "La data fi té que ser major o igual a l'actual");

$.validator.addMethod("duracioMultiple05", function(value, element, idObjecte) {
    
    var duracio = $(idObjecte).val();
    var duracioNormalitzat = duracio.replace(',', '.');

    if(duracioNormalitzat % 0.5 === 0) {
        return true;
    }
    return false;
    
}, "La duració ha de ser múltiple de 0.5");

$.validator.addMethod("preuMultiple01", function(value, element, idObjecte) {
    
    var preu = $(idObjecte).val();
    var preuNormalitzat = preu.replace(',', '.');
    
    // Obligar a Números + punt + 2 decimals
    return /^\d+\.\d{2}$/.test(preuNormalitzat)
    
}, "El preu ha de ser amb decimmals (ej: 10.55)");