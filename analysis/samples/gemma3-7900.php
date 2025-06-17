    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br>
    <button type="submit">Add to Cart</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'ecommerce_db';
$db_user = 'your_user';
$db_password = 'your_password';

// Function to connect to the database
function connectToDatabase() {
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    // Handle connection errors appropriately (log, display message, etc.)
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed.  Check your credentials and server status.");
  }
}

// Function to add a product to the cart
function addToCart($product_id, $quantity, $user_id) {
  $pdo = connectToDatabase();

  try {
    // Check if the product exists
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
      // Product not found
      return false;
    }

    // Check if the user has a cart
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
      // User has no cart, create a new one
      $new_cart_query = "INSERT INTO carts (user_id) VALUES (?)";
      $stmt = $pdo->prepare($new_cart_query);
      $stmt->execute([$user_id]);
      $cart_id = $pdo->lastInsertId();
    } else {
      // User has a cart
      $cart_id = $cart['id'];
    }


    // Add the product to the cart
    $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$cart_id, $product_id, $quantity]);

    return true;
  } catch (PDOException $e) {
    // Handle errors
    error_log("Error adding to cart: " . $e->getMessage());
    return false;
  }
}


// Function to view the cart
function viewCart($user_id) {
  $pdo = connectToDatabase();

  try {
    // Get cart items for the user
    $stmt = $pdo->prepare("SELECT c.id, ci.product_id, p.name, p.price, ci.quantity FROM cart_items ci JOIN carts c ON ci.cart_id = c.id JOIN products p ON ci.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $cart_items;
  } catch (PDOException $e) {
    // Handle errors
    error_log("Error viewing cart: " . $e->getMessage());
    return [];
  }
}

// Function to update the quantity of a product in the cart
function updateCartItemQuantity($product_id, $new_quantity, $user_id) {
    $pdo = connectToDatabase();

    try {
        // Check if the user has a cart
        $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            // User has no cart
            return false;
        }

        // Check if the cart item exists
        $stmt = $pdo->prepare("SELECT id FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart['id'], $product_id]);
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart_item) {
            // Cart item doesn't exist
            return false;
        }

        // Update the quantity
        $query = "UPDATE cart_items SET quantity = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$new_quantity, $cart_item['id']]);

        return true;

    } catch (PDOException $e) {
        error_log("Error updating cart item: " . $e->getMessage());
        return false;
    }
}


// Function to remove a product from the cart
function removeFromCart($product_id, $user_id) {
    $pdo = connectToDatabase();

    try {
        // Check if the user has a cart
        $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            // User has no cart
            return false;
        }

        // Check if the cart item exists
        $stmt = $pdo->prepare("SELECT id FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart['id'], $product_id]);
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart_item) {
            // Cart item doesn't exist
            return false;
        }

        // Delete the cart item
        $query = "DELETE FROM cart_items WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$cart_item['id']]);

        return true;

    } catch (PDOException $e) {
        error_log("Error removing from cart: " . $e->getMessage());
        return false;
    }
}


// --- Example Usage (Simulated - replace with your actual logic) ---

// Add a product to the cart
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
  $product_id = (int)$_POST['product_id']; // Sanitize input!
  $quantity = (int)$_POST['quantity'];
  $user_id = (int)$_SESSION['user_id'];  // Assuming you have a user ID in session

  if (addToCart($product_id, $quantity, $user_id)) {
    echo "Product added to cart!";
  } else {
    echo "Error adding product to cart.";
  }
}


// View the cart
if (isset($_SESSION['user_id'])) {
  $cart_items = viewCart($_SESSION['user_id']);
  echo "<h2>Your Cart</h2>";
  if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<table><thead><tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr></thead><tbody>";
    foreach ($cart_items as $item) {
      echo "<tr>";
      echo "<td>" . $item['name'] . "</td>";
      echo "<td>$" . $item['price'] . "</td>";
      echo "<td>" . $item['quantity'] . "</td>";
      echo "<td><button onclick=\"updateQuantity(" . $item['product_id'] . ", 1)">Update</button>  <button onclick=\"removeFromCart(" . $item['product_id'] . ")\">Remove</button></td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
  }
}

?>
