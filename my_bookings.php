<?php
include "db.php";
$results = null;

// Handle Search
if (isset($_POST['search'])) {
    $email_search = mysqli_real_escape_string($conn, $_POST['email']);
    $results = mysqli_query($conn, "SELECT * FROM booking_tb WHERE email='$email_search'");
}

// Handle Cancellation
if (isset($_GET['cancel_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['cancel_id']);
    // Extra security: ensure only 'Pending' bookings can be deleted
    $delete_query = "DELETE FROM booking_tb WHERE id='$id' AND status='Pending'";
    
    if (mysqli_query($conn, $delete_query)) {
        // Redirect back to clear the URL parameters and show success
        header("Location: my_bookings.php?msg=cancelled");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmCancel() {
            return confirm("Are you sure you want to cancel this booking? This action cannot be undone.");
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Track My Booking</h1>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'cancelled'): ?>
        <p style="color: #d9534f; font-weight: bold; margin-bottom: 15px;">Booking successfully cancelled.</p>
    <?php endif; ?>

    <form method="post" class="booking-search">
        <div class="booking-search-field">
            <label for="booking-email">Email used for the booking</label>
            <input id="booking-email" type="email" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" name="search">View Status</button>
    </form>

    <?php if ($results && mysqli_num_rows($results) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Event Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><?php echo $row['event_type']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo $row['guests']; ?></td>
                    <td><?php echo $row['venue']; ?></td>
                    <td><strong><?php echo $row['status']; ?></strong></td>
                    <td>
                        <?php if($row['status'] == 'Pending'): ?>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-link">Edit My Info</a>
                            <span style="color: #ccc; margin: 0 5px;">|</span>
                            <a href="my_bookings.php?cancel_id=<?php echo $row['id']; ?>" 
                               style="color: #d9534f; text-decoration: none;" 
                               onclick="return confirmCancel()">Cancel My Booking</a>
                        <?php else: ?>
                            <span style="color:gray; font-style: italic;">Locked</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif (isset($_POST['search'])): ?>
        <p style="margin-top: 20px; color: red;">No bookings found for this email.</p>
    <?php endif; ?>
    
    <div style="margin-top: 20px;">
        <a href="index.php">⬅ Back to Home</a>
    </div>
</div>
</body>
</html>