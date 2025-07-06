<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['uname'])) {
    header("Location: login.php");
    exit();
}

// Ensure order_id exists in session
if (isset($_SESSION['order_id'])) {
    $order_id = $_SESSION['order_id'];

    // Fetch order details from the database using the order_id
    $select_order = mysqli_query($conn, "SELECT * FROM `ordertable` WHERE ord_id = '$order_id'");
    if (mysqli_num_rows($select_order) > 0) {
        $order_details = mysqli_fetch_assoc($select_order);
        $total_price = $order_details['total_price'];
        $name = $order_details['name'];
        $email = $order_details['email'];
        $num = $order_details['number'];
    } else {
        echo "<p>Order not found.</p>";
        exit();
    }
} else {
    echo "<p>Order ID not found in session.</p>";
    exit();
}

// Debugging: Print fetched order data (for development purposes)
echo "<pre>Fetched Order Data:\n";
print_r([
    'order_id' => $order_id,
    'total_price' => $total_price,
    'name' => $name,
    'email' => $email,
    'number' => $num,
]);
echo "</pre>";

// Khalti payment API payload
$payload = array(
    "return_url" => "http://localhost/4thsemproject/order_receipt.php?order_id=$order_id",
    "website_url" => "http://localhost/phpmyadmin/",
    "amount" => $total_price * 100, // Convert to paisa for Khalti
    "purchase_order_id" => $order_id,
    "purchase_order_name" => "Order #$order_id",
    "customer_info" => array(
        "name" => $name,
        "email" => $email,
        "phone" => $num
    )
);

echo "<pre>Payload Sent to Khalti:\n";
print_r($payload);
// die;
echo "</pre>";

// Initialize cURL for Khalti API
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Key 7eb4cae617634dc8910508870f6040ef', // Replace with your actual Khalti API key
        'Content-Type: application/json',
    ),
));

// Execute the API request and capture the response
$response = curl_exec($curl);

// Check for cURL errors
if (curl_errno($curl)) {
    echo "<p>cURL Error: " . curl_error($curl) . "</p>";
    curl_close($curl);
    exit();
}

curl_close($curl);

// Debugging: Print the raw API response (for development purposes)
echo "<pre>Raw API Response:\n";
print_r($response);
echo "</pre>";
// die;
// Decode the API response
$responseData = json_decode($response, true);

// Debugging: Print the decoded API response (for development purposes)
echo "<pre>Decoded API Response:\n";
print_r($responseData);
echo "</pre>";

// Handle Khalti response and redirect to payment URL
if (isset($responseData['payment_url'])) {
    $payment_url = $responseData['payment_url'];

    // Debugging: Print the payment URL (for development purposes)
    echo "<pre>Redirecting to Payment URL: $payment_url</pre>";

    // Redirect to the Khalti payment page
    header('Location: ' . $payment_url);
    exit();
} else {
    // Handle API errors and display appropriate messages
    if (isset($responseData['detail'])) {
        echo "<p>Error initiating payment: " . $responseData['detail'] . "</p>";
    } else {
        echo "<p>Error initiating payment: Unknown error.</p>";
    }
}
?>
