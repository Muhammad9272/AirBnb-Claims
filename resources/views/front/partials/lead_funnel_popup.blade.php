{{-- Lead Funnel Popup - Add this to app.blade.php before closing </body> tag --}}

@guest
<!-- Lead Funnel Popup Modal (Backdrop Static) -->
<div id="leadPopup" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" style="z-index: 1111;">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all relative max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded-t-xl">
            <div class="flex items-center justify-center mb-2">
                <div class="bg-white/20 backdrop-blur-sm p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-bold text-center">Welcome! 🎉</h2>
            <p class="text-blue-100 text-center mt-1 text-sm">Get an exclusive discount on your first subscription</p>
        </div>

        <!-- Form State -->
        <div id="formState" class="p-4">
            <form id="leadPopupForm" onsubmit="return submitLeadForm(event)">
                @csrf
                
                <!-- Name Field -->
                <div class="mb-3">
                    <label for="leadName" class="block text-xs font-medium text-gray-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="leadName" 
                           name="name" 
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Enter your name">
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="leadEmail" class="block text-xs font-medium text-gray-700 mb-1">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="leadEmail" 
                           name="email" 
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Enter your email">
                </div>

                <!-- Phone Field (Optional) -->
                <div class="mb-3">
                    <label for="leadPhone" class="block text-xs font-medium text-gray-700 mb-1">
                        Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input type="tel" 
                           id="leadPhone" 
                           name="phone"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Enter your phone number">
                </div>

                <!-- How did you hear about us? -->
                <div class="mb-3">
                    <label for="leadSource" class="block text-xs font-medium text-gray-700 mb-1">
                        How did you hear about us? <span class="text-red-500">*</span>
                    </label>
                    <select id="leadSource" 
                            name="source" 
                            required
                            onchange="toggleReferralName()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Select an option</option>
                        <option value="referral">Referral</option>
                        <option value="instagram">Instagram</option>
                        <option value="google_ads">Google Ads</option>
                        <option value="facebook">Facebook</option>
                        <option value="twitter">Twitter</option>
                        <option value="youtube">YouTube</option>
                        <option value="tiktok">TikTok</option>
                        <option value="search_engine">Search Engine (Google, Bing, etc.)</option>
                        <option value="friend">Friend or Family</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Referral Name (Conditional) -->
                <div id="referralNameField" class="mb-4 hidden">
                    <label for="leadReferralName" class="block text-xs font-medium text-gray-700 mb-1">
                        Who referred you? <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="leadReferralName" 
                           name="referral_name"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Enter referrer's name">
                </div>

                <!-- Error Message -->
                <div id="leadFormError" class="hidden mb-3 p-2 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs"></div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="leadSubmitBtn"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2.5 px-4 rounded-lg text-sm font-semibold hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                    Get My Discount Code
                </button>
            </form>

            <!-- Never Show Again Link -->
            <div class="mt-3 text-center">
                <button onclick="neverShowAgain()" class="text-xs text-gray-500 hover:text-gray-700 underline">
                    Don't show this again
                </button>
            </div>

            <p class="text-xs text-gray-500 text-center mt-3">
                By submitting, you agree to our <a href="{{ route('front.help.terms') }}" target="_blank" class="text-blue-600 hover:underline">Terms</a> and <a href="{{ route('front.help.privacy') }}" target="_blank" class="text-blue-600 hover:underline">Privacy Policy</a>
            </p>
        </div>

        <!-- Success State (Hidden by default) -->
        <div id="successState" class="hidden p-4 text-center">
            <!-- Success Icon -->
            <div class="mb-4">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">Success! 🎉</h3>
            <p class="text-sm text-gray-600 mb-4">Here's your exclusive discount code:</p>

            <!-- Discount Code Display -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200 rounded-lg p-3 mb-4">
                <p class="text-xs text-gray-600 mb-1">Your Discount Code</p>
                <div class="flex items-center justify-center gap-2">
                    <span id="discountCodeDisplay" class="text-2xl font-bold text-blue-600"></span>
                    <button onclick="copyDiscountCode()" class="text-blue-600 hover:text-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-600 mt-1">Save <span id="discountPercentage"></span>% on your subscription!</p>
            </div>

            <p class="text-xs text-gray-600 mb-4">
                We've also sent this code to your email. Use it at checkout to get your discount!
            </p>

            <button onclick="closeLeadPopup()" class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                Start Exploring
            </button>
        </div>
    </div>
