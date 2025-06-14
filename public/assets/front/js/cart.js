document.addEventListener('DOMContentLoaded', function() {
    function updateCartCount(count) {
        const cartCount = document.getElementById('cart-count');
        if (cartCount) {
            cartCount.textContent = count;
        }
    }

    window.updateCartCount = updateCartCount;
});
