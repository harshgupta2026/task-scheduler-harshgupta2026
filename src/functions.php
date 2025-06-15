<?php
function addTask($task_name) {
    $tasks = getAllTasks();
    $tasks[] = [
        'id' => uniqid(),
        'name' => $task_name,
        'completed' => false
    ];
    file_put_contents(__DIR__ . '/tasks.txt', json_encode($tasks));
}

function getAllTasks() {
    if (!file_exists(__DIR__ . '/tasks.txt')) return [];
    $data = file_get_contents(__DIR__ . '/tasks.txt');
    return json_decode($data, true) ?? [];
}

function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();
    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = $is_completed;
            break;
        }
    }
    file_put_contents(__DIR__ . '/tasks.txt', json_encode($tasks));
}

function deleteTask($task_id) {
    $tasks = getAllTasks();
    $tasks = array_filter($tasks, fn($t) => $t['id'] !== $task_id);
    file_put_contents(__DIR__ . '/tasks.txt', json_encode(array_values($tasks)));
}

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function getSubscribers() {
    if (!file_exists(__DIR__ . '/subscribers.txt')) return [];
    $data = file_get_contents(__DIR__ . '/subscribers.txt');
    return json_decode($data, true) ?? [];
}

function saveSubscribers($subs) {
    file_put_contents(__DIR__ . '/subscribers.txt', json_encode($subs));
}

function getPendingSubscriptions() {
    if (!file_exists(__DIR__ . '/pending_subscriptions.txt')) return [];
    $data = file_get_contents(__DIR__ . '/pending_subscriptions.txt');
    return json_decode($data, true) ?? [];
}

function savePendingSubscriptions($pending) {
    file_put_contents(__DIR__ . '/pending_subscriptions.txt', json_encode($pending));
}

function subscribeEmail($email) {
    $pending = getPendingSubscriptions();
    $code = generateVerificationCode();
    $pending[$email] = ['code' => $code, 'timestamp' => time()];
    savePendingSubscriptions($pending);

    $link = "http://localhost/src/verify.php?email=" . urlencode($email) . "&code=" . $code;
    $message = "<p>Click <a href='$link'>here</a> to verify your subscription. Or use this code: $code</p>";
    mail($email, "Verify Your Subscription", $message, "Content-Type: text/html");
}

function verifyEmail($email, $code) {
    $pending = getPendingSubscriptions();
    if (isset($pending[$email]) && $pending[$email]['code'] === $code) {
        $subs = getSubscribers();
        $subs[] = $email;
        saveSubscribers($subs);
        unset($pending[$email]);
        savePendingSubscriptions($pending);
        return true;
    }
    return false;
}

function unsubscribeEmail($email) {
    $subs = getSubscribers();
    $subs = array_filter($subs, fn($e) => $e !== $email);
    saveSubscribers(array_values($subs));
}
?>