@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Participant Directory') }}</h1>
            <p class="mt-1 text-sm text-slate-500 font-medium">
                {{ __('View, search, and manage all registered participants.') }}
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
            <a href="{{ route('participants.export') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm shadow-indigo-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                {{ __('Export to Excel') }}
            </a>
        </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <form id="searchForm" class="grid grid-cols-1 md:grid-cols-12 gap-4">
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
                        placeholder="{{ __('Search by Token, Name, or Mobile...') }}">
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

    <!-- Participants Table -->
    @include('participants.partials.table')

    <!-- View Details Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"
            onclick="closeViewModal()"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <!-- Modal Header -->
                <!-- Close Button -->
                <div class="absolute top-0 right-0 pt-4 pr-4 z-50">
                    <button type="button"
                        class="rounded-full p-1 text-indigo-200 hover:text-white hover:bg-white/10 focus:outline-none transition-colors"
                        onclick="closeViewModal()">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Header -->
                <div class="bg-indigo-600 px-6 py-8 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-indigo-500 rounded-full opacity-50 blur-2xl">
                    </div>

                    <h3 class="relative text-base font-semibold leading-6 text-indigo-100">{{ __('Participant Details') }}
                    </h3>
                    <div class="relative mt-2 flex items-baseline gap-2">
                        <h2 class="text-3xl font-bold text-white tracking-tight" id="viewToken"></h2>
                        <span class="text-indigo-200">{{ __('Token Number') }}</span>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 font-medium">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Full Name') }}
                            </dt>
                            <dd class="mt-1 text-lg font-bold text-slate-900" id="viewName"></dd>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Age') }}
                                </dt>
                                <dd class="mt-1 text-base text-slate-900" id="viewAge"></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    {{ __('Adhar Card No') }}
                                </dt>
                                <dd class="mt-1 text-base text-slate-900 font-mono" id="viewAdhar"></dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    {{ __('Mobile Number') }}
                                </dt>
                                <dd class="mt-1 text-base text-slate-900 font-mono" id="viewMobile"></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    {{ __('Voter ID Status') }}
                                </dt>
                                <dd class="mt-1" id="viewVoter"></dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4" id="voterDetailsSection">
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    {{ __('Epic Voter ID No') }}
                                </dt>
                                <dd class="mt-1 text-base text-slate-900 font-mono" id="viewEpic"></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    {{ __('Voter Members') }}
                                </dt>
                                <dd class="mt-1 text-base text-slate-900" id="viewMemberCount"></dd>
                            </div>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                {{ __('Permanent Address') }}
                            </dt>
                            <dd class="mt-1 text-sm leading-relaxed text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100"
                                id="viewAddress"></dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                {{ __('Registered On') }}
                            </dt>
                            <dd class="mt-1 text-sm text-slate-600" id="viewRegistered"></dd>
                        </div>
                    </dl>
                </div>

                <!-- Modal Footer -->
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button"
                        class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto"
                        onclick="closeViewModal()">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Participant Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">{{ __('Edit Participant') }}</h3>
                    <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-4">
                    <form id="editForm" class="space-y-4">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">{{ __('Full Name') }}</label>
                                <input type="text" id="edit_full_name" name="full_name"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">{{ __('Age') }}</label>
                                <input type="number" id="edit_age" name="age"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">{{ __('Mobile Number') }}</label>
                                <input type="text" id="edit_mobile_number" name="mobile_number" maxlength="10"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">{{ __('Adhar Card No') }}</label>
                                <input type="text" id="edit_adharcard_no" name="adharcard_no" maxlength="12"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                            <label class="flex items-center gap-2 cursor-pointer mb-3">
                                <input type="checkbox" id="edit_voter_id" name="voter_id" value="1"
                                    class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="text-sm font-medium text-slate-700">{{ __('Participant has Voter ID') }}</span>
                            </label>

                            <div id="edit_voter_details" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700">{{ __('Epic Voter ID No') }}</label>
                                    <input type="text" id="edit_epic_voter_id_no" name="epic_voter_id_no"
                                        class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700">{{ __('Voter Member Count') }}</label>
                                    <input type="number" id="edit_voter_member_count" name="voter_member_count"
                                        class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">{{ __('Permanent Address') }}</label>
                            <textarea id="edit_permanent_address" name="permanent_address" rows="3"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-white border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" id="updateBtn"
                                class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Update Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function closeViewModal() {
            $('#viewModal').addClass('hidden');
        }
        function closeEditModal() {
            $('#editModal').addClass('hidden');
            $('#editForm')[0].reset();
        }

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Toggle Voter Details in Edit Modal
            $('#edit_voter_id').change(function () {
                if ($(this).is(':checked')) {
                    $('#edit_voter_details').removeClass('hidden');
                } else {
                    $('#edit_voter_details').addClass('hidden');
                }
            });

            // Search functionality
            let debounceTimer;
            const handleSearch = () => {
                const search = $('#search').val();
                const voterFilter = $('#voter_filter').val();

                let url = '{{ route("participants.index") }}?';
                if (search) url += `search=${search}&`;
                if (voterFilter) url += `voter_filter=${voterFilter}`;

                // Show loading state if needed

                $.get(url, function (data) {
                    $('#participantsTableContainer').replaceWith(data);
                    // Re-bind delete events since DOM changed
                    bindEvents();
                }).fail(function () {
                    alert('Failed to load data.');
                });

                // Update URL without reloading
                window.history.pushState({ path: url }, '', url);
            };

            // Bind Events Function
            function bindEvents() {
                $('.delete-btn').off('click').on('click', function () {
                    const id = $(this).data('id');
                    if (!id) { alert('Error: Participant ID not found.'); return; }
                    if (confirm('{{ __('Are you sure you want to delete this participant? This will move them to the trash.') }}')) {
                        $.ajax({
                            url: `/participants/${id}`,
                            method: 'DELETE',
                            success: function (response) {
                                if (response.success) {
                                    handleSearch(); // Refresh list via AJAX
                                }
                            },
                            error: function (xhr) {
                                console.error(xhr);
                                alert('An error occurred. Please try again.');
                            }
                        });
                    }
                });

                // Re-bind View Events
                $('.view-btn').off('click').on('click', function () {
                    const participant = $(this).data('participant');
                    $('#viewToken').text(participant.token);
                    $('#viewName').text(participant.full_name);
                    $('#viewAge').text(participant.age || 'N/A');
                    $('#viewAdhar').text(participant.adharcard_no || 'N/A');
                    $('#viewMobile').text(participant.mobile_number);

                    if (participant.voter_id) {
                        $('#viewVoter').html('<span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800">{{ __('Verified ID') }}</span>');
                        $('#voterDetailsSection').removeClass('hidden');
                        $('#viewEpic').text(participant.epic_voter_id_no || 'N/A');
                        $('#viewMemberCount').text(participant.voter_member_count || '0');
                    } else {
                        $('#viewVoter').html('<span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600">{{ __('No Verification') }}</span>');
                        $('#voterDetailsSection').addClass('hidden');
                    }

                    $('#viewAddress').text(participant.permanent_address);
                    $('#viewRegistered').text(new Date(participant.created_at).toLocaleString());
                    $('#viewModal').removeClass('hidden');
                });

                // Bind Edit Events
                $('.edit-btn').off('click').on('click', function () {
                    const participant = $(this).data('participant');
                    $('#edit_id').val(participant.id);
                    $('#edit_full_name').val(participant.full_name);
                    $('#edit_age').val(participant.age);
                    $('#edit_mobile_number').val(participant.mobile_number);
                    $('#edit_adharcard_no').val(participant.adharcard_no);
                    $('#edit_permanent_address').val(participant.permanent_address);

                    if (participant.voter_id) {
                        $('#edit_voter_id').prop('checked', true).trigger('change');
                        $('#edit_epic_voter_id_no').val(participant.epic_voter_id_no);
                        $('#edit_voter_member_count').val(participant.voter_member_count);
                    } else {
                        $('#edit_voter_id').prop('checked', false).trigger('change');
                        $('#edit_epic_voter_id_no').val('');
                        $('#edit_voter_member_count').val('');
                    }

                    $('#editModal').removeClass('hidden');
                });
            }

            // Handle Update Form Submission
            $('#editForm').on('submit', function (e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const formData = $(this).serialize();
                const btn = $('#updateBtn');
                const originalText = btn.text();

                btn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `/participants/${id}`,
                    method: 'PUT',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            closeEditModal();
                            handleSearch(); // Refresh table
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).text(originalText);
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = Object.values(xhr.responseJSON.errors).join('\n');
                            alert(errors);
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Initial binding
            bindEvents();

            // Auto-search on input with debounce
            $('#search').on('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(handleSearch, 500); // 500ms delay
            });

            // Auto-search on dropdown change
            $('#voter_filter').on('change', function () {
                handleSearch();
            });

            $('#searchForm').on('submit', function (e) {
                e.preventDefault();
                handleSearch();
            });

            $('#clearBtn, #clearBtnInBox').on('click', function () {
                window.location.href = '{{ route("participants.index") }}';
            });
        });
    </script>
@endpush