</div>

<script>
// Lead Popup Configuration
const LEAD_POPUP_CONFIG = {
    delay: {{ \App\Models\GeneralSetting::first()->popup_delay ?? 3 }} * 1000,
    cookieName: 'lead_popup_hidden',
    formSubmittedCookie: 'lead_form_submitted',
    cookieDays: 365
};

// Check if popup should be shown
function shouldShowLeadPopup() {
    // Check if user clicked "Never show again"
    if (getCookie(LEAD_POPUP_CONFIG.cookieName)) {
        return false;
    }
    
    // Check if form was already submitted
    if (getCookie(LEAD_POPUP_CONFIG.formSubmittedCookie)) {
        return false;
    }
    
    return true;
}

// Show lead popup
function showLeadPopup() {
    if (shouldShowLeadPopup()) {
        setTimeout(() => {
            document.getElementById('leadPopup').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }, LEAD_POPUP_CONFIG.delay);
    }
}

// Close lead popup (only after form submission)
function closeLeadPopup() {
    document.getElementById('leadPopup').classList.add('hidden');
    document.body.style.overflow = '';
    // Set cookie to prevent showing again after successful submission
    setCookie(LEAD_POPUP_CONFIG.formSubmittedCookie, '1', LEAD_POPUP_CONFIG.cookieDays);
}

// Never show again (user clicked link)
function neverShowAgain() {
    setCookie(LEAD_POPUP_CONFIG.cookieName, '1', LEAD_POPUP_CONFIG.cookieDays);
    document.getElementById('leadPopup').classList.add('hidden');
    document.body.style.overflow = '';
}

// Toggle referral name field
function toggleReferralName() {
    const sourceSelect = document.getElementById('leadSource');
    const referralNameField = document.getElementById('referralNameField');
    const referralNameInput = document.getElementById('leadReferralName');
    
    if (sourceSelect.value === 'referral') {
        referralNameField.classList.remove('hidden');
        referralNameInput.required = true;
    } else {
        referralNameField.classList.add('hidden');
        referralNameInput.required = false;
        referralNameInput.value = '';
    }
}

// Submit lead form
function submitLeadForm(event) {
    event.preventDefault();
    
    const form = document.getElementById('leadPopupForm');
    const submitBtn = document.getElementById('leadSubmitBtn');
    const errorDiv = document.getElementById('leadFormError');
    
    // Clear previous errors
    errorDiv.classList.add('hidden');
    
    // Validate referral name if source is referral
    const sourceSelect = document.getElementById('leadSource');
    const referralNameInput = document.getElementById('leadReferralName');
    if (sourceSelect.value === 'referral' && !referralNameInput.value.trim()) {
        errorDiv.textContent = 'Please enter who referred you.';
        errorDiv.classList.remove('hidden');
        return false;
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    // Get form data
    const formData = new FormData(form);
    
    // Submit via AJAX
    fetch('{{ route("leads.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Set cookie to prevent popup from showing again
            setCookie(LEAD_POPUP_CONFIG.formSubmittedCookie, '1', LEAD_POPUP_CONFIG.cookieDays);
            
            // Hide form, show success state
            document.getElementById('formState').classList.add('hidden');
            document.getElementById('successState').classList.remove('hidden');
            
            // Display discount code
            document.getElementById('discountCodeDisplay').textContent = data.discount_code;
            document.getElementById('discountPercentage').textContent = data.discount_percentage;
            
        } else {
            // Show error
            errorDiv.textContent = data.message || 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = 'An error occurred. Please try again.';
        errorDiv.classList.remove('hidden');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Get My Discount Code';
    });
    
    return false;
}

// Copy discount code
function copyDiscountCode() {
    const code = document.getElementById('discountCodeDisplay').textContent;
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 2000);
    });
}

// Cookie helper functions
function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
}

function getCookie(name) {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Prevent closing on backdrop click (Backdrop Static)
document.getElementById('leadPopup').addEventListener('click', function(e) {
    // Do nothing - popup won't close when clicking backdrop
    // User must submit form or click "Never show again"
});

// Initialize popup on page load
document.addEventListener('DOMContentLoaded', function() {
    showLeadPopup();
});
</script>

<style>
/* Smooth animations */
#leadPopup > div {
    animation: slideUp 0.3s ease-out;
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
</style>
@endguest