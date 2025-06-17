    <label for="productName">Product Name:</label>
    <input type="text" id="productName" name="productName" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Calculate Purchase">
  </form>

</body>
</html>


<?php

// Data storage (replace with database in a real application)
$cart = [];
$product_inventory = [
    "product1" => 10, // Quantity in stock
    "product2" => 5,
    "product3" => 20
];

// Function to add a product to the cart
function add_to_cart($product_id, $quantity = 1) {
    if (isset($product_inventory[$product_id])) {
        if ($product_inventory[$product_id] >= $quantity) {
            if (!isset($cart[$product_id])) {
                $cart[$product_id] = $quantity;
            } else {
                $cart[$product_id] += $quantity;
            }
            echo "<p>Added " . $quantity . " units of " . $product_id . " to your cart.</p>";
        } else {
            echo "<p>Sorry, we only have " . $product_inventory[$product_id] . " units of " . $product_id . " in stock.</p>";
        }
    } else {
        echo "<p>Product " . $product_id . " not found.</p>";
    }
}


// Function to view the cart
function view_cart() {
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h2>Your Cart</h2>";
        echo "<ul>";
        foreach ($cart as $product_id => $quantity) {
            $product_name = get_product_name($product_id); // Get the product name (see function below)
            echo "<li>" . $product_name . " - Quantity: " . $quantity . "</li>";
        }
        echo "</ul>";
        echo "<p><a href='checkout.php'>Proceed to Checkout</a></p>";
    }
}

// Function to get product name (for better display)
function get_product_name($product_id) {
    switch ($product_id) {
        case "product1":
            return "Awesome T-Shirt";
        case "product2":
            return "Cool Mug";
        case "product3":
            return "Fancy Hat";
        default:
            return "Unknown Product";
    }
}


// Function to simulate checkout (in a real app, this would process payment)
function checkout() {
    echo "<h2>Checkout</h2>";
    echo "<p>Thank you for your purchase!</p>";
    // In a real application, you'd process payment here.
    // Clear the cart after a successful order (optional)
    $cart = [];
    echo "<p>Your cart has been emptied.</p>";
}



// --- Handling Requests (Simulating a Form Submission) ---

// Example:  Adding a product to the cart
if (isset($_GET['add_to_cart']) && $_GET['add_to_cart'] == 'true') {
    $product_id = $_POST['product_id']; // Assuming the form uses POST

    if (isset($product_id)) {
        add_to_cart($product_id);
    }
}


// Example: Viewing the cart
if (isset($_GET['view_cart']) && $_GET['view_cart'] == 'true') {
    view_cart();
}

// Example: Checkout
if (isset($_GET['checkout']) && $_GET['checkout'] == 'true') {
    checkout();
}


// --- HTML Form for adding to cart ---
?>
