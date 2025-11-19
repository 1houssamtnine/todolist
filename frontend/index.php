<?php
include 'db.php';
include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_task'])) {
        $task = trim($_POST['task']);
        if (!empty($task)) {
            addTask($conn, $task);
        }
    } elseif (isset($_POST['delete_task'])) {
        $task_id = $_POST['task_id'];
        deleteTask($conn, $task_id);
    } elseif (isset($_POST['toggle_status'])) {
        $task_id = $_POST['task_id'];
        $status = $_POST['current_status'] == 1 ? 0 : 1;
        updateTaskStatus($conn, $task_id, $status);
    }
    header('Location: index.php');
    exit();
}


$tasks = getTasks($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Todo List</h1>
        
        
        <form method="POST" class="add-task-form">
            <input type="text" name="task" placeholder="Add a new task..." required>
            <button type="submit" name="add_task">Add Task</button>
        </form>

        
        <ul class="task-list">
            <?php if (empty($tasks)): ?>
                <li class="no-tasks">No tasks yet. Add one above!</li>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <li class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>">
                        <span class="task-text"><?php echo htmlspecialchars($task['task']); ?></span>
                        <div class="task-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $task['completed']; ?>">
                                <button type="submit" name="toggle_status" class="status-btn">
                                    <?php echo $task['completed'] ? 'Undo' : 'Complete'; ?>
                                </button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="delete_task" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
