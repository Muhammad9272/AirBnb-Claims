//  Simple JavaScript for carousel functionality

document.addEventListener("DOMContentLoaded", function () {
  const carouselContainer = document.querySelector(".carousel-container");
  const slides = document.querySelector(".carousel-slides");
  const slideElements = document.querySelectorAll(".carousel-slide");
  const prevButton = document.getElementById("prevButton");
  const nextButton = document.getElementById("nextButton");
  const dots = document.querySelectorAll(".carousel-dot");

  let currentIndex = 0;
  const totalSlides = slideElements.length;
  let slideWidth = 0;
  let autoplayInterval;
  let touchStartX = 0;
  let touchEndX = 0;
  let isSwiping = false;

  // Initialize carousel
  initCarousel();

  // Initialize functions
  function initCarousel() {
    updateSlideWidth();
    bindEvents();
    updateAriaAttributes();
    startAutoplay();
  }

  function updateSlideWidth() {
    slideWidth = slideElements[0].offsetWidth;
    updateCarousel(false);
  }

  function bindEvents() {
    // Navigation buttons
    prevButton.addEventListener("click", goToPrevSlide);
    nextButton.addEventListener("click", goToNextSlide);

    // Keyboard navigation
    carouselContainer.addEventListener("keydown", handleKeyboardNavigation);

    // Dot navigation using event delegation
    document.addEventListener("click", function (e) {
      if (e.target.classList.contains("carousel-dot")) {
        const dotIndex = Array.from(dots).indexOf(e.target);
        goToSlide(dotIndex);
      }
    });

    // Window resize event
    window.addEventListener(
      "resize",
      debounce(function () {
        updateSlideWidth();
      }, 250)
    );

    // Touch events for mobile swipe
    carouselContainer.addEventListener("touchstart", handleTouchStart, {
      passive: true,
    });
    carouselContainer.addEventListener("touchmove", handleTouchMove, {
      passive: true,
    });
    carouselContainer.addEventListener("touchend", handleTouchEnd);

    // Pause autoplay on hover/focus
    carouselContainer.addEventListener("mouseenter", pauseAutoplay);
    carouselContainer.addEventListener("mouseleave", startAutoplay);
    carouselContainer.addEventListener("focusin", pauseAutoplay);
    carouselContainer.addEventListener("focusout", startAutoplay);
  }

  function updateAriaAttributes() {
    slideElements.forEach((slide, index) => {
      slide.setAttribute("aria-hidden", index !== currentIndex);
    });

    dots.forEach((dot, index) => {
      dot.setAttribute("aria-selected", index === currentIndex);
    });
  }

  function updateCarousel(animate = true) {
    if (animate) {
      slides.style.transition = "transform 700ms ease-out";
    } else {
      slides.style.transition = "none";
    }

    requestAnimationFrame(() => {
      slides.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    });

    updateDots();
    updateAriaAttributes();
  }

  function updateDots() {
    dots.forEach((dot, index) => {
      if (index === currentIndex) {
        dot.classList.add(
          "bg-gradient-to-r",
          "from-indigo-600",
          "to-purple-600",
          "scale-125"
        );
        dot.classList.remove("bg-gray-300");
      } else {
        dot.classList.remove(
          "bg-gradient-to-r",
          "from-indigo-600",
          "to-purple-600",
          "scale-125"
        );
        dot.classList.add("bg-gray-300");
      }
    });
  }

  function goToPrevSlide() {
    currentIndex = currentIndex > 0 ? currentIndex - 1 : totalSlides - 1;
    updateCarousel();
  }

  function goToNextSlide() {
    currentIndex = currentIndex < totalSlides - 1 ? currentIndex + 1 : 0;
    updateCarousel();
  }

  function goToSlide(index) {
    if (index >= 0 && index < totalSlides) {
      currentIndex = index;
      updateCarousel();
    }
  }

  function startAutoplay() {
    pauseAutoplay();
    autoplayInterval = setInterval(goToNextSlide, 5000);
  }

  function pauseAutoplay() {
    if (autoplayInterval) {
      clearInterval(autoplayInterval);
      autoplayInterval = null;
    }
  }

  function handleKeyboardNavigation(e) {
    if (e.key === "ArrowLeft") {
      goToPrevSlide();
      e.preventDefault();
    } else if (e.key === "ArrowRight") {
      goToNextSlide();
      e.preventDefault();
    }
  }

  function handleTouchStart(e) {
    touchStartX = e.touches[0].clientX;
    isSwiping = true;
    pauseAutoplay();
  }

  function handleTouchMove(e) {
    if (!isSwiping) return;
    touchEndX = e.touches[0].clientX;
    const diffX = touchStartX - touchEndX;

    // Prevent page scrolling when swiping the carousel
    if (Math.abs(diffX) > 10) {
      e.preventDefault();
    }

    // Optional: Add visual feedback during swipe
    const moveX = -currentIndex * slideWidth - diffX;
    slides.style.transition = "none";
    slides.style.transform = `translateX(${moveX}px)`;
  }

  function handleTouchEnd() {
    if (!isSwiping) return;

    const diffX = touchStartX - touchEndX;
    // Determine if a swipe occurred (more than 50px motion)
    if (Math.abs(diffX) > 50) {
      if (diffX > 0) {
        goToNextSlide();
      } else {
        goToPrevSlide();
      }
    } else {
      // Return to current slide if swipe wasn't significant
      updateCarousel();
    }

    isSwiping = false;
    startAutoplay();
  }

  // Utility function for debouncing resize events
  function debounce(func, wait) {
    let timeout;
    return function () {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }

  // Preload images for smoother experience
  function preloadImages() {
    const imageElements = document.querySelectorAll(".carousel-slide img");
    imageElements.forEach((img) => {
      const src = img.getAttribute("src");
      if (src) {
        const newImg = new Image();
        newImg.src = src;
      }
    });
  }

  // Call preload function
  preloadImages();
});
