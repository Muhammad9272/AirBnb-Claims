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
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .container {
            max-width: 500px;
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
            padding: 28px 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .logo-text {
            color: #2563eb;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .subtitle {
            color: #64748b;
            font-size: 13px;
            line-height: 1.4;
        }

        .card-body {
            padding: 28px 24px;
        }

        .options-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 24px;
        }

        .option-wrapper {
            position: relative;
        }

        .option-label {
            display: flex;
            align-items: flex-start;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            position: relative;
            gap: 12px;
        }

        .option-label:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .option-input {
            display: none;
        }

        .option-input:checked + .option-label {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .option-input:checked + .option-label .radio-custom {
            border-color: #2563eb;
            background: #2563eb;
        }

        .option-input:checked + .option-label .radio-custom::after {
            opacity: 1;
            transform: scale(1);
        }

        .radio-custom {
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
            position: relative;
            margin-top: 2px;
        }

        .radio-custom::after {
            content: '';
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            opacity: 0;
            transform: scale(0);
            transition: all 0.2s ease;
        }

        .option-text {
            color: #334155;
            font-size: 14px;
            font-weight: 500;
            flex: 1;
            line-height: 1.4;
            word-break: break-word;
        }

        .other-input-wrapper {
            margin-top: 8px;
            padding-left: 44px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease;
        }

        .other-input-wrapper.show {
            max-height: 120px;
        }

        .other-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .other-input::placeholder {
            color: #94a3b8;
        }

        .other-input:focus {
            outline: none;
            border-color: #2563eb;
            background: #eff6ff;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-bottom: 20px;
            padding: 10px 12px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            display: none;
        }

        .submit-btn {
            width: 100%;
            padding: 12px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
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

        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: white;
            padding: 36px 28px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            text-align: center;
            z-index: 1000;
            opacity: 0;
            transition: all 0.3s ease;
            max-width: 90%;
        }

        .success-message.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .success-icon {
            width: 56px;
            height: 56px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .success-icon::after {
            content: '✓';
            color: #2563eb;
            font-size: 28px;
            font-weight: bold;
        }

        .success-message h3 {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 6px;
        }

        .success-message p {
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            .card-header {
                padding: 24px 20px;
            }

            .card-body {
                padding: 24px 20px;
            }

            .logo-text {
                font-size: 11px;
                margin-bottom: 10px;
            }

            h2 {
                font-size: 18px;
                margin-bottom: 4px;
            }

            .subtitle {
                font-size: 12px;
            }

            .options-container {
                gap: 6px;
                margin-bottom: 20px;
            }

            .option-label {
                padding: 12px 14px;
                gap: 10px;
            }

            .radio-custom {
                width: 18px;
                height: 18px;
                min-width: 18px;
            }

            .option-text {
                font-size: 13px;
            }

            .other-input-wrapper {
                padding-left: 40px;
            }

            .submit-btn {
                font-size: 13px;
                padding: 11px 14px;
            }

            .error-message {
                font-size: 11px;
                padding: 8px 10px;
            }

            .success-message {
                padding: 28px 20px;
                border-radius: 10px;
            }

            .success-icon {
                width: 48px;
                height: 48px;
                margin: 0 auto 12px;
            }

            .success-icon::after {
                font-size: 24px;
            }

            .success-message h3 {
                font-size: 16px;
            }

            .success-message p {
                font-size: 12px;
            }
        }

        @media (max-width: 360px) {
            .container {
                width: 100%;
            }

            .card-header {
                padding: 20px 16px;
            }

            .card-body {
                padding: 20px 16px;
            }

            h2 {
                font-size: 16px;
            }

            .option-label {
                padding: 11px 12px;
            }

            .radio-custom {
                width: 18px;
                height: 18px;
            }

            .option-text {
                font-size: 12px;
            }

            .other-input {
                font-size: 12px;
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="logo-text">ClaimPilot+</div>
                <h2>How did you hear about us?</h2>
                <p class="subtitle">We'd love to know how you discovered ClaimPilot+</p>
            </div>

            <div class="card-body">
                <form id="surveyForm" method="POST" action="{{ route('survey.submit') }}">
                    @csrf
                    <div class="options-container">
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

                    <div class="error-message" id="errorMessage">
                        Please select an option before submitting
                    </div>

                    <button type="submit" class="submit-btn">
                        Submit Response
                    </button>
                </form>
            </div>
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
                    otherInput.style.borderColor = '#dc2626';
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