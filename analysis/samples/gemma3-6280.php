    </select>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

</body>
</html>


<?php

// Session handling start
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ---  Example functions to manage the cart ---

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart contents
function get_cart_contents() {
    return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculate_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_item_price = $item['price'] * $item['quantity'];
        $total += $total_item_price;
    }
    return $total;
}

// --- Example usage (This section is for demonstration. In a real application,
//  this would be handled by a form submission) ---

// 1.  Adding an item to the cart (simulated form submission)
//    In a real application, this would come from a form submission.
//    For this example, let's add a product with ID 1, name "Shirt", price $20, quantity 2
//    add_to_cart(1, 'Shirt', 20, 2);

// 2.  Updating the quantity of an item
//    update_cart_quantity(1, 5); // Change quantity of "Shirt" (ID 1) to 5

// 3. Getting the cart contents
$cart_items = get_cart_contents();
print_r($cart_items); // This will show the contents of the cart

// 4. Calculating the total
$total = calculate_cart_total();
echo "Total cart value: $" . $total . "<br>";

// 5. Removing an item from the cart
// remove_from_cart(1); // Remove the "Shirt" (ID 1) from the cart.  This is commented out to prevent accidentally emptying the cart.

?>
