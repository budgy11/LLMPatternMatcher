    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
    //  Check if the product already exists in the cart
    $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Product already in cart, update quantity
        $row = $result->fetch_assoc();
        $query = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
        if ($conn->query($query) === TRUE) {
            echo "Product quantity updated successfully!";
        } else {
            echo "Error updating product quantity: " . $conn->error;
        }
    } else {
        // Product not in cart, add it
        $query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', $quantity)";
        if ($conn->query($query) === TRUE) {
            echo "Product added to cart successfully!";
        } else {
            echo "Error adding product to cart: " . $conn->error;
        }
    }
}

// Function to remove a product from the cart
function removeFromCart($conn, $product_id) {
    $query = "DELETE FROM cart WHERE product_id = '$product_id'";
    if ($conn->query($query) === TRUE) {
        echo "Product removed from cart successfully!";
    } else {
        echo "Error removing product from cart: " . $conn->error;
    }
}

// Function to update the quantity of a product in the cart
function updateQuantity($conn, $product_id, $new_quantity) {
  // Validate that quantity is a number
  if (!is_numeric($new_quantity) || $new_quantity <= 0) {
    echo "Invalid quantity. Please enter a positive integer.";
    return;
  }

  $query = "UPDATE cart SET quantity = '$new_quantity' WHERE product_id = '$product_id'";
  if ($conn->query($query) === TRUE) {
      echo "Product quantity updated successfully!";
  } else {
      echo "Error updating product quantity: " . $conn->error;
  }
}


// Function to display the cart contents
function displayCart($conn) {
    $query = "SELECT * FROM cart";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Your Shopping Cart</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['product_id'] . "</td>";
            echo "<td>" . $row['product_name'] . "</td>"; // Assuming you have a product_name column
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td><a href='update_quantity.php?product_id=" . $row['product_id'] . "&new_quantity=" . $row['quantity'] . "'>Update</a> | <a href='remove_from_cart.php?product_id=" . $row['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}

// ------------------  Example Usage & Handling  ------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] == "add_to_cart") {
        $product_id = $_POST["product_id"];
        $quantity = $_POST["quantity"];
        addToCart($conn, $product_id, $quantity);
    }
}

// ------------------  Example to display the cart  ------------------
displayCart($conn);

// Close the database connection
$conn->close();

?>
