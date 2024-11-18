const destinations = [
    {
        name: "Paris",
        title: "The Epitome of Luxury",
        description: "Experience unparalleled sophistication in the City of Light",
        highlights: ["Private Louvre Tours", "Michelin-Starred Dining", "Haute Couture"],
        image: "/assets/images/pexels-chetanvlad-2957461 copy.jpg"
    },
    {
        name: "Dubai",
        title: "Ultimate Opulence",
        description: "Where modern luxury reaches new heights",
        highlights: ["7-Star Hotels", "Desert Safaris", "Private Yacht Tours"],
        image: "/assets/images/pexels-amar-saleem-15661-70441.jpg"
    },
    {
        name: "Maldives",
        title: "Private Paradise",
        description: "Exclusive island luxury in pristine waters",
        highlights: ["Overwater Villas", "Personal Butler", "Private Reefs"],
        image: "/assets/images/pexels-chetanvlad-2957461 copy.jpg"
    },
    {
        name: "Swiss Alps",
        title: "Alpine Elegance",
        description: "Mountain luxury at its finest",
        highlights: ["Private Chalets", "Helicopter Tours", "Spa Retreats"],
        image: "/assets/images/pexels-amar-saleem-15661-70441.jpg"
    }
];

let currentSlide = 0;
const slidesContainer = document.getElementById('slides-container');
const indicatorsContainer = document.getElementById('indicators');


destinations.forEach((destination, index) => {
    const slide = document.createElement('div');
    slide.className = 'w-full flex-shrink-0 relative';
    slide.innerHTML = `
        <img src="${destination.image}" alt="${destination.name}" class="w-full h-[200px] object-cover"> 
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
            <div class="absolute bottom-0 left-0 right-0 p-5"> 
                <div class="max-w-3xl">
                    <h3 class="text-lg font-light mb-2">${destination.name}</h3>
                    <h4 class="text-3xl font-serif mb-3">${destination.title}</h4> <!-- Reduced text size -->
                    <p class="text-lg mb-4 opacity-90">${destination.description}</p> <!-- Reduced text size -->
                    <div class="flex flex-wrap gap-2"> <!-- Reduced gap -->
                        ${destination.highlights.map(highlight => 
                            `<span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-sm">
                                ${highlight}
                            </span>`
                        ).join('')}
                    </div>
                </div>
            </div>
        </div>
    `;
    slidesContainer.appendChild(slide);
});


destinations.forEach((_, index) => {
    const indicator = document.createElement('div');
    indicator.className = `h-1 rounded-full bg-white bg-opacity-50 transition-all duration-300 cursor-pointer indicator ${index === 0 ? 'active' : 'w-6'}`;
    indicator.onclick = () => goToSlide(index);
    indicatorsContainer.appendChild(indicator);
});

function updateSlidePosition() {
    slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
    document.querySelectorAll('.indicator').forEach((indicator, index) => {
        if (index === currentSlide) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
            indicator.classList.add('w-6');
        }
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % destinations.length;
    updateSlidePosition();
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + destinations.length) % destinations.length;
    updateSlidePosition();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlidePosition();
}

setInterval(nextSlide, 5000);
updateSlidePosition();

lucide.registerAll();