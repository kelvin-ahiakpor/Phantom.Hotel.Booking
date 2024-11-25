// view_hotel.js

document.addEventListener('DOMContentLoaded', function () {
    // Helper function for DOM elements
    const getEl = (selector) => document.querySelector(selector);
    const getAllEl = (selector) => document.querySelectorAll(selector);

    // Image Gallery Functionality
    class ImageGallery {
        constructor() {
            this.images = getAllEl('.gallery-image');
            this.mainImage = getEl('.main-gallery-image');
            this.currentIndex = 0;

            this.init();
        }

        init() {
            // Add click handlers to thumbnail images
            this.images.forEach((img, index) => {
                img.addEventListener('click', () => this.switchImage(index));
            });

            // Initialize navigation buttons if they exist
            const prevBtn = getEl('.gallery-prev');
            const nextBtn = getEl('.gallery-next');

            if (prevBtn && nextBtn) {
                prevBtn.addEventListener('click', () => this.navigate('prev'));
                nextBtn.addEventListener('click', () => this.navigate('next'));
            }
        }

        switchImage(index) {
            // Remove active class from current image
            this.images[this.currentIndex].classList.remove('active');
            // Add active class to new image
            this.images[index].classList.add('active');
            // Update main image if it exists
            if (this.mainImage) {
                this.mainImage.src = this.images[index].src;
                this.mainImage.alt = this.images[index].alt;
            }
            this.currentIndex = index;
        }

        navigate(direction) {
            let newIndex;
            if (direction === 'prev') {
                newIndex = this.currentIndex === 0 ? this.images.length - 1 : this.currentIndex - 1;
            } else {
                newIndex = this.currentIndex === this.images.length - 1 ? 0 : this.currentIndex + 1;
            }
            this.switchImage(newIndex);
        }
    }

    // Sticky Booking Summary
    class StickySummary {
        constructor() {
            this.summary = getEl('.booking-summary');
            this.initialOffset = this.summary ? this.summary.offsetTop : 0;
            this.handleScroll = this.handleScroll.bind(this);

            this.init();
        }

        init() {
            if (this.summary) {
                window.addEventListener('scroll', this.handleScroll);
                window.addEventListener('resize', this.handleScroll);
            }
        }

        handleScroll() {
            if (window.innerWidth >= 1024) { // Only stick on desktop
                const scrollPosition = window.scrollY;
                if (scrollPosition > this.initialOffset) {
                    this.summary.classList.add('sticky-summary');
                } else {
                    this.summary.classList.remove('sticky-summary');
                }
            } else {
                this.summary.classList.remove('sticky-summary');
            }
        }
    }

    // Room Booking Handler
    class RoomBooking {
        constructor() {
            this.bookingButtons = getAllEl('.book-now-btn');
            this.init();
        }

        init() {
            this.bookingButtons.forEach(btn => {
                btn.addEventListener('click', (e) => this.handleBooking(e));
            });
        }

        handleBooking(e) {
            const roomId = e.target.dataset.roomId;
            const hotelId = e.target.dataset.hotelId;
            // Store selected room details in sessionStorage before redirect
            if (roomId && hotelId) {
                sessionStorage.setItem('selectedRoom', roomId);
                sessionStorage.setItem('selectedHotel', hotelId);
                window.location.href = `booking_form.php?hotel_id=${hotelId}&room_id=${roomId}`;
            }
        }
    }

    // Amenities Display Handler
    class AmenitiesDisplay {
        constructor() {
            this.amenitiesSection = getEl('.amenities-section');
            this.amenitiesList = getAllEl('.amenity-item');
            this.init();
        }

        init() {
            this.amenitiesList.forEach(amenity => {
                // Add hover effect
                amenity.addEventListener('mouseenter', () => {
                    amenity.classList.add('amenity-hover');
                });
                amenity.addEventListener('mouseleave', () => {
                    amenity.classList.remove('amenity-hover');
                });
            });
        }
    }

    // Reviews Section Handler
    class ReviewsSection {
        constructor() {
            this.reviewsContainer = getEl('.reviews-container');
            this.loadMoreBtn = getEl('.load-more-reviews');
            this.page = 1;
            this.init();
        }

        init() {
            if (this.loadMoreBtn) {
                this.loadMoreBtn.addEventListener('click', () => this.loadMoreReviews());
            }
        }

        async loadMoreReviews() {
            try {
                // TODO: PEEZY implement this
                const hotelId = this.reviewsContainer.dataset.hotelId;
                const response = await fetch(`get_more_reviews.php?hotel_id=${hotelId}&page=${this.page + 1}`);
                const data = await response.json();

                if (data.reviews && data.reviews.length > 0) {
                    this.appendReviews(data.reviews);
                    this.page++;
                }

                if (!data.hasMore) {
                    this.loadMoreBtn.style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading more reviews:', error);
            }
        }

        appendReviews(reviews) {
            reviews.forEach(review => {
                const reviewElement = this.createReviewElement(review);
                this.reviewsContainer.appendChild(reviewElement);
            });
        }

        createReviewElement(review) {
            // Create and return review DOM element
            const div = document.createElement('div');
            div.className = 'review-item border-b pb-4 mb-4';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="font-bold">${review.reviewer_name}</p>
                        <p class="text-gray-500 text-sm">${new Date(review.created_at).toLocaleDateString()}</p>
                    </div>
                    <div class="flex">
                        ${this.generateStarRating(review.rating)}
                    </div>
                </div>
                <p class="text-gray-600">${review.comment}</p>
            `;
            return div;
        }

        generateStarRating(rating) {
            return Array(5).fill().map((_, i) => `
                <i class="fas fa-star ${i < rating ? 'text-yellow-400' : 'text-gray-300'}"></i>
            `).join('');
        }
    }

    // Initialize all components
    const initializeComponents = () => {
        const gallery = new ImageGallery();
        const stickySummary = new StickySummary();
        const roomBooking = new RoomBooking();
        const amenities = new AmenitiesDisplay();
        const reviews = new ReviewsSection();
    };

    // Initialize everything when DOM is loaded
    initializeComponents();

    // Handle browser back button
    const handleBackButton = () => {
        const backBtn = getEl('.back-button');
        if (backBtn) {
            backBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.history.back();
            });
        }
    };

    // Handle responsive navigation
    const handleResponsiveNav = () => {
        const navToggle = getEl('.nav-toggle');
        const navMenu = getEl('.nav-menu');

        if (navToggle && navMenu) {
            navToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
            });
        }
    };

    // Initialize additional functionality
    handleBackButton();
    handleResponsiveNav();

    // Handle smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add error handling for images
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function () {
            this.src = '../images/default-hotel.png';
        });
    });
});