
</body>
</html>


<?php

// Define product data (for example)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

// Function to handle the purchase process
function handlePurchase($cart) {
    $total = 0.00;
    $orderConfirmation = [];

    foreach ($cart as $productId => $quantity) {
        if (isset($products[$productId])) {
            $product = $products[$productId];
            $price = $product['price'];
            $productName = $product['name'];

            $total += $price * $quantity;
            $orderConfirmation[] = [
                'product' => $productName,
                'quantity' => $quantity,
                'price' => $price,
                'totalPrice' => $price * $quantity
            ];
        } else {
            echo "Product ID " . $productId . " not found.
";
        }
    }

    return ['total' => $total, 'order' => $orderConfirmation];
}

// Initialize the cart (you could read this from a session, database, etc.)
$cart = [
    1 => 2, // 2 T-shirts
    3 => 1 // 1 Hat
];

// Process the purchase
$purchaseResult = handlePurchase($cart);

// Display the results
echo "<h2>Purchase Summary</h2>
";
echo "Total: $" . number_format($purchaseResult['total'], 2) . "
";

echo "<h2>Order Details</h2>
";
if (count($purchaseResult['order']) > 0) {
    echo "<ul>
";
    foreach ($purchaseResult['order'] as $item) {
        echo "<li>" . $item['product'] . " - Quantity: " . $item['quantity'] . " - Price: $" . number_format($item['price'], 2) . " - Total: $" . number_format($item['totalPrice'], 2) . "</li>
";
    }
    echo "</ul>
";
} else {
    echo "<p>No items in the order.</p>
";
}


//  Example of adding a product to the cart (optional)
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; //Ensure it's an integer

    if (isset($products[$productId])) {
        $quantity = (int)$_POST['quantity']; //Ensure it's an integer
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        echo "<p>Product added to cart!</p>";
    } else {
        echo "<p>Product ID " . $productId . " not found.</p>";
    }
}

?>
