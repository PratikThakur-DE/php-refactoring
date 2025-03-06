# Order Processor Class

Simple PHP-based order processing system that calculates the total order value, applies differnt type of discounts, and sends an email notification.

## Features
- Calculate the total value of an order based on the price and quantity of items.
- Apply discounts:
  - General discount for orders above a specified amount.
  - Additional VIP discount for VIP customers.
- Validate orders to ensure the integrity of the data before processing.
- Simulate sending an email to the customer with the final order total.

## Requirements

- PHP >= 7.4
- Composer for managing dependencies (unit test)

## Installation
1. Clone the repository to your local machine:
2. Navigate to the project directory
3. Install Composer dependencies:

    ```bash
    composer install
    ```

## Usage
To process orders, use the OrderProcessor class. 
You can create an instance of the OrderProcessor class and call the processOrders method, passing in an array of orders.
Example:

```Code
$orders = [
    [
        'status' => 'pending',
        'customer_email' => 'customer1@example.com',
        'customer_type' => 'vip',
        'items' => [
            ['price' => 50, 'quantity' => 2],
            ['price' => 30, 'quantity' => 1]
        ]
    ]
];

$processor = new OrderProcessor();
$processor->processOrders($orders);
```
This will simulate processing the orders, calculating the total, applying the appropriate discounts, and sending an email with the final total.

## Testing
This project includes unit tests for order processing functionality using PHPUnit.

Run the tests with:
    ```
    vendor/bin/phpunit tests
    ```