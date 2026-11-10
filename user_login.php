<?php
session_start();
require_once 'db.php';

$error = '';

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
        $identity = trim((string) ($_POST['identity'] ?? ''));
        $password = $_POST['password'] ?? '';

        if ($identity === '' || !is_string($password) || $password === '') {
            $error = 'Enter your username or email and password.';
        } else {
            $statement = mysqli_prepare($conn, 'SELECT id, fullname, email, password_hash FROM registered_users WHERE username = ? OR email = ? LIMIT 1');
            mysqli_stmt_bind_param($statement, 'ss', $identity, $identity);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $user_id, $fullname, $email, $password_hash);

            if (mysqli_stmt_fetch($statement) && password_verify($password, $password_hash)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $fullname;
                $_SESSION['user_email'] = $email;
                mysqli_stmt_close($statement);
                header('Location: my_bookings.php');
                exit();
            }

            mysqli_stmt_close($statement);
            $error = 'Invalid username/email or password.';
        }
    }
} catch (mysqli_sql_exception $exception) {
    $error = 'User login is currently unavailable. Please try again later.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>User Login</h1>

    <?php if ($error !== '') { ?>
        <p role="alert" style="color: #b00020; text-align: center;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>

    <form method="post" action="user_login.php">
        <label for="identity">Username or Email</label>
        <input id="identity" type="text" name="identity" autocomplete="username" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" autocomplete="current-password" required>

        <button type="submit">Login</button>
    </form>

    <p style="text-align: center; margin-bottom: 0;">New user? <a href="register.php">Register here</a></p>
</div>
</body>
</html>