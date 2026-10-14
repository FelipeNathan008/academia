<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório do Treino</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 25px 32px
        }

        .cabecalho {
            text-align: center;
            margin-bottom: 18px
        }

        .empresa {
            font-size: 18px;
            font-weight: bold
        }

        .local {
            margin-top: 4px;
            font-size: 12px;
            color: #555
        }

        .titulo {
            margin-top: 16px;
            font-size: 16px;
            font-weight: bold
        }

        .data {
            margin-top: 4px;
            font-size: 12px;
            color: #555
        }

        .linha {
            border-top: 1px solid #ccc;
            margin: 15px 0
        }

        .turma {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 8px
        }

        .informacao {
            margin: 5px 0;
            font-size: 12px
        }

        .tecnica {
            margin-top: 8px;
            padding: 8px 10px;
            background: #f5f5f5;
            border-left: 3px solid #8E251F;
            border-radius: 3px;
            line-height: 1.4
        }

        .geral {
            margin-top: 20px;
            padding: 13px 15px;
            background: #fafafa;
            border: 1px solid #d5d5d5;
            border-radius: 6px
        }

        .geral-titulo {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px
        }

        .decisao {
            margin: 5px 0
        }

        .final {
            text-align: center;
            margin-top: 22px;
            padding-top: 12px;
            border-top: 1px solid #d5d5d5;
            font-weight: bold;
            font-size: 13px
        }

        .exercicio {
            margin-top: 5px;
            line-height: 1.4
        }

        .exercicio:first-child {
            margin-top: 3px
        }

        .exercicio span {
            color: #555
        }
    </style>
</head>

<body>

    <div class="cabecalho">
        <div class="empresa"> {{ $empresa->emp_nome ?? 'Academia' }}
        </div>
        <div class="local">Campus {{ $empresa->emp_end_cidade ?? '' }}</div>
        <div class="titulo">RELATÓRIO DO TREINO</div>
        <div class="data">{{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}</div>
    </div>

    <div class="linha"></div>

    @forelse($grades as $item)
    @php
    $grade=$item['grade'];
    $turma=strtolower($grade->grade_turma??'');
    $ehKids=str_contains($turma,'kids')||str_contains($turma,'crianca');
    @endphp

    <div class="turma">{{ $ehKids?'KIDS':'ADULTO' }}</div>

    <div class="informacao"><strong>Guerreiros(as):</strong> {{ $item['presentes_alunos'] }}</div>
    <div class="informacao"><strong>Visitantes:</strong> {{ str_pad($item['presentes_visitantes'],2,'0',STR_PAD_LEFT) }}</div>
    <div class="tecnica">
        <strong>Técnica:</strong>
        @if($item['aulas']->count())
        @foreach($item['aulas'] as $aula)
        <div class="exercicio">
            <strong>{{ $aula->aula_nome_exercicio ?? '-' }}</strong>
            @if($aula->aula_caract_exercicio)
            <span> — {{ $aula->aula_caract_exercicio }}</span>
            @endif
        </div>
        @endforeach
        @else
        Nenhuma aula cadastrada para esta data.
        @endif
    </div>

    <div class="linha"></div>

    @empty
    <div style="text-align:center;padding:20px">Nenhuma frequência registrada nesta data.</div>
    @endforelse

    <div class="final">Oss</div>

</body>

</html>