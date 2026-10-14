@props(['status', 'color' => null])

@php
$statusNormalizado = mb_strtolower(trim((string) $status));

$cores = [
'green' => 'bg-green-100 text-green-700',
'red' => 'bg-red-100 text-red-700',
'yellow' => 'bg-yellow-100 text-yellow-800',
'blue' => 'bg-blue-100 text-blue-700',
'purple' => 'bg-purple-100 text-purple-700',
'gray' => 'bg-gray-100 text-gray-700',
];

$mapaStatus = [
'vago' => 'green',
'ativo' => 'green',
'aprovado' => 'green',
'pago' => 'green',
'quitado' => 'green',
'entregue' => 'green',
'disponível' => 'green',
'disponivel' => 'green',
'ocupado' => 'red',
'rejeitado' => 'red',
'cancelado' => 'red',
'atrasado' => 'red',
'inadimplente' => 'red',
'não pago' => 'red',
'nao pago' => 'red',
'pendente' => 'yellow',
'aguardando' => 'yellow',
'em análise' => 'blue',
'em analise' => 'blue',
'acordo' => 'blue',
'processando' => 'blue',
'personalizado' => 'purple',
];

$cor = $color ?? ($mapaStatus[$statusNormalizado] ?? 'gray');
$classe = $cores[$cor] ?? $cores['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold $classe"]) }}>
    {{ $status }}
</span>