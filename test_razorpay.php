<?php
require 'vendor/autoload.php';
$api = new \Razorpay\Api\Api('rzp_test_SVmmlC7HyMgGtL', 'ib2Rn5MUutX0eVNIG011p6ck');
try {
    $api->order->create(['receipt' => '123', 'amount' => 1000, 'currency' => 'INR']);
    echo "Success\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
