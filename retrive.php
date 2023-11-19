<?php
// Assuming you have the database connection code in a separate file (dbConnect.php)

$servername = "localhost";
$username = "root";
$password = "2626#26Vsl";
$dbname = "pay_txn_book";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["submit"])) {
    $txnId = $_POST["txnId"];

    // Prepare and execute the query to retrieve the booking details
    $sql = "SELECT * FROM user_bookings WHERE token = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $txnId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if any rows are returned
        if (mysqli_num_rows($result) > 0) {
            // Output the booking details in a table
            echo "<h2>Booking Details for Transaction ID: $txnId</h2>";
            echo "<div style='display: flex;'>"; // Use CSS Flexbox for the layout

            // Left column for headings
            echo "<div style='flex: 1;'>";
            echo "<table>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>Name:</td><td>".$row['name']."</td></tr>";
                echo "<tr><td>Transaction ID:</td><td>".$row['token']."</td></tr>";
                echo "<tr><td>Mobile Number:</td><td>".$row['number']."</td></tr>";
                echo "<tr><td>Slot:</td><td>".$row['slot']."</td></tr>";
                echo "<tr><td>Time:</td><td>".$row['time']."</td></tr>";
                echo "<tr><td>City:</td><td>".$row['city']."</td></tr>";
                echo "<tr><td>Pin Code:</td><td>".$row['pincode']."</td></tr>";
                echo "<tr><td>Address Line 1:</td><td>".$row['AddressLine1']."</td></tr>";
                echo "<tr><td>Address Line 2:</td><td>".$row['AddressLine2']."</td></tr>";
                echo "<tr><td>Service:</td><td>".$row['service']."</td></tr>";
                echo "<tr><td>Issue:</td><td>".$row['issue']."</td></tr>";
            }
            echo "</table>";
            echo "</div>";

            echo "</div>";
        } else {
            echo "No booking found for the given Transaction ID.";
        }
    } else {
        echo "Something went wrong.";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link rel="shortcut icon" type="image/jpg" href="assets/NavLogo.jpeg" class="rounded-circle">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
    body {
        background-color: black;
        color: white;
        padding-top: 100px; /* Add padding to the top to prevent the navbar from covering the form */
    }

    table {
        color: white; /* Set the text color for the table elements to white */
    }

    .stripe-button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .stripe-button:hover {
        background-color: #0056b3;
    }
</style>

</head>
<body >
     <!-- Navbar -->
     <nav class="navbar navbar-expand-md fixed-top" style="">
     <div class="container my-3 mx-3">
       <a class="nav-link align-items-left mr-auto" href="" style="color: rgb(255, 73, 73); font-size: x-large; font-weight: 600; line-height: 0.5cm;">
        Leisure <br> computers|
       </a>
       <a class="btn btn-outline-warning btn-large rounded-pill "
       href="https://leisuretech.pages.dev" style="font-size: large; font-weight: 500; ml-1">Home
    </a>
      </div>
    </div>
  </nav>
  <br>
  <a></a>
    <h1 class="container my-3 mx-3">View you Booking Details</h1>
    
    <form method="post" class="container my-3 mx-3">
        <label for="txnId">Enter Transaction ID:</label>
        <input type="text" id="txnId" name="txnId" required autocomplete="off">
        <button type="submit" name="submit">View</button>
        <br>
        
    </form>
</body>
</html>
