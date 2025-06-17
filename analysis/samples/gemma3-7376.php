</form>


<?php
session_start();

// Configuration
$cart_file = 'cart.php';
$item_name_key = 'item_name';
$item_price_key = 'item_price';
$quantity_key = 'quantity';

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $item_name
 * @param float $item_price
 * @param int $quantity
 */
function addToCart($item_name, $item_price, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        $item_name_key => $item_name,
        $quantity_key => $quantity,
        $item_price_key => $item_price
    ];
}


/**
 * Removes an item from the cart by item name.
 *
 * @param string $item_name
 */
function removeFromCart($item_name) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item[$item_name_key] === $item_name) {
                unset($_SESSION['cart'][$key]);
                // Re-index the array to avoid gaps
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $item_name
 * @param int $new_quantity
 */
function updateQuantity($item_name, $new_quantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item[$item_name_key] === $item_name) {
                $_SESSION['cart'][$key][$quantity_key] = $new_quantity;
                // Re-index the array to avoid gaps
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }
}


/**
 * Calculates the total cart value.
 *
 * @return float
 */
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item[$item_price_key] * $item[$quantity_key];
        }
    }
    return $total;
}


/**
 * Clears the entire cart.
 */
function clearCart() {
    unset($_SESSION['cart']);
}


// --- Cart Handling Functions (Called based on user actions) ---

// 1. Add to Cart (handled by the product page)
if (isset($_POST['add_to_cart'])) {
    $item_name = $_POST['item_name'];
    $item_price = floatval($_POST['item_price']); // Ensure price is a float
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer
    addToCart($item_name, $item_price, $quantity);
}

// 2. Remove from Cart (handled by the product page)
if (isset($_POST['remove_from_cart'])) {
    $item_name = $_POST['item_name'];
    removeFromCart($item_name);
}


// 3. Update Quantity (handled by the product page)
if (isset($_POST['update_quantity'])) {
    $item_name = $_POST['item_name'];
    $new_quantity = intval($_POST['quantity']);  // Ensure quantity is an integer
    updateQuantity($item_name, $new_quantity);
}


// --- Cart Display Function ---

function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $item_name = $item[$item_name_key];
        $item_price = $item[$item_price_key];
        $quantity = $item[$quantity_key];
        $item_total = $item_total = $item_price * $quantity;
        $total += $item_total;

        echo "<tr>";
        echo "<td>" . $item_name . "</td>";
        echo "<td>$" . number_format($item_price, 2) . "</td>";
        echo "<td>" . $quantity . "</td>";
        echo "<td>$" . number_format($item_total, 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
    echo "<a href='checkout.php'>Proceed to Checkout</a>";  // Link to checkout page
}


// --- Example: Display the Cart ---
displayCart();
?>
