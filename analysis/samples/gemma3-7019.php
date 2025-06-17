        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1"><br><br>

        <button type="submit">Add to Cart</button>
    </form>

</body>
</html>


<?php
session_start();

// Database connection (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_username';
$db_pass = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}

// Function to add to cart
function addToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = array('quantity' => $quantity);
        }
    } else {
        $cart = array($product_id => array('quantity' => $quantity));
        $_SESSION['cart'] = json_encode($cart);
    }

    // Update the session
    $_SESSION['cart'] = json_encode($cart);

    $conn->close();
}

// Function to display the cart
function displayCart() {
    $conn = connectToDatabase();

    $cart = json_decode($_SESSION['cart'], true);
    $total_price = 0;

    echo "<div class='cart-container'>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h2>Shopping Cart</h2>";
        echo "<table id='cartTable'>";
        echo "<thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr></thead>";
        echo "<tbody>";

        foreach ($cart as $product_id => $product_data) {
            $product_name = "Product " . $product_id; // Replace with your product name retrieval
            $product_price = getProductPrice($product_id); // Call function to get product price
            $item_total = $product_price * $product_data['quantity'];

            echo "<tr>";
            echo "<td>" . $product_name . "</td>";
            echo "<td>$" . number_format($product_price, 2) . "</td>";
            echo "<td>" . $product_data['quantity'] . "</td>";
            echo "<td>$" . number_format($item_total, 2) . "</td>";
            echo "<td><button type='button' onclick='removeFromCart(" . $product_id . ")'>Remove</button></td>";
            echo "</tr>";

            $total_price += $item_total;
        }

        echo "</tbody>";
        echo "<tfoot><tr><td colspan='4'><strong>Total: $" . number_format($total_price, 2) . "</strong></td></tr></tfoot>";
        echo "</table>";
    }
    echo "</div>";
}

// Function to remove from cart
function removeFromCart($product_id) {
    $conn = connectToDatabase();
    $cart = json_decode($_SESSION['cart'], true);

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }

    $_SESSION['cart'] = json_encode($cart);
    // Optionally clear the session entirely if you want a completely empty cart
    // unset($_SESSION['cart']);
    // $_SESSION['cart'] = json_encode(array());
}

// Function to get product price (Replace with your actual product retrieval logic)
function getProductPrice($product_id) {
  // Replace this with your actual database query to fetch product price.
  // This is just an example.
  if ($product_id == 1) {
    return 10.00;
  } elseif ($product_id == 2) {
    return 25.00;
  } else {
    return 0.00; // Default price for unknown products.
  }
}



// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
}

// Handle Remove from Cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    removeFromCart($product_id);
}


// Display the cart
displayCart();

?>
