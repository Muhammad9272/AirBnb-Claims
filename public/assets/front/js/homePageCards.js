  document.addEventListener('DOMContentLoaded', function() {
    const cardContainer = document.getElementById('cardContainer');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');
    const indicators = document.querySelectorAll('#indicatorContainer button');
    let currentIndex = 0;
    
    const scrollToIndex = (index) => {
      const cards = cardContainer.querySelectorAll('.card');
      if (index >= 0 && index < cards.length) {
        const cardWidth = cards[0].offsetWidth + 32; // Adding margin
        cardContainer.style.transform = `translateX(-${index * cardWidth}px)`;
        currentIndex = index;
        
        // Update indicators
        indicators.forEach((indicator, i) => {
          if (i === currentIndex) {
            indicator.classList.add('bg-purple-500', 'dark:bg-purple-500');
            indicator.classList.remove('bg-gray-300', 'dark:bg-gray-700');
          } else {
            indicator.classList.remove('bg-purple-500', 'dark:bg-purple-500');
            indicator.classList.add('bg-gray-300', 'dark:bg-gray-700');
          }
        });
      }
    };
    
    scrollLeftBtn.addEventListener('click', () => {
      scrollToIndex(currentIndex - 1);
    });
    
    scrollRightBtn.addEventListener('click', () => {
      scrollToIndex(currentIndex + 1);
    });
    
    indicators.forEach((indicator, i) => {
      indicator.addEventListener('click', () => {
        scrollToIndex(i);
      });
    });
  });