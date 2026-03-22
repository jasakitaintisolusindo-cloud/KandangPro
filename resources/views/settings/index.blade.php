@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Sistem</h2>
                <p class="text-slate-500 font-medium">Konfigurasi operasional dan integrasi KandangPRO</p>
            </div>
            <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <!-- Tabs Header -->
            <div class="flex border-b border-slate-100 bg-slate-50/50 p-2">
                @foreach ($settings as $group => $items)
                    <button type="button" onclick="showTab('{{ Str::slug($group) }}')" id="tab-btn-{{ Str::slug($group) }}"
                        class="tab-btn flex-1 py-4 text-sm font-bold rounded-2xl transition-all duration-300 {{ $loop->first ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-400 hover:text-slate-600' }}">
                        {{ $group }}
                    </button>
                @endforeach
                <button type="button" onclick="showTab('manajemen-user')" id="tab-btn-manajemen-user"
                    class="tab-btn flex-1 py-4 text-sm font-bold rounded-2xl transition-all duration-300 text-slate-400 hover:text-slate-600">
                    Manajemen User
                </button>
            </div>

            <!-- Main Content Area -->
            <div class="p-8 md:p-10">
                <!-- Settings Form -->
                <form action="{{ route('settings.update') }}" method="POST" id="settings-form">
                    @csrf
                    @foreach ($settings as $group => $items)
                        <div id="tab-content-{{ Str::slug($group) }}"
                            class="tab-content {{ $loop->first ? '' : 'hidden' }} space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach ($items as $setting)
                                    <div class="space-y-2">
                                        <label for="{{ $setting->key }}" class="block text-sm font-bold text-slate-700 ml-1">
                                            {{ $setting->label }}
                                        </label>

                                        @if ($setting->type == 'textarea')
                                            <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="3"
                                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium">{{ $setting->value }}</textarea>
                                        @elseif($setting->type == 'number')
                                            <input type="number" step="0.01" name="{{ $setting->key }}"
                                                id="{{ $setting->key }}" value="{{ $setting->value }}"
                                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium">
                                        @elseif($setting->type == 'boolean')
                                            <select name="{{ $setting->key }}" id="{{ $setting->key }}" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium">
                                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>✅ Akses Terbuka</option>
                                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>❌ Akses Ditutup</option>
                                            </select>
                                        @else
                                            <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}"
                                                value="{{ $setting->value }}"
                                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium">
                                        @endif

                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider ml-1">Key:
                                            {{ $setting->key }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-end">
                                <button type="submit"
                                    class="px-10 py-4 bg-emerald-600 text-white rounded-2xl font-extrabold shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:shadow-emerald-200 active:scale-95 transition-all flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Simpan Perubahan</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </form>

                <!-- Management User Tab (Standalone content) -->
                <div id="tab-content-manajemen-user" class="tab-content hidden space-y-6">
                    <div class="flex justify-between items-center bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Petugas Aplikasi</h4>
                            <p class="text-xs text-slate-500 font-medium">Keluarkan atau tambahkan petugas baru untuk akses KandangPRO</p>
                        </div>
                        <button type="button" onclick="openAddUserModal()"
                            class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Tambah User</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="px-4 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Nama Lengkap</th>
                                    <th class="px-4 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Email</th>
                                    <th class="px-4 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Hak Akses</th>
                                    <th class="px-4 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($users as $user)
                                    <tr class="group hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-sm">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <span class="text-sm font-bold text-slate-700">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5 text-sm text-slate-500 font-medium">{{ $user->email }}</td>
                                        <td class="px-4 py-5">
                                            @if($user->isManager())
                                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase rounded-full">Manager</span>
                                            @else
                                                <span class="px-3 py-1 bg-teal-100 text-teal-700 text-[10px] font-black uppercase rounded-full">Petugas</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-5 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="openEditUserModal({{ $user }})"
                                                    class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                @if ($user->id !== auth()->id())
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus user ini?')"
                                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="bg-indigo-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-900/20">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-1/3 -translate-y-1/3 blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-white/10 rounded-2xl border border-white/10">
                        <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold tracking-tight">Butuh Bantuan Konfigurasi?</h4>
                        <p class="text-indigo-200 text-sm font-medium">Hubungi tim IT KandangPRO untuk integrasi API Chikin atau URL CCTV.</p>
                    </div>
                </div>
                <a href="https://wa.me/{{ setting('whatsapp_report') }}" target="_blank"
                    class="px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-sm font-bold transition-all">
                    Hubungi Support
                </a>
            </div>
        </div>
    </div>

    <!-- Modals (Outside main layout to prevent nesting issues) -->
    <!-- Add User Modal -->
    <div id="add-user-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddUserModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg transform overflow-hidden rounded-[2.5rem] bg-white p-8 shadow-2xl transition-all border border-slate-100">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900">Tambah Petugas Baru</h3>
                        <p class="text-xs text-slate-500 font-medium">Buat akun akses baru untuk KandangPRO</p>
                    </div>
                    <button onclick="closeAddUserModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Email</label>
                        <input type="email" name="email" required placeholder="budi@example.com"
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Hak Akses (Role)</label>
                        <select name="role" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium">
                            <option value="petugas">Petugas (Staff)</option>
                            <option value="manager">Manager / Super Admin</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Password</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Konfirmasi</label>
                            <input type="password" name="password_confirmation" required placeholder="••••••••"
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 font-medium">
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-extrabold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all active:scale-[0.98]">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditUserModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg transform overflow-hidden rounded-[2.5rem] bg-white p-8 shadow-2xl transition-all border border-slate-100">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900">Edit Petugas</h3>
                        <p class="text-xs text-slate-500 font-medium">Perbarui informasi akses petugas</p>
                    </div>
                    <button onclick="closeEditUserModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form id="edit-user-form" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" id="edit-user-name" required
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Email</label>
                        <input type="email" name="email" id="edit-user-email" required
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Hak Akses (Role)</label>
                        <select name="role" id="edit-user-role" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium">
                            <option value="petugas">Petugas (Staff)</option>
                            <option value="manager">Manager / Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100">
                        <p class="text-[10px] font-bold text-orange-600 uppercase tracking-widest mb-1.5">Opsional: Ganti Password</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold text-slate-500 ml-1">Password Baru</label>
                                <input type="password" name="password" placeholder="••••••••"
                                    class="block w-full px-3 py-2 bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold text-slate-500 ml-1">Konfirmasi</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                    class="block w-full px-3 py-2 bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all text-sm">
                            </div>
                        </div>
                        <p class="text-[9px] text-orange-400 mt-2 font-medium">Kosongkan jika tidak ingin mengganti password.</p>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-extrabold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showTab(groupId) {
                // Hide all contents
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                // Show selected content
                document.getElementById('tab-content-' + groupId).classList.remove('hidden');

                // Reset all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('bg-white', 'shadow-sm', 'text-emerald-600');
                    btn.classList.add('text-slate-400');
                });
                // Activate selected button
                const activeBtn = document.getElementById('tab-btn-' + groupId);
                activeBtn.classList.remove('text-slate-400');
                activeBtn.classList.add('bg-white', 'shadow-sm', 'text-emerald-600');
            }

            // Modal Controls
            function openAddUserModal() {
                document.getElementById('add-user-modal').classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeAddUserModal() {
                document.getElementById('add-user-modal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            function openEditUserModal(user) {
                const modal = document.getElementById('edit-user-modal');
                const form = document.getElementById('edit-user-form');
                
                // Set path
                form.action = `/users/${user.id}`;
                
                // Set values
                document.getElementById('edit-user-name').value = user.name;
                document.getElementById('edit-user-email').value = user.email;
                document.getElementById('edit-user-role').value = user.role;
                
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeEditUserModal() {
                document.getElementById('edit-user-modal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        </script>
    @endpush

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.6s cubic-bezier(0.2, 0, 0, 1) forwards; }
    </style>
@endsection
