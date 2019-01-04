<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Razorpay\Api\Api;

class Payment_controller extends CI_Controller
{
    public $api_key = 'rzp_test_JgKClOoVY7TjQR';
    public $api_secret = 'xRkcES9kew0nYcg3DLUo2mmO';
    public $currency = 'INR';

    public function index()
    {
        $this->load->view('header');
        $this->load->view('payment');
        $this->load->view('footer');
    }

    public function xhr_request_order()
    {
        //print_r($_POST);
        //exit();
        $api = new Api($this->api_key, $this->api_secret);
        $orderData = [
            'receipt' => $_POST['uid'],
            'amount' => $_POST['amount'] * 100,
            'currency' => $this->currency,
            'payment_capture' => 0
        ];
        $razorpayOrder = $api->order->create($orderData);
        $razorpayOrderId = $razorpayOrder['id'];
        if ($razorpayOrderId) {
            $amount = $orderData['amount'];
            $data = [
                "key" => $this->api_key,
                "amount" => $amount,
                "merchant_order_id" => $orderData['receipt'],
                "name" => 'Nagar Nigam',
                "description" => 'Water Tax',
                "order_id" => $razorpayOrderId,
                "success" => true,
            ];
            echo json_encode($data);
        } else {
            echo json_encode($razorpayOrder['error']);
        }


    }

    public function xhr_capture_order()
    {
        $api = new Api($this->api_key, $this->api_secret);
        $payment = $api->payment->fetch($_POST['razorpay_payment_id']);
        $paymentnew  = $api->payment->fetch($_POST['razorpay_payment_id'])->capture(array('amount'=>$payment->amount));
        if ($paymentnew['status']== 'captured') {
            echo json_encode($paymentnew);
        }else{
           echo json_encode($paymentnew['error']);
        }

    }

    public function xhr_get_payment_detail($data){
        $api = new Api($this->api_key, $this->api_secret);
        $order  = $api->order->fetch($data['orderid']); // Returns a particular order
       // $orders = $api->order->all($options);
        echo json_encode($order);
    }


}
