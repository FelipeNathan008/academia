// js/utils/validacoes.js

function validarNome(input) {
    input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
}

function validarTexto(input) {
    input.value = input.value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');

}function bloquearSubmit(event, form) {

    if (!form.checkValidity()) {
        return;
    }

    const btn = form.querySelector('button[type="submit"]');

    if (btn) {
        btn.disabled = true;
        btn.innerText = 'Salvando...';
    }
}

