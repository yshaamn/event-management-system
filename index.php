<?php
include "db.php";

$message = "";
if (isset($_POST['save'])) {
    
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $guests = mysqli_real_escape_string($conn, $_POST['guests']);    
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);  

    $sql = "INSERT INTO booking_tb (fullname, contact, email, address, event_type, date, time, guests, venue) 
            VALUES ('$fullname', '$contact', '$email', '$address', '$event_type', '$date', '$time', '$guests', '$venue')";
    
    if(mysqli_query($conn, $sql)) {
        $message = "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Popup Confirmation -->
    <div id="popup" class="popup" style="display: <?php echo ($message == 'success') ? 'flex' : 'none'; ?>;">
        <div class="popup-box">
            <h2>✨ Booking Sent!</h2>
            <p>Thank you for your reservation.</p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <div class="petals">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div> 

    <div class="container booking-container">
        <h1> Event Booking </h1>
        <form action="index.php" method="post">
            <div class="form-section client-information">
                <h2>Client Information</h2>

                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input id="fullname" type="text" name="fullname" required>
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number / WhatsApp</label>
                    <input id="contact" type="text" name="contact" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="address">Address / Location</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
            </div>

            <div class="form-section">
                <h2>Event Information</h2>

                <div class="form-group">
                    <label for="event_type">Type of Event</label>
                    <select id="event_type" name="event_type" required>
                        <option value="">-- Select Event --</option>
                        <option>Birthday</option>
                        <option>Wedding</option>
                        <option>Debut</option>
                        <option>Christening</option>
                        <option>Corporate Meeting</option>
                        <option>Reunion</option>
                        <option>Seminar</option>
                        <option>Others</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Preferred Date</label>
                    <input id="date" type="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="time">Preferred Time</label>
                    <input id="time" type="time" name="time" required>
                </div>

                <div class="form-group">
                    <label for="guests">Expected Number of Guests</label>
                    <input id="guests" type="text" name="guests" placeholder="e.g. 50 pax" required>
                </div>

                <div class="form-group">
                    <label for="venue">Type Of Venue</label>
                    <select id="venue" name="venue" required>
                        <option value="">-- Select Venue --</option>
                        <option>Banquet Hall</option>
                        <option>Pool Area</option>
                        <option>Conference Center</option>
                        <option>Rooftop Area</option>
                        <option>Park & Garden</option>
                        <option>Community Center</option>
                        <option>Others</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="save">Submit Booking</button>
        </form>

<div class="view-container">
    <a href="my_bookings.php" class="view-btn">🔍 Track My Booking</a>
    <a href="login.php" class="view-btn">🔐 Admin Login</a>
</div>

</div>
    </div>

    <script>
    function closePopup(){
        document.getElementById("popup").style.display = "none";
    }
    </script>
</body>
</html>
