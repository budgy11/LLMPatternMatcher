

<?php

// Define product information (for simplicity, this is in the code)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

// Session handling (for cart data)
session_start();

// Initialize the cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    global $products;

    if (isset($products[$product_id])) {
        if ($quantity <= 0) {
            echo "<p>Invalid quantity.  Please enter a positive number.</p>";
            return;
        }

        $product = $products[$product_id];

        if ($quantity > $product['quantity']) {
            echo "<p>Not enough stock! Only $product['quantity'] available.</p>";
            return;
        }

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $_SESSION['cart'][$product_id] = ['quantity' => $quantity, 'price' => $product['price']];
        }

        echo "<p>Item added to cart!</p>";
    } else {
        echo "<p>Product not found.</p>";
    }
}

// Function to view the cart
function viewCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product = $products[$product_id];
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $item['quantity'] . " = $" . ($item['quantity'] * $product['price']) . "</li>";
    }
    echo "</ul>";

    echo "<p><a href='checkout.php' class='btn'>Proceed to Checkout</a></p>";
}

// Function to handle checkout (simplified - just displays the cart total)
function checkout() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product = $products[$product_id];
        $total += ($item['quantity'] * $product['price']);
    }

    echo "<h2>Checkout</h2>";
    echo "<p>Total: $" . $total . "</p>";
    echo "<p>Thank you for your order!</p>";

    // Clear the cart after checkout
    $_SESSION['cart'] = [];
}

// ---  Handle incoming requests  ---

// 1. Add to Cart Request (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Convert to integer
    $quantity = (int)$_POST['quantity'];       // Convert to integer

    addToCart($product_id, $quantity);
}

// 2. View Cart Request
if (isset($_GET['view_cart'])) {
    viewCart();
}

// 3. Checkout Request
if (isset($_GET['checkout'])) {
    checkout();
}

// ---  Example HTML (for display) ---
?>
