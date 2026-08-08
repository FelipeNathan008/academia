// (public/js/planos-cores.js)

const CORES_PLANOS = {
    branca: {
        gradienteDe: "#f1f5f9",
        gradienteAte: "#e2e8f0",
        headerTexto: "#1f2937",
        corPreco: "#8E251F",
        corCheck: "#8E251F",
    },
    cinza: {
        gradienteDe: "#f1f5f9",
        gradienteAte: "#cbd5e1",
        headerTexto: "#1f2937",
        corPreco: "#475569",
        corCheck: "#475569",
    },
    amarela: {
        gradienteDe: "#fef9c3",
        gradienteAte: "#fde68a",
        headerTexto: "#713f12",
        corPreco: "#a16207",
        corCheck: "#ca8a04",
    },
    azul: {
        gradienteDe: "#dbeafe",
        gradienteAte: "#bfdbfe",
        headerTexto: "#1e3a8a",
        corPreco: "#1d4ed8",
        corCheck: "#2563eb",
    },
    roxa: {
        gradienteDe: "#f3e8ff",
        gradienteAte: "#e9d5ff",
        headerTexto: "#581c87",
        corPreco: "#7e22ce",
        corCheck: "#9333ea",
    },
    marrom: {
        gradienteDe: "#aa4827",
        gradienteAte: "#7c2d12",
        headerTexto: "#ffffff",
        corPreco: "#b45309",
        corCheck: "#d97706",
    },
    preta: {
        gradienteDe: "#1f2937",
        gradienteAte: "#000000",
        headerTexto: "#ffffff",
        corPreco: "#111827",
        corCheck: "#111827",
    },
    coral: {
        gradienteDe: "#ffedd5",
        gradienteAte: "#fed7aa",
        headerTexto: "#7c2d12",
        corPreco: "#ea580c",
        corCheck: "#ea580c",
    },
    vermelha: {
        gradienteDe: "#ef4444",
        gradienteAte: "#b91c1c",
        headerTexto: "#ffffff",
        corPreco: "#dc2626",
        corCheck: "#dc2626",
    },
    caixa: {
        gradienteDe: "#dcfce7",
        gradienteAte: "#bbf7d0",
        headerTexto: "#14532d",
        corPreco: "#16a34a",
        corCheck: "#16a34a",
    },
    laranja: {
        gradienteDe: "#ffedd5",
        gradienteAte: "#fed7aa",
        headerTexto: "#7c2d12",
        corPreco: "#ea580c",
        corCheck: "#ea580c",
    },
};

// Cor padrão usada quando o nome do plano não bate com nenhuma cor conhecida
const CORES_PLANO_PADRAO = {
    gradienteDe: "#f1f5f9",
    gradienteAte: "#e2e8f0",
    headerTexto: "#1f2937",
    corPreco: "#8E251F",
    corCheck: "#8E251F",
};

function extrairCorDoPlano(nomePlano) {
    if (!nomePlano) return null;

    // Remove a palavra "faixa" (se existir) e espaços extras
    const chave = nomePlano.toLowerCase().replace(/faixa/g, "").trim();

    return chave;
}

function aplicarCoresPlanos() {
    document.querySelectorAll(".plano-card").forEach((card) => {
        const tipo = card.dataset.planoTipo;

        if (tipo === "financeiro") {
            const header = card.querySelector(".plano-header");
            if (header)
                header.style.background =
                    "linear-gradient(135deg, #dcfce7, #bbf7d0)";

            const titulo = card.querySelector(".plano-titulo");
            if (titulo) titulo.style.color = "#14532d";

            const preco = card.querySelector(".plano-preco");
            if (preco) preco.style.color = "#16a34a";

            card.querySelectorAll(".plano-check").forEach((icone) => {
                icone.style.color = "#16a34a";
            });
            return;
        }

        const nomePlano = card.dataset.plano || "";
        const chave = extrairCorDoPlano(nomePlano);
        const cor = CORES_PLANOS[chave] || CORES_PLANO_PADRAO;

        const header = card.querySelector(".plano-header");
        if (header) {
            header.style.background = `linear-gradient(135deg, ${cor.gradienteDe}, ${cor.gradienteAte})`;
        }

        const titulo = card.querySelector(".plano-titulo");
        if (titulo) titulo.style.color = cor.headerTexto;

        const preco = card.querySelector(".plano-preco");
        if (preco) preco.style.color = cor.corPreco;

        card.querySelectorAll(".plano-check").forEach((icone) => {
            icone.style.color = cor.corCheck;
        });
    });
}

document.addEventListener("DOMContentLoaded", aplicarCoresPlanos);
