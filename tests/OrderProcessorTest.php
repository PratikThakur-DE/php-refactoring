<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/OrderProcessor.php';

class OrderProcessorTest extends TestCase {
    private OrderProcessor $processor;

    protected function setUp(): void {
        $this->processor = $this->getMockBuilder(OrderProcessor::class)
                                 ->onlyMethods(['sendEmail']) 
                                 ->getMock();
    }

    public function testCalculateOrderTotal() {
        $items = [
            ['price' => 50, 'quantity' => 2],
            ['price' => 30, 'quantity' => 1]
        ];
        $this->assertEquals(130, $this->processor->orderTotal($items));
    }

    public function testApplyDiscount() {
        $total = 150;
        $discountedTotal = $this->processor->applyDiscount($total, 'regular');
        $this->assertEquals(135, $discountedTotal);
    }

    public function testApplyVipDiscount() {
        $total = 150;
        $discountedTotal = $this->processor->applyDiscount($total, 'vip');
        $expected = 135 - (135 * 0.1);
        $this->assertEquals($expected, $discountedTotal);
    }

    public function testApplyDiscountBelowThreshold() {
        $total = 90;
        $discountedTotal = $this->processor->applyDiscount($total, 'regular');
        $this->assertEquals(90, $discountedTotal);
    }

    public function testProcessValidOrderWithDiscount() {
        
        // Prepare a valid order
        $order = [
            'status' => 'pending',
            'customer_email' => 'customer@example.com',
            'customer_type' => 'vip',
            'items' => [
                ['price' => 50, 'quantity' => 2],
                ['price' => 30, 'quantity' => 1]
            ]
        ];
    
        // Calculate expected total
        $total = 50 * 2 + 30 * 1;
        $totalAfterDiscount = $total - ($total * 0.1);
        $finalTotal = $totalAfterDiscount - ($totalAfterDiscount * 0.1); // VIP discount
    
        // Mock the sendEmail method
        $this->processor->expects($this->once())
                        ->method('sendEmail')
                        ->with('customer@example.com', "Your order total: $" . $finalTotal);
    
        $this->processor->processOrders([$order]);
    }

    public function testProcessInvalidOrder() {
        $invalidOrder = [
            'status' => 'pending',
            'customer_email' => 'invalid-email',
            'customer_type' => 'regular',
            'items' => [
                ['price' => 20, 'quantity' => 2]
            ]
        ];

        $this->processor->expects($this->never())
                        ->method('sendEmail');

        $this->processor->processOrders([$invalidOrder]);
    }
}
