    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email"><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" max="10"><br><br>

    <label for="total_price">Total Price:</label>
    <input type="number" id="total_price" name="total_price" step="0.01" min="1" max="100"><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'shopping_cart';
$dbUser = 'your_user';
$dbPass = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($userId, $productId, $quantity) {
    $conn = connectToDatabase();

    // Check if the user already has an item in the cart for this product
    $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item already exists, update the quantity
        $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        if ($conn->query($sql) === TRUE) {
            echo "Cart updated successfully!";
        } else {
            echo "Error updating cart: " . $conn->error;
        }
    } else {
        // Item not in cart, add it
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        if ($conn->query($sql) === TRUE) {
            echo "Item added to cart successfully!";
        } else {
            echo "Error adding item to cart: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}

// Function to view the cart
function viewCart($userId) {
    $conn = connectToDatabase();

    $sql = "SELECT p.product_name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Your Shopping Cart</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $total = $row['price'] * $row['quantity'];
            echo "<tr>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $total . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }

    $stmt->close();
    $conn->close();
}

// Function to remove an item from the cart
function removeFromCart($productId, $userId) {
    $conn = connectToDatabase();

    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    if ($conn->query($sql) === TRUE) {
        echo "Item removed from cart successfully!";
    } else {
        echo "Error removing item from cart: " . $conn->error;
    }

    $conn->close();
}

// Example Usage (This would typically be called from a form or other input)

// Simulate a user ID
$userId = 1; // Replace with the actual user ID

// Add an item to the cart
//addToCart($userId, 1, 2); // Add product 1 in quantity 2

// View the cart
viewCart($userId);

// Remove an item from the cart
//removeFromCart(1, $userId); // Remove product 1 from user 1's cart
?>
