<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: bookingsite.php");
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Deatils</title>
    <link rel="shortcut icon" type="image/jpg" href="assets/NavLogo.jpeg" class="rounded-circle">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body {
            background-color: black;
            color: white;
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
    <style>
      .spinner-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 2);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

/* ... Previous CSS code ... */

.spinner {
  border: 6px solid rgba(255, 0, 0, 0.5); /* Increased border width */
  border-top: 6px solid #000000; /* Increased border width */
  border-radius: 50%;
  width: 60px; /* Doubled spinner size */
  height: 60px; /* Doubled spinner size */
  animation: spin 1s linear infinite;
  background-color: rgba(0, 0, 0, 0); /* Opaque background */
}

/* ... Rest of the CSS code ... */


@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <a></a>
    <nav class="navbar navbar-expand-md fixed-top" style="background-color: black;"> 
        <div class="container my-3 mx-auto">
            <a class="nav-link align-items-left mr-auto" href="" style="color: rgb(255, 73, 73); font-size: x-large; font-weight: 600; line-height: 0.5cm;">
                Leisure <br> computers|
            </a>
            <a class="btn btn-outline-warning btn-large rounded-pill " href="https://leisuretech.pages.dev" style="font-size: large; font-weight: 500; ml-1 ">Home</a>
        </div>
    </nav>
    <br>
    <div id="spinner" class="spinner-overlay">
    <div class="spinner"></div>
  </div>
    <br>
    <a></a>
    <div style="font-size: xx-large; font-weight: bold; color: golden rod" class="container d-flex flex-column align-items-center mt-5">Enter your details below</div>
    <br>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
            $txn_id = $_POST["txn_id"];
            $customer_email = $_POST["customer_email"];
            require_once "dbConnect.php";
            
            // Modify the following code as per your database connection method
            $conn = mysqli_connect("localhost", "root", "2626#26Vsl", "pay_txn_book");
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            $sql = "SELECT * FROM transactions WHERE txn_id = '$txn_id'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            if ($user) {
                if ($user["customer_email"] === $customer_email) {
                    $_SESSION["user"] = $txn_id; // Store email in session variable
                    header("Location: bookingsite.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>E-mail does not match</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Invalid Transaction ID!</div>";
            }
        }
        ?>
        <center>
        <form action="login.php" method="post">
            <a> </a>
            <br>
            <div class="form-group" >
                <input type="text" placeholder="Enter Transaction ID:" name="txn_id" class="form-control" autocomplete="off">
            </div>
            <a></a>
            <br>
            <div class="form-group">
                <input type="email" placeholder="Enter E-mail:" name="customer_email" class="form-control">
            </div>
            <a></a>
            <br>
            <div class="form-btn">
                <input type="submit" value="Procced to booking" name="login" class="btn btn-primary">
            </div>
            <br>
        </form>
        </center>
        <div><p>Looking for payment? <a href="index.php">Pay here!</a></p></div>
    </div>
    <footer>
      <div class="container-fluid text-center" style="background-color:rgb(0, 0, 0);;">
        <div class="py-5">
          <h2 style="color: rgb(255, 73, 73);">Interested in working with Us?</h2>
          <a href="https://forms.gle/eM8FZ7bnvSiTWsu46"  class="btn btn-outline-warning btn-lg mt-3">
            Let's talk
            </a>
         </div>
         <div class="row">
          <div class="col-12 col-md-4 py-3">
            <h5 class=" pb-3" style="color: rgb(255, 73, 73);">More links</h5>
            
            <a href="#" class="d-block" style="color: rgb(255, 241, 184);">Home</a>
            <a href="https://forms.gle/wmccyTN6Qqimzssp6" class="d-block" style="color: rgb(255, 241, 184);">Contact Us</a>
            <a href="https://forms.gle/X2dE8iokghQakRESA" class="d-block" style="color: rgb(255, 241, 184);">
              Write a suggestion!  <i class="fas fa-heart" style="color: rgb(255, 73, 73);"></i>
            </a>
          </div>
          <div class="col-12 col-md-4 text-justify py-3" style="color:rgb(255, 241, 184) ;">
            <p>
              We're not just a tech company. We're a team of people who care about your technology and know how to use it to make your life better. Follow us on social media and see us unveil the future of edge computing.
Join our intern program to be part of building the Technology. 
            </p>
          </div>
          <div class="col-12 col-md-4 py-3">
            <h5 class="text-info pb-3">Social</h5>
            <a href="https://twitter.com/Leisuretech24?ref_src=twsrc%5Etfw">
              <i class="fab fa-twitter h1 d-block" style="color: rgb(124, 238, 255);"></i>
            </a>
            <a href="https://www.instagram.com/leisurecomputers">
              <i class=" fab fa-instagram h1 d-block" style="color: rgb(255, 78, 175);"></i>
            </a>
            <a href="youtube.com/@Leisurecomputers">
              <i class="fas fa-play-circle h1 d-block " style="color: rgb(255, 114, 93);"></i>
            </a>
          </div>
        </div>
        <div class="text-muted py-3">
          <p>Copyright © leisurecomputers Pvt.Ltd 2022 | All Rights Reserved</p>
        </div>
      </div>
    </footer>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
  const spinner = document.getElementById("spinner");
  const button = document.querySelector(".btn-primary"); // Change this selector to match your button

  button.addEventListener("click", function () {
    spinner.style.display = "flex"; // Show the spinner

    // You can also simulate a delay here using setTimeout if you want
    // For example: setTimeout(function () { spinner.style.display = "none"; }, 3000);

    window.addEventListener("load", function () {
      spinner.style.display = "none"; // Hide the spinner when the page finishes loading
    });
  });
});

    </script>
</body>
</html>
