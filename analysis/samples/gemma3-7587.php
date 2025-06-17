

<?php

// Initialize order data (replace with database interaction in a real application)
$order = array(
    'customer_name' => '',
    'items' => array(),
    'total_amount' => 0.00
);

// Handle form submission (if you have a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data (assuming form fields are named accordingly)
    $order['customer_name'] = $_POST['customer_name'];

    // Process items (example - you'd likely read from a database)
    $items = array(
        array('name' => 'Product A', 'quantity' => $_POST['quantity_a'], 'price' => 25.00),
        array('name' => 'Product B', 'quantity' => $_POST['quantity_b'], 'price' => 15.00)
    );

    // Update the order items
    $order['items'] = $items;

    // Calculate the total amount
    $order['total_amount'] = 0.00;
    foreach ($order['items'] as $item) {
        $order['total_amount'] += $item['quantity'] * $item['price'];
    }
}

?>
