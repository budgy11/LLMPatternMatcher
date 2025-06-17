

<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'shopping_cart';
$dbUser = 'root';
$dbPass = '';

// Connect to the database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --------------------- Product Data ---------------------
//  This is a simplified way to store product data.  In a real application,
//  you would likely pull this from a database.
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200.00, 'image' => 'laptop.jpg'],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25.00, 'image' => 'mouse.jpg'],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75.00, 'image' => 'keyboard.jpg'],
];


// --------------------- Cart Functions ---------------------

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @return void
 */
function addToCart($product_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Product not in cart, add it
        $_SESSION['cart'][$product_id] = [
            'quantity' => 1,
            'price' => $products[$product_id]['price']
        ];
    }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float
 */
function calculateCartTotal() {
    $total = 0.00;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }
    }
    return $total;
}

// --------------------- Cart Operations (Handle Actions) ---------------------

// Add to cart (e.g., from a button click)
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure it's an integer
    addToCart($product_id);
}

// Update quantity (e.g., from a quantity input)
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateCartQuantity($product_id, $quantity);
}

// Remove from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
}

// --------------------- Display the Cart ---------------------

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Shopping Cart</title>";
echo "</head>";
echo "<body>";

echo "<h1>Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>";
        echo "<img src='$products[$item['id']]['image']' alt='$products[$item['id']]['name']' style='width:100px;'>";
        echo "<p>$products[$item['id']]['name']</p>";
        echo "<p>Quantity: $item['quantity']</p>";
        echo "<p>Price: $items[$item['id']]['price']</p>";
        echo "<form method='post'>";
        echo "<label for='quantity_$item['id']'>Update Quantity:</label>";
        echo "<input type='number' id='quantity_$item['id']' name='quantity_$item['id']' value='$item['quantity']'>";
        echo "<input type='submit' name='update_quantity' value='Update'>";
        echo "<input type='hidden' name='product_id' value='$item['id']'>";
        echo "</form>";
        echo "<input type='submit' name='remove_from_cart' value='Remove from Cart'>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculateCartTotal() . "</p>";
}

// Display checkout button
echo "<form method='post'>";
echo "<input type='submit' name='checkout' value='Checkout'>";
echo "</form>";

echo "</body>";
echo "</html>";

?>
