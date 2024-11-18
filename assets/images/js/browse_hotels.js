const hotels = [
    {
        id: 1,
        name: "HU AT KO OLINA",
        location: "Ko Olina, Hawaii",
        image: "/assets/images/engin-akyurt-SMwCQZWayj0-unsplash.jpg",
        description: "Experience luxury in the heart of Hawaii"
    },
    {
        id: 2,
        name: "ANGUILLA",
        location: "Caribbean Islands",
        image: "/assets/images/engin-akyurt-SMwCQZWayj0-unsplash.jpg",
        description: "Paradise found in the Caribbean"
    },
    {
        id: 3,
        name: "THE OCEAN CLUB",
        location: "Bahamas",
        image: "/assets/images/engin-akyurt-SMwCQZWayj0-unsplash.jpg",
        description: "Connect with true Bahamian beauty and enjoy remarkable seclusion at this legendary Caribbean hideaway."
    },
    {
        id: 4,
        name: "BORA BORA",
        location: "French Polynesia",
        image: "/assets/images/engin-akyurt-SMwCQZWayj0-unsplash.jpg",
        description: "Escape to the pearl of the Pacific"
    },
    {
        id: 5,
        name: "COSTA RICA PENINSULA",
        location: "Papagayo, Costa Rica",
        image: "/assets/images/engin-akyurt-SMwCQZWayj0-unsplash.jpg",
        description: "Where luxury meets nature"
    }
];

let currentIndex = 0;
const carousel = document.getElementById('carousel');
const currentSlide = document.getElementById('current-slide');
const totalSlides = document.getElementById('total-slides');

function createHotelCard(hotel, position) {
    const card = document.createElement('div');
    card.className = `
        absolute transform transition-all duration-500 ease-in-out cursor-pointer
        w-[400px] h-[600px] border border-white/20
        ${position === 'center' ? 'z-30 scale-100 opacity-100 translate-x-0' : ''}
        ${position === 'left' ? 'z-20 scale-95 opacity-70 -translate-x-[420px]' : ''}
        ${position === 'right' ? 'z-20 scale-95 opacity-70 translate-x-[420px]' : ''}
        ${position === 'far-left' ? 'z-10 scale-90 opacity-40 -translate-x-[840px]' : ''}
        ${position === 'far-right' ? 'z-10 scale-90 opacity-40 translate-x-[840px]' : ''}
    `;
    
    const isCenter = position === 'center';
    
    card.innerHTML = `
        <div class="relative w-full h-full overflow-hidden">
            ${isCenter ? `
                <img src="${hotel.image}" alt="${hotel.name}" class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
                    <div class="space-y-4">
                        <h2 class="text-white text-2xl font-light">${hotel.name}</h2>
                        <p class="text-white/80 text-sm">${hotel.location}</p>
                        <p class="text-white/90 text-sm max-w-sm">${hotel.description}</p>
                        <button class="mt-4 px-6 py-2 border border-white text-white text-sm hover:bg-white hover:text-black transition-colors">
                            VIEW PROPERTY
                        </button>
                    </div>
                </div>
            ` : `
                <div class="absolute inset-0 flex items-start p-8">
                    <h2 class="text-white text-2xl font-light">${hotel.name}</h2>
                </div>
            `}
        </div>
    `;

    card.addEventListener('click', () => {
        if (position === 'left') navigateCarousel('prev');
        if (position === 'right') navigateCarousel('next');
    });

    return card;
}

function updateCarousel() {
    carousel.innerHTML = '';
    currentSlide.textContent = currentIndex + 1;
    totalSlides.textContent = hotels.length;

    const positions = ['far-left', 'left', 'center', 'right', 'far-right'];
    positions.forEach((position, i) => {
        let index = currentIndex + (i - 2);
        if (index < 0) index = hotels.length + index;
        if (index >= hotels.length) index = index - hotels.length;
        
        const card = createHotelCard(hotels[index], position);
        carousel.appendChild(card);
    });
}

function navigateCarousel(direction) {
    if (direction === 'next') {
        currentIndex = (currentIndex + 1) % hotels.length;
    } else {
        currentIndex = currentIndex === 0 ? hotels.length - 1 : currentIndex - 1;
    }
    updateCarousel();
}

document.getElementById('prevBtn').addEventListener('click', () => navigateCarousel('prev'));
document.getElementById('nextBtn').addEventListener('click', () => navigateCarousel('next'));

updateCarousel();

document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') navigateCarousel('prev');
    if (e.key === 'ArrowRight') navigateCarousel('next');
});