// js/utils/buscarCep.js

function buscarCEP() {

    const cepInput = document.getElementById('resp_cep');

    const cep = cepInput.value.replace(/\D/g, '');

    if (cep.length !== 8) return;

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {

            if (data.erro) {
                alert('CEP não encontrado');
                return;
            }

            document.querySelector('input[name="resp_logradouro"]').value = data.logradouro || '';

            document.querySelector('input[name="resp_bairro"]').value = data.bairro || '';

            document.querySelector('input[name="resp_cidade"]').value = data.localidade || '';

        })
        .catch(() => {
            alert('Erro ao buscar o CEP');
        });
}