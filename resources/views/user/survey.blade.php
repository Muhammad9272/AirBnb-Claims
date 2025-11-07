<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How Did You Hear About Us? - ClaimPilot+</title>
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
            background: rgba(139, 92, 246, 0.1);
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

        .container {
            max-width: 600px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 48px;
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

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-text {
            color: #6366f1;
            font-size: 24px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 8px;
            display: inline-block;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 16px;
            margin-bottom: 32px;
        }

        .options-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 32px;
        }

        .option-wrapper {
            position: relative;
        }

        .option-label {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .option-label::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
            transition: width 0.3s ease;
            z-index: 0;
        }

        .option-label:hover::before {
            width: 100%;
        }

        .option-label:hover {
            border-color: #a5b4fc;
            transform: translateX(4px);
        }

        .option-input {
            display: none;
        }

        .option-input:checked + .option-label {
            border-color: #6366f1;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .option-input:checked + .option-label .radio-custom {
            border-color: #6366f1;
            background: #6366f1;
        }

        .option-input:checked + .option-label .radio-custom::after {
            opacity: 1;
            transform: scale(1);
        }

        .radio-custom {
            width: 24px;
            height: 24px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            margin-right: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .radio-custom::after {
            content: '';
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
        }

        .option-text {
            color: #334155;
            font-size: 16px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .other-input-wrapper {
            margin-top: 12px;
            padding-left: 40px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .other-input-wrapper.show {
            max-height: 100px;
        }

        .other-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .other-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 8px;
            display: none;
            animation: shake 0.3s ease;
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
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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

        .submit-btn span {
            position: relative;
            z-index: 1;
        }

        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: white;
            padding: 48px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            z-index: 1000;
            opacity: 0;
            transition: all 0.4s ease;
        }

        .success-message.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: successBounce 0.6s ease;
        }

        @keyframes successBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .success-icon::after {
            content: '✓';
            color: white;
            font-size: 48px;
            font-weight: bold;
        }

        .success-message h3 {
            color: #1e293b;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .success-message p {
            color: #64748b;
            font-size: 16px;
        }

        @media (max-width: 640px) {
            .card {
                padding: 32px 24px;
            }

            h2 {
                font-size: 24px;
            }

            .option-label {
                padding: 14px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-particle"></div>
    <div class="bg-particle"></div>
    <div class="bg-particle"></div>

    <div class="container">
        <div class="card">
            <div class="logo">
                <div class="logo-text">
                    <span class="logo-icon"></span>
                    CLAIM PILOT+
                </div>
            </div>

            <h2>How did you hear about us?</h2>
            <p class="subtitle">We'd love to know how you discovered ClaimPilot+</p>

            <form id="surveyForm" method="POST" action="{{ route('survey.submit') }}">
                @csrf
                <div class="options-container">
                    <!-- Dynamic options will be rendered here from backend -->
                    @foreach($options as $option)
                    <div class="option-wrapper">
                        <input type="radio" name="survey_option" value="{{ $option->id }}" id="option{{ $option->id }}" class="option-input" data-text="{{ strtolower($option->option_text) }}" required>
                        <label for="option{{ $option->id }}" class="option-label">
                            <span class="radio-custom"></span>
                            <span class="option-text">{{ $option->option_text }}</span>
                        </label>
                        @if(strtolower($option->option_text) === 'other')
                        <div class="other-input-wrapper" id="otherInputWrapper{{ $option->id }}">
                            <input type="text" class="other-input" id="otherInput{{ $option->id }}" name="other_text" placeholder="Please specify...">
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="error-message" id="errorMessage" style="display:none;">
                    Please select an option before submitting
                </div>

                <button type="submit" class="submit-btn">
                    <span>Submit Response</span>
                </button>
            </form>
        </div>
    </div>

    <div class="success-message" id="successMessage">
        <div class="success-icon"></div>
        <h3>Thank You!</h3>
        <p>Your response has been successfully submitted</p>
    </div>

    <script>
        // Show/hide "Other" input
        document.querySelectorAll('.option-input').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.other-input-wrapper').forEach(wrapper => {
                    wrapper.classList.remove('show');
                    const otherInput = wrapper.querySelector('.other-input');
                    if (otherInput) otherInput.value = '';
                });

                const optionText = this.getAttribute('data-text') || '';
                if (optionText.toLowerCase().includes('other')) {
                    const optionId = this.id.replace('option', '');
                    const otherWrapper = document.getElementById('otherInputWrapper' + optionId);
                    const otherInput = document.getElementById('otherInput' + optionId);
                    if (otherWrapper && otherInput) {
                        otherWrapper.classList.add('show');
                        otherInput.required = true;
                        otherInput.focus();
                    }
                } else {
                    document.querySelectorAll('.other-input').forEach(i => i.required = false);
                }
                document.getElementById('errorMessage').style.display = 'none';
            });
        });

        // Validate "Other" before submit
        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            const selectedOption = document.querySelector('input[name="survey_option"]:checked');
            if (!selectedOption) {
                document.getElementById('errorMessage').style.display = 'block';
                e.preventDefault();
                return;
            }
            const optionText = selectedOption.getAttribute('data-text') || '';
            if (optionText.toLowerCase().includes('other')) {
                const optionId = selectedOption.id.replace('option', '');
                const otherInput = document.getElementById('otherInput' + optionId);
                if (otherInput && !otherInput.value.trim()) {
                    otherInput.focus();
                    otherInput.style.borderColor = '#ef4444';
                    setTimeout(() => {
                        otherInput.style.borderColor = '#e2e8f0';
                    }, 2000);
                    e.preventDefault();
                    return;
                }
            }
        });
    </script>
</body>
</html>