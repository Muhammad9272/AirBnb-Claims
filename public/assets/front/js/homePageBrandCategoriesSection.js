// JavaScript for fade-in animation and carousel functionality
document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.getElementById('carousel');
  const items = document.querySelectorAll('[data-carousel-item]');
  const indicators = document.querySelectorAll('.indicator');
  const prevBtn = document.getElementById('prev');
  const nextBtn = document.getElementById('next');
  let currentIndex = 0;
  
  // Intersection Observer for fade-in animation
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        // Add delay to create sequential animation
        setTimeout(() => {
          entry.target.classList.remove('opacity-0', 'translate-y-8');
          entry.target.classList.add('opacity-100', 'translate-y-0', 'transition-all', 'duration-1000');
        }, parseInt(entry.target.dataset.index) * 300);
        
        // Stop observing after animation
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  
  // Observe all carousel items
  items.forEach(item => {
    observer.observe(item);
  });
  
  // Update carousel position
  function updateCarousel() {
    const translateValue = -currentIndex * 100;
    carousel.style.transform = `translateX(${translateValue}%)`;
    
    // Update indicators
    indicators.forEach((indicator, index) => {
      if (index === currentIndex) {
        indicator.classList.add('bg-gradient-to-r', 'from-purple-500', 'to-pink-500', 'w-8');
      } else {
        indicator.classList.remove('bg-gradient-to-r', 'from-purple-500', 'to-pink-500', 'w-8');
      }
    });
    
    // Update button visibility
    prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
    nextBtn.style.display = currentIndex === items.length - 1 ? 'none' : 'flex';
  }
  
  // Event listeners for prev/next buttons
  prevBtn.addEventListener('click', () => {
    currentIndex = Math.max(0, currentIndex - 1);
    updateCarousel();
  });
  
  nextBtn.addEventListener('click', () => {
    currentIndex = Math.min(items.length - 1, currentIndex + 1);
    updateCarousel();
  });
  
  // Event listeners for indicators
  indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
      currentIndex = index;
      updateCarousel();
    });
  });
  
  // Initialize
  updateCarousel();
});