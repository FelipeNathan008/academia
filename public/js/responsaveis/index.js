// index.js
function toggleCadastro() {

    const form = document.getElementById('cadastroForm');

    form.classList.toggle('hidden');

    form.scrollIntoView({
        behavior: 'smooth'
    });
}

function fecharCadastro() {
    document.getElementById('cadastroForm').classList.add('hidden');
}

const tel = document.getElementById('resp_telefone');

if (tel) {

    tel.addEventListener('input', () => {

        let v = tel.value.replace(/\D/g, '');

        if (v.length > 11) {
            v = v.slice(0, 11);
        }

        let formatado = '';

        if (v.length > 0) {
            formatado = '(' + v.slice(0, 2);
        }

        if (v.length >= 3) {
            formatado += ') ' + v.slice(2, 7);
        }

        if (v.length >= 8) {
            formatado += '-' + v.slice(7, 11);
        }

        tel.value = formatado;
    });
}

document.addEventListener('DOMContentLoaded', function () {

    const filtroNome = document.getElementById('filtroNomeResponsavel');

    const limparBtn = document.getElementById('limparFiltroResponsavel');

    const linhas = document.querySelectorAll('.linha-responsavel');

    function aplicarFiltro() {

        const nome = filtroNome.value.toLowerCase();

        linhas.forEach(linha => {

            const nomeResponsavel = linha.dataset.nome || '';

            let mostrar = true;

            if (nome && !nomeResponsavel.includes(nome)) {
                mostrar = false;
            }

            linha.style.display = mostrar ? '' : 'none';
        });
    }

    if (filtroNome) {
        filtroNome.addEventListener('input', aplicarFiltro);
    }

    if (limparBtn) {
        limparBtn.addEventListener('click', function () {

            filtroNome.value = '';

            aplicarFiltro();
        });
    }
});