 <!-- SIDEBAR -->
 <aside id="sidebar" class="w-64 bg-[#3A403E] text-[#9A9F9A] border-r shadow-sm">

     <div class="p-6">
         <span class="text-xl font-bold text-white">DudaJJ</span>
     </div>

     <nav class="px-4 space-y-1">

         @php
         $menu = [
         'Dashboard' => 'dashboard',
         'Alunos/Responsáveis' => 'alunos',
         'Professores' => 'professores',
         'Administração' => [
         'Graduações' => 'graduacoes',
         'Modalidades' => 'modalidades',
         ],
         ];
         @endphp

         @foreach ($menu as $label => $route)

         @if(is_array($route))
         <button onclick="toggleSubMenu('{{ Str::slug($label) }}')"
             class="w-full text-left px-4 py-2 rounded-lg hover:bg-[#8E251F]/20 hover:text-white">
             {{ $label }}
         </button>

         <div id="submenu-{{ Str::slug($label) }}" class="hidden ml-6">
             @foreach($route as $subLabel => $subRoute)
             <a href="{{ route($subRoute) }}"
                 class="block px-4 py-2 rounded-lg hover:bg-[#732920]/50 hover:text-white">
                 {{ $subLabel }}
             </a>
             @endforeach
         </div>
         @else
         <a href="{{ route($route) }}"
             class="block px-4 py-2 rounded-lg hover:bg-[#8E251F]/20 hover:text-white">
             {{ $label }}
         </a>
         @endif

         @endforeach
     </nav>
 </aside>

 <script>
     function toggleSidebar() {
         document.getElementById('sidebar').classList.toggle('closed');
     }

     function toggleSubMenu(id) {
         document.getElementById('submenu-' + id).classList.toggle('hidden');
     }
 </script>