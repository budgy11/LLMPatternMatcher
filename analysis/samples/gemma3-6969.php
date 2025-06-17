

<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have a 'products' table with columns: id, name, price
// And a 'carts' table with columns: id, user_id, product_id, quantity

// Function to add a product to the cart
function add_to_cart($user_id, $product_id, $quantity) {
    global $conn;

    // Check if product exists
    $sql_check = "SELECT id FROM products WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $product_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Product exists, proceed to add to cart
        $sql_insert = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $user_id, $product_id, $quantity);
        if ($stmt_insert->execute()) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // Product not found
    }
}

// Function to get the cart items for a user
function get_cart_items($user_id) {
    $sql = "SELECT p.id AS product_id, p.name, p.price, c.quantity FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    return $cart_items;
}


// Function to update the quantity of a product in the cart
function update_cart_quantity($user_id, $product_id, $quantity) {
    $sql = "UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_params("iii")->execute();

    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to remove a product from the cart
function remove_from_cart($user_id, $product_id) {
    $sql = "DELETE FROM carts WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    return $stmt->execute();
}


// --- Example Usage (Simulated Purchase) ---

// 1. User adds a product to the cart
$user_id = 1; // Example user ID
$product_id = 1; // Example product ID
$quantity = 2;

if (add_to_cart($user_id, $product_id, $quantity)) {
    echo "Product added to cart successfully!
";
} else {
    echo "Failed to add product to cart.
";
}

// 2. Get the cart items
$cart_items = get_cart_items($user_id);

echo "Cart Items:
";
foreach ($cart_items as $item) {
    echo "Product ID: " . $item['product_id'] . ", Name: " . $item['name'] . ", Price: " . $item['price'] . ", Quantity: " . $item['quantity'] . "
";
}

// 3. User updates the quantity of a product
$updated_quantity = 3;
if (update_cart_quantity($user_id, $product_id, $updated_quantity)) {
    echo "Quantity updated successfully!
";
} else {
    echo "Failed to update quantity.
";
}


// 4. Get the updated cart items
$updated_cart_items = get_cart_items($user_id);

echo "
Updated Cart Items:
";
foreach ($updated_cart_items as $item) {
    echo "Product ID: " . $item['product_id'] . ", Name: " . $item['name'] . ", Price: " . $item['price'] . ", Quantity: " . $item['quantity'] . "
";
}

// 5. User removes a product from the cart
if (remove_from_cart($user_id, $product_id)) {
    echo "Product removed from cart.
";
} else {
    echo "Failed to remove product from cart.
";
}


// 6. Get the updated cart items (after removal)
$final_cart_items = get_cart_items($user_id);

echo "
Final Cart Items:
";
foreach ($final_cart_items as $item) {
    echo "Product ID: " . $item['product_id'] . ", Name: " . $item['name'] . ", Price: " . $item['price'] . ", Quantity: " . $item['quantity'] . "
";
}
?>
