
    <button type="submit">Add to Cart</button>
  </form>

  <a href="cart.php">View Cart</a> <!-- Link to cart page -  you need to create this page -->

</body>
</html>


<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    // Assuming you have a 'cart' table with 'user_id', 'product_id', 'quantity' columns
    $user_id = $_SESSION['user_id']; // Assuming you have user authentication
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to retrieve the cart items
function getCartItems($conn, $user_id) {
    $sql = "SELECT c.product_id, p.name, p.price, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = '$user_id'";

    $result = $conn->query($sql);
    $items = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }

    return $items;
}


// Function to remove an item from the cart
function removeFromCart($conn, $product_id, $user_id) {
    $sql = "DELETE FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity, $user_id) {
    // Check if the item exists in the cart
    $sql = "SELECT quantity FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_quantity = $row['quantity'];

        // Update the quantity
        $new_quantity = $current_quantity + $quantity;

        $sql = "UPDATE cart SET quantity = '$new_quantity' WHERE product_id = '$product_id' AND user_id = '$user_id'";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }

    } else {
        return false; // Item not found in cart
    }
}


// ---  Example Usage (This section is just for demonstration) ---

// 1.  Connect to the database
$conn = connectDB();

// 2.  Get the product ID from the request (e.g., from a form submission)
$product_id = $_GET['product_id'];

// 3.  Add the product to the cart
$quantity = $_POST['quantity']; // Get quantity from a form
if (addToCart($conn, $product_id, $quantity)) {
    echo "Product added to cart successfully!";
} else {
    echo "Failed to add product to cart.";
}


// 4.  Retrieve the cart items
$cart_items = getCartItems($conn, $_SESSION['user_id']);

// 5. Display the cart items (this is just an example, you'll need to adapt this to your template)
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// 6.  (Example: Remove item from cart)
// if (isset($_GET['remove_product'])) {
//     $product_id_to_remove = $_GET['remove_product'];
//     if (removeFromCart($conn, $product_id_to_remove, $_SESSION['user_id'])) {
//         echo "Product removed from cart successfully!";
//     } else {
//         echo "Failed to remove product from cart.";
//     }
// }


// 7. (Example: Update quantity)
// if (isset($_GET['update_quantity'])) {
//     $new_quantity = $_POST['quantity'];
//     if (updateQuantity($conn, $product_id, $new_quantity, $_SESSION['user_id'])) {
//         echo "Quantity updated successfully!";
//     } else {
//         echo "Failed to update quantity.";
//     }
// }



// Close the database connection (important!)
$conn->close();

?>
