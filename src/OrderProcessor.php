<?php
/**
 * Class OrderProcessor
 * This class processes customer orders, applies discounts, and sends email notifications.
 */

class OrderProcessor {
    const MIN_ORDER_FOR_DISCOUNT = 100;
    const DISCOUNT_RATE = 0.1;
    const VIP_DISCOUNT = 0.1;

    public function processOrders($orders){
        foreach ($orders as $order) {

            // Skip processing invalid order
            if (!$this->validateOrder($order)) {
                echo "Invalid order data";
                continue; 
            }

            // Skip non-pending orders
            if ($order['status'] !== 'pending') {
                continue;
            }

            $total = $this->orderTotal($order['items']);
            $finalTotal = $this->applyDiscount($total, $order['customer_type']);

            $this->sendEmail($order['customer_email'], "Your order total: $" . $finalTotal);
        }
        
    }
    
    private function validateOrder($order) {
        if (empty($order['status']) || empty($order['customer_email']) || empty($order['items']) || empty($order['customer_type'])) {
            return false;
        }
    
        if (!filter_var($order['customer_email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
    
        if (!is_array($order['items'])) {
            return false;
        }
    
        foreach ($order['items'] as $item) {
            if (empty($item['price']) || !is_numeric($item['price']) || $item['price'] <= 0) {
                return false;
            }
            if (empty($item['quantity']) || !is_int($item['quantity']) || $item['quantity'] <= 0) {
                return false;
            }
        }
    
        return true;
    }

    public function orderTotal($items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function applyDiscount($total, $customerType) {
        if ($total > self::MIN_ORDER_FOR_DISCOUNT) {
            $total -= $total * self::DISCOUNT_RATE;
        }
    
        // Apply VIP discount
        if ($customerType === 'vip') {
            $total -= $total * self::VIP_DISCOUNT;
        }
    
        return $total;
    }

    public function sendEmail($email, $message) {
        // Simulating email sending
        echo "Sending email to $email: $message\n";
    }
}

$orders = [
    [
        'status' => 'pending',
        'customer_email' => 'customer1@example.com',
        'customer_type' => 'vip',
        'items' => [
            ['price' => 50, 'quantity' => 2],
            ['price' => 30, 'quantity' => 1]
        ]
    ],
    [
        'status' => 'completed',
        'customer_email' => 'customer2@example.com',
        'customer_type' => 'regular',
        'items' => [
            ['price' => 20, 'quantity' => 3]
        ]
    ]
];

$processor = new OrderProcessor();
$processor->processOrders($orders);