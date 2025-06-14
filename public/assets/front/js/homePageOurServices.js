// Initialize AOS 

document.addEventListener('DOMContentLoaded', function() {
  AOS.init({
    once: true,
    disable: 'phone'
  });

  // Add additional scroll-triggered animations
  const serviceCards = document.querySelectorAll('.service-card');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        // Add a staggered delay to each card
        setTimeout(() => {
          entry.target.classList.add('animate-in');
        }, index * 150);

        // Unobserve after animation is triggered
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1
  });

  serviceCards.forEach(card => {
    observer.observe(card);
  });
});