<?php
include 'includes/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT* FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["userid"] = $row["id"];
            header("Location: dashboard.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <form action="login.php" method="post" onsubmit="return validateLoginForm()">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required minlength="3" maxlength="50">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required minlength="6">
        <button type="submit">Login</button>
    </form>
    <script>
        function validateLoginForm() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (username.length < 3 || username.length > 50) {
                alert('Username must be between 3 and 50 characters.');
                return false;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters.');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>