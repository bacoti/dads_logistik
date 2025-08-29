<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-user mr-2"></i>
                {{ __('Detail User: ') }} {{ $user->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Avatar -->
                            <div class="flex flex-col items-center mb-6">
                                <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-2xl font-bold text-gray-700">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-gray-600">{{ $user->email }}</p>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex justify-center mb-6">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            <!-- Role Badge -->
                            <div class="flex justify-center mb-6">
                                @if($user->role == 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-user-shield mr-2"></i>
                                        Administrator
                                    </span>
                                @elseif($user->role == 'user_lapangan')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-hard-hat mr-2"></i>
                                        User Lapangan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-clipboard-list mr-2"></i>
                                        User PO
                                    </span>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            @if($user->id !== auth()->id())
                                <div class="space-y-2">
                                    <!-- Toggle Status -->
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full bg-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center"
                                                onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} mr-2"></i>
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <!-- Reset Password -->
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center"
                                                onclick="return confirm('Yakin ingin reset password user ini?')">
                                            <i class="fas fa-key mr-2"></i>
                                            Reset Password
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Details Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informasi Detail
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Basic Information -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-3">Informasi Dasar</h4>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">ID User</dt>
                                            <dd class="text-sm text-gray-900 font-mono">{{ $user->id }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">Nama Lengkap</dt>
                                            <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">Email</dt>
                                            <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">Role</dt>
                                            <dd class="text-sm text-gray-900">
                                                @if($user->role == 'admin')
                                                    Administrator
                                                @elseif($user->role == 'user_lapangan')
                                                    User Lapangan
                                                @else
                                                    User PO
                                                @endif
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Account Status -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-3">Status Akun</h4>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">Status</dt>
                                            <dd class="text-sm">
                                                @if($user->is_active)
                                                    <span class="text-green-600 font-medium">Aktif</span>
                                                @else
                                                    <span class="text-gray-600 font-medium">Nonaktif</span>
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">Email Terverifikasi</dt>
                                            <dd class="text-sm">
                                                @if($user->email_verified_at)
                                                    <span class="text-green-600 font-medium">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        {{ $user->email_verified_at->format('d M Y H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-red-600 font-medium">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Belum Terverifikasi
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">Terdaftar</dt>
                                            <dd class="text-sm text-gray-900">
                                                {{ $user->created_at->format('d M Y H:i') }}
                                                <span class="text-gray-500">({{ $user->created_at->diffForHumans() }})</span>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-700">Terakhir Diupdate</dt>
                                            <dd class="text-sm text-gray-900">
                                                {{ $user->updated_at->format('d M Y H:i') }}
                                                <span class="text-gray-500">({{ $user->updated_at->diffForHumans() }})</span>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Role Permissions -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Hak Akses Role</h4>
                                <div class="bg-gray-50 rounded-md p-4">
                                    @if($user->role == 'admin')
                                        <div class="text-sm text-gray-700">
                                            <p class="font-medium text-red-700 mb-2">Administrator - Akses Penuh</p>
                                            <ul class="space-y-1 list-disc list-inside">
                                                <li>Mengelola semua user dalam sistem</li>
                                                <li>Akses ke semua fitur manajemen</li>
                                                <li>Melihat dan mengelola semua transaksi</li>
                                                <li>Konfigurasi sistem dan pengaturan</li>
                                                <li>Laporan dan dashboard lengkap</li>
                                            </ul>
                                        </div>
                                    @elseif($user->role == 'user_lapangan')
                                        <div class="text-sm text-gray-700">
                                            <p class="font-medium text-green-700 mb-2">User Lapangan</p>
                                            <ul class="space-y-1 list-disc list-inside">
                                                <li>Mengelola data lapangan</li>
                                                <li>Input dan update transaksi</li>
                                                <li>Melihat dashboard lapangan</li>
                                                <li>Upload dokumen terkait lapangan</li>
                                                <li>Laporan aktivitas lapangan</li>
                                            </ul>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-700">
                                            <p class="font-medium text-purple-700 mb-2">User PO (Purchase Order)</p>
                                            <ul class="space-y-1 list-disc list-inside">
                                                <li>Mengelola Purchase Order</li>
                                                <li>Mengelola data material</li>
                                                <li>Approval workflow PO</li>
                                                <li>Melihat dashboard PO</li>
                                                <li>Laporan material dan PO</li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action History (if available) -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Aktivitas Terakhir</h4>
                                <div class="bg-blue-50 rounded-md p-4">
                                    <p class="text-sm text-blue-700">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Sistem logging aktivitas user akan segera ditambahkan untuk melacak aktivitas user secara detail.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
