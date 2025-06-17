
</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


//  Helper Function to sanitize inputs
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    $product_id = sanitizeInput($product_id);
    $quantity = sanitizeInput($quantity);

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_id = $product['id'];
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Check if the product is already in the cart
        $sql = "SELECT id FROM cart WHERE product_id = $product_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Product exists in the cart, update the quantity
            $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = $product_id";
        } else {
            // Product doesn't exist in the cart, add it
            $sql = "INSERT INTO cart (product_id, quantity) VALUES ($product_id, $quantity)";
        }

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // Product not found
    }
}



// Function to get the cart contents
function getCartContents() {
    $conn = connectToDatabase();

    $sql = "SELECT p.id AS product_id, p.name AS product_name, p.price AS product_price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id";
    $result = $conn->query($sql);

    $cart_contents = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cart_contents[] = $row;
        }
    }

    return $cart_contents;
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    $conn = connectToDatabase();
    $product_id = sanitizeInput($product_id);

    $sql = "DELETE FROM cart WHERE product_id = $product_id";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    $conn = connectToDatabase();
    $product_id = sanitizeInput($product_id);
    $quantity = sanitizeInput($quantity);

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_id = $product['id'];
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Check if the product is already in the cart
        $sql = "SELECT id FROM cart WHERE product_id = $product_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Product exists in the cart, update the quantity
            $sql = "UPDATE cart SET quantity = $quantity WHERE product_id = $product_id";
        } else {
            // Product doesn't exist in the cart, this should not happen
            return false;
        }

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // Product not found
    }
}

// Example Usage (This part would typically be in a separate PHP file, e.g., cart.php)

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    if (addToCart($product_id, $quantity)) {
        echo "Product added to cart.";
    } else {
        echo "Error adding product to cart.";
    }
}


// 2. Get Cart Contents (For Displaying the Cart)
$cart_contents = getCartContents();

// 3. Remove Item (Example)
if (isset($_GET['remove_item'])) {
    $product_id = $_GET['remove_item'];
    if (removeFromCart($product_id)) {
        echo "Product removed from cart.";
    } else {
        echo "Error removing product from cart.";
    }
}

// 4. Update Quantity (Example)
if (isset($_GET['update_quantity'])) {
  $product_id = $_GET['update_quantity'];
  $new_quantity = $_POST['quantity'];

  if (updateCartQuantity($product_id, $new_quantity)) {
    echo "Quantity updated successfully.";
  } else {
    echo "Error updating quantity.";
  }
}

?>
