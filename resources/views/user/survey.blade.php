<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How Did You Hear About Us? - ClaimPilot+</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: '#4F46E5',
                        'accent-light': '#6366F1',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    },
                    boxShadow: {
                        'accent': '0 4px 20px -2px rgba(79, 70, 229, 0.3)',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .decorative-blob {
            animation: float 6s ease-in-out infinite;
            opacity: 0.15;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up {
            animation: slideUp 0.6s ease-out;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <!-- Decorative background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-accent rounded-full blur-3xl decorative-blob"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-light rounded-full blur-3xl decorative-blob" style="animation-delay: -3s;"></div>
    </div>

    <div class="relative z-10 min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="max-width w-full max-w-md">
            <!-- Header Section -->
            <div class="text-center mb-2">
                <h1 class="text-4xl font-bold text-gray-800 mb-3">
                    How did you hear about us?
                </h1>
                <p class="text-xl text-gray-600">
                    We'd love to know how you discovered ClaimPilot+
                </p>
            </div>

            <!-- Survey Card -->
            <div class="auth-card p-8 slide-up">
                <form id="surveyForm" method="POST" action="{{ route('survey.submit') }}">
                    @csrf
                    <!-- Options Container -->
                    <div class="space-y-3 mb-8">
                        @foreach($options as $option)
                        <div class="option-wrapper">
                            <input 
                                type="radio" 
                                name="survey_option" 
                                value="{{ $option->id }}" 
                                id="option{{ $option->id }}" 
                                class="option-input peer hidden" 
                                data-text="{{ strtolower($option->option_text) }}" 
                                required
                            >
                            <label 
                                for="option{{ $option->id }}" 
                                class="peer-checked:border-accent peer-checked:bg-gradient-to-r peer-checked:from-accent/5 peer-checked:to-accent-light/5 peer-checked:shadow-accent flex items-center gap-3 p-4 border-2 border-gray-300 rounded-xl cursor-pointer transition-all duration-200 hover:border-accent hover:bg-gray-50"
                            >
                                <!-- Radio Button -->
                                <!-- Label Text -->
                                <span class="text-base font-medium text-gray-700 flex-1">{{ $option->option_text }}</span>
                            </label>

                            <!-- Other Input -->
                            @if(strtolower($option->option_text) === 'other')
                            <div 
                                id="otherInputWrapper{{ $option->id }}" 
                                class="other-input-wrapper max-h-0 overflow-hidden transition-all duration-200 mt-3 pl-12"
                            >
                                <input 
                                    type="text" 
                                    class="other-input w-full px-4 py-3 rounded-xl border-2 border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200" 
                                    id="otherInput{{ $option->id }}" 
                                    name="other_text" 
                                    placeholder="Please tell us..."
                                >
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Error Message -->
                    <div 
                        id="errorMessage" 
                        class="hidden mb-6 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"
                    >
                        Please select an option before submitting
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-4 py-3 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-accent hover:shadow-2xl flex items-center justify-center mb-4"
                    >
                        <span>Submit Response</span>
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>

                    <!-- Skip Link -->
                    <p class="text-center text-sm text-gray-600">
                        <a 
                            href="{{ route('user.dashboard') }}" 
                            class="text-accent hover:text-accent-light font-medium transition duration-200"
                        >
                            ← Back to Dashboard
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div 
        id="successMessage" 
        class="fixed inset-0 hidden items-center justify-center z-50"
    >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        <!-- Modal -->
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4 text-center transform transition-all duration-300 scale-0 origin-center" id="successModal">
            <!-- Success Icon -->
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-accent to-accent-light mx-auto mb-6">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-2">Thank You!</h3>
            <p class="text-gray-600 mb-6">Your response has been successfully submitted</p>

            <p class="text-sm text-gray-500">
                Redirecting to dashboard...
            </p>
        </div>
    </div>

    <script>
        // Show/hide "Other" input
        document.querySelectorAll('.option-input').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.other-input-wrapper').forEach(wrapper => {
                    wrapper.classList.remove('max-h-96');
                    wrapper.classList.add('max-h-0');
                    const otherInput = wrapper.querySelector('.other-input');
                    if (otherInput) otherInput.value = '';
                });

                const optionText = this.getAttribute('data-text') || '';
                if (optionText.toLowerCase().includes('other')) {
                    const optionId = this.id.replace('option', '');
                    const otherWrapper = document.getElementById('otherInputWrapper' + optionId);
                    const otherInput = document.getElementById('otherInput' + optionId);
                    if (otherWrapper && otherInput) {
                        otherWrapper.classList.remove('max-h-0');
                        otherWrapper.classList.add('max-h-96');
                        otherInput.required = true;
                        otherInput.focus();
                    }
                } else {
                    document.querySelectorAll('.other-input').forEach(i => i.required = false);
                }
                document.getElementById('errorMessage').classList.add('hidden');
            });
        });

        // Form submission
        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            const selectedOption = document.querySelector('input[name="survey_option"]:checked');
            
            if (!selectedOption) {
                document.getElementById('errorMessage').classList.remove('hidden');
                e.preventDefault();
                return;
            }

            const optionText = selectedOption.getAttribute('data-text') || '';
            if (optionText.toLowerCase().includes('other')) {
                const optionId = selectedOption.id.replace('option', '');
                const otherInput = document.getElementById('otherInput' + optionId);
                
                if (otherInput && !otherInput.value.trim()) {
                    otherInput.focus();
                    otherInput.classList.add('border-red-500');
                    setTimeout(() => {
                        otherInput.classList.remove('border-red-500');
                    }, 2000);
                    e.preventDefault();
                    return;
                }
            }

            // Show success message and submit
            e.preventDefault();
            showSuccessMessage();
            
            setTimeout(() => {
                this.submit();
            }, 1500);
        });

        function showSuccessMessage() {
            const successMessage = document.getElementById('successMessage');
            const successModal = document.getElementById('successModal');
            
            successMessage.classList.remove('hidden');
            successMessage.classList.add('flex');
            
            setTimeout(() => {
                successModal.classList.remove('scale-0');
                successModal.classList.add('scale-100');
            }, 100);
        }
    </script>
</body>
</html>