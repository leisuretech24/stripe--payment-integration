<?php 
// Include configuration file   
require_once 'config.php';  

// Define the product details
$productName = "Leisure Computers Booking Fee"; // Replace with your actual product name
$productPrice = 149; // Replace with your actual product price
$currency = "INR"; // Replace with your desired currency

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
</head>
<body>
    <!-- Navbar -->
    <a></a>
    <nav class="navbar navbar-expand-md fixed-top" style="background-color: black;">
        <div class="container my-4 mx-auto">
            <a class="nav-link align-items-left mr-auto" href="" style="color: rgb(255, 73, 73); font-size: x-large; font-weight: 600; line-height: 0.5cm;">
                Leisure <br> computers|
            </a>
            <a class="btn btn-outline-warning btn-large rounded-pill " href="https://leisuretech.pages.dev" style="font-size: large; font-weight: 500; ml-1 ">Home</a>
        </div>
    </nav>
    <br>
    <a>____</a>
    <a class="container my-4" ></a>
    <br>
    <a>------</a>

    <div class="container d-flex flex-column align-items-center mt-5 my-4">
        <!-- Display errors returned by checkout session -->
        <div id="paymentResponse" class="hidden"></div>
        <br>
        <br>
        <!-- Product details -->
        <h2><?php echo $productName; ?></h2>
        <div class="col-md-6 " >
        <img src="assets/Pay.png" alt="Product Image" class="img-fluid">
        </div>
        <p>After payment, save your transaction ID and email address</p>
        <p>Price: <b><?php echo $productPrice.' '.strtoupper($currency); ?></b></p>

        <!-- Payment button -->
        <button class="stripe-button" id="payButton">
            <div class="spinner " id="spinner"></div>
            <span id="buttonText">Pay Now</span>
        </button>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Set Stripe publishable key to initialize Stripe.js
        const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

        // Select payment button
        const payBtn = document.querySelector("#payButton");

        // Payment request handler
        payBtn.addEventListener("click", function (evt) {
            setLoading(true);

            createCheckoutSession().then(function (data) {
                if(data.sessionId){
                    stripe.redirectToCheckout({
                        sessionId: data.sessionId,
                    }).then(handleResult);
                }else{
                    handleResult(data);
                }
            });
        });
        
        // Create a Checkout Session with the selected product
        const createCheckoutSession = function () {
            return fetch("payment_init.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    createCheckoutSession: 1,
                }),
            }).then(function (result) {
                return result.json();
            });
        };

        // Handle any errors returned from Checkout
        const handleResult = function (result) {
            if (result.error) {
                showMessage(result.error.message);
            }
            
            setLoading(false);
        };

        // Show a spinner on payment processing
        function setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                payBtn.disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#buttonText").classList.add("hidden");
            } else {
                // Enable the button and hide spinner
                payBtn.disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#buttonText").classList.remove("hidden");
            }
        }

        // Display message
        function showMessage(messageText) {
            const messageContainer = document.querySelector("#paymentResponse");
        
            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;
        
            setTimeout(function () {
                messageContainer.classList.add("hidden");
                messageContainer.textContent = "";
            }, 5000);
        }
    </script>
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
</body>
</html>
