<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" id="participantsTableContainer">
    <!-- Header with count -->
    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">{{ __('Registered Users') }}</h3>
        <span
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
            {{ $participants->total() }} {{ __('total records') }}
        </span>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                        {{ __('Token') }}
                    </th>
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
                        {{ __('ID Status') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                        {{ __('Address') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                        {{ __('Registered') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($participants as $participant)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 font-mono">
                                {{ $participant->token }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs mr-3">
                                    {{ substr($participant->full_name, 0, 1) }}
                                </div>
                                <div class="text-sm font-semibold text-slate-900">{{ $participant->full_name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-600 font-mono">{{ $participant->mobile_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($participant->voter_id)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('With ID') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ __('No ID') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-500 max-w-xs truncate"
                                title="{{ $participant->permanent_address }}">
                                {{ Str::limit($participant->permanent_address, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $participant->created_at->format('M d, Y') }}
                            <div class="text-xs text-slate-400">{{ $participant->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    class="view-btn p-1.5 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded transition-colors"
                                    title="View Details" data-id="{{ $participant->id }}"
                                    data-participant='@json($participant)'>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="edit-btn p-1.5 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors"
                                    title="Edit Participant" data-id="{{ $participant->id }}"
                                    data-participant='@json($participant)'>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                @if(auth()->user()->role === 'admin')
                                    <button
                                        class="delete-btn p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded transition-colors"
                                        title="Delete Participant" data-id="{{ $participant->id }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-slate-900">{{ __('No participants found') }}</h3>
                                <p class="text-sm text-slate-500 mt-1">{{ __('Try adjusting your search or filters.') }}</p>
                                <button type="button" id="clearBtnInBox"
                                    class="mt-4 px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                    {{ __('Clear all filters') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($participants->hasPages())
        <div class="bg-white px-6 py-4 border-t border-slate-200">
            {{ $participants->links() }}
        </div>
    @endif
</div>