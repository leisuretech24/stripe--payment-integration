<?php
session_start();

// Generate CSRF token

if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit();
}
$token = $_SESSION["user"]; // Retrieve email from session variable
// Check for inactivity timeout
$timeout = 1000; // 5 minutes in seconds
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update the last activity tim
$_SESSION['LAST_ACTIVITY'] = time();

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
?>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
    />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
    />
     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <title>Booking</title>
    <link rel="shortcut icon" type="image/jpg" href="assets/NavLogo.jpeg" class="rounded-circle">
    <style>
      .scrollbar::-webkit-scrollbar {
        display: none;
      }
    </style>
  </head>
  <br>
  <body style="background-color: black;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md fixed-top" style="background-color: rgb(0, 0, 0);">
     <div class="container my-auto mx-auto">
      <a class="navbar-brand align-items-center md-1"><img src="assets/NavLogo.jpeg" class="rounded-circle" alt=" sandheld logo"
        style="width: 70px; height: 70px;"></a>
       <a class="nav-link align-items-left mr-auto" href="" style="color: rgb(255, 73, 73); font-size: x-large; font-weight: 600; line-height: 0.5cm;">
        Leisure <br> computers|
       </a>
       <a class="btn btn-outline-warning btn-large rounded-pill "
       href="https://leisuretech.pages.dev" style="font-size: large; font-weight: 500; ml-1">Home
    </a>
      </div>
    </div>
  </nav>
<br><a style="color: rgb(255, 73, 73);"></a>
<br>
<br>
<a>kj</a>
    <div class="container" style="background-color: black;">
        <?php
        if (isset($_POST["submit"])) {
           $fullName = $_POST["fullname"]; //1 N/A //mt 1
           $token = $_POST["token"]; //done mt 2
           $email = $_POST["email"];
           $number = $_POST["number"]; // N/A mt 3
           $slot = $_POST["slot"]; // done mt 4
           $time = $_POST["time"];// done mt 5
           $city = $_POST["city"]; //done mt 6
           $pincode = $_POST["pincode"];// N/A mt 7
           $AddressLine1 = $_POST["AddressLine1"]; // N/A 8
           $AddressLine2 = $_POST["AddressLine2"];// N/A 9
           $service = $_POST["service"]; //done 10
           $issue = $_POST["issue"]; // N/A 11



           $errors = array();
           if (empty($fullName) || empty($token) || empty($number) || empty($slot) || empty($city) || empty($time) || empty($pincode) || empty($AddressLine1) || empty($service) || empty($issue) || empty($AddressLine2) || empty($email)) {
            array_push($errors, "All fields are required");
           }
           if (strlen($number) < 10) {
            array_push($errors, "Enter your 10 digit mobile number!");
           }
           // Only allow specific fruits (apple and mango)
           $allowedToken = array($_SESSION['user']);
           if (!in_array($token, $allowedToken)) {
            array_push($errors, "Invalid Transaction ID. Please enter valid Transaction ID.");
           }
           $allowedCity = array("vizag");
           if (!in_array($city, $allowedCity)) {
            array_push($errors, "Sorry service not available in your city");
           }
           $allowedService = array("cleaning", "repair", "build");
           if (!in_array($service, $allowedService)) {
            array_push($errors, "Sorry invalid service ");
           }
           $allowedTime = array("4PM", "10AM");
           if (!in_array($time, $allowedTime)) {
            array_push($errors, "Sorry invalid time slot ");
           }
           $allowedSlot = array("SEP24", "SEP23", "SEP17", "SEP16", "SEP10", "SEP9", "SEP3", "SEP2", "AUG27", "AUG26", "AUG20", "AUG19", "AUG13", "AUG12", "AUG6", "AUG5");
           if (!in_array($slot, $allowedSlot)) {
            array_push($errors, "Sorry invalid slot ");
           }
           require_once "dbConnect.php";
           $sql = "SELECT * FROM user_bookings WHERE slot = '$slot'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount > 0) {
            array_push($errors, "Sorry, selected slot unavailable!");
           }
           require_once "dbConnect.php";
           $sql = "SELECT * FROM user_bookings WHERE token = '$token'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount > 0) {
            array_push($errors, "Sorry, this Token is already used!");
              // Logout the user
              session_unset();
              session_destroy();
              header("Location: login.php");
              exit();
           }
           if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           } else {  
            $sql = "INSERT INTO user_bookings (name, token, email, number, slot, city, time, pincode, AddressLine1, AddressLine2, service, issue ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
$prepareStmt = mysqli_stmt_prepare($stmt, $sql);
if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt, "ssssssssssss", $fullName, $token, $email, $number, $slot, $city, $time, $pincode, $AddressLine1, $AddressLine2, $service, $issue);
    mysqli_stmt_execute($stmt);
    echo "<div class='alert alert-success'>You are registered successfully.</div>";
    // Logout the user
    session_unset();
    session_destroy();
    header("Location: https://leisuretech.pages.dev/Payment");
    exit();
} else {
    die("Something went wrong");
}

           }
        }
        ?>
