//CREATE HOTEL

// JavaScript function to update image previews
function updateImagePreview() {
// Get file input elements
const image1 = document.getElementById('hotelImage1').files[0];
const image2 = document.getElementById('hotelImage2').files[0];
const image3 = document.getElementById('hotelImage3').files[0];

// Create image preview for each file
if (image1) {
    const reader1 = new FileReader();
    reader1.onload = function(e) {
        document.getElementById('previewImage1').src = e.target.result;
    };
    reader1.readAsDataURL(image1);
}

if (image2) {
    const reader2 = new FileReader();
    reader2.onload = function(e) {
        document.getElementById('previewImage2').src = e.target.result;
    };
    reader2.readAsDataURL(image2);
}

if (image3) {
    const reader3 = new FileReader();
    reader3.onload = function(e) {
        document.getElementById('previewImage3').src = e.target.result;
    };
    reader3.readAsDataURL(image3);
}
}


//DASHBOARD
// Sample data for guests
let guests = [

    { id: 1, name: "Emma Johnson", room: "Deluxe Room - 205", status: "Checked In", checkIn: "2024-11-01", checkOut: "2024-11-07", profileImage: "../../assets/images/emily-clark.avif" },
    { id: 2, name: "John Doe", room: "Suite - 302", status: "Checked Out", checkIn: "2024-10-20", checkOut: "2024-10-27", profileImage: "../../assets/images/john-doe.avif" },
    { id: 3, name: "Sarah Connor", room: "Standard Room - 110", status: "Checked In", checkIn: "2024-11-05", checkOut: "2024-11-12", profileImage: "../../assets/images/conner-guest.avif" },
    { id: 4, name: "Emily Clarke", room: "Penthouse Suite", status: "Checked Out", checkIn: "2024-11-05", checkOut: "2024-11-12", profileImage: "../../assets/images/emma-jhonson.avif" }
];

    // Color-coded badge
function getStatusBadge(status) {
    let colorClass = status === "Checked In" ? "bg-green-100 text-green-600" : "bg-red-100 text-red-600";
    if (status === "VIP") colorClass = "bg-yellow-100 text-yellow-600";
    return `<span class="px-2 py-1  ${colorClass}">${status}</span>`;
}

function loadGuests() {
    const guestTableBody = document.getElementById("guestTableBody");
    guestTableBody.innerHTML = "";
    guests.forEach(guest => {
        guestTableBody.innerHTML += `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <img src="${guest.profileImage}" class="w-10 h-10 rounded-full object-cover">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${guest.name}</td>
                <td class="px-6 py-4 whitespace-nowrap">${guest.room}</td>
                <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(guest.status)}</td> 
                <td class="px-6 py-4 whitespace-nowrap">${guest.checkIn}</td>
                <td class="px-6 py-4 whitespace-nowrap">${guest.checkOut}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button onclick="viewGuest(${guest.id})" class="border border-black text-black bg-white font-serif uppercase tracking-widest hover:bg-black hover:text-white transition sm:px-6 sm:py-3">Details</button>
                    <button onclick="deleteGuest(${guest.id})" class="ml-2 sm:px-6 sm:py-3 font-serif uppercase tracking-widest text-white bg-black hover:bg-gray-800">Remove</button>
                </td>
            </tr>`;
    });
}

function viewGuest(id) {
    const guest = guests.find(g => g.id === id);
    if (guest) {
        document.getElementById("guestDetails").innerHTML = `
            <p><strong>Name:</strong> ${guest.name}</p>
            <p><strong>Room:</strong> ${guest.room}</p>
            <p><strong>Status:</strong> ${guest.status}</p>
            <p><strong>Check-in:</strong> ${guest.checkIn}</p>
            <p><strong>Check-out:</strong> ${guest.checkOut}</p>`;
        document.getElementById("guestModal").classList.remove("hidden");
    }
}

function deleteGuest(id) {
    guests = guests.filter(g => g.id !== id);
    loadGuests();
}

function openAddGuestModal() {
document.getElementById("addGuestModal").classList.remove("hidden");
}

function closeAddGuestModal() {
document.getElementById("addGuestModal").classList.add("hidden");
}

function closeGuestModal() {
document.getElementById("guestModal").classList.add("hidden");
}

function addGuest() {
const name = document.getElementById("newGuestName").value;
const room = document.getElementById("newGuestRoom").value;
const checkIn = document.getElementById("newGuestCheckIn").value;
const checkOut = document.getElementById("newGuestCheckOut").value;


if (name && room && checkIn && checkOut) {
    const newGuest = {
        id: guests.length + 1,
        name,
        room,
        status: "Checked In",
        checkIn,
        checkOut
    };
    guests.push(newGuest);
    loadGuests();
    closeAddGuestModal();
} else {
    alert("Please fill in all fields.");
}
}

    // Filter function for search and dropdown
    function filterGuests() {
const searchValue = document.getElementById("searchInput").value.toLowerCase();
const statusValue = document.getElementById("statusFilter").value;
const filteredGuests = guests.filter(guest =>
    (guest.name.toLowerCase().includes(searchValue)) &&
    (statusValue === "" || guest.status === statusValue || (statusValue === "VIP" && guest.status === "Checked In"))
);
loadFilteredGuests(filteredGuests);
}

function loadFilteredGuests(filteredGuests) {
const guestTableBody = document.getElementById("guestTableBody");
guestTableBody.innerHTML = "";
filteredGuests.forEach(guest => {
    guestTableBody.innerHTML += `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap"><img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full"></td>
            <td class="px-6 py-4 whitespace-nowrap">${guest.name}</td>
            <td class="px-6 py-4 whitespace-nowrap">${guest.room}</td>
            <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(guest.status)}</td>
            <td class="px-6 py-4 whitespace-nowrap">${guest.checkIn}</td>
            <td class="px-6 py-4 whitespace-nowrap">${guest.checkOut}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <button onclick="viewGuest(${guest.id})" class="border border-black text-black bg-white font-serif uppercase tracking-widest hover:bg-black hover:text-white transition sm:px-5 sm:py-1.5">Details</button>
                <button onclick="deleteGuest(${guest.id})" class="ml-2 sm:px-5 sm:py-1.5 font-serif uppercase tracking-widest text-white bg-black hover:bg-gray-800">Remove</button>
            </td>
        </tr>`;
});
}


// Initialize guest list on page load
loadGuests();