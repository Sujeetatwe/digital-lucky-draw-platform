@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('participants.index') }}"
                class="group inline-flex items-center justify-center w-10 h-10 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-indigo-600 hover:border-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    {{ __('Trash Can') }}
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 border border-rose-200">
                        {{ $trashedParticipants->total() }} {{ __('deleted') }}
                    </span>
                </h1>
                <p class="mt-1 text-sm text-slate-500 font-medium">
                    {{ __('Deleted participants can be restored from here.') }}</p>
            </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">{{ __('Deleted Records') }}</h3>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                {{ __('Token') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                {{ __('Full Name') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                {{ __('Mobile') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                {{ __('Address') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                {{ __('Deleted On') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                {{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($trashedParticipants as $participant)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-sm font-bold bg-slate-100 text-slate-500 border border-slate-200 font-mono line-through opacity-70">
                                        {{ $participant->token }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center opacity-70">
                                        <div
                                            class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs mr-3">
                                            {{ substr($participant->full_name, 0, 1) }}
                                        </div>
                                        <div class="text-sm font-semibold text-slate-900">{{ $participant->full_name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap opacity-70">
                                    <div class="text-sm text-slate-600 font-mono">{{ $participant->mobile_number }}</div>
                                </td>
                                <td class="px-6 py-4 opacity-70">
                                    <div class="text-sm text-slate-500 max-w-xs truncate"
                                        title="{{ $participant->permanent_address }}">
                                        {{ Str::limit($participant->permanent_address, 30) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 opacity-70">
                                    {{ $participant->deleted_at->format('M d, Y') }}
                                    <div class="text-xs text-slate-400">{{ $participant->deleted_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end">
                                        @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('participants.restore', $participant->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:text-emerald-800 rounded-lg text-xs font-bold uppercase tracking-wide transition-all border border-emerald-200"
                                                title="Restore Participant">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                {{ __('Restore') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('participants.forceDelete', $participant->id) }}" method="POST"
                                            class="inline-block ml-2" onsubmit="return confirm('{{ __('Are you sure you want to permanently delete this participant? This action cannot be undone.') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:text-rose-800 rounded-lg text-xs font-bold uppercase tracking-wide transition-all border border-rose-200"
                                                title="Delete Permanently">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                {{ __('Delete Permanently') }}
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-xs text-slate-400">{{ __('Admin Only') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900">{{ __('Trash is Empty') }}</h3>
                                        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                                            {{ __('There are no deleted participants. Records deleted from the main list will appear here.') }}
                                        </p>
                                        <a href="{{ route('participants.index') }}"
                                            class="mt-6 inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Go Back to List') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection