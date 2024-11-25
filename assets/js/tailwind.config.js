import checkInternetConnection from './checkInternetConnection.js';

function handleOffline() {
    alert("No internet connection. Redirecting to offline page...");
    window.location.href = "/no_internet.html"; // Adjust the path as needed
}

function handleOnline() {
    console.log("Back online!");
}

checkInternetConnection(handleOffline, handleOnline);