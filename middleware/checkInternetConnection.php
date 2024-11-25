<?php
function checkInternetConnection()
{
    // Simulate internet check
    if (!@fsockopen("www.google.com", 80)) { // Attempt to connect to a common server
        header("Location: ../view/no_internet.php");
        exit;
    }
}
