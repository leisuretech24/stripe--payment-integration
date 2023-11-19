<?php 
// Include configuration file  
require_once 'config.php'; 
 
// Include database connection file  
include_once 'dbConnect.php'; 
 
$payment_id = $statusMsg = ''; 
$status = 'error'; 
 
// Check whether stripe checkout session is not empty 
if(!empty($_GET['session_id'])){ 
    $session_id = $_GET['session_id']; 
     
    // Fetch transaction data from the database if already exists 
    $sqlQ = "SELECT * FROM transactions WHERE stripe_checkout_session_id = ?"; 
    $stmt = $conn->prepare($sqlQ);  
    $stmt->bind_param("s", $db_session_id); 
    $db_session_id = $session_id; 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
 
    if($result->num_rows > 0){ 
        // Transaction details 
        $transData = $result->fetch_assoc(); 
        $payment_id = $transData['id']; 
        $transactionID = $transData['txn_id']; 
        $paidAmount = $transData['paid_amount']; 
        $paidCurrency = $transData['paid_amount_currency']; 
        $payment_status = $transData['payment_status']; 
         
        $customer_name = $transData['customer_name']; 
        $customer_email = $transData['customer_email']; 
         
        $status = 'success'; 
        $statusMsg = 'Your Payment is Successful! & <br> please copy your transaction ID'; 
    }else{ 
        // Include the Stripe PHP library 
        require_once 'stripe-php/init.php'; 
         
        // Set API key 
        $stripe = new \Stripe\StripeClient(STRIPE_API_KEY); 
         
        // Fetch the Checkout Session to display the JSON result on the success page 
        try { 
            $checkout_session = $stripe->checkout->sessions->retrieve($session_id); 
        } catch(Exception $e) {  
            $api_error = $e->getMessage();  
        } 
         
        if(empty($api_error) && $checkout_session){ 
            // Get customer details 
            $customer_details = $checkout_session->customer_details; 
 
            // Retrieve the details of a PaymentIntent 
            try { 
                $paymentIntent = $stripe->paymentIntents->retrieve($checkout_session->payment_intent); 
            } catch (\Stripe\Exception\ApiErrorException $e) { 
                $api_error = $e->getMessage(); 
            } 
             
            if(empty($api_error) && $paymentIntent){ 
                // Check whether the payment was successful 
                if(!empty($paymentIntent) && $paymentIntent->status == 'succeeded'){ 
                    // Transaction details  
                    $transactionID = $paymentIntent->id; 
                    $paidAmount = $paymentIntent->amount; 
                    $paidAmount = ($paidAmount/100); 
                    $paidCurrency = $paymentIntent->currency; 
                    $payment_status = $paymentIntent->status; 
                     
                    // Customer info 
                    $customer_name = $customer_email = ''; 
                    if(!empty($customer_details)){ 
                        $customer_name = !empty($customer_details->name)?$customer_details->name:''; 
                        $customer_email = !empty($customer_details->email)?$customer_details->email:''; 
                    } 
                     
                    // Check if any transaction data is exists already with the same TXN ID 
                    $sqlQ = "SELECT id FROM transactions WHERE txn_id = ?"; 
                    $stmt = $conn->prepare($sqlQ);  
                    $stmt->bind_param("s", $transactionID); 
                    $stmt->execute(); 
                    $result = $stmt->get_result(); 
                    $prevRow = $result->fetch_assoc(); 
                     
                    if(!empty($prevRow)){ 
                        $payment_id = $prevRow['id']; 
                    }else{ 
                        // Insert transaction data into the database 
                        $sqlQ = "INSERT INTO transactions (customer_name,customer_email,item_name,item_number,item_price,item_price_currency,paid_amount,paid_amount_currency,txn_id,payment_status,stripe_checkout_session_id,created,modified) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())"; 
                        $stmt = $conn->prepare($sqlQ); 
                        $stmt->bind_param("ssssdsdssss", $customer_name, $customer_email, $productName, $productID, $productPrice, $currency, $paidAmount, $paidCurrency, $transactionID, $payment_status, $session_id); 
                        $insert = $stmt->execute(); 
                         
                        if($insert){ 
                            $payment_id = $stmt->insert_id; 
                        } 
                    } 
                     
                    $status = 'success'; 
                    $statusMsg = 'Thanks for the payment! & please copy your transaction ID'; 
                }else{ 
                    $statusMsg = "Transaction has been failed!"; 
                } 
            }else{ 
                $statusMsg = "Unable to fetch the transaction details! $api_error";  
            } 
        }else{ 
            $statusMsg = "Invalid Transaction! $api_error";  
        } 
    } 
}else{ 
    $statusMsg = "Invalid Request!"; 
} 
?>

<!DOCTYPE html>
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
    <title>payment successful</title>
    <link rel="shortcut icon" type="image/jpg" href="assets/NavLogo.jpeg" class="rounded-circle">
    <style>
      .scrollbar::-webkit-scrollbar {
        display: none;
      }
    </style>
    <title>Payment Status</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            flex-direction: column;
        }
    </style>
    <style>
    body {
        background-color: black;
        color: white;
    }
    
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
        flex-direction: column;
    }
    
    .success {
        color: green;
    }
    
    .error {
        color: red;
    }
</style>

</head>
<body >
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg " style="background-color: black;">
        <a class="navbar-brand" href="https://leisuretech.pages.dev/Booking" style="color: rgb(255, 73, 73); font-size: x-large; font-weight: 600; line-height: 0.5cm;">Leisure<br>computers|</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item btn btn-primary rounded-pill">
                    <a class="nav-link" href="login.php" style="color: aliceblue;">Booking</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <?php if(!empty($payment_id)){ ?>
            <h1 class="<?php echo $status; ?>"><?php echo $statusMsg; ?></h1>
            
            <h4>Payment Information</h4>
            <p><b>Reference Number:</b> <?php echo $payment_id; ?></p>
            <p><b>Transaction ID:</b> <?php echo $transactionID; ?></p>
            <p><b>Paid Amount:</b> <?php echo $paidAmount.' '.$paidCurrency; ?></p>
            <p><b>Payment Status:</b> <?php echo $payment_status; ?></p>
            
            <h4>Customer Information</h4>
            <p><b>Name:</b> <?php echo $customer_name; ?></p>
            <p><b>Email:</b> <?php echo $customer_email; ?></p>
            
            <h4>Product Information</h4>
            <p><b>Name:</b> <?php echo $productName; ?></p>
            <p><b>Price:</b> <?php echo $productPrice.' '.$currency; ?></p>
            
            <!-- Copy transaction ID button -->
            <button onclick="copyTransactionID()" class="btn btn-danger">Copy Transaction ID</button>
            <br>
            <!-- Print button -->
        <button onclick="window.print()" class="btn btn-info">Print</button>
            
            <script>
                function copyTransactionID() {
                    /* Get the transaction ID */
                    var transactionID = "<?php echo $transactionID; ?>";
                    
                    /* Create a temporary input element to copy the text */
                    var tempInput = document.createElement('input');
                    tempInput.value = transactionID;
                    document.body.appendChild(tempInput);
                    
                    /* Select the text and copy it */
                    tempInput.select();
                    document.execCommand('copy');
                    
                    /* Remove the temporary input element */
                    document.body.removeChild(tempInput);
                    
                    /* Display a confirmation message */
                    alert('Transaction ID copied to clipboard!');
                }
            </script>
            <br>
        <?php }else{ ?>
            <h1 class="error">Your Payment has been failed!</h1>
            <p class="error"><?php echo $statusMsg; ?></p>
        <?php } ?>
    </div>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>
