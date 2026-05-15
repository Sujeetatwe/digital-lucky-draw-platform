@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{ 
                                            activeTab: 'master', 
                                            showAddGiftModal: false,
                                            editMode: false,
                                            giftForm: { id: null, gift_name: '', quantity: '' },

                                            // Search & Assign Data
                                            searchQuery: '',
                                            searchResults: [],
                                            selectedParticipant: null,
                                            selectedGiftId: '',
                                            searchLoading: false,

                                            // Winners Table Data
                                            winners: [],
                                            winnersSearch: '',

                                            init() {
                                                this.fetchWinners();

                                                // Watch for tab changes to reload data if needed
                                                this.$watch('activeTab', value => {
                                                    if (value === 'winners') {
                                                        this.fetchWinners();
                                                    }
                                                });
                                            },

                                            openAddModal() {
                                                this.editMode = false;
                                                this.giftForm = { id: null, gift_name: '', quantity: '' };
                                                this.showAddGiftModal = true;
                                            },

                                            openEditModal(gift) {
                                                this.editMode = true;
                                                this.giftForm = { id: gift.id, gift_name: gift.gift_name, quantity: gift.quantity };
                                                this.showAddGiftModal = true;
                                            },

                                            searchParticipants() {
                                                if (this.searchQuery.length < 1) {
                                                    this.searchResults = [];
                                                    return;
                                                }
                                                this.searchLoading = true;
                                                fetch(`{{ route('winners.search') }}?query=${this.searchQuery}`)
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        this.searchResults = data;
                                                        this.searchLoading = false;
                                                    });
                                            },

                                            selectParticipant(participant) {
                                                this.selectedParticipant = participant;
                                                this.searchQuery = '';
                                                this.searchResults = [];
                                            },

                                            assignGift() {
                                                if (!this.selectedParticipant || !this.selectedGiftId) return;

                                                fetch(`{{ route('winners.assign') }}`, {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                    },
                                                    body: JSON.stringify({
                                                        participant_id: this.selectedParticipant.id,
                                                        winning_gift_id: this.selectedGiftId
                                                    })
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    alert(data.message);
                                                    this.selectedParticipant = null;
                                                    this.selectedGiftId = '';
                                                    // Refresh winners list if needed, or redirect
                                                    this.fetchWinners();
                                                })
                                                .catch(err => {
                                                    alert('Error assigning gift possibly duplicate or server error.');
                                                });
                                            },

                                            fetchWinners() {
                                                fetch(`{{ route('winners.index') }}?search=${this.winnersSearch}`)
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        this.winners = data;
                                                    });
                                            }
                                        }">

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('Winning Gifts Module') }}</h1>
                <p class="text-slate-500 text-sm mt-1">{{ __('Manage gifts, assign winners, and view reports.') }}</p>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar w-full md:w-auto"
                style="padding-bottom: 15px;">
                <!-- Tabs Navigation -->
                <button @click="activeTab = 'master'"
                    :class="activeTab === 'master' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
                    class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-semibold shadow-sm border border-slate-200 transition-all">
                    {{ __('Winning Gifts') }}
                </button>
                <button @click="activeTab = 'assignment'"
                    :class="activeTab === 'assignment' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
                    class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-semibold shadow-sm border border-slate-200 transition-all">
                    {{ __('Winner Assignment') }}
                </button>
                <button @click="activeTab = 'winners'"
                    :class="activeTab === 'winners' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
                    class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-semibold shadow-sm border border-slate-200 transition-all">
                    {{ __('Winners List') }}
                </button>
            </div>
        </div>

        <!-- TAB 1: GIFT MASTER -->
        <div x-show="activeTab === 'master'" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4">
                <h2 class="text-lg font-bold text-slate-800">{{ __('Winning Gifts') }}</h2>
                <button @click="openAddModal()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Add Gift') }}
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                        <tr>
                            <th class="px-4 sm:px-6 py-4">{{ __('ID') }}</th>
                            <th class="px-4 sm:px-6 py-4">{{ __('Gift Name') }}</th>
                            <th class="px-4 sm:px-6 py-4 text-center">{{ __('Quantity') }}</th>
                            <th class="px-4 sm:px-6 py-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($gifts as $gift)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 font-mono font-medium">#{{ $gift->id }}</td>
                                <td class="px-4 sm:px-6 py-4 font-bold text-slate-800">{{ $gift->gift_name }}</td>
                                <td class="px-4 sm:px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $gift->quantity }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right flex justify-end gap-2">
                                    <button @click='openEditModal(@json($gift))'
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-xs border border-indigo-200 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-all">{{ __('Edit') }}</button>
                                    <form action="{{ route('winning-gifts.destroy', $gift->id) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-rose-600 hover:text-rose-900 font-medium text-xs border border-rose-200 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition-all">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 sm:px-6 py-12 text-center text-slate-400">
                                    {{ __('No gifts found. Add some gifts to get started.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: WINNER ASSIGNMENT -->
        <div x-show="activeTab === 'assignment'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Search Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:col-span-1 h-fit">
                <h2 class="text-lg font-bold text-slate-800 mb-4">{{ __('Search Candidate') }}</h2>
                <div class="relative">
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchParticipants()"
                        placeholder="{{ __('Search by Token, Name, Mobile...') }}"
                        class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow">

                    <!-- Suggestions Dropdown -->
                    <div x-show="searchResults.length > 0"
                        class="absolute z-10 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 max-h-60 overflow-y-auto">
                        <template x-for="participant in searchResults" :key="participant.id">
                            <div @click="selectParticipant(participant)"
                                class="p-3 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 last:border-0 transition-colors">
                                <p class="font-bold text-sm text-slate-800" x-text="participant.full_name"></p>
                                <div class="flex gap-2 text-xs text-slate-500 mt-1">
                                    <span class="bg-slate-100 px-1.5 rounded"
                                        x-text="'T: ' + (participant.token || 'N/A')"></span>
                                    <span x-text="participant.mobile_number"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div x-show="searchLoading" class="absolute right-3 top-3">
                        <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Selected Candidate Preview -->
                <template x-if="selectedParticipant">
                    <div class="mt-6 bg-indigo-50 rounded-xl p-4 border border-indigo-100 relative">
                        <button @click="selectedParticipant = null"
                            class="absolute top-2 right-2 text-indigo-400 hover:text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="text-center mb-3">
                            <div
                                class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-2 text-lg font-bold">
                                <span x-text="selectedParticipant.full_name.charAt(0)"></span>
                            </div>
                            <h3 class="font-bold text-slate-900" x-text="selectedParticipant.full_name"></h3>
                            <p class="text-sm text-slate-500" x-text="selectedParticipant.token"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-white p-2 rounded border border-indigo-100">
                                <span class="block text-slate-400">Mobile</span>
                                <span class="font-semibold text-slate-700"
                                    x-text="selectedParticipant.mobile_number"></span>
                            </div>
                            <div class="bg-white p-2 rounded border border-indigo-100">
                                <span class="block text-slate-400">Age</span>
                                <span class="font-semibold text-slate-700" x-text="selectedParticipant.age"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Assignment Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:col-span-2">
                <h2 class="text-lg font-bold text-slate-800 mb-6">{{ __('Assign Gift') }}</h2>

                <div x-show="!selectedParticipant"
                    class="text-center py-12 text-slate-400 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <p>{{ __('Please select a candidate first') }}</p>
                </div>

                <template x-if="selectedParticipant">
                    <div class="overflow-x-auto rounded-lg border border-slate-200 mb-6">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Candidate Name</th>
                                    <th class="px-4 py-3">Token</th>
                                    <th class="px-4 py-3">Mobile</th>
                                    <th class="px-4 py-3">Select Gift</th>
                                    <th class="px-4 py-3 w-20">Qty</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-800"
                                        x-text="selectedParticipant.full_name"></td>
                                    <td class="px-4 py-3 font-mono text-slate-600" x-text="selectedParticipant.token"></td>
                                    <td class="px-4 py-3 text-slate-600" x-text="selectedParticipant.mobile_number"></td>
                                    <td class="px-4 py-3">
                                        <select x-model="selectedGiftId"
                                            class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Select Gift...</option>
                                            @foreach($gifts as $g)
                                                <option value="{{ $g->id }}">{{ $g->gift_name }} ({{ $g->quantity }} left)
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" value="1" disabled
                                            class="w-full rounded-lg border-slate-200 bg-slate-50 text-center text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button @click="assignGift()" :disabled="!selectedGiftId"
                                            class="bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            {{ __('Assign') }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>

        <!-- TAB 3: WINNERS LIST -->
        <div x-show="activeTab === 'winners'"
            class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-lg font-bold text-slate-800">{{ __('Winners List') }}</h2>
                <div class="flex gap-3 w-full md:w-auto">
                    <input type="text" x-model="winnersSearch" @input.debounce.500ms="fetchWinners()"
                        placeholder="Search winners..."
                        class="w-full md:w-64 rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <a href="{{ route('winners.export') }}"
                        class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        {{ __('Export') }}
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                        <tr>
                            <th class="px-4 sm:px-6 py-4">{{ __('Candidate Name') }}</th>
                            <th class="px-4 sm:px-6 py-4">{{ __('Token') }}</th>
                            <th class="px-4 sm:px-6 py-4">{{ __('Mobile') }}</th>
                            <th class="px-4 sm:px-6 py-4">{{ __('Gift Won') }}</th>
                            <th class="px-4 sm:px-6 py-4 text-center">{{ __('Quantity') }}</th>
                            <th class="px-4 sm:px-6 py-4">{{ __('Assigned Date') }}</th>
                            <th class="px-4 sm:px-6 py-4">{{ __('Assigned By') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="winner in winners" :key="winner.id">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 font-bold text-slate-800"
                                    x-text="winner.participant?.full_name || 'N/A'"></td>
                                <td class="px-4 sm:px-6 py-4 font-mono font-medium text-indigo-600"
                                    x-text="winner.participant?.token || '-'"></td>
                                <td class="px-4 sm:px-6 py-4" x-text="winner.participant?.mobile_number"></td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                            </path>
                                        </svg>
                                        <span x-text="winner.gift?.gift_name"></span>
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-center" x-text="winner.quantity"></td>
                                <td class="px-4 sm:px-6 py-4" x-text="new Date(winner.created_at).toLocaleString()"></td>
                                <td class="px-4 sm:px-6 py-4 text-xs italic text-slate-400"
                                    x-text="winner.assigner?.name || 'Unknown'"></td>
                            </tr>
                        </template>
                        <tr x-show="winners.length === 0">
                            <td colspan="7" class="px-4 sm:px-6 py-12 text-center text-slate-400">
                                {{ __('No winners found yet.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL: ADD/EDIT GIFT -->
        <div x-show="showAddGiftModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-xl w-full overflow-hidden" style="max-width: 400px;"
                @click.away="showAddGiftModal = false">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-800" x-text="editMode ? 'Edit Gift' : 'Add New Gift'"></h3>
                    <button @click="showAddGiftModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form
                    :action="editMode ? `{{ url('winning-gifts') }}/${giftForm.id}` : `{{ route('winning-gifts.store') }}`"
                    method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Gift Name') }}</label>
                        <input type="text" name="gift_name" x-model="giftForm.gift_name" required
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Quantity') }}</label>
                        <input type="number" name="quantity" x-model="giftForm.quantity" required min="1"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showAddGiftModal = false"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-lg">{{ __('Cancel') }}</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">{{ __('Save Gift') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection