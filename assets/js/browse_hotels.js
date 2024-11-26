import checkInternetConnection from '../../utils/checkInternetConnection.js';

function handleOffline() {
    alert("No internet connection. Redirecting to offline page...");
    window.location.href = "../no_internet.html"; 
}

function handleOnline() {
    console.log("Back online!");
}

checkInternetConnection(handleOffline, handleOnline);

document.addEventListener("DOMContentLoaded", () => {
    const carousel = document.getElementById("carousel");
    let hotels = [];
    let currentIndex = 0;
  
    // Fetch hotels from the server
    fetch("../actions/getHotels.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          hotels = data.hotels;
          updateCarousel();
        } else {
          carousel.innerHTML = `<p class="text-red-500">${data.message}</p>`;
        }
      })
      .catch((error) => {
        carousel.innerHTML = `<p class="text-red-500">Error loading hotels: ${error.message}</p>`;
      });
  
    function createHotelCard(hotel, position) {
      const card = document.createElement("div");
      card.className = `
        absolute transform transition-all duration-500 ease-in-out cursor-pointer
        w-[400px] h-[600px] border border-white/20
        ${position === "center" ? "z-30 scale-100 opacity-100 translate-x-0" : ""}
        ${position === "left" ? "z-20 scale-95 opacity-70 -translate-x-[420px]" : ""}
        ${position === "right" ? "z-20 scale-95 opacity-70 translate-x-[420px]" : ""}
        ${position === "far-left" ? "z-10 scale-90 opacity-40 -translate-x-[840px]" : ""}
        ${position === "far-right" ? "z-10 scale-90 opacity-40 translate-x-[840px]" : ""}
      `;
  
      const isCenter = position === "center";
  
      card.innerHTML = `
        <div class="relative w-full h-full overflow-hidden">
          ${isCenter ? `
            <img src="${hotel.image}" alt="${hotel.name}" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
              <div class="space-y-4">
                <h2 class="text-white text-2xl font-light">${hotel.hotel_name}</h2>
                <p class="text-white/80 text-sm">${hotel.location}</p>
                <p class="text-white/90 text-sm max-w-sm">${hotel.description}</p>
                <button class="mt-4 px-6 py-2 border border-white text-white text-sm hover:bg-white hover:text-black transition-colors">
                  VIEW PROPERTY
                </button>
              </div>
            </div>
          ` : `
            <div class="absolute inset-0 flex items-start p-8">
              <h2 class="text-white text-2xl font-light">${hotel.hotel_name}</h2>
            </div>
          `}
        </div>
      `;
  
      card.addEventListener("click", () => {
        if (position === "left") navigateCarousel("prev");
        if (position === "right") navigateCarousel("next");
      });
  
      return card;
    }
  
    function updateCarousel() {
      carousel.innerHTML = "";
      const totalSlides = document.getElementById("total-slides");
      const currentSlide = document.getElementById("current-slide");
  
      currentSlide.textContent = currentIndex + 1;
      totalSlides.textContent = hotels.length;
  
      const positions = ["far-left", "left", "center", "right", "far-right"];
      positions.forEach((position, i) => {
        let index = currentIndex + (i - 2);
        if (index < 0) index = hotels.length + index;
        if (index >= hotels.length) index -= hotels.length;
  
        const card = createHotelCard(hotels[index], position);
        carousel.appendChild(card);
      });
    }
  
    function navigateCarousel(direction) {
      if (direction === "next") {
        currentIndex = (currentIndex + 1) % hotels.length;
      } else {
        currentIndex = currentIndex === 0 ? hotels.length - 1 : currentIndex - 1;
      }
      updateCarousel();
    }
  
    document.getElementById("prevBtn").addEventListener("click", () => navigateCarousel("prev"));
    document.getElementById("nextBtn").addEventListener("click", () => navigateCarousel("next"));
  
    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") navigateCarousel("prev");
      if (e.key === "ArrowRight") navigateCarousel("next");
    });
  });
  
document.addEventListener("DOMContentLoaded", () => {
    const carousel = document.getElementById("carousel");
    let hotels = [];
    let currentIndex = 0;
  
    // Fetch hotels from the server
    fetch("../actions/getHotels.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          hotels = data.hotels;
          updateCarousel();
        } else {
          carousel.innerHTML = `<p class="text-red-500">${data.message}</p>`;
        }
      })
      .catch((error) => {
        carousel.innerHTML = `<p class="text-red-500">Error loading hotels: ${error.message}</p>`;
      });
  
    function createHotelCard(hotel, position) {
      const card = document.createElement("div");
      card.className = `
        absolute transform transition-all duration-500 ease-in-out cursor-pointer
        w-[400px] h-[600px] border border-white/20
        ${position === "center" ? "z-30 scale-100 opacity-100 translate-x-0" : ""}
        ${position === "left" ? "z-20 scale-95 opacity-70 -translate-x-[420px]" : ""}
        ${position === "right" ? "z-20 scale-95 opacity-70 translate-x-[420px]" : ""}
        ${position === "far-left" ? "z-10 scale-90 opacity-40 -translate-x-[840px]" : ""}
        ${position === "far-right" ? "z-10 scale-90 opacity-40 translate-x-[840px]" : ""}
      `;
  
      const isCenter = position === "center";
  
      card.innerHTML = `
        <div class="relative w-full h-full overflow-hidden">
          ${isCenter ? `
            <img src="${hotel.image}" alt="${hotel.name}" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
              <div class="space-y-4">
                <h2 class="text-white text-2xl font-light">${hotel.hotel_name}</h2>
                <p class="text-white/80 text-sm">${hotel.location}</p>
                <p class="text-white/90 text-sm max-w-sm">${hotel.description}</p>
                <button class="mt-4 px-6 py-2 border border-white text-white text-sm hover:bg-white hover:text-black transition-colors">
                  VIEW PROPERTY
                </button>
              </div>
            </div>
          ` : `
            <div class="absolute inset-0 flex items-start p-8">
              <h2 class="text-white text-2xl font-light">${hotel.hotel_name}</h2>
            </div>
          `}
        </div>
      `;
  
      card.addEventListener("click", () => {
        if (position === "left") navigateCarousel("prev");
        if (position === "right") navigateCarousel("next");
      });
  
      return card;
    }
  
    function updateCarousel() {
      carousel.innerHTML = "";
      const totalSlides = document.getElementById("total-slides");
      const currentSlide = document.getElementById("current-slide");
  
      currentSlide.textContent = currentIndex + 1;
      totalSlides.textContent = hotels.length;
  
      const positions = ["far-left", "left", "center", "right", "far-right"];
      positions.forEach((position, i) => {
        let index = currentIndex + (i - 2);
        if (index < 0) index = hotels.length + index;
        if (index >= hotels.length) index -= hotels.length;
  
        const card = createHotelCard(hotels[index], position);
        carousel.appendChild(card);
      });
    }
  
    function navigateCarousel(direction) {
      if (direction === "next") {
        currentIndex = (currentIndex + 1) % hotels.length;
      } else {
        currentIndex = currentIndex === 0 ? hotels.length - 1 : currentIndex - 1;
      }
      updateCarousel();
    }
  
    document.getElementById("prevBtn").addEventListener("click", () => navigateCarousel("prev"));
    document.getElementById("nextBtn").addEventListener("click", () => navigateCarousel("next"));
  
    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") navigateCarousel("prev");
      if (e.key === "ArrowRight") navigateCarousel("next");
    });
  });
  
