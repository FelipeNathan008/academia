<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;

class SuperAdminEmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::with([
            'filiais',
            'contratos.detalheAtual'
        ])
            ->whereNot('emp_tipo', 'PS')
            ->orderBy('emp_nome')
            ->paginate(10)
            ->withQueryString();

        $totalEmpresas = Empresa::whereNot('emp_tipo', 'PS')->count();

        $empresaIds = $empresas->getCollection()->pluck('id_empresa')->toArray();

        $contagensMatriculas = DB::table('matricula')
            ->select('id_emp_id', DB::raw('COUNT(*) as total'))
            ->whereIn('id_emp_id', $empresaIds)
            ->whereIn('matri_status', ['Matriculado', 'Pausada'])
            ->groupBy('id_emp_id')
            ->pluck('total', 'id_emp_id');

        $empresas->getCollection()->transform(function ($empresa) use ($contagensMatriculas) {
            $empresa->contagem_matriculas = (int) ($contagensMatriculas[$empresa->id_empresa] ?? 0);
            $empresa->contrato_atual = $empresa->contratos->sortByDesc('id_contrato')->first();
            $empresa->detalhe_plano = $empresa->contrato_atual?->detalheAtual;
            $empresa->limite_matriculas = null;
            $empresa->percentual_matriculas = null;
            $empresa->status_matriculas = 'Sem contrato';
            $empresa->cor_matriculas = 'gray';

            if ($empresa->detalhe_plano) {
                if ($empresa->detalhe_plano->det_plano_tipo === 'financeiro') {
                    $empresa->status_matriculas = 'Pacote adicional';
                    $empresa->cor_matriculas = 'blue';
                } else {
                    $limite = (int) $empresa->detalhe_plano->det_plano_quantidade;
                    $empresa->limite_matriculas = $limite;

                    if ($limite > 0) {
                        $empresa->percentual_matriculas = min(
                            ($empresa->contagem_matriculas / $limite) * 100,
                            100
                        );

                        if ($empresa->contagem_matriculas > $limite) {
                            $empresa->status_matriculas = 'Limite excedido';
                            $empresa->cor_matriculas = 'red';
                        } elseif ($empresa->contagem_matriculas == $limite) {
                            $empresa->status_matriculas = 'Limite atingido';
                            $empresa->cor_matriculas = 'yellow';
                        } elseif ($empresa->contagem_matriculas >= ($limite * 0.8)) {
                            $empresa->status_matriculas = 'Próximo do limite';
                            $empresa->cor_matriculas = 'yellow';
                        } else {
                            $empresa->status_matriculas = 'Dentro do limite';
                            $empresa->cor_matriculas = 'green';
                        }
                    } else {
                        $empresa->status_matriculas = 'Limite não definido';
                        $empresa->cor_matriculas = 'gray';
                    }
                }
            }

            return $empresa;
        });

        return view('view_superadmin.dashboard.empresas', compact('empresas', 'totalEmpresas'));
    }

    public function contratos($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $empresa = Empresa::with([
            'contratos.detalhes.plano',
            'contratos.detalhes.mensalidades'
        ])
            ->whereNot('emp_tipo', 'PS')
            ->findOrFail($id);

        $contratos = $empresa->contratos->sortByDesc('id_contrato');

        return view(
            'view_superadmin.dashboard.empresa_contratos',
            compact('empresa', 'contratos')
        );
    }
}
