
    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_name = 'e_commerce';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a product to the cart
function addProductToCart($productId, $quantity) {
    $conn = connectToDatabase();

    // Check if the product exists
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $productName = $row['name'];
        $productPrice = $row['price'];

        // Check if the product is already in the cart
        $cart_key = 'cart_' . session_id();
        if (isset($_SESSION[$cart_key])) {
            $cart = $_SESSION[$cart_key];
            $found = false;
            foreach ($cart as $index => $item) {
                if ($item['id'] == $productId) {
                    $cart[$index]['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = [
                    'id' => $productId,
                    'name' => $productName,
                    'price' => $productPrice,
                    'quantity' => $quantity
                ];
            }
        } else {
            $cart = [[
                'id' => $productId,
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => $quantity
            ]];
            $_SESSION[$cart_key] = $cart;
        }

        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}

// Function to get the cart contents
function getCartContents() {
    $cart_key = 'cart_' . session_id();
    if (isset($_SESSION[$cart_key])) {
        return $_SESSION[$cart_key];
    } else {
        return [];
    }
}

// Function to remove a product from the cart
function removeProductFromCart($productId) {
    $cart_key = 'cart_' . session_id();
    if (isset($_SESSION[$cart_key])) {
        $cart = $_SESSION[$cart_key];
        foreach ($cart as $index => $item) {
            if ($item['id'] == $productId) {
                unset($cart[$index]);
                // Re-index the array to avoid gaps
                $cart = array_values($cart);
                break;
            }
        }
        $_SESSION[$cart_key] = $cart;
        return true;
    } else {
        return false;
    }
}

// Function to update the quantity of a product in the cart
function updateQuantityInCart($productId, $quantity) {
  $cart_key = 'cart_' . session_id();

  if (isset($_SESSION[$cart_key])) {
    $cart = $_SESSION[$cart_key];
    foreach ($cart as $index => $item) {
      if ($item['id'] == $productId) {
        $item['quantity'] = $quantity;
        $cart[$index] = $item;
        break;
      }
    }
    $_SESSION[$cart_key] = $cart;
    return true;
  } else {
    return false;
  }
}

// *** Example Usage (for testing, replace with your actual form handling) ***

// Example: Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addProductToCart($productId, $quantity);
    echo "<p>Product added to cart.</p>";
}

// Example: Display the cart contents
$cartContents = getCartContents();
echo "<h2>Cart</h2>";
if (empty($cartContents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartContents as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['quantity'] * $item['price'] . "</li>";
    }
    echo "</ul>";
}


// Example: Remove a product
if (isset($_POST['remove_product'])) {
  $productId = $_POST['product_id_to_remove'];
  removeProductFromCart($productId);
  echo "<p>Product removed from cart.</p>";
}

// Example: Update quantity
if (isset($_POST['update_quantity'])) {
  $productId = $_POST['product_id_to_update'];
  $newQuantity = $_POST['new_quantity'];
  updateQuantityInCart($productId, $newQuantity);
  echo "<p>Quantity updated in cart.</p>";
}

?>
