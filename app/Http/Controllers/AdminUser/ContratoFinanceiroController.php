<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\MensalidadeContrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\PixService;
use App\Services\PagamentoContratoService;

class ContratoFinanceiroController extends Controller
{
    public function showPagamento($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $mensalidade = MensalidadeContrato::with('detalheContrato.contrato')->findOrFail($id);
        $contrato = $mensalidade->detalheContrato->contrato;

        if ($contrato->empresa_id != $user->id_emp_id) {
            abort(403);
        }

        $payloadPix = PixService::gerar(
            chave: $contrato->contrato_pix_chave,
            nome: $contrato->contrato_pix_recebedor ?? $contrato->contrato_empresa_nome,
            cidade: $contrato->contrato_pix_cidade,
            valor: (float) $mensalidade->mensa_contrato_valor,
            txid: 'CONT' . $mensalidade->id_mensalidade_contrato
        );

        return view('view_admin_user.view_contratos.pagamento', compact(
            'mensalidade',
            'contrato',
            'payloadPix'
        ));
    }

    public function enviarComprovante(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $request->validate([
            'comprovante' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,application/pdf',
                'max:5120'
            ]
        ]);

        $user = Auth::user();

        $mensalidade = MensalidadeContrato::with('detalheContrato.contrato')->findOrFail($id);
        $contrato = $mensalidade->detalheContrato->contrato;

        if ($contrato->empresa_id != $user->id_emp_id) {
            abort(403);
        }

        if ($mensalidade->mensa_contrato_status == 'Pago') {
            return back()->with('error', 'Esta mensalidade já foi paga.');
        }

        if (
            $mensalidade->mensa_contrato_comprovante &&
            Storage::disk('local')->exists($mensalidade->mensa_contrato_comprovante)
        ) {
            Storage::disk('local')->delete($mensalidade->mensa_contrato_comprovante);
        }

        $arquivo = $request->file('comprovante');

        $nomeArquivo = 'comprovantes_contratos/' . Str::uuid() . '.' . $arquivo->getClientOriginalExtension();

        Storage::disk('local')->put($nomeArquivo, file_get_contents($arquivo));

        $mensalidade->update([
            'mensa_contrato_comprovante' => $nomeArquivo,
            'mensa_contrato_comprovante_enviado_em' => now(),
            'mensa_contrato_status' => 'Comprovante enviado'
        ]);

        return back()->with(
            'success',
            'Comprovante enviado com sucesso. Aguarde a confirmação.'
        );
    }

    public function verComprovante($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $mensalidade = MensalidadeContrato::with('detalheContrato.contrato')->findOrFail($id);
        $contrato = $mensalidade->detalheContrato->contrato;

        if ($contrato->empresa_id != $user->id_emp_id) {
            abort(403);
        }

        if (
            !$mensalidade->mensa_contrato_comprovante ||
            !Storage::disk('local')->exists($mensalidade->mensa_contrato_comprovante)
        ) {
            abort(404);
        }

        return response()->file(
            Storage::disk('local')->path($mensalidade->mensa_contrato_comprovante)
        );
    }
}
