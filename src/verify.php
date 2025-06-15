<?php
include 'functions.php';
$email = $_GET['email'] ?? '';
$code = $_GET['code'] ?? '';

if (verifyEmail($email, $code)) {
    echo "Email verified!";
} else {
    echo "Invalid code or email.";
}
?>