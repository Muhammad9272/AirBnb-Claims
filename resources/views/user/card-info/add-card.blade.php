<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Payment Method - ClaimPilot+</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        /* Animated background particles */
        .bg-particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            animation: float 20s infinite;
        }

        .bg-particle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .bg-particle:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
            animation-delay: 5s;
        }

        .bg-particle:nth-child(3) {
            width: 250px;
            height: 250px;
            top: 50%;
            right: -125px;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        .container-main {
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            padding: 40px 32px;
            color: white;
        }

        .card-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .card-body {
            padding: 40px 32px;
        }

        .payment-element-wrapper {
            margin-bottom: 24px;
        }

        #payment-element {
            margin-bottom: 16px;
        }

        .error-message {
            display: none;
            padding: 16px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 24px;
            animation: shake 0.3s ease;
        }

        .error-message.show {
            display: block;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .submit-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .submit-btn span {
            position: relative;
            z-index: 1;
        }

        .skip-link {
            display: block;
            text-align: center;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .skip-link:hover {
            color: #334155;
        }

        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #0c4a6e;
        }

        .info-box svg {
            display: inline;
            width: 18px;
            height: 18px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 12px;
            color: #6b7280;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .security-badge svg {
            width: 16px;
            height: 16px;
            color: #10b981;
        }

        @media (max-width: 640px) {
            .card-header {
                padding: 32px 24px;
            }

            .card-body {
                padding: 32px 24px;
            }

            .card-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-particle"></div>
    <div class="bg-particle"></div>
    <div class="bg-particle"></div>

    <div class="container-main">
        <div class="card">
            <div class="card-header">
                <h1>💳 Save Payment Method</h1>
                <p>Secure payment processing with Stripe</p>
            </div>

            <div class="card-body">
                <div class="info-box">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    We need your payment method to process claims quickly and securely.
                </div>

                <form id="payment-form">
                    <div class="payment-element-wrapper">
                        <div id="payment-element"></div>
                    </div>
                    
                    <div id="error-message" class="error-message"></div>

                    <button 
                        id="submit-btn" 
                        type="submit" 
                        class="submit-btn">
                        <span>Save Payment Method</span>
                    </button>

                    <a href="{{ route('user.dashboard') }}" class="skip-link">
                        ← Back to Dashboard
                    </a>

                    <div class="security-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0l-5.25 5.25a.75.75 0 101.06 1.06L11 3.622v17.128a.75.75 0 001.5 0V3.622l4.178 4.178a.75.75 0 101.06-1.06l-5.25-5.25z" clip-rule="evenodd" />
                        </svg>
                        SSL Secured by Stripe
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
            submitBtn.innerHTML = '<span>Processing...</span>';
            errorMessage.classList.remove("show");

            try {
                const { setupIntent, error } = await stripe.confirmSetup({
                    elements,
                    confirmParams: {
                        return_url: "{{ route('user.card.save') }}",
                    }
                });

                if (error) {
                    errorMessage.textContent = error.message;
                    errorMessage.classList.add("show");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>Save Payment Method</span>';
                }
            } catch (err) {
                errorMessage.textContent = "An error occurred. Please try again.";
                errorMessage.classList.add("show");
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Save Payment Method</span>';
            }
        });
    </script>
</body>
</html>
