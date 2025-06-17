    </select>

    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1">

    <br><br>

    <button type="submit">Purchase</button>
  </form>

</body>
</html>


<?php

// Sample Product Data (Replace with a database or other data source)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Session management for cart
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1)
{
    global $products;

    // Check if the product exists
    if (isset($products[$product_id])) {
        $product = $products[$product_id];

        // Check if the item is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add the item to the cart
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }
    } else {
        // Product not found -  Handle this appropriately (e.g., display an error)
        echo "<p>Product ID: " . $product_id . " not found.</p>";
    }
}


// Function to remove an item from the cart
function remove_from_cart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        // Handle the case where the item is not in the cart
        echo "<p>Product ID: " . $product_id . " not found in cart.</p>";
    }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity)
{
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    } else {
        // Handle the case where the item is not in the cart
        echo "<p>Product ID: " . $product_id . " not found in cart.</p>";
    }
}

// Function to display the cart
function display_cart()
{
    echo "<h2>Your Shopping Cart</h2>";

    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")";
        echo "<br>";
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
        echo "<label for='quantity_" . $item_id . "'>Quantity:</label>";
        echo "<input type='number' id='quantity_" . $item_id . "' name='quantity_" . $item_id . "' value='" . $item['quantity'] . "' min='1'>";
        echo "<input type='hidden' name='product_id' value='" . $item_id . "'>";
        echo "<button type='submit'>Update</button>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
}

// Handle adding items to the cart (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the 'product_id' parameter is present
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        // Check if the quantity parameter is present
        if (isset($_POST['quantity_' . $product_id])) {
            $new_quantity = intval($_POST['quantity_' . $product_id]);
            add_to_cart($product_id, $new_quantity);
        }
    }
}

// Handle removing items from the cart
if (isset($_GET['remove']) && isset($_GET['remove']) == 1) {
    $product_id = $_GET['product_id'];
    remove_from_cart($product_id);
}

// Display the cart
display_cart();

?>
