<?php 
 
// Product Details  
// Minimum amount is $0.50 US  
$productName = "Leisure computers Booking fee";  
$productID = "LC23JUL";  
$productPrice = 149; 
$currency = "INR"; 
  
/* 
 * Stripe API configuration 
 * Remember to switch to your live publishable and secret key in production! 
 * See your keys here: https://dashboard.stripe.com/account/apikeys 
 */ 
define('STRIPE_API_KEY', 'sk_test_51NA5LLSGaq3dNnDvO2jmse9AtrgMu4i50QQPKVuy43dcbfzkt4b6c4xRJhK4bpymrSaszckFmDIwVoQPLLl61XNB00LYscWuxJ'); 
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51NA5LLSGaq3dNnDvJgeQHSAVHK9fY0cqaXVWN39djjd0rKtm1gvT8AKH5IpeAn7ULvYQU4M6tGipNY21YgPSj3bm001DYhVsFe'); 
define('STRIPE_SUCCESS_URL', 'https://localhost/stripeyt2/payment-success.php'); //Payment success URL 
define('STRIPE_CANCEL_URL', 'https://localhost/stripeyt2/index.php'); //Payment cancel URL 
    
// Database configuration    
define('DB_HOST', '127.0.0.1');   
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '2626#26Vsl');   
define('DB_NAME', 'pay_txn_book'); 
 
?>