<!--Form start-->
        <form action="bookingsite.php" method="post">

            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:" required>
            </div>
            <div class="form-group" style="color: azure;">Transaction ID:
                <input type="text" class="form-control" name="token" placeholder="token:" value="<?php echo $token; ?>" readonly>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="E-mail:*" required>
            </div>
            <div class="form-group">
              <input type="number" class="form-control" name="number" placeholder="Mobile Number:" required>
            </div>
            <a style="color: azure;"> Address</a>
            <div class="form-group">
              <select type="text" class="form-control" name="city" placeholder="city:" required>
              <option value="">Select City:</option>
              <option value="vizag">Visakhapatnam</option>
              </select>
            </div>
            <a></a>
            <div class="form-group">
              <input type="number" class="form-control" name="pincode" placeholder="Pin Code*:" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="AddressLine1" placeholder="Address Line* : D.No, Area, City, State, LandMark" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="AddressLine2" placeholder="Address Line 2 :">
            </div>
            <a style="color: azure;">Issue</a>
            <div class="form-group">
              <select type="text" class="form-control" name="service" placeholder="service:" required>
              <option value="">Select Type of service:</option>
              <option value="cleaning">Ctrl + f5 (cleaning)</option>
              <option value="repair">Ctrl + R (Repair) </option>
              <option value="build">G-Builds (Builds & Upgrades)</option>
              </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="issue" placeholder="Describe your issue:*" required>
            </div>
            <?php
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
?>

      <?php
$sql = "SELECT * FROM user_bookings";
$result = mysqli_query($conn, $sql);
?>
<div>
<table style="color: azure;">
    <tr>
        
        <th>Booked Slots</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            
            <td style="color: goldenrod;"><?php echo $row['id']; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $row['slot']; ?></td>
        </tr>
    <?php  }?>
</table>
            <a style="color: azure;">Slot</a>
            <div class="form-group">
            <select type="text" class="form-control" name="slot" placeholder="Slot:" required>
              <!--Dates-->
            <option value="">Date:</option>
            <option value="AUG5" style="color:darkblue;">AUG 5TH SAT</option>
            <option value="AUG6" style="color: red;">AUG 6TH SUN</option>
            .
            <option value="AUG12" style="color: darkblue;">AUG 12TH SAT</option>
            <option value="AUG13" style="color: red;">AUG 13TH SUN</option>
            .
            <option value="AUG19" style="color:darkblue;">AUG 19TH SAT</option>
            <option value="AUG20" style="color: red;">AUG 20TH SUN</option>

            <option value="AUG26" style="color: darkblue;">AUG 26TH SAT</option>
            <option value="AUG27" style="color: red;">AUG 27TH SUN</option>
            .
            <option value="SEP2" style="color:darkblue;">SEP 2ND SAT</option>
            <option value="SEP3" style="color: red;">SEP 3RD SUN</option>
            .
            <option value="SEP9" style="color:darkblue;">SEP 9TH SAT</option>
            <option value="SEP10" style="color: red;">SEP 10TH SUN</option>
            .
            <option value="SEP16" style="color:darkblue;">SEP 16TH SAT</option>
            <option value="SEP17" style="color: red;">SEP 17TH SUN</option>
            .
            <option value="SEP23" style="color:darkblue;">SEP 23RD SAT</option>
            <option value="SEP24" style="color: red;">SEP 24TH SUN</option>
            .
            </select>
            </div>
            <div class="form-group">
              <select type="text" class="form-control" name="time" placeholder="Time:" required>
                <option value="">Time:</option>
                <option value="10AM" style="color: darkcyan;">10 AM</option>
                <option value="4PM" style="color: brown;">4 PM</option>
              </select>
            </div>
           
            


            
            <br>
            <div class="form-btn">
                <input type="submit" class="btn btn-info rounded-pill" value="Confirm Booking!" name="submit">
            </div>
            <a href="logout.php" class="btn btn-warning" style="align-items:center;">Logout</a>
        </form>
      <br>
      
</div>

    </div>
    
</body>
</html>