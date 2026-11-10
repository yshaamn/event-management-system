<?php
include "db.php";


if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM booking_tb WHERE id=$id");
$event = mysqli_fetch_assoc($result);

if (isset($_POST['update'])){
   
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);
    $date= mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $guests = mysqli_real_escape_string($conn, $_POST['guests']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);

    
    $sql = "UPDATE booking_tb SET 
            fullname='$fullname', 
            email='$email', 
            contact='$contact', 
            address='$address', 
            event_type='$event_type', 
            date='$date', 
            time='$time', 
            guests='$guests', 
            venue='$venue', 
            status='Pending' 
            WHERE id=$id";

    if (mysqli_query($conn, $sql)){
        
        header("Location: my_bookings.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Event</h1>
        <form action="" method="post">
            <label>Full name</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($event['fullname'] ?? ''); ?>" required>
            
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($event['email'] ?? ''); ?>" required>
            
            <label>Contact</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($event['contact'] ?? ''); ?>" required>  
            
            <label>Address</label>
            <textarea name="address" required><?php echo htmlspecialchars($event['address'] ?? ''); ?></textarea>

            <label>Event Type</label>
            <select name="event_type" required>
                <option <?php if($event['event_type'] == 'Birthday') echo 'selected'; ?>>Birthday</option>
                <option <?php if($event['event_type'] == 'Wedding') echo 'selected'; ?>>Wedding</option>
                <option <?php if($event['event_type'] == 'Debut') echo 'selected'; ?>>Debut</option>
                <option <?php if($event['event_type'] == 'Christening') echo 'selected'; ?>>Christening</option>
                <option <?php if($event['event_type'] == 'Corporate Meeting') echo 'selected'; ?>>Corporate Meeting</option>
                <option <?php if($event['event_type'] == 'Reunion') echo 'selected'; ?>>Reunion</option>
                <option <?php if($event['event_type'] == 'Seminar') echo 'selected'; ?>>Seminar</option>
                <option <?php if($event['event_type'] == 'Others') echo 'selected'; ?>>Others</option>
            </select>

            <label>Date</label>
            <input type="date" name="date" value="<?php echo $event['date'] ?? ''; ?>" required>

            <label>Time</label>
            <input type="time" name="time" value="<?php echo $event['time'] ?? ''; ?>" required>

            <label>Guests</label>
            <input type="text" name="guests" value="<?php echo htmlspecialchars($event['guests'] ?? ''); ?>" required>
            
            <label>Venue</label>
            <input type="text" name="venue" value="<?php echo htmlspecialchars($event['venue'] ?? ''); ?>" required>  
            
            <button type="submit" name="update">Update Booking</button>
            <a href="my_bookings.php" style="display:block; text-align:center; margin-top:10px;">Cancel</a>
        </form>
    </div>
</body>
</html>