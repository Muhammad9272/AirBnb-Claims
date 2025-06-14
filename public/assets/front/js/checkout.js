document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');

    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
        const formData = new FormData(checkoutForm);

        try {
            const response = await fetch(checkoutForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const result = await response.json();

            if (result.success) {
                if (selectedPayment === 'stripe') {
                    handleStripePayment(result);
                } else if (selectedPayment === 'paypal') {
                    handlePayPalPayment(result);
                }
            } else {
                showError(result.message);
            }
        } catch (error) {
            showError('An error occurred. Please try again.');
        }
    });

    function handleStripePayment(result) {
        const stripe = Stripe(result.publishable_key);
        stripe.redirectToCheckout({ sessionId: result.session_id });
    }

    function handlePayPalPayment(result) {
        window.location.href = result.approval_url;
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.textContent = message;
        checkoutForm.prepend(errorDiv);
        setTimeout(() => errorDiv.remove(), 5000);
    }

    // Payment method selection UI
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('border-purple-500', 'bg-purple-50');
            });
            
            if (this.checked) {
                this.closest('.payment-option').classList.add('border-purple-500', 'bg-purple-50');
            }
        });
    });
});
