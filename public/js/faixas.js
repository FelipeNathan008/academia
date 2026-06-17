const CORES_FAIXAS = {
    'branca': '#ffffff',
    'cinza': '#808080',
    'amarela': '#facc15',
    'laranja': '#f97316',
    'verde': '#22c55e',
    'azul': '#2563eb',
    'roxa': '#7c3aed',
    'marrom': '#78350f',
    'preta': '#000000',
    'vermelha': '#dc2626',
};

const FAIXAS_COM_PONTAS = {
    'cinza e branca': ['#808080', '#ffffff'],
    'cinza e preta': ['#808080', '#000000'],

    'amarela e branca': ['#facc15', '#ffffff'],
    'amarela e preta': ['#facc15', '#000000'],

    'laranja e branca': ['#f97316', '#ffffff'],
    'laranja e preta': ['#f97316', '#000000'],

    'verde e branca': ['#22c55e', '#ffffff'],
    'verde e preta': ['#22c55e', '#000000'],

    'cinza e azul': ['#808080', '#2563eb'],

    'azul e amarela': ['#2563eb', '#facc15'],

    'amarela e laranja': ['#facc15', '#f97316'],
    
    'coral preta e vermelha': ['#000000', '#dc2626'],
    'coral vermelha e branca': ['#dc2626', '#ffffff']
};

function aplicarCoresFaixas() {

    document.querySelectorAll('.bolinha-faixa').forEach(bolinha => {

        const faixa = bolinha.dataset.faixa.toLowerCase().trim();

        bolinha.style.background = '';
        bolinha.style.backgroundColor = '';

        // Faixas com pontas
        if (FAIXAS_COM_PONTAS[faixa]) {

            const [cor1, cor2] = FAIXAS_COM_PONTAS[faixa];

            bolinha.style.background =
                `linear-gradient(to right, ${cor1} 50%, ${cor2} 50%)`;

            return;
        }

        // Faixas normais
        if (CORES_FAIXAS[faixa]) {

            bolinha.style.backgroundColor =
                CORES_FAIXAS[faixa];
        }
    });
}