<?php
session_start(); // Start a session to remember the logged-in user aka store the data

include 'database.php'; // Include database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Simple query to check user
    $result = $conn->query("SELECT id, role FROM employees WHERE email = '$email' AND password = '$password'");

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'employee') {
            header("Location: vacation_request.php");
        } elseif ($user['role'] === 'manager') {
            header("Location: manage_requests.php");
        }
        exit;
    } else {
        $error = "Invalid email or password or role is not managerial.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) echo "$error"; ?>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
