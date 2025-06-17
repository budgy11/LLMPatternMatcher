

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
    // Check if the product already exists in the cart
    $sql = "SELECT * FROM cart WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id); // 'i' for integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product already in cart, update quantity
        $row = $result->fetch_assoc();
        $quantity_in_cart = $row['quantity'] + $quantity;
        $sql = "UPDATE cart SET quantity = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $quantity_in_cart, $product_id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } else {
        // Product not in cart, add it
        $sql = "INSERT INTO cart (product_id, quantity) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $product_id, $quantity);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    $stmt->close();
}

// Function to get the cart contents
function getCartContents($conn) {
    $sql = "SELECT * FROM cart";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $cart_contents = [];
        while ($row = $result->fetch_assoc()) {
            $cart_contents[] = $row;
        }
        return $cart_contents;
    } else {
        return [];
    }
}

// Function to remove a product from the cart
function removeFromCart($conn, $product_id) {
    $sql = "DELETE FROM cart WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $stmt->close();
}

// Function to update the quantity of a product in the cart
function updateQuantity($conn, $product_id, $new_quantity) {
    if ($new_quantity <= 0) {
        // Don't allow zero or negative quantities
        removeFromCart($conn, $product_id);
        return false;
    }

    $sql = "UPDATE cart SET quantity = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $new_quantity, $product_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $stmt->close();
}

// --- Example Usage (Demonstration) ---

// 1. Connect to the database
$conn = connectToDatabase();

// 2. Add a product to the cart (example: product_id = 1)
if (addToCart($conn, 1, 2)) {
    echo "Product 1 added to cart (quantity = 2)
";
} else {
    echo "Failed to add Product 1 to cart.
";
}

// 3. Get the cart contents
$cart = getCartContents($conn);
echo "Cart Contents:
";
foreach ($cart as $item) {
    echo "- Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "
";
}

// 4. Update quantity of product 1
if (updateQuantity($conn, 1, 5)) {
    echo "Quantity of Product 1 updated to 5.
";
} else {
    echo "Failed to update Product 1 quantity.
";
}

// 5. Get the cart contents again
$cart = getCartContents($conn);
echo "Updated Cart Contents:
";
foreach ($cart as $item) {
    echo "- Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "
";
}

// 6. Remove a product from the cart (example: product_id = 2)
if (removeFromCart($conn, 2)) {
    echo "Product 2 removed from cart.
";
} else {
    echo "Failed to remove Product 2 from cart.
";
}

// 7. Get the cart contents
$cart = getCartContents($conn);
echo "Final Cart Contents:
";
foreach ($cart as $item) {
    echo "- Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "
";
}

// Close the database connection (Important!)
$conn->close();

?>
