<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strlen($username) < 3 || strlen($username) > 50) {
        die('Username must be between 3 and 50 characters.');
    }

    if (strlen($password) < 6) {
        die('Password must be at least 6 characters.');
    }

    $username = $conn->real_escape_string($username);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="">
</head>

<body>
    <form action="register.php" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Register</button>
    </form>
    <script>
        function validateRegisterForm() {
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