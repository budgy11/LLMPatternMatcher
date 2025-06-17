
  </form>

</body>
</html>


<?php
session_start();

// Cart data (in a real application, this would likely come from a database)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $cart[$product_id]['quantity'] += $quantity;
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    unset($cart[$product_id]);
}

// Function to get the cart total
function get_cart_total() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to display the cart
function display_cart() {
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($cart as $product_id => $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . number_format($item['price'] * $item['quantity'], 2) . "</td>";
        echo "<td><a href='update_cart.php?product_id=" . $product_id . "&quantity=1'>-</a> <a href='update_cart.php?product_id=" . $product_id . "&quantity=1'>Update</a> <a href='remove_from_cart.php?product_id=" . $product_id . "'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format(get_cart_total(), 2) . "</p>";
}

// ---  Handling Cart Updates (update_cart.php) ---

//If the update_cart.php is accessed, let's handle the quantity updates
if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = $_GET['product_id'];
    $quantity = (int)$_GET['quantity']; // Ensure it's an integer

    if ($quantity > 0) {
      update_cart_quantity($product_id, $quantity);
    } else {
      // Handle invalid quantity, e.g., display an error message
      echo "<p>Invalid quantity. Please enter a positive number.</p>";
    }
    header("Location: cart.php"); // Redirect to the cart page
    exit(); // Important to stop further execution
}



// ---  Removing Items (remove_from_cart.php) ---

//If the remove_from_cart.php is accessed, let's handle the removal
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    remove_from_cart($product_id);
    header("Location: cart.php"); // Redirect to the cart page
    exit();
}

// --- Initial Cart Display (cart.php) ---

// Ensure the cart is started.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = $cart;
}
?>
