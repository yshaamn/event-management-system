<?php
require_once 'db.php';

$error = '';
$success = '';

try {
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS registered_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = trim((string) ($_POST['fullname'] ?? ''));
        $username = trim((string) ($_POST['username'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (!is_string($password) || !is_string($confirm_password)) {
            $error = 'Invalid form submission.';
        } elseif ($fullname === '' || $username === '' || $email === '') {
            $error = 'Please complete all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
            $check = mysqli_prepare($conn, 'SELECT id FROM registered_users WHERE username = ? OR email = ? LIMIT 1');
            mysqli_stmt_bind_param($check, 'ss', $username, $email);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) > 0) {
                $error = 'That username or email is already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = mysqli_prepare($conn, 'INSERT INTO registered_users (fullname, username, email, password_hash) VALUES (?, ?, ?, ?)');
                mysqli_stmt_bind_param($insert, 'ssss', $fullname, $username, $email, $hash);
                mysqli_stmt_execute($insert);
                $success = 'Registration successful.';
            }

            mysqli_stmt_close($check);
            if (isset($insert)) {
                mysqli_stmt_close($insert);
            }
        }
    }
} catch (mysqli_sql_exception $exception) {
    $error = 'Registration is currently unavailable. Please try again later.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Create Account</h1>

    <?php if ($error !== '') { ?>
        <p role="alert" style="color: #b00020; text-align: center;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>

    <?php if ($success !== '') { ?>
        <p role="status" style="color: #26734d; text-align: center;"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>

    <form method="post" action="register.php">
        <label for="fullname">Full Name</label>
        <input id="fullname" type="text" name="fullname" maxlength="100" autocomplete="name" required>

        <label for="username">Username</label>
        <input id="username" type="text" name="username" maxlength="50" autocomplete="username" required>

        <label for="email">Email Address</label>
        <input id="email" type="email" name="email" maxlength="255" autocomplete="email" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" required>

        <label for="confirm_password">Confirm Password</label>
        <input id="confirm_password" type="password" name="confirm_password" minlength="8" autocomplete="new-password" required>

        <button type="submit">Register</button>
    </form>

    <p style="text-align: center; margin-bottom: 0;">Already have an account? <a href="user_login.php">User login</a></p>
</div>
</body>
</html>