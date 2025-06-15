<?php
include 'functions.php';
$subs = getSubscribers();
$tasks = array_filter(getAllTasks(), fn($t) => !$t['completed']);

if (!$tasks) return;

$taskList = "<ul>";
foreach ($tasks as $t) {
    $taskList .= "<li>" . htmlspecialchars($t['name']) . "</li>";
}
$taskList .= "</ul>";

foreach ($subs as $email) {
    mail($email, "Pending Tasks Reminder", $taskList, "Content-Type: text/html");
}
?>