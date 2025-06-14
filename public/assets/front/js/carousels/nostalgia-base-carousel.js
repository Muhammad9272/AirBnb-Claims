class ImageCarousel {
    constructor(options) {
        this.mainImage = document.getElementById(options.mainImageId);
        this.thumbnails = document.querySelectorAll(options.thumbnailSelector);
        this.prevButton = document.getElementById(options.prevButtonId);
        this.nextButton = document.getElementById(options.nextButtonId);
        this.images = Array.from(this.thumbnails).map(thumb => thumb.getAttribute('data-full-img'));
        this.currentIndex = 0;

        this.initializeCarousel();
    }

    initializeCarousel() {
        // Add click listeners to thumbnails
        this.thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', () => this.changeImage(index));
            // Add active state to first thumbnail
            if (index === 0) thumb.classList.add('border-purple-500');
        });

        // Add navigation button listeners
        this.prevButton.addEventListener('click', () => this.navigate('prev'));
        this.nextButton.addEventListener('click', () => this.navigate('next'));

        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.navigate('prev');
            if (e.key === 'ArrowRight') this.navigate('next');
        });

        // Add touch support
        let touchStartX = 0;
        let touchEndX = 0;

        this.mainImage.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        this.mainImage.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 50) this.navigate('next');
            if (touchStartX - touchEndX < -50) this.navigate('prev');
        });
    }

    changeImage(index) {
        // Remove active state from all thumbnails
        this.thumbnails.forEach(thumb => 
            thumb.classList.remove('border-purple-500'));

        // Add active state to selected thumbnail
        this.thumbnails[index].classList.add('border-purple-500');

        // Add fade out effect
        this.mainImage.classList.add('opacity-0');
        
        setTimeout(() => {
            this.mainImage.src = this.images[index];
            this.currentIndex = index;
            // Add fade in effect
            this.mainImage.classList.remove('opacity-0');
        }, 300);
    }

    navigate(direction) {
        const newIndex = direction === 'next' 
            ? (this.currentIndex + 1) % this.images.length
            : (this.currentIndex - 1 + this.images.length) % this.images.length;
        
        this.changeImage(newIndex);
    }
}
