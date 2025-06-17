

<?php
session_start();

// Configuration
$items = []; // Array to store the shopping cart items
$database_file = "cart_data.txt"; // File to store cart data

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price of the single product.
 */
function addItemToCart(string $product_id, string $product_name, int $quantity, float $price): void
{
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $item = [
        'id' => $product_id,
        'name' => $product_name,
        'quantity' => $quantity,
        'price' => $price
    ];

    $_SESSION['cart'][] = $item;
    
    //Persist the cart data to a file (for session persistence)
    saveCartData();
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $newQuantity The new quantity of the product.
 */
function updateCartItemQuantity(string $product_id, int $newQuantity): void
{
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) { // Use &$item for modification
            if ($item['id'] === $product_id) {
                $item['quantity'] = $newQuantity;
                break;
            }
        }
        saveCartData();
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeItemFromCart(string $product_id): void
{
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] === $product_id) {
                unset($_SESSION['cart'][$key]);
                // Optionally, you can re-index the array if needed
                //  $_SESSION['cart'] = array_values($_SESSION['cart']);
                saveCartData();
                break;
            }
        }
    }
}


/**
 * Retrieves the contents of the shopping cart.
 *
 * @return array The shopping cart items.
 */
function getCartContents(): array
{
    return $_SESSION['cart'] ?? []; // Return empty array if cart is empty
}

/**
 * Saves the cart data to a file.
 */
function saveCartData(): void
{
    file_put_contents($database_file, json_encode($_SESSION['cart']));
}

/**
 * Loads the cart data from the file.
 */
function loadCartData(): void
{
    if (file_exists($database_file)) {
        $cartData = file_get_contents($database_file);
        if ($cartData = json_decode($cartData, true)) {
            $_SESSION['cart'] = $cartData;
        }
    }
}



// ---  Handle Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default to 1 if not provided
    $price = floatval($_POST['price']);  // Convert to float
    
    addItemToCart($product_id, $product_name, $quantity, $price);
    // Redirect to the cart page or display a confirmation message
    header("Location: cart.php"); // Redirect to cart.php
    exit();
}

// --- Handle Update Quantity Request ---
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $newQuantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    updateCartItemQuantity($product_id, $newQuantity);
    header("Location: cart.php");
    exit();
}

// --- Handle Remove Item Request ---
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    removeItemFromCart($product_id);
    header("Location: cart.php");
    exit();
}

// --- Load Cart Data on Page Load ---
loadCartData();

// --- Display Cart Contents (cart.php would handle this) ---
$cart_items = getCartContents();
?>
