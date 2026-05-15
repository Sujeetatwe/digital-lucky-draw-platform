<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('', 'होमिनिस्टर रजिस्ट्रेशन | सौ. मालिका ताई निखिल साकळे') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .translate-btn {
            margin-top: -72px;
            margin-left: -26px;
        }
    </style>
</head>

<body class="text-slate-800 antialiased min-h-screen flex flex-col justify-center py-6 sm:px-6 lg:px-8 relative"
    style="background-image: url('{{ asset('images/background_register.png') }}'); background-size: cover; background-position: center; background-attachment: fixed;"
    x-data="{ showPopup: true }" x-init="setTimeout(() => showPopup = false, 10000)">

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-white/80 backdrop-blur-[2px] z-0"></div>

    <div class="relative z-10 w-full">

        <!-- Popup Modal -->
        <!-- <div x-show="showPopup"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        style="display: none;">

        <div class="relative w-[650px] h-[650px] bg-white rounded-xl shadow-2xl overflow-hidden"
            @click.away="showPopup = false">

            <button @click="showPopup = false"
                class="absolute top-2 right-2 z-50 p-2 bg-black/20 hover:bg-black/50 text-white rounded-full transition-colors backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <img src="{{ asset('images/lucky_draw_popup.png') }}" alt="Lucky Draw Event"
                class="w-[650px] h-[650px] object-cover">

        </div>
    </div> -->

        <div class="mx-auto w-[650px]">
            <h2 class="mt-2 text-center text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ __('होमिनिस्टर रजिस्ट्रेशन') }}
            </h2>
            <h3 class="mt-2 text-center text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ __('सौ. मालिका ताई निखिल साकळे') }}
            </h3>
            <p class="mt-2 text-center text-sm text-slate-800">
                {{ __('पक्ष : राष्ट्रवादी काँग्रेस पक्ष | वॉर्ड क्रमांक : २३') }}
            </p>


        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl relative">
            <div
                class="bg-white py-10 px-6 shadow-xl shadow-slate-200/50 sm:rounded-2xl sm:px-10 border border-slate-100 relative">

                <!-- Language Switcher -->
                <div class="absolute top-2 left-6 z-10 translate-btn">
                    <div class="flex items-center bg-slate-50 rounded-lg p-1 shadow-sm border border-slate-200">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="px-2 py-1 text-[10px] font-bold rounded-md transition-all {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">EN</a>
                        <a href="{{ route('lang.switch', 'mr') }}"
                            class="px-2 py-1 text-[10px] font-bold rounded-md transition-all {{ app()->getLocale() == 'mr' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">MR</a>
                    </div>
                </div>

                <form id="publicRegistrationForm" class="space-y-6 mt-12" x-data="{ hasVoterId: false }">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label for="full_name"
                                class="block text-sm font-semibold text-slate-700">{{ __('Full Name') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="text" id="full_name" name="full_name" required
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                placeholder="{{ __('Enter Your Name') }}">
                        </div>

                        <!-- Age -->
                        <div class="space-y-2">
                            <label for="age" class="block text-sm font-semibold text-slate-700">{{ __('Age') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="number" id="age" name="age" required min="18" max="75"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                placeholder="{{ __('e.g. 25') }}" oninput="this.value = this.value.slice(0, 2)">
                            <div id="ageError" class="text-xs text-rose-500 mt-1 hidden font-bold"></div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="space-y-2">
                            <label for="mobile_number"
                                class="block text-sm font-semibold text-slate-700">{{ __('Mobile Number') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="tel" id="mobile_number" name="mobile_number" required maxlength="10"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                placeholder="9876543210">
                            <div id="mobileError" class="text-xs text-rose-500 mt-1 hidden font-bold"></div>
                        </div>

                        <!-- Adhar Card (Optional) -->
                        <div class="space-y-2">
                            <label for="adharcard_no"
                                class="block text-sm font-semibold text-slate-700">{{ __('Adhar Card No') }} <span
                                    class="text-xs text-slate-400 font-normal">({{ __('Optional') }})</span></label>
                            <input type="text" id="adharcard_no" name="adharcard_no" maxlength="12" minlength="12"
                                pattern="\d{12}"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                placeholder="{{ __('12-digit Adhar No') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2">
                        <label for="permanent_address"
                            class="block text-sm font-semibold text-slate-700">{{ __('Permanent Address') }} <span
                                class="text-rose-500">*</span></label>
                        <textarea id="permanent_address" name="permanent_address" rows="3" required
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                            placeholder="{{ __('Enter full address...') }}"></textarea>
                    </div>

                    <!-- Voter ID Section (Admin Style) -->
                    <div class="bg-indigo-50/50 rounded-xl p-5 border border-indigo-100">
                        <label class="flex items-center gap-3 cursor-pointer group mb-4">
                            <div class="relative flex items-center">
                                <input type="checkbox" id="voter_id" name="voter_id" value="1" x-model="hasVoterId"
                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-indigo-600 checked:bg-indigo-600 hover:border-indigo-400">
                                <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 transition-opacity peer-checked:opacity-100"
                                    width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M3 6L5 8L9 4" stroke="currentColor" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ __('Participant has Voter ID') }}
                                <span class="text-xs text-slate-400 font-normal">({{ __('Optional') }})</span></span>
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="hasVoterId" x-transition>
                            <div class="space-y-2">
                                <label for="epic_voter_id_no"
                                    class="block text-sm font-semibold text-slate-700">{{ __('Epic Voter ID No') }}</label>
                                <input type="text" id="epic_voter_id_no" name="epic_voter_id_no" maxlength="10"
                                    minlength="10"
                                    class="block w-full rounded-xl border-slate-200 bg-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                    placeholder="{{ __('ABC1234567') }}"
                                    onblur="if(this.value.length > 0 && this.value.length !== 10) { alert('Voter ID must be exactly 10 characters.'); }">
                            </div>
                            <div class="space-y-2">
                                <label for="voter_member_count"
                                    class="block text-sm font-semibold text-slate-700">{{ __('Voter Member Count') }}</label>
                                <input type="number" id="voter_member_count" name="voter_member_count" min="0"
                                    class="block w-full rounded-xl border-slate-200 bg-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 py-2.5"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="submitBtn"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.01] hover:shadow-lg">
                            {{ __('Submit Registration') }}
                        </button>
                    </div>
                </form>
            </div>

            <div id="successMessage"
                class="hidden mt-6 bg-white border border-emerald-100 rounded-2xl p-8 text-center shadow-xl shadow-emerald-50 relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-emerald-50 rounded-full opacity-50 blur-2xl">
                </div>
                <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-teal-50 rounded-full opacity-50 blur-2xl">
                </div>

                <div class="relative">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 mb-6">
                        <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Form submitted for approval') }}</h3>
                    <p class="text-slate-600 text-sm mb-6">
                        {{ __('Your registration is pending approval by the administrator.') }}
                    </p>
                    <button onclick="window.location.reload()"
                        class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        {{ __('Submit Another') }}
                    </button>
                </div>
            </div>

        </div>


        <!-- Footer -->
        <footer class="relative z-10 mt-10 text-center text-xs text-slate-600 py-4">
            © 2025 | Developed by
            <a href="https://www.raydito.com/" target="_blank" class="font-semibold text-indigo-600 hover:underline">
                Raydito Services
            </a>
            |
            <a href="tel:8956684834" class="font-semibold text-indigo-600 hover:underline">
                Sujeet Atwe
            </a>
        </footer>


        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                // Age Validation
                $('#age').on('input', function () {
                    const val = $(this).val();
                    const $error = $('#ageError');
                    const $submitBtn = $('#submitBtn');

                    $error.addClass('hidden').text('');
                    $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

                    if (val && (val < 18 || val > 75)) {
                        $error.removeClass('hidden').text('Age must be between 18 and 75.');
                        $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                    }
                });

                // Real-time Mobile Validation
                let mobileDebounce;
                $('#mobile_number').on('input', function () {
                    const val = $(this).val();
                    const $error = $('#mobileError');
                    const $submitBtn = $('#submitBtn');

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

                $('#publicRegistrationForm').on('submit', function (e) {
                    e.preventDefault();

                    // Reset errors
                    $('.error-text').remove();
                    $('input, textarea').removeClass('border-rose-500');

                    const btn = $('#submitBtn');
                    const originalText = btn.text();
                    btn.prop('disabled', true).html('<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...');

                    const formData = {
                        full_name: $('#full_name').val(),
                        age: $('#age').val(),
                        mobile_number: $('#mobile_number').val(),
                        adharcard_no: $('#adharcard_no').val(),
                        permanent_address: $('#permanent_address').val(),
                        voter_id: $('#voter_id').is(':checked') ? 1 : 0,
                        epic_voter_id_no: $('#epic_voter_id_no').val(),
                        voter_member_count: $('#voter_member_count').val()
                    };

                    $.ajax({
                        url: "{{ route('public.store') }}",
                        method: 'POST',
                        data: formData,
                        success: function (response) {
                            if (response.success) {
                                $('#publicRegistrationForm').slideUp();
                                $('#successMessage').removeClass('hidden').hide().fadeIn();
                            }
                        },
                        error: function (xhr) {
                            btn.prop('disabled', false).text(originalText);

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                alert(xhr.responseJSON.message);
                            }

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;

                                // Highlight fields
                                if (errors) {
                                    Object.keys(errors).forEach(key => {
                                        $(`#${key}`).addClass('border-rose-500');
                                    });
                                }
                            }
                        }
                    });
                });
            });
        </script>


</body>

</html>