@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Pending Approvals') }}</h1>
            <p class="mt-1 text-sm text-slate-500 font-medium">
                {{ __('Review and approve participant applications.') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('participants.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                {{ __('Participants List') }}
            </a>
            <a href="{{ route('participants.export_pending') }}"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                {{ __('Export Pending List') }}
            </a>
        </div>
    </div>

    {{-- Messages handled in layout --}}

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg">
            <div class="font-medium mb-1">Error:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search and Filter Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <form id="searchForm" method="GET" action="{{ route('participants.pending') }}"
            class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-5 relative">
                <label for="search"
                    class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 pl-1">{{ __('Search') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        class="pl-10 block w-full rounded-lg border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 sm:text-sm py-2.5"
                        placeholder="{{ __('Search by Name, Mobile or Token...') }}">
                </div>
            </div>

            <!-- Filter Dropdown -->
            <div class="md:col-span-3">
                <label for="voter_filter"
                    class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 pl-1">{{ __('Voter ID Status') }}</label>
                <select id="voter_filter" name="voter_filter"
                    class="block w-full rounded-lg border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 sm:text-sm py-2.5 px-3">
                    <option value="">{{ __('All Verification Status') }}</option>
                    <option value="1" {{ request('voter_filter') == '1' ? 'selected' : '' }}>{{ __('Verified (With ID)') }}
                    </option>
                    <option value="0" {{ request('voter_filter') == '0' ? 'selected' : '' }}>{{ __('Unverified (No ID)') }}
                    </option>
                </select>
            </div>

            <!-- Actions -->
            <div class="md:col-span-4 flex items-end gap-3">
                <button type="submit"
                    class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                    {{ __('Apply Filters') }}
                </button>
                <button type="button" id="clearBtn"
                    class="inline-flex justify-center items-center px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all">
                    {{ __('Clear') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Pending Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            {{ __('Full Name') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            {{ __('Mobile') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            {{ __('Details') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            {{ __('Applied On') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($pendingParticipants as $participant)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-900">{{ $participant->full_name }}</div>
                                <div class="text-xs text-slate-500">{{ __('Age') }}: {{ $participant->age }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-slate-600">{{ $participant->mobile_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-500 space-y-1">
                                    @if($participant->voter_id)
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800">Voter
                                                ID</span>
                                            <span>{{ $participant->epic_voter_id_no }}</span>
                                        </div>
                                    @endif
                                    @if($participant->adharcard_no)
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">Adhar</span>
                                            <span>{{ $participant->adharcard_no }}</span>
                                        </div>
                                    @endif
                                    <div class="truncate max-w-xs" title="{{ $participant->permanent_address }}">
                                        {{ $participant->permanent_address }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $participant->created_at->format('M d, Y') }}
                                <div class="text-xs text-slate-400">{{ $participant->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="openApproveModal('{{ $participant->id }}', '{{ $participant->full_name }}')"
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-lg text-xs font-bold text-white uppercase tracking-wide hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                                    {{ __('Approve') }}
                                </button>
                                <button
                                    class="delete-btn ml-2 inline-flex items-center px-3 py-1.5 bg-white border border-rose-200 rounded-lg text-xs font-bold text-rose-600 uppercase tracking-wide hover:bg-rose-50 hover:text-rose-700 transition-all"
                                    title="Delete" data-id="{{ $participant->id }}">
                                    {{ __('Reject') }}
                                </button>
                                <button onclick='openViewModal(@json($participant))'
                                    class="ml-2 inline-flex items-center px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 uppercase tracking-wide hover:bg-slate-200 hover:text-slate-800 transition-all">
                                    {{ __('View') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                {{ __('No pending participants found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingParticipants->hasPages())
            <div class="bg-white px-6 py-4 border-t border-slate-200">
                {{ $pendingParticipants->links() }}
            </div>
        @endif
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeApproveModal()"></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="bg-white px-6 py-6 pt-8">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('Approve Participant') }}</h3>
                        <p class="text-sm text-slate-500 mb-6">
                            {{ __('Assign a token to approve') }} <span id="approveName"
                                class="font-bold text-slate-800"></span>.
                            <br>
                            <span
                                class="text-xs text-indigo-600 font-semibold mt-1 inline-block bg-indigo-50 px-2 py-1 rounded">
                                {{ __('Total Approved So Far') }}: <span class="font-bold">{{ $totalRegistered }}</span>
                            </span>
                        </p>

                        <div>
                            <label for="token"
                                class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Assign Token') }}</label>
                            <input type="text" name="token" id="token" required maxlength="4" pattern="\d{1,4}"
                                class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg font-mono tracking-wider text-center py-3"
                                placeholder="1" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)">
                            <div id="approveTokenError" class="text-xs text-rose-500 mt-1 hidden font-bold text-center">
                            </div>
                            <p class="mt-2 text-xs text-slate-500">{{ __('Enter a unique numeric token manually.') }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button"
                            class="inline-flex justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto"
                            onclick="closeApproveModal()">{{ __('Cancel') }}</button>
                        <button type="submit"
                            class="inline-flex justify-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">{{ __('Approve') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-lg">
                <div class="bg-white px-6 py-6 pt-8">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-slate-900">{{ __('Participant Details') }}</h3>
                        <button onclick="closeViewModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Full Name') }}</label>
                                <p id="viewFullName" class="text-slate-900 font-semibold text-lg"></p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Age') }}</label>
                                <p id="viewAge" class="text-slate-900 font-medium"></p>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Mobile Number') }}</label>
                            <p id="viewMobile" class="text-slate-900 font-mono font-medium"></p>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Address') }}</label>
                            <p id="viewAddress" class="text-slate-700 bg-slate-50 p-3 rounded-lg text-sm"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Adhar Card') }}</label>
                                <p id="viewAdhar" class="text-slate-900 font-mono"></p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Voter ID Details') }}</label>
                                <div id="viewVoterDetails"></div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Applied On') }}</label>
                            <p id="viewDate" class="text-slate-500 text-sm"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end">
                    <button type="button"
                        class="inline-flex justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        onclick="closeViewModal()">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openApproveModal(id, name) {
            $('#approveName').text(name);
            $('#approveForm').attr('action', `/participants/${id}/approve`);
            $('#token').val('');
            $('#approveModal').removeClass('hidden');
            setTimeout(() => $('#token').focus(), 100);
        }

        function closeApproveModal() {
            $('#approveModal').addClass('hidden');
        }

        function openViewModal(participant) {
            $('#viewFullName').text(participant.full_name);
            $('#viewAge').text(participant.age);
            $('#viewMobile').text(participant.mobile_number);
            $('#viewAddress').text(participant.permanent_address);
            $('#viewAdhar').text(participant.adharcard_no || 'N/A');

            // Format Voter ID
            if (participant.voter_id) {
                $('#viewVoterDetails').html(`
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 mb-1">Has Voter ID</span>
                                    <div class="text-sm font-mono text-slate-600">${participant.epic_voter_id_no || '-'}</div>
                                    <div class="text-xs text-slate-400">Members: ${participant.voter_member_count}</div>
                                 `);
            } else {
                $('#viewVoterDetails').html(`<span class="text-slate-400 italic">No Voter ID</span>`);
            }

            // Format Date
            const date = new Date(participant.created_at);
            $('#viewDate').text(date.toLocaleString());

            $('#viewModal').removeClass('hidden');
        }

        function closeViewModal() {
            $('#viewModal').addClass('hidden');
        }

        $(document).ready(function () {
            // Auto-search logic
            let debounceTimer;
            $('#search').on('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => $('#searchForm').submit(), 500);
            });

            $('#voter_filter').on('change', function () {
                $('#searchForm').submit();
            });

            $('#clearBtn').on('click', function () {
                window.location.href = "{{ route('participants.pending') }}";
            });

            // Real-time Token Validation
            let tokenDebounce;
            $('#token').on('input', function () {
                const val = $(this).val();
                const $error = $('#approveTokenError');
                const $submitBtn = $('#approveForm button[type="submit"]');

                clearTimeout(tokenDebounce);
                $error.addClass('hidden').text('');
                $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

                if (val.length > 0) {

                    if (val < 1 || val > 5000) {
                        $error.removeClass('hidden').text('Token must be between 1 and 5000.');
                        $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                        return;
                    }
                    if (val.length >= 1) {
                        tokenDebounce = setTimeout(function () {
                            $.get('{{ route("api.check-token") }}', { token: val }, function (res) {
                                if (res.exists) {
                                    $error.removeClass('hidden').text('{{ __('This token already exists') }}');
                                    $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                                }
                            });
                        }, 300);
                    }
                }
            });

            // Reuse delete logic
            $('.delete-btn').on('click', function () {
                const id = $(this).data('id');
                if (confirm('{{ __('Are you sure you want to reject (delete) this application?') }}')) {
                    $.ajax({
                        url: `/participants/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                window.location.reload();
                            }
                        },
                        error: function (xhr) {
                            alert('{{ __('Error deleting participant') }}');
                        }
                    });
                }
            });
        });
    </script>
@endpush