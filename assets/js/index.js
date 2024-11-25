import checkInternetConnection from '../../utils/checkInternetConnection.js';

// Service Worker Registration
if ('serviceWorker' in navigator) {
    navigator.serviceWorker
        .register('/sw.js') // Ensure sw.js is in the root directory
        .then(registration => {
            console.log('Service Worker registered with scope:', registration.scope);
        })
        .catch(error => {
            console.error('Service Worker registration failed:', error);
        });
}

function handleOffline() {
    alert("No internet connection. Redirecting to offline page...");
    window.location.href = "../no_internet.html"; 
}

function handleOnline() {
    console.log("Back online!");
}

checkInternetConnection(handleOffline, handleOnline);

document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    } else {
        console.error('Lucide is not defined.');
    }

    const destinations = [
        // Your destinations array
    ];

    const slidesContainer = document.getElementById('slides-container');
    const indicatorsContainer = document.getElementById('indicators');

    if (!slidesContainer || !indicatorsContainer) {
        console.error("Required elements (slides-container or indicators) not found in DOM.");
        return;
    }

    let currentSlide = 0;

    destinations.forEach((destination, index) => {
        const slide = document.createElement('div');
        slide.className = 'w-full flex-shrink-0 relative';
        slide.innerHTML = `
            <img src="${destination.image}" alt="${destination.name}" class="w-full h-[200px] object-cover"> 
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
                <div class="absolute bottom-0 left-0 right-0 p-5"> 
                    <div class="max-w-3xl">
                        <h3 class="text-lg font-light mb-2">${destination.name}</h3>
                        <h4 class="text-3xl font-serif mb-3">${destination.title}</h4>
                        <p class="text-lg mb-4 opacity-90">${destination.description}</p>
                        <div class="flex flex-wrap gap-2">
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

        const indicator = document.createElement('div');
        indicator.className = `h-1 rounded-full bg-white bg-opacity-50 transition-all duration-300 cursor-pointer indicator ${index === 0 ? 'active' : 'w-6'}`;
        indicatorsContainer.appendChild(indicator);
    });

    indicatorsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('indicator')) {
            const index = Array.from(indicatorsContainer.children).indexOf(e.target);
            goToSlide(index);
        }
    });

    function updateSlidePosition() {
        slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
        Array.from(indicatorsContainer.children).forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
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
});