//  JavaScript for scroll animation 
  document.addEventListener('DOMContentLoaded', () => {
    // Set up the Intersection Observer
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        // When product card enters viewport
        if (entry.isIntersecting) {
          // Get animation delay from data attribute
          const delay = parseInt(entry.target.getAttribute('data-delay') || 0);

          // Add animation after specified delay
          setTimeout(() => {
            entry.target.classList.remove('opacity-0', 'translate-y-8');
            entry.target.classList.add('opacity-100', 'translate-y-0');
          }, delay);

          // Unobserve after animation to improve performance
          observer.unobserve(entry.target);
        }
      });
    }, {
      root: null, // viewport
      threshold: 0.1, // trigger when 10% visible
      rootMargin: '0px 0px -100px 0px' // trigger before fully visible
    });

    // Observe all product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
      observer.observe(card);
    });
  });