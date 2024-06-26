<?php
include 'includes/db.php';

session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addTask'])) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        if (strlen($title) < 3 || strlen($title) > 100) {
            die('Task title must be between 3 and 100 characters.');
        }

        if (strlen($description) > 0 && (strlen($description) < 3 || strlen($description) > 500)) {
            die('Task description must be between 3 and 500 characters.');
        }

        $title = $conn->real_escape_string($title);
        $description = $conn->real_escape_string($description);

        $sql = "INSERT INTO tasks (user_id, title, description) VALUES ('$userid', '$title', '$description')";
        $conn->query($sql);
    }

    if (isset($_POST['deleteTask'])) {
        $taskid = intval($_POST['taskid']);
        $sql = "DELETE FROM tasks WHERE id='$taskid' AND user_id='$userid'";
        $conn->query($sql);
    }

    if (isset($_POST['editTask'])) {
        $taskid = intval($_POST['taskid']);
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        if (strlen($title) < 3 || strlen($title) > 100) {
            die('Task title must be between 3 and 100 characters.');
        }

        if (strlen($description) > 0 && (strlen($description) < 3 || strlen($description) > 500)) {
            die('Task description must be between 3 and 500 characters.');
        }

        $title = $conn->real_escape_string($title);
        $description = $conn->real_escape_string($description);

        $sql = "UPDATE tasks SET title='$title', description='$description' WHERE id='$taskid' AND user_id='$userid'";
        $conn->query($sql);
    }
}

// Fetch tasks
$tasks_sql = "SELECT * FROM tasks WHERE user_id='$userid'";
$tasks = $conn->query($tasks_sql);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h1>Task Dashboard</h1>
    <form action="dashboard.php" method="post" onsubmit="return validateTaskForm()">
        <input type="text" name="title" placeholder="Task Title" required minlength="3" maxlength="100">
        <textarea name="description" placeholder="Task Description" minlength="3" maxlength="500"></textarea>
        <button type="submit" name="addTask">Add Task</button>
    </form>
    <div id="editTaskModal" style="display:none;">
        <form id="editTaskForm" action="dashboard.php" method="post" onsubmit="return validateTaskForm()">
            <input type="hidden" name="taskid" id="editTaskId">
            <input type="text" name="title" id="editTaskTitle" required minlength="3" maxlength="100">
            <textarea name="description" id="editTaskDescription" minlength="3" maxlength="500"></textarea>
            <button type="submit" name="editTask">Update Task</button>
        </form>
    </div>

    <script>
        function validateTaskForm() {
            const title = document.querySelector('[name="title"]').value;
            const description = document.querySelector('[name="description"]').value;

            if (title.length < 3 || title.length > 100) {
                alert('Task title must be between 3 and 100 characters.');
                return false;
            }

            if (description.length > 0 && (description.length < 3 || description.length > 500)) {
                alert('Task description must be between 3 and 500 characters.');
                return false;
            }
            return true;
        }
    </script>

    <ul>
        <?php while ($task = $tasks->fetch_assoc()) { ?>
            <li>
                <h2><?php echo $task['title']; ?></h2>
                <p><?php echo $task['description']; ?></p>
                <form action="dashboard.php" method="post">
                    <input type="hidden" name="taskid" value="<?php echo $task['id']; ?>">
                    <button type="submit" name="deleteTask">Delete</button>
                </form>
            </li>
        <?php } ?>
    </ul>
</body>

</html>