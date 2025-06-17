    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Sample Product Data (replace with your database connection)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Session handling (for storing shopping cart data)
session_start();

// Initialize shopping cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add a product to the cart
function add_to_cart($product_id, $quantity = 1) {
    if (array_key_exists($product_id, $products)) {
        $product = $products[$product_id];

        if (isset($_SESSION['cart'][$product_id])) {
            // Product already in cart, increase quantity
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Product not in cart, add it
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }
    }
}

// Function to remove a product from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of a product in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart total
function get_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Handling form submissions ---

// Check if the "add" button was pressed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default quantity is 1

    add_to_cart($product_id, $quantity);
}

// Check if the "remove" button was pressed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Check if the "update_quantity" button was pressed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default quantity is 1

    update_quantity($product_id, $new_quantity);
}


// --- Displaying the Cart ---

?>
