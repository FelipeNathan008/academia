<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
use App\Models\Mensalidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\PixService;

class AlunoUserFinanceiroController extends Controller
{
    public function index(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $aluno = Aluno::with('responsavel')
            ->where('id_aluno', $id)
            ->where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->firstOrFail();

        // Atualiza mensalidades atrasadas
        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $query = Mensalidade::with([
            'matricula.grade.professor',
            'detalhes.empresa'
        ]);

        if ($request->has('matricula')) {
            $query->where('matricula_id_matricula', $request->matricula);
        }

        $mensalidades = $query
            ->where('aluno_id_aluno', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'view_aluno_user.financeiro.index',
            compact('aluno', 'mensalidades')
        );
    }

    public function enviarComprovante(Request $request, $id)
    {
        $request->validate([
            'comprovante' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp,application/pdf', 'max:5120',],
        ]);

        $user = Auth::user();
        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $detalhe = DetalhesMensalidade::with('mensalidade.aluno')
            ->findOrFail($id);

        $aluno = $detalhe->mensalidade->aluno ?? null;

        if (!$aluno || $aluno->responsavel_id_responsavel != $responsavel->id_responsavel) {
            abort(403);
        }

        if ($detalhe->det_mensa_status === 'Pago') {
            return back()->with('error', 'Essa mensalidade já está paga.');
        }

        if ($detalhe->det_mensa_comprovante_path && Storage::disk('local')->exists($detalhe->det_mensa_comprovante_path)) {
            Storage::disk('local')->delete($detalhe->det_mensa_comprovante_path);
        }

        $file = $request->file('comprovante');

        $nomeArquivo = 'comprovantes/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('local')->put($nomeArquivo, file_get_contents($file));

        $detalhe->update([
            'det_mensa_comprovante_path' => $nomeArquivo,
            'det_mensa_comprovante_enviado_em' => now(),
            'det_mensa_status' => 'Comprovante enviado',
        ]);

        return back()->with(
            'success',
            'Seu comprovante foi enviado com sucesso. Agora basta aguardar a aprovação do gestor da academia.
            Assim que o pagamento for confirmado, o status da mensalidade será atualizado automaticamente.'
        );
    }

    public function verComprovante($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $responsavel = Auth::user()->responsavel;

        $detalhe = DetalhesMensalidade::with('mensalidade.aluno')
            ->findOrFail($id);

        if (
            !$responsavel ||
            $detalhe->mensalidade->aluno->responsavel_id_responsavel != $responsavel->id_responsavel
        ) {
            abort(403);
        }

        if (
            !$detalhe->det_mensa_comprovante_path ||
            !Storage::disk('local')->exists($detalhe->det_mensa_comprovante_path)
        ) {
            abort(404);
        }
        
        return response()->file(
            Storage::disk('local')->path($detalhe->det_mensa_comprovante_path)
        );
    }

    public function showPagamento($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $detalhe = DetalhesMensalidade::with([
            'empresa',
            'mensalidade.aluno',
            'mensalidade.matricula.grade.professor'
        ])->findOrFail($id);

        $aluno = $detalhe->mensalidade->aluno;

        if ($aluno->responsavel_id_responsavel != $responsavel->id_responsavel) {
            abort(403);
        }

        $payloadPix = PixService::gerar(
            chave: $detalhe->empresa->emp_chave_pix,
            nome: $detalhe->empresa->emp_nome_recebedor ?? $detalhe->empresa->emp_nome,
            cidade: $detalhe->empresa->emp_cidade,
            valor: $detalhe->det_mensa_valor,
            txid: 'MENSA' . $detalhe->id_detalhes_mensalidade
        );

        return view(
            'view_aluno_user.financeiro.pagamento',
            compact('detalhe', 'payloadPix')
        );
    }
}
