            document.addEventListener('DOMContentLoaded', function() {
              // Get all necessary elements
              const cartToggle = document.getElementById('cart-toggle');
              const cartDropdown = document.getElementById('cart-dropdown');
              const closeCart = document.getElementById('close-cart');

              // Check if we're on mobile or desktop
              const isMobile = window.innerWidth < 768;

              // Handle cart icon click
              cartToggle.addEventListener('click', function(e) {
                e.preventDefault();

                if (isMobile) {
                  // On mobile, navigate to cart page
                  window.location.href = '/cart';
                } else {
                  // On desktop, toggle dropdown
                  cartDropdown.classList.toggle('hidden');
                }
              });

              // Close cart when clicking the close button
              if (closeCart) {
                closeCart.addEventListener('click', function() {
                  cartDropdown.classList.add('hidden');
                });
              }

              // Close cart dropdown when clicking outside
              document.addEventListener('click', function(e) {
                if (!isMobile && !cartToggle.contains(e.target) && !cartDropdown.contains(e.target)) {
                  cartDropdown.classList.add('hidden');
                }
              });

              // Update mobile/desktop behavior on window resize
              window.addEventListener('resize', function() {
                const newIsMobile = window.innerWidth < 768;
                if (newIsMobile !== isMobile) {
                  location.reload();
                }
              });
            });