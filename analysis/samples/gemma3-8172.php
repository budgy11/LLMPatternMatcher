
    <a href="checkout.php">Checkout</a> <!-- Link to checkout page -->

</body>
</html>


<?php
session_start();

// Configuration
$items = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

$cart = []; // Initialize an empty cart
$total = 0;
$cart_id = "cart_" . md5(time()); // Unique cart ID


// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $itemId The ID of the item to add.
 * @param int $quantity The quantity of the item to add.
 */
function addToCart(int $itemId, int $quantity = 1)
{
    global $cart, $itemId, $cart_id;

    if (isset($items[$itemId])) {
        $item = $items[$itemId];
        $item_id = $itemId;

        // Check if the item is already in the cart
        if (isset($cart[$item_id][$item_id])) {
            $cart[$item_id][$item_id]['quantity'] += $quantity;
            $cart[$item_id][$item_id]['quantity'] = $cart[$item_id][$item_id]['quantity'];
        } else {
            $cart[$item_id][$item_id] = ['name' => $item['name'], 'price' => $item['price'], 'quantity' => $quantity];
        }

        // Update the total
        $total += $item['price'] * $quantity;
    } else {
        // Item not found
        echo "Item ID " . $itemId . " not found.";
    }
}


/**
 * Removes an item from the cart.
 *
 * @param int $itemId The ID of the item to remove.
 */
function removeFromCart(int $itemId)
{
    global $cart, $cart_id;

    if (isset($cart[$cart_id][$itemId])) {
        unset($cart[$cart_id][$itemId]);

        // Update the total
        $total -= $items[$itemId]['price'] * $items[$itemId]['price'];

        // If the cart is now empty, reset the total to 0
        if (empty($cart[$cart_id])) {
            $total = 0;
        }

    } else {
        // Item not found in the cart
        echo "Item ID " . $itemId . " not found in the cart.";
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $itemId The ID of the item to update.
 * @param int $quantity The new quantity of the item.
 */
function updateQuantity(int $itemId, int $quantity)
{
    global $cart, $cart_id, $items;

    if (isset($items[$itemId])) {
        if (isset($cart[$cart_id][$itemId])) {
            $cart[$cart_id][$itemId]['quantity'] = $quantity;
            $total -= $items[$itemId]['price'] * ($cart[$cart_id][$item_id]['quantity'] - $quantity);
            $total += $items[$itemId]['price'] * $quantity;

        } else {
            // Item not found in the cart
            echo "Item ID " . $itemId . " not found in the cart.";
        }
    } else {
        // Item not found
        echo "Item ID " . $itemId . " not found.";
    }
}



/**
 * Gets the cart contents.
 *
 * @return array  An array representing the cart contents.
 */
function getCart()
{
    return $cart;
}


/**
 * Gets the total cart value.
 *
 * @return float The total value of the cart.
 */
function getTotal()
{
    return $total;
}



// --- Handling Cart Actions (Example) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $itemId = (int)$_POST['item_id'];
    $quantity = (int)$_POST['quantity'] ?? 1; // Default to 1 if quantity not provided
    addToCart($itemId, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $itemId = (int)$_POST['item_id'];
    removeFromCart($itemId);
}

if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $itemId = (int)$_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    updateQuantity($itemId, $quantity);
}

// --- Displaying the Cart ---
$cart_contents = getCart();


?>
