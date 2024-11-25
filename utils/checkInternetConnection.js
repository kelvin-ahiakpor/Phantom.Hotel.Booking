export default function checkInternetConnection(handleOffline, handleOnline) {
    function updateConnectionStatus() {
        if (!navigator.onLine) {
            handleOffline();
        } else {
            handleOnline();
        }
    }

    // Listen for online and offline events
    window.addEventListener("online", updateConnectionStatus);
    window.addEventListener("offline", updateConnectionStatus);

    // Check the connection status on load
    updateConnectionStatus();
}
