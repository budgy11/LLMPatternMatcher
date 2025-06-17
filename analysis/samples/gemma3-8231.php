

<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Initialize the cart (if not already initialized)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param float $price The price of the product.
 * @param int $quantity The quantity to add.
 */
function addToCart($product_id, $name, $price, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 */
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


/**
 * Returns the cart contents.
 */
function getCartContents() {
    return $_SESSION['cart'];
}

// --- Handle Form Submission (Add to Cart) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        addToCart($product_id, $name, $price, $quantity);
        // Redirect to the cart page
        header("Location: cart.php");
        exit();
    }

    // Handle quantity updates (if any)
    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = $_POST['quantity'];
        updateQuantity($product_id, $new_quantity);
        header("Location: cart.php");
        exit();
    }

    // Handle remove from cart
    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        removeFromCart($product_id);
        header("Location: cart.php");
        exit();
    }
}


?>
