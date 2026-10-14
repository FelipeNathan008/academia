 @extends('layouts.dashboard')

 @section('title', 'Professores')

 @section('content')
 <style>
     .professor-sem-graduacao td {
         background-color: #fffaf7;
     }

     .professor-sem-graduacao td:first-child {
         box-shadow: inset 3px 0 0 #d97757;
     }

     .professor-sem-graduacao:hover td {
         background-color: #fff3ed;
     }
 </style>

 <x-alert-error />

 <div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
     <x-page-header title="Professores" :breadcrumbs="[['label' => 'Administração', 'url' => route('admin.administracao')], ['label' => 'Professores']]">
         <x-slot:action>
             <div class="flex flex-wrap items-center gap-3">
                 <x-back-button :url="route('admin.administracao')" />
                 <x-primary-button icon="plus" onclick="toggleCadastro()">Cadastrar professor</x-primary-button>
             </div>
         </x-slot:action>
     </x-page-header>

     <div id="cadastroForm" class="mb-8 hidden">
         <form id="formCadastro" action="{{ route('professores.store') }}" method="POST" enctype="multipart/form-data" onsubmit="bloquearSubmit(event, this)">
             @csrf
             <x-form-card title="Cadastrar professor" description="Preencha os dados para cadastrar um novo professor.">
                 <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                     <div>
                         <label for="cad_nome" class="mb-2 block text-sm font-semibold text-gray-700">Nome completo</label>
                         <input type="text" name="prof_nome" id="cad_nome" maxlength="120" required value="{{ old('prof_nome') }}" placeholder="Ex.: João da Silva" oninput="validarNome(this)" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                     </div>
                     <div>
                         <label for="cad_nascimento" class="mb-2 block text-sm font-semibold text-gray-700">Data de nascimento</label>
                         <input type="date" name="prof_nascimento" id="cad_nascimento" required value="{{ old('prof_nascimento') }}" min="{{ now()->subYears(130)->format('Y-m-d') }}" max="{{ now()->subYears(16)->format('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                     </div>
                     <div>
                         <label for="prof_telefone" class="mb-2 block text-sm font-semibold text-gray-700">Telefone</label>
                         <input type="text" name="prof_telefone" id="prof_telefone" maxlength="15" required value="{{ old('prof_telefone') }}" placeholder="(99) 99999-9999" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                     </div>
                     <div class="md:col-span-2">
                         <label for="cad_desc" class="mb-2 block text-sm font-semibold text-gray-700">Observações</label>
                         <textarea name="prof_desc" id="cad_desc" rows="3" required placeholder="Ex.: Professor de Judô, horários flexíveis" class="w-full resize-y rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">{{ old('prof_desc') }}</textarea>
                     </div>
                     <div class="md:col-span-2">
                         <label for="cad_foto" class="mb-2 block text-sm font-semibold text-gray-700">Foto do professor</label>
                         <input type="file" name="prof_foto" id="cad_foto" required accept="image/*" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:font-medium">
                     </div>
                 </div>
                 <x-slot:actions>
                     <x-secondary-button onclick="fecharCadastro()">Cancelar</x-secondary-button>
                     <x-primary-button type="submit" icon="check">Salvar professor</x-primary-button>
                 </x-slot:actions>
             </x-form-card>
         </form>
     </div>

     <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md">
         <div class="border-b border-gray-200 px-6 py-5">
             <form action="{{ url()->current() }}" method="GET">
                 <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                     <div class="w-full sm:max-w-sm">
                         <x-search-input name="busca" id="filtroNomeProfessor" label="Buscar professor" :value="request('busca')" placeholder="Pesquise pelo nome..." />
                     </div>
                     <div class="w-full sm:max-w-xs">
                         <label for="filtroGraduado" class="mb-2 block text-sm font-semibold text-gray-700">Graduação</label>
                         <select name="graduacao" id="filtroGraduado" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                             <option value="">Todos</option>
                             <option value="sim" @selected(request('graduacao')==='sim' )>Graduado</option>
                             <option value="nao" @selected(request('graduacao')==='nao' )>Sem graduação</option>
                         </select>
                     </div>
                     <div class="flex flex-col gap-3 sm:flex-row">
                         <x-primary-button type="submit" icon="search">Buscar</x-primary-button>
                         <a href="{{ url()->current() }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-gray-100 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Limpar</a>
                     </div>
                 </div>
             </form>

             <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                 <p class="text-sm text-gray-500">Consulte os professores, suas graduações e os alunos vinculados.</p>
                 <x-record-count :count="$professores->total()" singular="professor" plural="professores" />
             </div>
         </div>

         <div class="p-4 sm:p-6">
             @if ($professores->isEmpty())
             @if (request()->filled('busca') || request()->filled('graduacao'))
             <x-empty-state title="Nenhum professor encontrado" message="Tente alterar os filtros da pesquisa." :route="url()->current()" button-text="Limpar filtros" />
             @else
             <x-empty-state title="Nenhum professor cadastrado" message="Cadastre um professor para começar." button-type="button" button-action="toggleCadastro()" button-text="Cadastrar professor" />
             @endif
             @else
             <div class="overflow-x-auto rounded-xl border border-gray-200">
                 <table class="w-full border-collapse text-left">
                     <thead class="bg-gray-700 text-white">
                         <tr>
                             <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Nome</th>
                             <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Foto</th>
                             <th class="border-r border-gray-500 px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Qtd. alunos</th>
                             <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Ações</th>
                         </tr>
                     </thead>
                     <tbody class="divide-y divide-gray-200 bg-white">
                         @foreach ($professores as $professor)
                         @php
                         $professorGraduado = $professor->detalhes->isNotEmpty();
                         @endphp
                         <tr class="transition-colors {{ !$professorGraduado ? 'professor-sem-graduacao' : 'hover:bg-gray-50' }}">
                             <td class="border-r border-gray-200 px-5 py-4">
                                 <div class="flex flex-col gap-1">
                                     <span class="text-sm font-semibold text-gray-800">{{ Str::upper($professor->prof_nome) }}</span>
                                     @unless ($professorGraduado)
                                     <span class="text-xs font-medium text-[#a65d42]">Graduação não cadastrada</span>
                                     @endunless
                                 </div>
                             </td>
                             <td class="border-r border-gray-200 px-5 py-4">
                                 @if ($professor->prof_foto)
                                 <img src="{{ asset('images/professores/' . $professor->prof_foto) }}" alt="Foto de {{ $professor->prof_nome }}" class="h-12 w-12 rounded-lg border border-gray-200 object-cover">
                                 @else
                                 <span class="text-sm text-gray-400">Sem foto</span>
                                 @endif
                             </td>
                             <td class="border-r border-gray-200 px-5 py-4 text-center">
                                 <a href="{{ route('alunos.index', ['prof_nome' => $professor->prof_nome]) }}" title="Ver alunos deste professor" class="inline-flex min-w-10 items-center justify-center rounded-full bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                     {{ $professor->qtd_aluno ?? 0 }}
                                 </a>
                             </td>
                             <td class="px-5 py-4">
                                 <x-table-actions
                                     :extra-route="route('grades.aulas', ['professor' => Crypt::encrypt($professor->id_professor)])"
                                     extra-text="Aulas"
                                     extra-color="blue-dark"
                                     :second-route="route('detalhes-professor.index', Crypt::encrypt($professor->id_professor))"
                                     second-text="Graduações"
                                     second-color="wine"
                                     :edit-route="route('professores.edit', Crypt::encrypt($professor->id_professor))"
                                     :delete-action="route('professores.destroy', Crypt::encrypt($professor->id_professor))"
                                     :delete-id="'formExcluirProfessor'.$professor->id_professor"
                                     delete-modal="modalExclusao" />
                             </td>
                         </tr>
                         @endforeach
                     </tbody>
                 </table>
             </div>
             <div class="mt-5">
                 <x-pagination-compact :paginator="$professores" />
             </div>
             @endif
         </div>
     </div>
 </div>

 <x-modal-confirmacao id="modalExclusao" titulo="Confirmar exclusão" mensagem="Deseja realmente excluir este professor?" texto-confirmar="Excluir" />

 <script>
     function toggleCadastro() {
         const cadastro = document.getElementById('cadastroForm');
         const abrir = cadastro.classList.contains('hidden');

         cadastro.classList.toggle('hidden', !abrir);

         if (abrir) {
             cadastro.scrollIntoView({
                 behavior: 'smooth',
                 block: 'start'
             });
         }
     }

     function fecharCadastro() {
         document.getElementById('cadastroForm').classList.add('hidden');
     }

     function bloquearSubmit(event, form) {
         if (!form.checkValidity()) return;
         const botao = form.querySelector('button[type="submit"]');
         if (botao) {
             botao.disabled = true;
             botao.innerText = 'Salvando...';
         }
     }

     function validarNome(input) {
         input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
     }

     const tel = document.getElementById('prof_telefone');
     if (tel) {
         tel.addEventListener('input', () => {
             let v = tel.value.replace(/\D/g, '').slice(0, 11);
             if (!v.length) {
                 tel.value = '';
                 return;
             }
             tel.value = v.length <= 2 ? '(' + v : '(' + v.slice(0, 2) + ') ' + v.slice(2, v.length > 10 ? 7 : 6) + (v.length > 6 ? '-' + v.slice(v.length > 10 ? 7 : 6) : '');
         });
     }
 </script>
 @endsection