<?php
session_start();
include "db.php";


if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}


if (isset($_GET['action'], $_GET['id'])) {
    $actions = [
        'accept' => 'Accepted',
        'approve' => 'Accepted',
        'decline' => 'Declined',
    ];
    $action = $_GET['action'];
    $id = is_scalar($_GET['id']) ? filter_var((string) $_GET['id'], FILTER_VALIDATE_INT) : false;

    if (is_string($action) && $id !== false && isset($actions[$action])) {
        $status = $actions[$action];
        $statement = mysqli_prepare($conn, "UPDATE booking_tb SET status=? WHERE id=? AND status='Pending'");
        mysqli_stmt_bind_param($statement, 'si', $status, $id);
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
    }

    header("Location: view.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM booking_tb");
?>

<!DOCTYPE html>
<html> 
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page">
    <main class="admin-dashboard">
        <header class="admin-dashboard-header">
            <h1>Admin Management</h1>
            <a class="admin-logout" href="logout.php">Logout</a>
        </header>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th>Manage</th>
                </tr>
                <?php while ($row = mysqli_fetch_array($result)){
                    $statusLabel = $row['status'] === 'Approved' ? 'Accepted' : $row['status'];
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['event_type']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo $row['venue']; ?></td>
                    <td><?php echo $row['guests']; ?></td>
                    <td><b><?php echo htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></b></td>
                    <td>
                        <?php if ($row['status'] === 'Pending') { ?>
                        <a class="admin-action admin-action-accept" href="view.php?action=accept&amp;id=<?php echo (int) $row['id']; ?>">Accept</a>
                        <a class="admin-action admin-action-decline" href="view.php?action=decline&amp;id=<?php echo (int) $row['id']; ?>">Decline</a>
                        <?php } else { ?>
                        <span class="admin-decision-recorded">Decision recorded</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </main>
</body>
</html>