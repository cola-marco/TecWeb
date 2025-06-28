//input validation
function showError(error_id, error_message){
    document.querySelector("."+error_id).classList.add("display-error");
    document.querySelector("."+error_id).innerHTML = error_message;
}

function clearError(){
    let server_errors = document.querySelectorAll("div.error-msg");
    for (let server_error of server_errors){
        server_error.innerHTML = "";
    }
    let errors = document.querySelectorAll(".error-msg");
    for(let error of errors){
        error.classList.remove("display-error");
    }
}

let admin_form = document.getElementById('add-book-section');
titolo_error = false;
autore_error = false;
casa_error = false;
genere_error = false;
anno_error = false;
trama_error = false;
ncopie_error = false;

if(admin_form){
admin_form.addEventListener('change', function() {
    
    clearError();
    titolo_error = false;
    autore_error = false;
    casa_error = false;
    genere_error = false;
    anno_error = false;
    trama_error = false;
    ncopie_error = false;

    //validazione titolo
    let titolo = document.getElementById('titolo').value;
    if(titolo.length > 0 && titolo.trim().length == 0){
        showError("titolo-error", "Titolo non può contenere soli spazi");
        titolo_error = true;
    };
    titolo = titolo.trim();
    
    
    //validazione autore
    let autore = document.getElementById('autore').value;
    const autorePattern = /^[\p{L}\s'\-\.]+$/u;
    if(autore.length > 0 && autore.trim().length == 0){
        showError("autore1-error", "Autore non può contenere soli spazi");
        autore_error = true;
    }
    autore = autore.trim();
    if(autore.length > 0 && !autorePattern.test(autore)){
        showError("autore2-error", "Autore non può contenere numeri o caratteri speciali");
        autore_error = true;
    }

    //validazione casa
    let casa = document.getElementById('casa_editrice').value;
    if(casa.length > 0 && casa.trim().length == 0){
        showError("casa-error", "Casa editrice non può contenere soli spazi");
        casa_error = true;
    }

    //validazione genere
    let genere = document.getElementById('genere').value;
    const generePattern = /^[\p{L}\s'\-]+$/u;
    if(genere.length > 0 && genere.trim().length == 0){
        showError("genere1-error", "Genere non può contenere soli spazi");
        genere_error = true;
    }
    genere = genere.trim();
    if(genere.length > 0 && !generePattern.test(genere)){
        showError("genere2-error", "Genere non può contenere numeri o caratteri speciali");
        genere_error = true;
    }

    //validazione anno pubblicazione
    let anno = document.getElementById('pubblicazione').value;
    const annoPattern = /^[0-9]+$/;
    if(anno.length > 0 && anno.trim().length == 0){
        showError("anno1-error", "Anno di pubblicazione non può contenere soli spazi");
        anno_error = true;
    }
    anno = anno.trim();
    if(anno.length > 0 && (!annoPattern.test(anno) || anno < 1000 || anno > 2025)){
        showError("anno2-error", "Anno di pubblicazione può contenere solo numeri naturali tra il 1000 e il 2025");
        anno_error = true;
    }

    //validazione trama
    let trama = document.getElementById('trama').value;
    if(trama.length > 0 && trama.trim().length == 0){
        showError("trama-error", "Trama non può contenere soli spazi");
        trama_error = true;
    }

    //validazione numero copie
    let ncopie = document.getElementById('numero_copie').value;
    const ncopiePattern = /^[0-9]+$/;
    if(ncopie.length > 0 && ncopie.trim().length == 0){
        showError("ncopie1-error", "Numero di copie non può contenere soli spazi");
        ncopie_error = true;
    }
    ncopie = ncopie.trim();
    if(ncopie.length > 0 && (!ncopiePattern.test(ncopie) || ncopie == 0)){
        showError("ncopie2-error", "Numero di copie può contenere solo numeri naturali e non può essere 0");
        ncopie_error = true;
    }
});
}

if(admin_form){
    admin_form.addEventListener('submit', function (e) {

        e.preventDefault();
        if(!(titolo_error || autore_error || casa_error || genere_error || anno_error || trama_error || ncopie_error)){
            this.submit();
        }
    });
}