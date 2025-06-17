

<?php
session_start(); // Start the session

// Define some basic product information (for example)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200, 'quantity' => 1],
    2 => ['name' => 'Mouse', 'price' => 25, 'quantity' => 2],
    3 => ['name' => 'Keyboard', 'price' => 75, 'quantity' => 1],
];

// Function to add a product to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'][$productId])) {
        // Product already in cart, increase quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Product not in cart, add it
        $_SESSION['cart'][$productId] = [
            'name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => $quantity,
        ];
    }
}

// Function to update the quantity of a product in the cart
function updateCartQuantity($productId, $quantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to remove a product from the cart
function removeCartItem($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}


// --- Example Usage (Simulating User Interactions) ---

// User adds a Laptop to the cart
addToCart(1);

// User adds 2 Mouses to the cart
addToCart(2, 2);

// User updates the quantity of the Laptop to 3
updateCartQuantity(1, 3);

// User removes the Mouse from the cart
removeCartItem(2);

// --- Displaying the Cart Contents ---

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate total price
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $productId => $item) {
        $totalPrice += ($item['price'] * $item['quantity']);
    }
    echo "<p><b>Total: $" . number_format($totalPrice, 2) . "</p>";
}

?>
