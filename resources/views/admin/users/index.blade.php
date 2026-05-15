@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('User Management') }}</h1>
            <p class="mt-1 text-sm text-slate-500 font-medium">
                {{ __('Manage system users and their roles.') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                {{ __('Dashboard') }}
            </a>
            <button onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm shadow-indigo-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add New User') }}
            </button>
        </div>
    </div>

    {{-- Messages handled in layout --}}
    @if($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-200">
            <ul>
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            {{ __('Name') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            {{ __('Email') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            {{ __('Role') }}</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="openEditModal({{ json_encode($user) }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-4">{{ __('Edit') }}</button>
                                @if($user->id !== auth()->id())
                                    <button onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}')"
                                        class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal (Shared for Create/Edit) -->
    <div id="userModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeUserModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg">
                <form id="userForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-xl font-bold text-slate-900 mb-4" id="modalTitle">{{ __('Add New User') }}</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700">{{ __('Name') }}</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-slate-700">{{ __('Email') }}</label>
                                <input type="email" name="email" id="email" required
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium text-slate-700">{{ __('Role') }}</label>
                                <select name="role" id="role"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div>
                                <label for="password"
                                    class="block text-sm font-medium text-slate-700">{{ __('Password') }}</label>
                                <input type="password" name="password" id="password" autocomplete="new-password"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-slate-500" id="passwordLast">
                                    {{ __('Min length 6 characters.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 flex flex-row-reverse gap-2 sm:px-6">
                        <button type="submit"
                            class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">{{ __('Save') }}</button>
                        <button type="button"
                            class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto"
                            onclick="closeUserModal()">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = '{{ __('Add New User') }}';
            document.getElementById('userForm').action = '{{ route('admin.users.store') }}';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('role').value = 'user';
            document.getElementById('password').required = true;
            document.getElementById('passwordLast').innerText = '{{ __('Min length 6 characters.') }}';

            document.getElementById('userModal').classList.remove('hidden');
        }

        function openEditModal(user) {
            document.getElementById('modalTitle').innerText = '{{ __('Edit User') }}';
            document.getElementById('userForm').action = '/admin/users/' + user.id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('role').value = user.role;
            document.getElementById('password').required = false;
            document.getElementById('passwordLast').innerText = '{{ __('Leave blank to keep current password. Min length 6 if changing.') }}';

            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function confirmDelete(url) {
            if (confirm('{{ __('Are you sure you want to delete this user?') }}')) {
                const form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endpush