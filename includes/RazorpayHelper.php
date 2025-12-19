<?php
require_once 'config/razorpay.php';

class RazorpayHelper {
    private $key_id;
    private $key_secret;
    private $api_url;

    public function __construct() {
        $this->key_id = RAZORPAY_KEY_ID;
        $this->key_secret = RAZORPAY_KEY_SECRET;
        $this->api_url = RAZORPAY_API_URL;
    }

    public function createOrder($amount, $receipt_id) {
        $data = array(
            'amount' => $amount * 100, // Amount in paise
            'currency' => RAZORPAY_CURRENCY,
            'receipt' => $receipt_id,
            'payment_capture' => 1
        );

        $json = json_encode($data);
        $ch = curl_init($this->api_url . 'orders');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->key_id . ':' . $this->key_secret)
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public function verifyPayment($razorpay_payment_id, $razorpay_order_id, $razorpay_signature) {
        $body = $razorpay_order_id . '|' . $razorpay_payment_id;
        $expected_signature = hash_hmac('sha256', $body, $this->key_secret);
        return hash_equals($expected_signature, $razorpay_signature);
    }

    public function getPaymentDetails($payment_id) {
        $ch = curl_init($this->api_url . 'payments/' . $payment_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($this->key_id . ':' . $this->key_secret)
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
} 