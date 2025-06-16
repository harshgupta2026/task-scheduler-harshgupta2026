<?php include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        addTask($_POST['task']);
    }
    if (isset($_POST['mark_complete'])) {
        markTaskAsCompleted($_POST['task_id'], true);
    }
    if (isset($_POST['delete'])) {
        deleteTask($_POST['task_id']);
    }
    if (isset($_POST['email'])) {
        subscribeEmail($_POST['email']);
    }
}

$tasks = getAllTasks(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Scheduler</title>
</head>
<body>
<h2>Task List</h2>
<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <?= htmlspecialchars($task['name']) ?> 
        <?= $task['completed'] ? "(Done)" : "" ?>
        <form method="POST" style="display:inline">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <button name="mark_complete">Mark as Complete</button>
            <button name="delete">Delete</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<h2>Add Task</h2>
<form method="POST">
    <input name="task" required>
    <button>Add</button>
</form>

<h2>Subscribe</h2>
<form method="POST">
    <input name="email" type="email" required>
    <button>Subscribe</button>
</form>
</body>
</html>


// Dummy update by Harsh for final PR