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
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container-main {
            max-width: 450px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: white;
            padding: 32px 28px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .card-header p {
            font-size: 14px;
            color: #64748b;
        }

        .card-body {
            padding: 32px 28px;
        }

        .payment-element-wrapper {
            margin-bottom: 20px;
        }

        #payment-element {
            margin-bottom: 12px;
        }

        .error-message {
            display: none;
            padding: 12px 16px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .error-message.show {
            display: block;
        }

        .submit-btn {
            width: 100%;
            padding: 12px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 12px;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .submit-btn:active {
            transform: scale(0.98);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .skip-link {
            display: block;
            text-align: center;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s ease;
            margin-bottom: 16px;
        }

        .skip-link:hover {
            color: #475569;
        }

        .info-box {
            background: #f0f9ff;
            border-left: 3px solid #0284c7;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #0c4a6e;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-box svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .security-badge svg {
            width: 16px;
            height: 16px;
            color: #10b981;
        }

        @media (max-width: 640px) {
            .card-header {
                padding: 24px 20px;
            }

            .card-body {
                padding: 24px 20px;
            }

            .card-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="card">
            <div class="card-header">
                <h1>Save Payment Method</h1>
                <p>Securely add a payment method to your account</p>
            </div>

            <div class="card-body">
                <div class="info-box">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Your payment method will be securely stored and used to process claims.</span>
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
                        Save Payment Method
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
            submitBtn.textContent = "Processing...";
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
                    submitBtn.textContent = "Save Payment Method";
                }
            } catch (err) {
                errorMessage.textContent = "An error occurred. Please try again.";
                errorMessage.classList.add("show");
                submitBtn.disabled = false;
                submitBtn.textContent = "Save Payment Method";
            }
        });
    </script>
</body>
</html>
