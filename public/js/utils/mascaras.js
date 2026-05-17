// js/utils/mascaras.js

function mascaraCPF(input) {
    let value = input.value.replace(/\D/g, '').slice(0, 11);

    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

    input.value = value;
}

function mascaraCEP(input) {
    let value = input.value.replace(/\D/g, '').slice(0, 8);

    if (value.length > 5) {
        value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    }

    input.value = value;
}

