document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('evidenceModal');
    const checkbox = document.getElementById('evidenceConfirmCheckbox');
    const continueBtn = document.getElementById('continueToClaimBtn');
    window.openEvidenceModal = function() {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };
    
    function closeModalAndRedirect() {
        if (checkbox.checked) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            window.location.href = '/user/claims/create';
        }
    }
    
    if (checkbox) {
        checkbox.addEventListener('change', function() {
            continueBtn.disabled = !this.checked;
        });
    }
    
    if (continueBtn) {
        continueBtn.addEventListener('click', closeModalAndRedirect);
    }
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    }
});