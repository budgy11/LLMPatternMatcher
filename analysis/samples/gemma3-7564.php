

<?php

// Simulate a product database (replace with your actual database connection)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

// Initialize shopping cart
$cart = [];

// Function to add an item to the cart
function addToCart($productId, $quantity, $products) {
    if (isset($products[$productId]) && $products[$productId]['quantity'] > 0) {
        $product = $products[$productId];
        $cart[] = ['product_id' => $productId, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
        echo "<p>Added " . $quantity . " " . $product['name'] . " to your cart.</p>";
    } else {
        echo "<p>Sorry, " . $product['name'] . " is out of stock or invalid product ID.</p>";
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        $product = next(array_filter($products, function ($key, $value) use ($item) {
            return $item['product_id'] === $key;
        }));

        echo "<li>" . $product['name'] . " - $" . $product['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . calculateTotal($cart) . "</p>";
}

// Function to calculate the total price of the cart
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return round($total, 2);
}

// ---  Handling User Input (Simulated) ---

// Example: Add to cart on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        addToCart($productId, $quantity, $products);

        //  Refresh the cart display
        displayCart($cart);
    }
}

?>
