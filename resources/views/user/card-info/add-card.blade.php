<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Payment Method - ClaimPilot+</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<body class="font-sans bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Decorative background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-accent rounded-full blur-3xl decorative-blob"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-light rounded-full blur-3xl decorative-blob" style="animation-delay: -3s;"></div>
    </div>

    <div class="relative z-10 min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="w-full max-w-md">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-3">
                    Add Payment Method
                </h1>
                <p class="text-xl text-gray-600">
                    Securely save your payment information
                </p>
            </div>

            <!-- Payment Card -->
            <div class="auth-card p-8 slide-up">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-accent to-accent-light rounded-xl p-6 mb-8 text-white">
                    <h2 class="text-2xl font-bold mb-2">💳 Save Payment Method</h2>
                    <p class="text-white text-sm">Secure payment processing with Stripe</p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-8 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-blue-700">We need your payment method to process claims quickly and securely.</span>
                </div>

                <!-- Payment Form -->
                <form id="payment-form" class="space-y-6">
                    <!-- Payment Element Container -->
                    <div class="payment-element-wrapper">
                        <div id="payment-element" class="stripe-payment-element"></div>
                    </div>
                    
                    <!-- Error Message -->
                    <div 
                        id="error-message" 
                        class="hidden px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"
                    >
                    </div>

                    <!-- Submit Button -->
                    <button 
                        id="submit-btn" 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-4 py-3 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-accent hover:shadow-2xl flex items-center justify-center"
                    >
                        <span>Save Payment Method</span>
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>

                    <!-- Back Link -->
                    <p class="text-center text-sm text-gray-600">
                        <a 
                            href="{{ route('user.dashboard') }}" 
                            class="text-accent hover:text-accent-light font-medium transition duration-200"
                        >
                            ← Back to Dashboard
                        </a>
                    </p>

                    <!-- Security Badge -->
                    <div class="flex items-center justify-center gap-2 text-xs text-gray-600 pt-4 border-t border-gray-200">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0l-5.25 5.25a.75.75 0 101.06 1.06L11 3.622v17.128a.75.75 0 001.5 0V3.622l4.178 4.178a.75.75 0 101.06-1.06l-5.25-5.25z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">SSL Secured by Stripe</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const clientSecret = "{{ $clientSecret }}";

        const elements = stripe.elements({ clientSecret: clientSecret });
        const paymentElement = elements.create("payment");
        paymentElement.mount("#payment-element");

        const form = document.getElementById("payment-form");
        const submitBtn = document.getElementById("submit-btn");
        const errorMessage = document.getElementById("error-message");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Processing...</span><svg class="h-5 w-5 ml-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            errorMessage.classList.add("hidden");

            try {
                const { setupIntent, error } = await stripe.confirmSetup({
                    elements,
                    confirmParams: {
                        return_url: "{{ route('user.card.save') }}",
                    }
                });

                if (error) {
                    errorMessage.textContent = error.message;
                    errorMessage.classList.remove("hidden");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>Save Payment Method</span><svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>';
                }
            } catch (err) {
                errorMessage.textContent = "An error occurred. Please try again.";
                errorMessage.classList.remove("hidden");
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Save Payment Method</span><svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>';
            }
        });
    </script>
</body>
</html>
