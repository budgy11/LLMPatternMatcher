
</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
    // Check if the product already exists in the cart for this user
    $query = "SELECT * FROM cart WHERE product_id = '$product_id' AND user_id = 1;"; // Assuming user_id = 1
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Product exists, update the quantity
        $query = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id' AND user_id = 1;";
    } else {
        // Product doesn't exist, add a new entry
        $query = "INSERT INTO cart (product_id, user_id, quantity) VALUES ('$product_id', 1, $quantity)";
    }

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to retrieve the cart contents
function getCartContents($conn) {
    $query = "SELECT p.product_name, c.quantity, c.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $cart_items = array();
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
        return $cart_items;
    } else {
        return array();
    }
}

// Function to remove a product from the cart
function removeFromCart($conn, $product_id) {
    $query = "DELETE FROM cart WHERE product_id = '$product_id' AND user_id = 1";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to update the quantity of a product in the cart
function updateQuantity($conn, $product_id, $quantity) {
    $query = "UPDATE cart SET quantity = '$quantity' WHERE product_id = '$product_id' AND user_id = 1";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// ----- Example Usage (Handle form submissions) -----

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add to Cart
    if (isset($_POST["add_to_cart"])) {
        $product_id = $_POST["product_id"];
        $quantity = $_POST["quantity"];
        if (addToCart($conn, $product_id, $quantity)) {
            echo "Product added to cart!";
        } else {
            echo "Error adding product to cart.";
        }
    }

    // Remove from Cart
    if (isset($_POST["remove_from_cart"])) {
        $product_id = $_POST["product_id"];
        if (removeFromCart($conn, $product_id)) {
            echo "Product removed from cart!";
        } else {
            echo "Error removing product from cart.";
        }
    }

    // Update Quantity
    if (isset($_POST["update_quantity"])) {
        $product_id = $_POST["product_id"];
        $new_quantity = $_POST["quantity"];
        if (updateQuantity($conn, $product_id, $new_quantity)) {
            echo "Quantity updated!";
        } else {
            echo "Error updating quantity.";
        }
    }
}

// Retrieve cart contents for display
$cart_items = getCartContents($conn);

?>
