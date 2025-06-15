<?php
include 'functions.php';
$email = $_GET['email'] ?? '';

if ($email) {
    unsubscribeEmail($email);
    echo "Unsubscribed successfully.";
} else {
    echo "No email provided.";
}
?>