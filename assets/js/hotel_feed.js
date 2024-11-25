import checkInternetConnection from '../../utils/checkInternetConnection.js';

function handleOffline() {
    alert("No internet connection. Redirecting to offline page...");
    window.location.href = "/no_internet.html"; 
}

function handleOnline() {
    console.log("Back online!");
}

checkInternetConnection(handleOffline, handleOnline);

const hotels = [
    {
      id: 1,
      name: "HU AT KO OLINA",
      location: "Ko Olina, Hawaii",
      image: "../assets/images/bilderboken-rlwE8f8anOc-unsplash.jpg",
      description: "Experience luxury in the heart of Hawaii",
    },
    {
      id: 2,
      name: "ANGUILLA",
      location: "Caribbean Islands",
      image: "../assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg",
      description: "Paradise found in the Caribbean",
    },
    {
      id: 3,
      name: "THE OCEAN CLUB",
      location: "Bahamas",
      image: "../assets/images/sara-dubler-Koei_7yYtIo-unsplash.jpg",
      description:
        "Connect with true Bahamian beauty and enjoy remarkable seclusion at this legendary Caribbean hideaway.",
    },
    {
      id: 4,
      name: "BORA BORA",
      location: "French Polynesia",
      image: "../assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg",
      description: "Escape to the pearl of the Pacific",
    },
    {
      id: 5,
      name: "COSTA RICA PENINSULA",
      location: "Papagayo, Costa Rica",
      image: "../assets/images/bilderboken-rlwE8f8anOc-unsplash.jpg",
      description: "Where luxury meets nature",
    },
  ];

  let currentIndex = 0;
  const carousel = document.getElementById("carousel");
  const currentSlide = document.getElementById("current-slide");
  const totalSlides = document.getElementById("total-slides");

  function createHotelCard(hotel, position) {
    const card = document.createElement("div");
    card.className = `hotel-card absolute bg-white rounded-lg shadow-xl ${position} w-[300px] md:w-[400px]`;

    card.innerHTML = `
            <div class="relative h-[400px] md:h-[500px] overflow-hidden rounded-lg">
                <img src="${hotel.image}" alt="${hotel.name}" class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/50 to-transparent p-6">
                    <div class="space-y-2">
                        <h2 class="text-white text-xl md:text-2xl font-light">${hotel.name}</h2>
                        <p class="text-white text-sm md:text-base">${hotel.location}</p>
                        <p class="text-white text-xs md:text-sm">${hotel.description}</p>
                        <button class="mt-4 px-4 py-2 border border-white text-white hover:bg-white hover:text-black transition-colors text-sm md:text-base">
                            VIEW PROPERTY
                        </button>
                    </div>
                </div>
            </div>
        `;

    return card;
  }

  function updateCarousel() {
    carousel.innerHTML = "";
    currentSlide.textContent = currentIndex + 1;
    totalSlides.textContent = hotels.length;

    const positions = ["far-left", "left", "center", "right", "far-right"];
    positions.forEach((position, i) => {
      let index = currentIndex + (i - 2);
      if (index < 0) index = hotels.length + index;
      if (index >= hotels.length) index = index - hotels.length;

      const card = createHotelCard(hotels[index], position);
      carousel.appendChild(card);
    });
  }

  function navigateCarousel(direction) {
    if (direction === "next") {
      currentIndex = (currentIndex + 1) % hotels.length;
    } else {
      currentIndex =
        currentIndex === 0 ? hotels.length - 1 : currentIndex - 1;
    }
    updateCarousel();
  }
  
  document
    .getElementById("prevBtn")
    .addEventListener("click", () => navigateCarousel("prev"));
  document
    .getElementById("nextBtn")
    .addEventListener("click", () => navigateCarousel("next"));

  document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") navigateCarousel("prev");
    if (e.key === "ArrowRight") navigateCarousel("next");
  });

  updateCarousel();

  const articles = [
    {
      image: "../assets/images/pexels-amar-saleem-15661-70441.jpg",
      title: "Highly-anticipated Luxury Hotel Openings for 2025",
      description:
        "A preview of the highly-anticipated luxury hotel openings for 2025",
      url: "https://www.luxurytravelmagazine.com/news-articles/highly-anticipated-luxury-hotel-openings-for-2025",
    },
    {
      image: "../assets/images/pexels-chetanvlad-2957461.jpg",
      title: "Park Hyatt & Aman will debut in Cabo in 2025 ",
      description:
        "Los Cabos has long been a favorite destination for U.S. travelers and the region continues to experience remarkable growth in both hospitality and real estate.",
      url: "https://www.luxurytravelmagazine.com/news-articles/park-hyatt-aman-will-debut-in-cabo-in-2025",
    },
    {
      image: "../assets/images/pexels-ericazhao-2670273.jpg",
      title:
        "Regent Santa Monica Beach Now Open Marking The Legendary Brand's Return To The Americas",
      description:
        "The legendary Regent brand returns to the shores of the United States with the opening of Regent Santa Monica Beach.",
      url: "https://www.luxurytravelmagazine.com/news-articles/regent-santa-monica-beach-now-open",
    },
    {
      image: "../assets/images/pexels-rickyrecap-1771832.jpg",
      title: "Grand Hyatt Scottsdale Resort Debuts $115 Million Renovation",
      description:
        "Hyatt Hotels Corporation announces Grand Hyatt Scottsdale Resort has completed its highly anticipated $115 million property-wide renovation.",
      url: "https://www.luxurytravelmagazine.com/news-articles/grand-hyatt-scottsdale-resort-debuts-115-million-renovation",
    },
    {
      image: "../assets/images/pexels-soulful-pizza-2080276-3914755.jpg",
      title:
        "The Sira, New Luxury Resort to Open in Lombok, Indonesia Nov. 2024",
      description:
        "The Sira, a Luxury Collection Resort & Spa, Lombok is slated to open in November 2024 in North Lombok, Indonesia between the awe-inspiring Mount Rinjani volcano and the idyllic Gili Islands.",
      url: "https://www.luxurytravelmagazine.com/news-articles/the-sira-new-luxury-resort-to-open-in-lombok-indonesia-nov-2024",
    },
    {
      image: "../assets/images/pexels-pavel-danilyuk-9119733.jpg",
      title:
        "Sun Lodge Opens in the Heart of Southern Vermont as an All-Season Destination",
      description:
        "Saltaire Hotels announces its latest opening, Sun Lodge. Located in Peru, VT, 15 minutes Northeast of the Manchester area, the newly opened Sun Lodge invites travelers to experience comfortable lodge-style living at the foot of Bromley Mountain.",
      url: "https://www.luxurytravelmagazine.com/news-articles/sun-lodge-opens-in-the-heart-of-southern-vermont-as-an-all-season-destination",
    },
  ];

  function createArticleCard(article) {
    const card = document.createElement("div");
    card.classList.add("bg-white", "rounded-lg", "shadow-lg", "p-4");

    card.innerHTML = `
        <img src="${article.image}" alt="Article Image" class="w-full h-48 object-cover ">
        <h3 class="text-xl font-bold mt-2">${article.title}</h3>
        <p class="text-gray-600 mt-2">${article.description}</p>
        <a href="${article.url}" class="block mt-4 text-blue-500 hover:underline">Read More</a>
      `;

    return card;
  }

  const articleGrid = document.getElementById("article-grid");
  articles.forEach((article) => {
    articleGrid.appendChild(createArticleCard(article));
  });

  const profileBtn = document.getElementById("profile-btn");
  const profileModal = document.getElementById("profile-modal");
  const closeModal = document.getElementById("close-modal");

  let isModalOpen = false; // Track modal state

  profileBtn.addEventListener("click", () => {
    if (isModalOpen) {
      profileModal.classList.add("hidden"); // Close modal
    } else {
      profileModal.classList.remove("hidden"); // Open modal
    }
    isModalOpen = !isModalOpen; // Toggle modal state
  });
  
  closeModal.addEventListener("click", () => {
    profileModal.classList.add("hidden"); // Close modal
    isModalOpen = false; // Reset modal state
  });
  