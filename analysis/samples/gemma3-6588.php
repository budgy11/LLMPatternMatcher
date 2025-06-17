    </select>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">

    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

</body>
</html>


<?php
session_start();

// --- Product Information (for demonstration) ---
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80.00],
];

// --- Cart Session Variables ---
$cart = [];  // Array to store items in the cart (id => quantity)

// --- Helper Functions ---
function add_to_cart($product_id, $quantity = 1)
{
    global $cart;

    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
}

function remove_from_cart($product_id, $quantity = 1)
{
    global $cart;

    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id] -= $quantity;

        // If quantity is 0, remove the item from the cart
        if ($cart[$product_id] <= 0) {
            unset($cart[$product_id]);
        }
    }
}

function get_cart_total()
{
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        if (array_key_exists($product_id, $products)) {
            $total += $products[$product_id]['price'] * $quantity;
        }
    }
    return $total;
}


// --- Example Usage & Cart Modification ---
// Add a T-Shirt to the cart
add_to_cart(1); // Add 1 T-Shirt
// Add 2 Jeans to the cart
add_to_cart(2, 2);
// Remove one pair of jeans
remove_from_cart(2,1);



// --- Display the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $quantity) {
        if (array_key_exists($product_id, $products)) {
            $product = $products[$product_id];
            echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
        }
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . number_format(get_cart_total(), 2) . "</strong></p>"; // Format total to 2 decimal places
}

?>
