@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Dashboard Overview') }}</h1>
            <p class="mt-1 text-sm text-slate-500 font-medium">
                {{ __('Manage lucky draw registrations and monitor real-time statistics.') }}
            </p>
        </div>
        <div class="hidden sm:block">
            <span
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-xs font-semibold text-slate-600 border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                {{ __('System Active') }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div
            class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-indigo-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <span class="flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Active
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1 tracking-tight" id="totalRegistered">
                {{ $totalRegistered }}
            </div>
            <p class="text-sm font-medium text-slate-500">{{ __('Total Registered') }}</p>
        </div>

        <div
            class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-rose-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-full">/ 5000</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1 tracking-tight" id="remainingSlots">
                {{ $remainingSlots }}
            </div>
            <p class="text-sm font-medium text-slate-500">{{ __('Remaining Slots') }}</p>
        </div>

        <div
            class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-sky-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1 tracking-tight" id="withVoterId">{{ $withVoterId }}
            </div>
            <p class="text-sm font-medium text-slate-500">{{ __('With Voter ID') }}</p>
        </div>

        <div
            class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-amber-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1 tracking-tight" id="withoutVoterId">
                {{ $withoutVoterId }}
            </div>
            <p class="text-sm font-medium text-slate-500">{{ __('Without Voter ID') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Registration Form (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ __('New Registration') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Enter participant details below') }}</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="p-8">
                    @if($remainingSlots <= 0)
                        <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                            <div
                                class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-red-900 mb-2">{{ __('Registration Closed') }}</h3>
                            <p class="text-red-700">{{ __('The maximum limit of 5000 participants has been reached.') }}</p>
                        </div>
                    @else
                        <!-- Messages -->
                        <div id="successMessage"
                            class="hidden mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-6 relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-100 rounded-full opacity-50 blur-xl group-hover:scale-150 transition-transform duration-700">
                            </div>
                            <div class="relative flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-emerald-800 uppercase tracking-wide mb-1">
                                        {{ __('Registration Successful') }}
                                    </p>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-slate-600">{{ __('Assigned Token') }}:</span>
                                        <span id="successToken"
                                            class="text-3xl font-black text-emerald-600 tracking-tight font-mono"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="errorMessage"
                            class="hidden mb-6 bg-amber-50 border border-amber-200 rounded-xl p-6 relative overflow-hidden">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-amber-800 uppercase tracking-wide mb-1">
                                        {{ __('Already Registered') }}
                                    </p>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-slate-600">{{ __('Existing Token') }}:</span>
                                        <span id="errorToken"
                                            class="text-3xl font-black text-amber-600 tracking-tight font-mono"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="registrationForm" class="space-y-6" x-data="{ hasVoterId: false }">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="token" class="block text-sm font-semibold text-slate-700">{{ __('Token') }}
                                        <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" id="token" name="token" required maxlength="4" pattern="\d{1,4}"
                                            class="pl-10 block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5 font-mono tracking-wider"
                                            placeholder="{{ __('1') }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)">
                                        <div id="tokenError" class="text-xs text-rose-500 mt-1 hidden font-bold"></div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="full_name"
                                        class="block text-sm font-semibold text-slate-700">{{ __('Full Name') }}
                                        <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" id="full_name" name="full_name" required
                                            class="pl-10 block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                            placeholder="{{ __('Enter Full Name') }}">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="age" class="block text-sm font-semibold text-slate-700">{{ __('Age') }} <span
                                            class="text-rose-500">*</span></label>
                                    <input type="number" id="age" name="age" required min="18" max="75"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                        placeholder="{{ __('e.g. 25') }}" oninput="this.value = this.value.slice(0, 2)">
                                    <div id="ageError" class="text-xs text-rose-500 mt-1 hidden font-bold"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="mobile_number"
                                        class="block text-sm font-semibold text-slate-700">{{ __('Mobile Number') }}
                                        <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" id="mobile_number" name="mobile_number" required maxlength="10"
                                            pattern="[0-9]{10}" title="Must be exactly 10 digits"
                                            class="pl-10 block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                            placeholder="9876543210">
                                        <div id="mobileError" class="text-xs text-rose-500 mt-1 hidden font-bold"></div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="adharcard_no"
                                        class="block text-sm font-semibold text-slate-700">{{ __('Adhar Card No') }}</label>
                                    <input type="text" id="adharcard_no" name="adharcard_no" maxlength="12" minlength="12"
                                        pattern="\d{12}"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                        placeholder="{{ __('12-digit Adhar No (Optional)') }}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="permanent_address"
                                    class="block text-sm font-semibold text-slate-700">{{ __('Permanent Address') }} <span
                                        class="text-rose-500">*</span></label>
                                <textarea id="permanent_address" name="permanent_address" rows="3" required
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200"
                                    placeholder="{{ __('Enter full address...') }}"></textarea>
                            </div>

                            <div class="bg-indigo-50/50 rounded-xl p-5 border border-indigo-100">
                                <label class="flex items-center gap-3 cursor-pointer group mb-4">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="voter_id" name="voter_id" value="1" x-model="hasVoterId"
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-indigo-600 checked:bg-indigo-600 hover:border-indigo-400">
                                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 transition-opacity peer-checked:opacity-100"
                                            width="12" height="12" viewBox="0 0 12 12" fill="none">
                                            <path d="M3 6L5 8L9 4" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ __('Participant has Voter ID') }}</span>
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="hasVoterId" x-transition>
                                    <div class="space-y-2">
                                        <label for="epic_voter_id_no"
                                            class="block text-sm font-semibold text-slate-700">{{ __('Epic Voter ID No') }}</label>
                                        <input type="text" id="epic_voter_id_no" name="epic_voter_id_no" maxlength="10"
                                            minlength="10"
                                            class="block w-full rounded-xl border-slate-200 bg-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                            placeholder="{{ __('Enter VoterID No (e.g. ABC1234567)') }}">
                                    </div>
                                    <div class="space-y-2">
                                        <label for="voter_member_count"
                                            class="block text-sm font-semibold text-slate-700">{{ __('Voter Member Count') }}</label>
                                        <input type="number" id="voter_member_count" name="voter_member_count" min="0" value="0"
                                            class="block w-full rounded-xl border-slate-200 bg-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transform active:scale-95 transition-all duration-200 shadow-lg shadow-indigo-200 w-full sm:w-auto">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    {{ __('Register Now') }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Registrations (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-lg font-bold text-slate-900">{{ __('Recent Activity') }}</h2>
                    <p class="text-xs text-slate-500">{{ __('Latest registrations') }}</p>
                </div>
                <div class="p-0">
                    <div id="recentList" class="divide-y divide-slate-50 max-h-[500px] overflow-y-auto">
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-500">{{ __('No recent registrations') }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ __('New participants will appear here') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-indigo-900 rounded-2xl p-8 relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-xl font-bold text-white mb-2">{{ __('Need to manage participants?') }}</h2>
                <p class="text-indigo-200 max-w-lg">
                    {{ __('View complete list, search, filter, export data or manage deleted records from here.') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('participants.index') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-white text-indigo-900 font-bold rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-900 focus:ring-white transition-all shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    {{ __('View Participants') }}
                </a>
                <a href="{{ route('participants.export') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-indigo-800 text-white font-bold rounded-lg hover:bg-indigo-700/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-900 focus:ring-indigo-400 transition-all border border-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    {{ __('Export Data') }}
                </a>
                <a href="{{ route('participants.trashed') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-transparent border border-indigo-500 text-indigo-200 font-bold rounded-lg hover:bg-indigo-800 hover:text-white focus:outline-none transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    {{ __('Trash') }}
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-500 text-white font-bold rounded-lg hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-900 focus:ring-indigo-400 transition-all border border-indigo-500/50 shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        {{ __('Users') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let recentRegistrations = [];

        $(document).ready(function () {
            // Real-time Token Validation
            let tokenDebounce;
            $('#token').on('input', function () {
                const val = $(this).val();
                const $error = $('#tokenError');
                const $submitBtn = $('#registrationForm button[type="submit"]');

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
                                    $error.removeClass('hidden').text('This token already exists.');
                                    $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                                }
                            });
                        }, 300);
                    }
                }
            });

            // Real-time Mobile Validation
            let mobileDebounce;
            $('#mobile_number').on('input', function () {
                const val = $(this).val();
                const $error = $('#mobileError');
                const $submitBtn = $('#registrationForm button[type="submit"]');

                clearTimeout(mobileDebounce);
                $error.addClass('hidden').text('');
                $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

                if (val.length === 10) {
                    mobileDebounce = setTimeout(function () {
                        $.get('{{ route("api.check-mobile") }}', { mobile: val }, function (res) {
                            if (res.exists) {
                                $error.removeClass('hidden').text('Mobile number is already registered.');
                                $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                            }
                        });
                    }, 300);
                }
            });

            // Age Validation
            $('#age').on('input', function () {
                const val = $(this).val();
                const $error = $('#ageError');
                const $submitBtn = $('#registrationForm button[type="submit"]');

                $error.addClass('hidden').text('');
                $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

                if (val && (val < 18 || val > 75)) {
                    $error.removeClass('hidden').text('Age must be between 18 and 75.');
                    $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                }
            });

            $('#registrationForm').on('submit', function (e) {
                e.preventDefault();

                const $btn = $(this).find('button[type="submit"]');
                const originalText = $btn.html();
                $btn.html('<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...');
                $btn.prop('disabled', true);

                const formData = {
                    full_name: $('#full_name').val(),
                    age: $('#age').val(),
                    mobile_number: $('#mobile_number').val(),
                    token: $('#token').val(),
                    adharcard_no: $('#adharcard_no').val(),
                    permanent_address: $('#permanent_address').val(),
                    voter_id: $('#voter_id').is(':checked') ? 1 : 0,
                    epic_voter_id_no: $('#epic_voter_id_no').val(),
                    voter_member_count: $('#voter_member_count').val()
                };

                $.ajax({
                    url: '{{ route("participants.store") }}',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            $('#successToken').text(response.participant.token);
                            $('#successMessage').removeClass('hidden').hide().slideDown();
                            $('#errorMessage').addClass('hidden');

                            addToRecentList(response.participant);
                            updateStats();
                            $('#registrationForm')[0].reset();

                            setTimeout(function () {
                                $('#successMessage').slideUp();
                            }, 5000);
                        } else if (response.already_registered) {
                            $('#errorToken').text(response.participant.token);
                            $('#errorMessage').removeClass('hidden').hide().slideDown();
                            $('#successMessage').addClass('hidden');

                            setTimeout(function () {
                                $('#errorMessage').slideUp();
                            }, 5000);
                        }
                    },
                    error: function (xhr) {
                        // Clear previous errors
                        $('.error-text').remove();
                        $('input').removeClass('border-rose-500');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMsgShown = false;

                            // Handle specific Token error
                            if (errors.token) {
                                alert(errors.token[0]); // "Token is already registered"
                                $('#token').addClass('border-rose-500');
                                errorMsgShown = true;
                            }

                            // Handle specific Mobile error
                            if (errors.mobile_number) {
                                alert(errors.mobile_number[0]); // "already user registered"
                                $('#mobile_number').addClass('border-rose-500');
                                errorMsgShown = true;
                            }

                            // Show other errors if any, but only if we haven't shown alert yet to avoid span
                            if (!errorMsgShown) {
                                Object.keys(errors).forEach(function (key) {
                                    alert(errors[key][0]);
                                    return false; // Break loop
                                });
                            }

                        } else if (xhr.responseJSON && xhr.responseJSON.already_registered) {
                            // This block might be redundant now as we handle it via validation errors but sticking to legacy just in case
                            const participant = xhr.responseJSON.participant;
                            $('#errorToken').text(participant.token);
                            $('#errorMessage').removeClass('hidden').hide().slideDown();
                            $('#successMessage').addClass('hidden');

                            setTimeout(function () {
                                $('#errorMessage').slideUp();
                            }, 5000);
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            alert(xhr.responseJSON.message);
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        $btn.html(originalText);
                        $btn.prop('disabled', false);
                    }
                });
            });
        });

        function addToRecentList(participant) {
            // Remove empty state if present
            if (recentRegistrations.length === 0) {
                $('#recentList').empty();
            }

            recentRegistrations.unshift(participant);
            if (recentRegistrations.length > 10) {
                recentRegistrations.pop();
            }

            // Create new element (hidden initially for animation)
            const newRow = $(`
                                                                                                                                <div class="p-4 hover:bg-slate-50 transition-colors animate-fade-in-down border-l-4 border-indigo-500 bg-indigo-50/30">
                                                                                                                                    <div class="flex items-start gap-4">
                                                                                                                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                                                                                                                            ${participant.token}
                                                                                                                                        </div>
                                                                                                                                        <div class="flex-1 min-w-0">
                                                                                                                                            <p class="text-sm font-bold text-slate-900 truncate">${participant.full_name}</p>
                                                                                                                                            <div class="flex items-center gap-2 mt-0.5">
                                                                                                                                                <span class="text-xs font-mono text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">${participant.mobile_number}</span>
                                                                                                                                                ${participant.voter_id ?
                    '<span class="text-[10px] uppercase font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Voter ID</span>' :
                    '<span class="text-[10px] uppercase font-bold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded">No ID</span>'}
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="text-[10px] font-semibold text-slate-400 whitespace-nowrap">Now</div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            `).hide();

            $('#recentList').prepend(newRow);
            newRow.slideDown();
        }

        function updateStats() {
            $.ajax({
                url: '{{ route("dashboard") }}',
                method: 'GET',
                success: function (response) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(response, 'text/html');

                    const newTotal = $(doc).find('#totalRegistered').text();
                    const newRemaining = $(doc).find('#remainingSlots').text();
                    const newWithVoter = $(doc).find('#withVoterId').text();
                    const newWithoutVoter = $(doc).find('#withoutVoterId').text();

                    animateValue('totalRegistered', parseInt($('#totalRegistered').text()), parseInt(newTotal), 500);
                    animateValue('remainingSlots', parseInt($('#remainingSlots').text()), parseInt(newRemaining), 500);
                    animateValue('withVoterId', parseInt($('#withVoterId').text()), parseInt(newWithVoter), 500);
                    animateValue('withoutVoterId', parseInt($('#withoutVoterId').text()), parseInt(newWithoutVoter), 500);
                }
            });
        }

        function animateValue(id, start, end, duration) {
            const range = end - start;
            const increment = range / (duration / 16);
            let current = start;

            const timer = setInterval(function () {
                current += increment;
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                    current = end;
                    clearInterval(timer);
                }
                document.getElementById(id).textContent = Math.round(current);
            }, 16);
        }
    </script>

    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -20px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out forwards;
        }
    </style>
@endpush