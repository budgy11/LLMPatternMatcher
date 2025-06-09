
<?php
session_start();

// Initialize cart data if not already set
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get cart contents
function get_cart_contents() {
  return $_SESSION['cart'];
}


// ---  Shopping Cart Operations ---

// 1. Add to Cart (Handle POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default quantity is 1
    add_to_cart($product_id, $product_name, $price, $quantity);
  } elseif (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    update_cart_quantity($product_id, $new_quantity);
  } elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
  }
}



// --- Display the Cart ---

// Get cart contents
$cart_contents = get_cart_contents();

// Calculate total price
$total_price = 0;
if (count($cart_contents) > 0) {
  foreach ($cart_contents as $item_id => $item_data) {
    $total_price += $item_data['price'] * $item_data['quantity'];
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
      border: 1px solid #ddd;
      padding: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_contents)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>

    <table id="cart-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_contents as $item_id => $item_data): ?>
          <tr class="cart-item">
            <td><?php echo $item_data['name']; ?></td>
            <td><?php echo number_format($item_data['price'], 2); ?></td>
            <td><?php echo $item_data['quantity']; ?></td>
            <td><?php echo number_format($item_data['price'] * $item_data['quantity'], 2); ?></td>
            <td>
              <a href="?action=remove_from_cart&product_id=<?php echo $item_id; ?>">Remove</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p><strong>Total:</strong> <?php echo number_format($total_price, 2); ?></p>
  <?php endif; ?>

  <hr>

  <h2>Add to Cart</h2>
  <?php if (empty($cart_contents)): ?>
    <p>No products to add.</p>
  <?php else: ?>

    <?php
    // Example product data (replace with your actual product data)
    $products = [
      1 => ['name' => 'Laptop', 'price' => 1200],
      2 => ['name' => 'Mouse', 'price' => 25],
      3 => ['name' => 'Keyboard', 'price' => 75]
    ];

    foreach ($products as $product_id => $product_data):
      echo '<form method="post" action="">
              <label for="product_id_'.$product_id.'">Product: <input type="hidden" name="product_id" value="'.$product_id.'">';
      echo '<input type="text" name="product_name" value="' . $product_data['name'] . '" readonly>';
      echo '<input type="number" name="price" value="' . $product_data['price'] . '" readonly>
              <label for="quantity">Quantity:</label>
              <input type="number" name="quantity" value="1" min="1" max="10" id="quantity">
              <input type="submit" name="action" value="add_to_cart">
            </form><br>';
    endforeach; ?>

  <?php endif; ?>
</body>
</html>


<?php
session_start();

// Database connection (replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = ['name' => $item_name, 'price' => $price, 'quantity' => $quantity];
    } else {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    }
}

// Function to update cart item quantity
function update_cart_quantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}


// Function to remove item from cart
function remove_from_cart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


// Function to get cart items
function get_cart_items() {
    return $_SESSION['cart'];
}

// Function to calculate cart total
function calculate_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle add to cart action
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    add_to_cart($item_id, $item_name, $price, $quantity);
}

// Handle update quantity action
if (isset($_POST['update_quantity'])) {
  $item_id = $_POST['item_id'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
  update_cart_quantity($item_id, $quantity);
}

// Handle remove from cart action
if (isset($_POST['remove_from_cart'])) {
    $item_id = $_POST['item_id'];
    remove_from_cart($item_id);
}

// Get cart items to display
$cart_items = get_cart_items();

// Calculate cart total
$total = calculate_cart_total();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .cart-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .cart-total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table id="cart_table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr class="cart-item">
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <a href="?action=remove&item_id=<?php echo $item['item_id']; ?>">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="cart-total">
        Total: <?php echo number_format($total, 2); ?>
    </div>
<?php endif; ?>

</body>
</html>


<?php
session_start();

// Configuration
$items = []; // Array to store items in the cart
$cart_file = 'cart.json'; // File to store cart data

// Function to load cart from JSON file
function loadCart() {
    if (file_exists($cart_file)) {
        $data = file_get_contents($cart_file);
        if ($data) {
            $cart = json_decode($data, true);
            return $cart;
        } else {
            return []; // Return empty array if file is corrupt
        }
    }
    return [];
}

// Function to save cart to JSON file
function saveCart($cart) {
    file_put_contents($cart_file, json_encode($cart, JSON_PRETTY_PRINT));
}


// --------------------- Cart Functions ---------------------

// Add item to cart
function add_to_cart($product_id, $quantity = 1) {
    $cart = loadCart();

    // Check if product already in cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            saveCart($cart);
            return;
        }
    }

    // If not in cart, add new item
    $cart[] = ['product_id' => $product_id, 'quantity' => $quantity];
    saveCart($cart);
}


// Update quantity of item in cart
function update_quantity($product_id, $quantity) {
    $cart = loadCart();

    // Find the product in the cart and update the quantity
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            saveCart($cart);
            return;
        }
    }

    // Product not found - could be handled differently (e.g., throw an error)
    echo "<p>Product ID '$product_id' not found in cart.</p>";
}


// Remove item from cart
function remove_from_cart($product_id) {
    $cart = loadCart();

    // Filter out the item to be removed
    $cart = array_filter($cart, function ($item) use ($product_id) {
        return $item['product_id'] !== $product_id;
    });

    saveCart($cart);
}


// Get cart contents
function get_cart_contents() {
    return loadCart();
}


// Calculate total price
function calculate_total_price() {
    $cart = get_cart_contents();
    $total = 0;
    foreach ($cart as $item) {
        // Assuming you have a product database or data source
        //  and you can retrieve the price.  Replace this with your actual logic.
        $product_price = get_product_price($item['product_id']); // Implement this function
        $total_item_price = $product_price * $item['quantity'];
        $total += $total_item_price;
    }
    return $total;
}

// --------------------- Product Data (Dummy for Example) ---------------------
// Replace this with your actual product data source
function get_product_price($product_id) {
    // This is a dummy function. Replace it with your database query or data retrieval.
    switch ($product_id) {
        case 1: return 10.00;
        case 2: return 25.50;
        case 3: return 5.75;
        default: return 0; // Price not found
    }
}

// --------------------- Example Usage (In a View/Page) ---------------------

// Add to cart (example)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;  // Default to 1
    add_to_cart($product_id, $quantity);
    echo "<p>Item added to cart.</p>";
}

// Update quantity (example)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default to 1
    update_quantity($product_id, $quantity);
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// Get cart contents to display
$cart_contents = get_cart_contents();

// Calculate total price
$total_price = calculate_total_price();

// Display cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $item) {
        echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . ", Price: $" . get_product_price($item['product_id']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}
?>


<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    // Handle the case where the product is not in the cart
    // You might want to log this or display an error message
    // For this example, we'll just do nothing.
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents as an associative array.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart isn't initialized. Use null coalescing operator.
}


/**
 * Calculates the total cost of the cart.
 *
 * @return float The total cost.
 */
function calculateTotal() {
  $total = 0;
  $cart = getCartContents();
  foreach ($cart as $product_id => $quantity) {
    // Assume you have a function to get the product price (e.g., getProductPrice($product_id))
    $price = getProductPrice($product_id); // Replace with your actual function call
    $totalForProduct = $price * $quantity;
    $total += $totalForProduct;
  }
  return $total;
}


/**
 *  Placeholder function to get product price.  Replace with your database query or logic.
 * @param int $product_id
 * @return float
 */
function getProductPrice($product_id) {
    //  Replace this with your database query or other logic to get the price
    //  This is just a placeholder
    switch($product_id) {
        case 1: return 10.00;
        case 2: return 25.50;
        default: return 0;
    }
}



// ---  Shopping Cart Implementation (Example) ---

// 1. Add to Cart (Example)
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];

  addToCart($product_id, $quantity);
  echo "<p>Item added to cart.  Total: " . calculateTotal() . "</p>";
}

// 2. Update Cart Quantity
if (isset($_POST['update_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateCartQuantity($product_id, $quantity);
    echo "<p>Cart updated.  Total: " . calculateTotal() . "</p>";
}


// 3. Remove from Cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = (int)$_POST['product_id'];
  removeFromCart($product_id);
  echo "<p>Item removed from cart.  Total: " . calculateTotal() . "</p>";
}

// 4. Display Cart Contents
$cartContents = getCartContents();
echo "<h2>Your Shopping Cart</h2>";

if (empty($cartContents)) {
    echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
  echo "<p>Total Cost: $" . calculateTotal() . "</p>";
}
?>


function getProductPrice($product_id) {
  // Assuming you have a database connection established as $db

  $stmt = $db->prepare("SELECT price FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id); // 'i' for integer
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    return $row['price'];
  } else {
    return 0; // Or handle the error appropriately (e.g., log the error)
  }

  $stmt->close();
}


<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Product Data (For demonstration - Replace with database query) ---
$products = [
  ["id" => 1, "name" => "T-Shirt", "price" => 20],
  ["id" => 2, "name" => "Jeans", "price" => 50],
  ["id" => 3, "name" => "Hat", "price" => 15],
];

// --- Cart Logic ---

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Add to Cart Function
function addToCart($product_id, $_SESSION) {
  $product_id = (int)$product_id;  // Ensure product_id is an integer

  // Find the product in the product array
  $product = null;
  foreach ($products as $p) {
    if ($p['id'] == $product_id) {
      $product = $p;
      break;
    }
  }

  if ($product) {
    if (!isset($_SESSION['cart'][$product['id']])) {
      $_SESSION['cart'][$product['id']] = 1; // Add to cart
    } else {
      $_SESSION['cart'][$product['id']]++; // Increment quantity
    }
  }
}

// Remove from Cart Function
function removeFromCart($product_id, $_SESSION) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Get Cart Contents
function getCartContents($_SESSION) {
  $cart_contents = [];
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = null;
    foreach ($products as $p) {
      if ($p['id'] == $product_id) {
        $product = $p;
        break;
      }
    }
    if ($product) {
      $cart_contents[] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'total' => $product['price'] * $quantity
      ];
    }
  }
  return $cart_contents;
}

// ---  Handle Actions (e.g., Add to Cart) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  addToCart($product_id, $_SESSION);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id, $_SESSION);
}



// --- Display the Cart ---

$cart = getCartContents($_SESSION);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Purchase Cart</title>
  <style>
    .cart-item {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }
    .cart-total {
      font-weight: bold;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h1>Purchase Cart</h1>

  <form method="post">
    <?php if (count($cart) > 0): ?>
      <h2>Cart Items</h2>
      <?php foreach ($cart as $item): ?>
        <div class="cart-item">
          <strong><?php echo $item['name']; ?></strong> - $<?php echo $item['price']; ?>
          <p>Quantity: <?php echo $item['quantity']; ?></p>
          <p>Total: $<?php echo $item['total']; ?></p>
          <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
            <button type="submit" name="action" value="remove_from_cart">Remove</button>
          </form>
        </div>
      <?php
    endif;
    ?>

    <br>
    <a href="checkout.php">Proceed to Checkout</a> <!-- Replace with your checkout logic -->

  </form>

  <br>
  <p>Total Items in Cart: <?php echo count($cart); ?></p>
  <p>Total Cart Value: $<?php echo round(array_sum(array_column($cart, 'total')), 2); ?></p>

</body>
</html>


<?php
session_start();

// Configuration (Adjust these as needed)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 1],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 1],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 1],
];

// Cart initialization
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Helper Functions
function add_to_cart($product_id, $quantity) {
    global $products;

    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

function get_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return round($total, 2);
}

function display_cart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . get_cart_total() . "</strong></p>";
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    add_to_cart($product_id, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// Handle Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = (int)$_GET['remove_from_cart'];
    remove_from_cart($product_id);
    header("Location: cart.php");
    exit();
}

// Handle Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    update_cart_quantity($product_id, $quantity);
    header("Location: cart.php");
    exit();
}

// Display the cart
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .product {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 200px;
        }
    </style>
</head>
<body>

    <h1>Online Store</h1>

    <div class="product">
        <h2>T-Shirt</h2>
        <p>Price: $20.00</p>
        <form method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
            <button type="submit" name="update_quantity" value="<?php echo $product_id = 1; ?>">Add to Cart</button>
        </form>
    </div>

    <div class="product">
        <h2>Jeans</h2>
        <p>Price: $50.00</p>
        <form method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
            <button type="submit" name="update_quantity" value="<?php echo $product_id = 2; ?>">Add to Cart</button>
        </form>
    </div>

    <div class="product">
        <h2>Hat</h2>
        <p>Price: $15.00</p>
        <form method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
            <button type="submit" name="update_quantity" value="<?php echo $product_id = 3; ?>">Add to Cart</button>
        </form>
    </div>


    <hr>

    <?php display_cart(); ?>


</body>
</html>


<?php
session_start();

// Configuration
$items = []; // Array to store shopping cart items
$cart_file = 'cart.json'; // File to store cart data

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price of the product.
 */
function addToCart($product_id, $name, $quantity, $price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  $product_exists = false;
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] += $quantity;
      $product_exists = true;
      break;
    }
  }

  // If the product is not in the cart, add it
  if (!$product_exists) {
    $_SESSION['cart'][] = [
      'id' => $product_id,
      'name' => $name,
      'quantity' => $quantity,
      'price' => $price
    ];
  }

  // Save the cart data to the file
  saveCartToFile($_SESSION['cart']);
}


/**
 * Removes an item from the cart by its ID.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Optionally: Re-index the array to avoid gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        break;
      }
    }
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        break;
      }
    }
  }
}


/**
 * Gets the cart total.
 *
 * @return float The total cost of the cart.
 */
function getCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


/**
 * Saves the cart data to a JSON file.
 *
 * @param array $cart_data The cart data to save.
 */
function saveCartToFile($cart_data) {
  file_put_contents($cart_file, json_encode($cart_data, JSON_PRETTY_PRINT));
}

/**
 * Loads the cart data from the JSON file.
 */
function loadCartFromFile() {
  global $items; // Access the global array

  if (file_exists($cart_file)) {
    $cart_data = file_get_contents($cart_file);
    if ($cart_data = json_decode($cart_data, true)) {
      $items = $cart_data;
    }
  }
}



// --- Main Script ---

// Load cart data from file
loadCartFromFile();


// --- Cart Actions (Based on Form Submission) ---

if ($_SERVER->is_uploaded_file) { // Check if the form was submitted
  if (isset($_POST['action']) && isset($_POST['product_id'])) {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    switch ($action) {
      case 'add':
        addToCart($product_id, $_POST['name'], $quantity, $_POST['price']);
        break;
      case 'remove':
        removeFromCart($product_id);
        break;
      case 'update':
        updateQuantity($product_id, $quantity);
        break;
    }
  }
}

// --- Cart Display ---

$cart_total = getCartTotal();

echo '<h2>Shopping Cart</h2>';

if (empty($_SESSION['cart'])) {
  echo '<p>Your cart is empty.</p>';
} else {
  echo '<ul>';
  foreach ($_SESSION['cart'] as $item) {
    echo '<li>';
    echo '<span class="product-name">' . $item['name'] . '</span>';
    echo ' - Quantity: ' . $item['quantity'] . ' - Price: $' . $item['price'] . ' - Total: $' . $item['price'] * $item['quantity'] . '</li>';
  }
  echo '</ul>';
  echo '<p><strong>Total: $' . $cart_total . '</strong></p>';
}

?>

<!-- Add some CSS for styling (example) -->
<style>
  .product-name {
    font-weight: bold;
  }
</style>


<?php
session_start();

// Configuration
$cart_file = 'cart.json'; // File to store the cart data
$item_name_key = 'item_name';
$item_price_key = 'item_price';
$quantity_key = 'quantity';

// Function to load cart from JSON file
function loadCart() {
  if (file_exists($cart_file)) {
    $cart = json_decode(file_get_contents($cart_file), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return array(); // Return an empty array in case of JSON error
    }
    return $cart;
  } else {
    return array(); // Return an empty array if the file doesn't exist
  }
}

// Function to save cart to JSON file
function saveCart($cart) {
  $json_data = json_encode($cart, JSON_PRETTY_PRINT); // Use JSON_PRETTY_PRINT for readability
  if ($json_data = file_put_contents($cart_file, $json_data)) {
    return $json_data;
  } else {
    return false;
  }
}


// ----------------------- Cart Management Functions -----------------------

// Add an item to the cart
function addToCart($item_name, $item_price, $quantity = 1) {
  $cart = loadCart();

  $item_name = trim($item_name); // Clean the item name
  $item_price = (float) $item_price; // Ensure price is a float
  $quantity = (int) $quantity; // Ensure quantity is an integer

  if ($quantity <= 0) {
    return false; // Invalid quantity
  }

  $item_name = $item_name;
  if (isset($cart[$item_name])) {
    $cart[$item_name]['quantity'] += $quantity;
  } else {
    $cart[$item_name] = [
      $item_name_key => $item_name,
      $item_price_key => $item_price,
      $quantity_key => $quantity
    ];
  }
  return saveCart($cart);
}

// Remove an item from the cart
function removeFromCart($item_name) {
  $cart = loadCart();
  $item_name = trim($item_name);

  if (isset($cart[$item_name])) {
    unset($cart[$item_name]);
    return saveCart($cart);
  } else {
    return false; // Item not found in cart
  }
}

// Update quantity of an item in the cart
function updateQuantity($item_name, $new_quantity) {
  $cart = loadCart();
  $item_name = trim($item_name);
  $new_quantity = (int) $new_quantity;

  if (isset($cart[$item_name])) {
    $cart[$item_name][$quantity_key] = $new_quantity;
    return saveCart($cart);
  } else {
    return false; // Item not found in cart
  }
}


// Get the cart contents
function getCartContents() {
  $cart = loadCart();
  return $cart;
}

// -----------------------  Cart Display Functions -----------------------

// Display the cart contents
function displayCart() {
  $cart = getCartContents();

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

  foreach ($cart as $item_name => $details) {
    $price = $details[$item_price_key];
    $quantity = $details[$quantity_key];
    $total = $price * $quantity;

    echo "<tr>";
    echo "<td>" . $details[$item_name_key] . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total, 2) . "</td>";
    echo "<td><a href='cart.php?action=remove&item=$item_name'>Remove</a></td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<p><a href='checkout.php'>Checkout</a></p>";
}


// -----------------------  Example Usage (for demonstration only - use within a PHP page) -----------------------

// Example usage for adding items.  This is just to show how you'd call the functions
/*
if (isset($_POST['add_to_cart'])) {
  $item_name = $_POST['item_name'];
  $item_price = (float)$_POST['item_price'];
  $quantity = (int)$_POST['quantity'];

  addToCart($item_name, $item_price, $quantity);
  echo "<p>Item added to cart!</p>";
}
*/

?>


<?php
session_start();

// Your checkout logic here
echo "<p>Thank you for your order!</p>";
// Example:  Store order details in a database, send confirmation email, etc.
?>


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

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 20px;
            width: 500px;
        }

        #cartTable {
            width: 100%;
            border-collapse: collapse;
        }

        #cartTable th, #cartTable td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <!-- Product Listing (Replace with your actual product listing) -->
    <h2>Products</h2>
    <div class="products">
      <form method="post">
        <label for="product_1">Product 1 (ID: 1)</label><br>
        <input type="number" id="quantity_1" name="quantity_1" value="1"><br><br>
        <input type="hidden" name="product_id" value="1"><br><br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
      </form><br>

      <form method="post">
        <label for="product_2">Product 2 (ID: 2)</label><br>
        <input type="number" id="quantity_2" name="quantity_2" value="1"><br><br>
        <input type="hidden" name="product_id" value="2"><br><br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
      </form>
    </div>


</body>
</html>


<?php
session_start();

// Database connection (Replace with your database details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Define product data (for demonstration - replace with your database)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Cart function
function get_cart() {
    if (empty($_SESSION['cart'])) {
        return [];
    }
    return $_SESSION['cart'];
}

function add_to_cart($product_id, $quantity = 1) {
    $cart = get_cart();

    // Check if product exists
    if (!isset($products[$product_id])) {
        return false;
    }

    // Check if product is already in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return true;
        }
    }

    // If not in cart, add it
    $cart[] = $products[$product_id];
    return true;
}

function remove_from_cart($product_id) {
    $cart = get_cart();
    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return true;
        }
    }
    return false;
}


function calculate_total() {
    $cart = get_cart();
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    add_to_cart($product_id, (int)$_POST['quantity']); // Ensure quantity is an integer
    // Optionally, you could redirect to a success page or refresh the cart view
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    remove_from_cart($product_id);
}

// Display Cart
$cart = get_cart();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post">
        <?php if (empty($cart)) {
            echo "<p>Your cart is empty.</p>";
        } else { ?>
            <table id="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($cart as $item) {
                        $product_name = $item['name'];
                        $product_price = $item['price'];
                        $quantity = $item['quantity'];
                        $item_total = $product_price * $quantity;
                        $total += $item_total;

                        echo "<tr class='cart-item'>
                                <td>" . $product_name . "</td>
                                <td>$" . $product_price . "</td>
                                <td>" . $quantity . "</td>
                                <td>$" . $item_total . "</td>
                                <td>
                                    <form method='post'>
                                        <input type='hidden' name='product_id' value='" . $item['id'] . "'>
                                        <button type='submit'>Remove</button>
                                    </form>
                                </td>
                            </tr>";
                    } ?>
                </tbody>
            </table>

            <p><strong>Total:</strong> $" . $total . "</p>
        <?php } ?>
    </form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "root";
$db_pass = "";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add item to cart
function addToCart($product_id, $quantity) {
  $conn = connectToDatabase();

  // Check if the product exists
  $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $product_name = $row["name"];
    $product_price = $row["price"];

    // Check if the product is already in the cart
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key][$product_id])) {
      $_SESSION[$cart_key][$product_id]["quantity"] += $quantity;
    } else {
      $_SESSION[$cart_key][$product_id] = [
        "name" => $product_name,
        "price" => $product_price,
        "quantity" => $quantity,
      ];
    }
  } else {
    // Product not found
    echo "Product with ID " . $product_id . " not found.";
  }

  $stmt->close();
}

// Function to get cart contents
function getCartContents() {
  $cart_key = "cart_" . session_id();

  if (isset($_SESSION[$cart_key])) {
    return $_SESSION[$cart_key];
  } else {
    return []; // Return an empty array if the cart is empty
  }
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key][$product_id])) {
        unset($_SESSION[$cart_key][$product_id]);
    }
}


// ---  Handlers for adding to cart and handling the cart  ---

// Check for POST requests to add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  addToCart($product_id, $quantity);
}

// Check for POST requests to remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_item"])) {
    $product_id = $_POST["product_id"];
    removeItemFromCart($product_id);
}

// Display the cart contents
$cart_contents = getCartContents();

$total_price = 0;
if (!empty($cart_contents)) {
  foreach ($cart_contents as $item) {
    $total_price += $item["price"] * $item["quantity"];
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    body { font-family: sans-serif; }
    .cart-item { margin-bottom: 10px; }
    .cart-total { font-weight: bold; }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (!empty($cart_contents)) { ?>
    <table id="cartTable">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_contents as $item) { ?>
          <tr class="cart-item">
            <td><?php echo $item["name"]; ?></td>
            <td>$<?php echo number_format($item["price"], 2); ?></td>
            <td><?php echo $item["quantity"]; ?></td>
            <td>$<?php echo number_format($item["price"] * $item["quantity"], 2); ?></td>
            <td>
              <a href="?action=remove&product_id=<?php echo $item["id"]; ?>">Remove</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="cart-total">Total: $<?php echo number_format($total_price, 2); ?></div>
  <?php } else { ?>
    <p>Your cart is empty.</p>
  <?php } ?>

  <h2>Add to Cart</h2>
  <?php if (!empty($cart_contents)) { ?>
    <form method="post" action="">
      <?php
      $products = [];
      foreach($cart_contents as $item) {
        $products[$item['id']] = $item;
      }

      foreach ($products as $product_id => $product_data) {
          echo "<label for=\"product_" . $product_id . "\">" . $product_data["name"] . ":</label>";
          echo "<input type=\"number\" id=\"product_" . $product_id . "\" name=\"quantity\" min=\"1\" value=\"" . $product_data["quantity"] . "\"><br>";
          }

        ?>

      <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
      <input type="submit" value="Update Cart">
    </form>
  <?php } ?>
</body>
</html>


<?php

// Define the cart file
$cartFile = 'cart.txt';

// Initialize the cart as an empty array if the file doesn't exist
$cart = file_exists($cartFile) ?  file($cartFile, FILE_IGNORE_NEW_LINES) : [];

// Function to add an item to the cart
function addToCart($cart, $item, $quantity = 1) {
    if (isset($cart[$item]) ) {
        $cart[$item] += $quantity;
    } else {
        $cart[$item] = $quantity;
    }
    file_put_contents($cartFile, json_encode($cart)); // Save the updated cart
}


// Function to remove an item from the cart
function removeFromCart($cart, $item) {
    unset($cart[$item]);
    file_put_contents($cartFile, json_encode($cart));
}

// Function to update the quantity of an item in the cart
function updateQuantity($cart, $item, $quantity) {
    $cart[$item] = $quantity;
    file_put_contents($cartFile, json_encode($cart));
}


// Get the requested action (add, remove, update, view)
$action = $_GET['action'];

// Handle actions
switch ($action) {
    case 'add':
        $item = $_POST['item'];
        $quantity = $_POST['quantity'] ?? 1; // Default quantity is 1
        addToCart($cart, $item, $quantity);
        break;

    case 'remove':
        $item = $_POST['item'];
        removeFromCart($cart, $item);
        break;

    case 'update':
        $item = $_POST['item'];
        $quantity = $_POST['quantity'];
        updateQuantity($cart, $item, $quantity);
        break;

    case 'view':
        // Display the cart contents
        echo "<h2>Your Shopping Cart</h2>";
        if (empty($cart)) {
            echo "<p>Your cart is empty.</p>";
        } else {
            echo "<ul>";
            foreach ($cart as $item => $quantity) {
                echo "<li>$item - Quantity: $quantity<br>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='item' value='$item'>";
                echo "<input type='number' name='quantity' value='$quantity' min='1' style='width:50px;'>";
                echo "<button type='submit' name='action' value='update'>Update</button> | <a href='?action=remove&item=$item'>Remove</a>";
                echo "</form>";
                echo "</li>";
            }
            echo "</ul>";
        }
        break;

    default:
        // Handle unknown actions (e.g., display an error)
        echo "<p>Invalid action.</p>";
}
?>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

//  Helper function to sanitize input (prevent SQL injection)
function sanitizeInput($data) {
    global $conn; // Access the database connection
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



// Start the shopping cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Function to add an item to the cart
function addToCart($product_id, $name, $price, $quantity) {
    global $conn, $_SESSION['cart'];

    $name = sanitizeInput($name);
    $price = sanitizeInput($price);
    $quantity = sanitizeInput($quantity);

    if (!is_numeric($quantity) || $quantity <= 0) {
        return false; // Invalid quantity
    }

    $item = array(
        'id' => $product_id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    );

    $_SESSION['cart'][] = $item;
    return true;
}


// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    global $conn, $_SESSION['cart'];

    $new_quantity = sanitizeInput($new_quantity);

    if (!is_numeric($new_quantity) || $new_quantity <= 0) {
        return false; // Invalid quantity
    }


    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $product_id) {
            $_SESSION['cart'][$i]['quantity'] = $new_quantity;
            return true;
        }
    }
    return false;
}



// Function to remove an item from the cart
function removeFromCart($product_id) {
    global $conn, $_SESSION['cart'];

    $product_id = sanitizeInput($product_id);

    $keys_to_remove = array();

    foreach($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            $keys_to_remove[] = $key;
        }
    }

    foreach ($keys_to_remove as $key) {
        unset($_SESSION['cart'][$key]);
    }

    return true;
}



// Function to calculate the total cart value
function calculateTotal() {
    global $conn, $_SESSION['cart'];
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// Handling different actions

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    if (addToCart($product_id, $name, $price, $quantity)) {
        echo "<p>Item added to cart.</p>";
    } else {
        echo "<p>Failed to add item to cart.</p>";
    }
}


// Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    if (updateQuantity($product_id, $new_quantity)) {
        echo "<p>Quantity updated in cart.</p>";
    } else {
        echo "<p>Failed to update quantity.</p>";
    }
}

// Remove Item
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
    echo "<p>Item removed from cart.</p>";
}



// Display Cart
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . number_format($item['price'], 2) . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";

        // Optional: Add a button to update quantity or remove item
        echo "<form method='post'>";
        echo "<label for='quantity_" . $item['id'] . "'>Quantity:</label>";
        echo "<input type='number' id='quantity_" . $item['id'] . "' value='" . $item['quantity'] . "' min='1' max='99' name='quantity_" . $item['id'] . "'>";
        echo "<input type='hidden' name='product_id' value='" . $item['id'] . "'>";
        echo "<input type='submit' value='Update'>";
        echo "</form>";


        echo "<br>";
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . number_format(calculateTotal(), 2) . "</strong></p>";
}


?>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connect_db() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $product_id = intval($product_id); // Ensure product ID is an integer
    $quantity = intval($quantity);      // Ensure quantity is an integer

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // Product doesn't exist
    }

    $product = $result->fetch_assoc();

    // Check if the item is already in the cart
    $cart_item_id = isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : [];
    if (in_array($product['id'], $cart_item_id)) {
        // Item already in cart, update quantity
        $cart_item_id = array_keys($_SESSION['cart']);
        $index = array_search($product['id'], $cart_item_id);
        $_SESSION['cart'][$index]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
    $stmt->close();
    return true;
}


// Function to get the cart items
function get_cart_items() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    return $_SESSION['cart'];
}

// Function to update quantity in cart
function update_cart_quantity($product_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $product_id = intval($product_id); // Ensure product ID is an integer
    $quantity = intval($quantity);      // Ensure quantity is an integer

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // Product doesn't exist
    }

    $product = $result->fetch_assoc();

    // Check if the product exists in the cart
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return false;
    }
    
    $cart_items = get_cart_items();

    foreach ($cart_items as $key => $item) {
        if ($item['id'] == $product['id']) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
            break;
        }
    }

    $stmt->close();
    return true;
}


// Function to remove item from cart
function remove_from_cart($product_id) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $product_id = intval($product_id); // Ensure product ID is an integer

    // Check if the product exists in the cart
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return false;
    }

    $cart_items = get_cart_items();

    foreach ($cart_items as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $cart_items = get_cart_items();
            break;
        }
    }
    return true;
}

// Function to get the total cart value
function calculate_cart_total() {
    $cart_items = get_cart_items();
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


//--------------------  Example usage and handling the request (e.g., from a form)  --------------------

// If the request is to add an item to the cart:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
}

// If the request is to update quantity in cart:
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    update_cart_quantity($product_id, $quantity);
}

//If the request is to remove an item from cart:
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Display the cart contents:
$cart_items = get_cart_items();
$total = calculate_cart_total();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (!empty($cart_items)) { ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item) { ?>
                    <tr class="cart-item">
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <a href="?remove_from_cart=<?php echo $item['id']; ?>">Remove</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>

    <form action="" method="post">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual details)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    $product_id = intval($product_id); // Ensure product_id is an integer
    $quantity = intval($quantity); // Ensure quantity is an integer

    if ($product_id <= 0 || $quantity <= 0) {
        return false; // Invalid input
    }

    // Check if the product exists in the database (Simple example - enhance for real use)
    $query = "SELECT id, name, price FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false; // Product not found
    }

    $product_data = mysqli_fetch_assoc($result);
    if ($product_data['id'] == 0) { // Check if any data was retrieved
        return false;
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity,
            'name' => $product_data['name'],
            'price' => $product_data['price']
        ];
    }

    return true;
}


// Function to get the cart total
function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Product Listing (Example - Replace with your product database query) ---
$product_list = [
    ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// --- Cart Handling Functions ---

// Function to display the cart contents
function displayCart() {
    $cart = $_SESSION['cart'];
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($cart as $product_id => $item) {
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $total = $price * $quantity;

        echo "<tr>";
        echo "<td>" . $name . "</td>";
        echo "<td>$" . number_format($price, 2) . "</td>";
        echo "<td>" . $quantity . "</td>";
        echo "<td>$" . number_format($total, 2) . "</td>";
        echo "<td><a href='cart.php?action=remove&product_id=$product_id'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format(calculateCartTotal($cart), 2) . "</p>";
}

// --- Actions Based on User Input ---

if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0; // Ensure product_id is an integer
    if (addToCart($conn, $product_id, 0) === true) { // Add quantity 0 to effectively remove
        unset($_SESSION['cart'][$product_id]);
    }
}


// --- Display the Cart and the Product List ---

// Establish database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Online Store</h1>

    <h2>Product List</h2>
    <ul>
        <?php
        foreach ($product_list as $product) {
            echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . " <a href='cart.php?action=add&product_id=" . $product['id'] . "'>Add to Cart</a></li>";
        }
        ?>
    </ul>

    <hr>

    <?php
    displayCart();
    ?>

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// --- Shopping Cart Functions ---

/**
 * Add an item to the shopping cart
 *
 * @param int $productId The ID of the product to add
 * @param int $quantity  The quantity to add
 */
function addToCart($productId, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = array(
      'quantity' => $quantity,
      'price'    => getProductPrice($productId) // Ensure price is accurate
    );
  }
}

/**
 * Get the price of a product
 *
 * @param int $productId The ID of the product
 * @return float|null The price of the product, or null if not found
 */
function getProductPrice($productId) {
  //  Simulate fetching price from database
  // Replace this with your actual database query
  $products = array(
    1 => array('name' => 'Laptop', 'price' => 1200.00),
    2 => array('name' => 'Mouse', 'price' => 25.00),
    3 => array('name' => 'Keyboard', 'price' => 75.00)
  );

  if (isset($products[$productId])) {
    return $products[$productId]['price'];
  } else {
    return null; // Product not found
  }
}

/**
 * Update the quantity of an item in the cart
 *
 * @param int $productId The ID of the product to update
 * @param int $quantity  The new quantity
 */
function updateCartQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  }
}

/**
 * Remove an item from the cart
 *
 * @param int $productId The ID of the product to remove
 */
function removeCartItem($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

/**
 * Get the cart contents
 *
 * @return array The shopping cart contents
 */
function getCartContents() {
  return $_SESSION['cart'];
}


/**
 * Calculate the total cart value
 *
 * @return float The total value of the cart
 */
function calculateTotal() {
  $total = 0;
  $cart = getCartContents();

  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}


// --- Handle Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
  $productId = (int)$_POST['product_id']; // Ensure product_id is an integer
  $quantity = (int)$_POST['quantity'];

  addToCart($productId, $quantity);
  //  You might want to redirect the user after adding to the cart
  header("Location: cart.php");  // Redirect to the cart page
  exit();
}

// --- Handle Update Cart Request ---
if (isset($_POST['update_cart'])) {
  $productId = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];

  updateCartQuantity($productId, $quantity);
  header("Location: cart.php");
  exit();
}

// --- Handle Remove Cart Item Request ---
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];
    removeCartItem($productId);
    header("Location: cart.php");
    exit();
}

// --- Display Cart Contents ---
$cart = getCartContents();
$total = calculateTotal();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <?php
    foreach ($cart as $item):
      ?>
      <div class="cart-item">
        <span><?php echo $item['name']; ?></span> (Quantity: <?php echo $item['quantity']; ?>) - $<?php echo number_format($item['price'], 2); ?>
        <form action="cart.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
          <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
          <button type="submit" name="update_cart">Update</button>
        </form>
        <form action="cart.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
          <button type="submit" name="remove_from_cart">Remove</button>
        </form>
      </div>
    <?php
  endforeach;
  ?>

  <p>Total: $<?php echo number_format($total, 2); ?></p>
<?php
}
?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Ensure the database connection works
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to check if a product is in the cart
function isInCart($product_id) {
  global $conn;
  if (isset($_SESSION['cart'][$product_id]['quantity'])) {
    return true;
  } else {
    return false;
  }
}

// Function to update cart quantity
function updateCartQuantity($product_id, $quantity) {
  global $conn;

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    $_SESSION['cart'][$product_id]['quantity'] = max(0, $_SESSION['cart'][$product_id]['quantity']); // Ensure quantity doesn't go below 0
  } else {
    // If product not in cart, add it with the specified quantity
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

// Function to remove a product from the cart
function removeProductFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get cart total
function getCartTotal($cart) {
    $total = 0;
    foreach ($cart as $product_id => $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Validate quantity (optional, but recommended)
  if (empty($quantity) || $quantity <= 0) {
    echo "<p style='color:red;'>Invalid quantity.</p>";
    exit;
  }

  updateCartQuantity($product_id, $quantity);
  echo "<p>Product added to cart.</p>";
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeProductFromCart($product_id);
  echo "<p>Product removed from cart.</p>";
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Validate quantity
  if (empty($quantity) || $quantity <= 0) {
    echo "<p style='color:red;'>Invalid quantity.</p>";
    exit;
  }
  updateCartQuantity($product_id, $quantity);
}


// Display the cart contents
$cart_items = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        // Fetch product details from your database
        $product_query = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($product_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $cart_items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $item['quantity']
            ];
        }
    }
}

$cart_total = getCartTotal($cart_items);


?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)) {
      echo "<p>Your cart is empty.</p>";
    } else {
        echo "<table class='cart-items'>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

        foreach ($cart_items as $item) {
          echo '<tr class="cart-item">';
          echo "<td>" . $item['name'] . "</td>";
          echo "<td>$" . $item['price'] . "</td>";
          echo "<td>" . $item['quantity'] . "</td>";
          echo "<td>$" . $item['quantity'] * $item['price'] . "</td>";
          echo "<td><form method='post'><input type='hidden' name='product_id' value='" . $item['id'] . "'> <button type='submit'>Remove</button></form></td>";
          echo "</tr>";
        }

        echo "</table>";
        echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
    }

    ?>

  <hr>

  <h2>Add to Cart</h2>
  <?php if (empty($cart_items)) {
        echo "<form method='post'>
                  <label for='product_id'>Product ID:</label>
                  <input type='number' id='product_id' name='product_id' required>
                  <label for='quantity'>Quantity:</label>
                  <input type='number' id='quantity' name='quantity' value='1' min='1'>
                  <button type='submit' name='add_to_cart'>Add to Cart</button>
                </form>";
    }
    ?>

</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "root";
$db_password = "";

// Database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not already
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------- Functions ---------------------

// Function to add item to cart
function add_to_cart($conn, $product_id, $quantity) {
  global $db_host, $db_name;

  // Check if product exists (basic validation - can be improved)
  $product_query = "SELECT id, name, price FROM products WHERE id = ?";
  $stmt = $conn->prepare($product_query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return false; // Product not found
  }
  $product = $result->fetch_assoc();

  // Add to cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'name' => $product['name'],
      'price' => $product['price']
    );
  }
  return true;
}

// Function to remove item from cart
function remove_from_cart($conn, $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
  return true;
}

// Function to update quantity of item in cart
function update_cart_quantity($conn, $product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
  return true;
}


// Function to get cart total
function calculate_cart_total($conn) {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// ---------------------  Handler Functions  ---------------------

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  add_to_cart($conn, $product_id, $quantity);
}

// Handle removing item from cart
if (isset($_GET['remove_from_cart'])) {
  remove_from_cart($conn, $_GET['remove_from_cart']);
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_cart_quantity($conn, $product_id, $new_quantity);
}


// ---------------------  Display Cart  ---------------------

// Display cart items
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . number_format($item['price'], 2) . " ";
    echo "Quantity: " . $item['quantity'];
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='product_id' value='" . $item['id'] . "'>";
    echo "<input type='hidden' name='quantity' value='" . $item['quantity'] . "'>";
    echo "<input type='submit' value='Remove'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . number_format(calculate_cart_total($conn), 2) . "</strong></p>";
}


// ---------------------  End of Script  ---------------------

// Close database connection
$conn->close();
?>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_username';
$db_password = 'your_password';

// --- Database Connection ---
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Helper Functions ---
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); // Important for security!
    return $data;
}

// --- Product Data (for demonstration - replace with your database query) ---
$products = array(
    1 => array('id' => 1, 'name' => 'T-Shirt', 'price' => 20.00, 'stock' => 10),
    2 => array('id' => 2, 'name' => 'Jeans', 'price' => 50.00, 'stock' => 5),
    3 => array('id' => 3, 'name' => 'Sneakers', 'price' => 80.00, 'stock' => 3)
);

// --- Cart Functions ---

// Initialize Cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to Cart
function addToCart($product_id, $quantity) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Remove from Cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update Quantity in Cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Get Cart Contents
function getCartContents() {
    return $_SESSION['cart'];
}

// --- Cart Actions based on HTTP request ---

// 1. Add to Cart (GET request)
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $product_id = sanitize_input($_GET['product_id']);
    $quantity = sanitize_input($_GET['quantity']);

    if (is_numeric($product_id) && is_numeric($quantity) && $quantity > 0) {
        addToCart($product_id, $quantity);
        echo "<p>Item added to cart.</p>";
    } else {
        echo "<p>Invalid product ID or quantity.</p>";
    }
}

// 2. Remove from Cart (GET request)
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = sanitize_input($_GET['product_id']);
    removeFromCart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// 3. Update Quantity in Cart (GET request)
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $product_id = sanitize_input($_GET['product_id']);
    $quantity = sanitize_input($_GET['quantity']);

    if (is_numeric($product_id) && is_numeric($quantity) && $quantity > 0) {
        updateQuantity($product_id, $quantity);
        echo "<p>Quantity updated in cart.</p>";
    } else {
        echo "<p>Invalid product ID or quantity.</p>";
    }
}


// --- Display Cart Contents ---
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
    $cart_contents = getCartContents();

    if (!empty($cart_contents)) {
        echo "<h2>Cart Contents:</h2>";
        foreach ($cart_contents as $product_id => $item) {
            echo "<div class='cart-item'>";
            echo "Product ID: " . $product_id . "<br>";
            echo "Name: " . $products[$product_id]['name'] . "<br>";
            echo "Price: $" . $products[$product_id]['price'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "Subtotal: $" . ($products[$product_id]['price'] * $item['quantity']) . "<br>";
            echo "</div>";
        }

        echo "<p><strong>Total:</strong> $" . round(getTotalCartValue(), 2) . "</p>"; // Calculate and display total
    } else {
        echo "<p>Your cart is empty.</p>";
    }
?>

<hr>

<h2>Add to Cart</h2>
<form method="GET">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value='" . $id . "'>" . $id . "</option>";
        }
        ?>
    </select>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <br>
    <button type="submit" name="action" value="add">Add to Cart</button>
</form>

<hr>

<h2>Remove from Cart</h2>
<form method="GET">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value='" . $id . "'>" . $id . "</option>";
        }
        ?>
    </select>
    <br>
    <button type="submit" name="action" value="remove">Remove from Cart</button>
</form>

<hr>

<h2>Update Quantity in Cart</h2>
<form method="GET">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value='" . $id . "'>" . $id . "</option>";
        }
        ?>
    </select>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <br>
    <button type="submit" name="action" value="update">Update Quantity</button>
</form>


</body>
</html>

<?php

// Helper Function to calculate total cart value.
function getTotalCartValue() {
    $total = 0;
    $cart_contents = getCartContents();
    foreach ($cart_contents as $product_id => $item) {
        $total += ($products[$product_id]['price'] * $item['quantity']);
    }
    return $total;
}

?>


<?php
session_start();

// Configuration
$cart_file = 'cart.php';
$item_name_key = 'item_name';
$item_price_key = 'item_price';
$quantity_key = 'quantity';

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $item_name
 * @param float $item_price
 * @param int $quantity
 */
function addToCart($item_name, $item_price, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        $item_name_key => $item_name,
        $quantity_key => $quantity,
        $item_price_key => $item_price
    ];
}


/**
 * Removes an item from the cart by item name.
 *
 * @param string $item_name
 */
function removeFromCart($item_name) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item[$item_name_key] === $item_name) {
                unset($_SESSION['cart'][$key]);
                // Re-index the array to avoid gaps
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $item_name
 * @param int $new_quantity
 */
function updateQuantity($item_name, $new_quantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item[$item_name_key] === $item_name) {
                $_SESSION['cart'][$key][$quantity_key] = $new_quantity;
                // Re-index the array to avoid gaps
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }
}


/**
 * Calculates the total cart value.
 *
 * @return float
 */
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item[$item_price_key] * $item[$quantity_key];
        }
    }
    return $total;
}


/**
 * Clears the entire cart.
 */
function clearCart() {
    unset($_SESSION['cart']);
}


// --- Cart Handling Functions (Called based on user actions) ---

// 1. Add to Cart (handled by the product page)
if (isset($_POST['add_to_cart'])) {
    $item_name = $_POST['item_name'];
    $item_price = floatval($_POST['item_price']); // Ensure price is a float
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer
    addToCart($item_name, $item_price, $quantity);
}

// 2. Remove from Cart (handled by the product page)
if (isset($_POST['remove_from_cart'])) {
    $item_name = $_POST['item_name'];
    removeFromCart($item_name);
}


// 3. Update Quantity (handled by the product page)
if (isset($_POST['update_quantity'])) {
    $item_name = $_POST['item_name'];
    $new_quantity = intval($_POST['quantity']);  // Ensure quantity is an integer
    updateQuantity($item_name, $new_quantity);
}


// --- Cart Display Function ---

function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $item_name = $item[$item_name_key];
        $item_price = $item[$item_price_key];
        $quantity = $item[$quantity_key];
        $item_total = $item_total = $item_price * $quantity;
        $total += $item_total;

        echo "<tr>";
        echo "<td>" . $item_name . "</td>";
        echo "<td>$" . number_format($item_price, 2) . "</td>";
        echo "<td>" . $quantity . "</td>";
        echo "<td>$" . number_format($item_total, 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
    echo "<a href='checkout.php'>Proceed to Checkout</a>";  // Link to checkout page
}


// --- Example: Display the Cart ---
displayCart();
?>


<?php
session_start();

// Configuration
$items = []; // Array to store shopping cart items
$database_file = "cart_data.txt"; // File to store cart data (for simplicity - use a database in a real application)

// --- Helper Functions ---

// Add an item to the cart
function addToCart($product_id, $quantity) {
  global $items;

  // Check if the item is already in the cart
  foreach ($items as $key => $item) {
    if ($item['product_id'] == $product_id) {
      $items[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If item not in cart, add it
  $items[] = ['product_id' => $product_id, 'quantity' => $quantity];
}

// Calculate the total price
function calculateTotal() {
  $total = 0;
  foreach ($items as $item) {
    // Assume you have a function to get product price by ID
    $price = getProductPrice($item['product_id']);
    $total_for_item = $price * $item['quantity'];
    $total += $total_for_item;
  }
  return $total;
}

// Save the cart to a file (for persistence)
function saveCartToFile() {
  file_put_contents($database_file, serialize($items));
}

// Load the cart from a file
function loadCartFromFile() {
  global $items;
  if (file_exists($database_file)) {
    $cartData = file_get_contents($database_file);
    if ($cartData = @unserialize($cartData)) { //Use @ to suppress errors
        $items = $cartData;
    }
  }
}

// --- Mock Product Price Function (Replace with your actual database query) ---
function getProductPrice($product_id) {
  // This is a mock function. In a real application, you'd query your database.
  // For demonstration purposes, it returns a hardcoded price.
  $product_prices = [
    1 => 10.00,
    2 => 20.00,
    3 => 15.00
  ];
  return $product_prices[$product_id] ?? 0; // Return 0 if product_id not found
}


// --- Cart Handling Functions ---

// Add to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $quantity);
  saveCartToFile();
  header("Location: cart.php"); // Redirect to cart.php
  exit();
}

// Remove item from cart
if (isset($_GET['remove_from_cart'])) {
  $product_id = $_GET['remove_from_cart'];
  removeItemFromCart($product_id);
  saveCartToFile();
  header("Location: cart.php"); // Redirect to cart.php
  exit();
}

// Remove Item function (helper function for remove from cart)
function removeItemFromCart($product_id) {
  global $items;
  foreach ($items as $key => $item) {
    if ($item['product_id'] == $product_id) {
      unset($items[$key]);
      return;
    }
  }
}

// --- Display Cart (cart.php) ---

// Load cart data on page load
loadCartFromFile();

// Calculate total
$total = calculateTotal();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h2>Shopping Cart</h2>

  <?php if (empty($items)) {
    echo "<p>Your cart is empty.</p>";
  } else { ?>
    <table border="1">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $key => $item) { ?>
          <tr class="cart-item">
            <td><?php echo $item['product_id']; ?></td>
            <td><?php echo $item['product_id']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo getProductPrice($item['product_id']); ?></td>
            <td><?php echo getProductPrice($item['product_id']) * $item['quantity']; ?></td>
            <td>
              <a href="cart.php?remove_from_cart=<?php echo $item['product_id']; ?>">Remove</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <p>Total: $<?php echo $total; ?></p>
  <?php } ?>

  <form method="post" action="cart.php">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <?php
      // Populate the dropdown with product IDs (replace with your actual product data)
      foreach ($product_ids as $id) {
        echo "<option value=\"$id\">$id</option>";
      }
      ?>
    </select>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

</body>
</html>


<?php
session_start();

// Configuration
$cart_file = 'cart.php'; // File to store the cart data
$item_name_key = 'item_name';
$item_price_key = 'item_price';
$quantity_key = 'quantity';

// Helper Functions

/**
 * Adds an item to the cart.
 *
 * @param string $itemName The name of the item.
 * @param float $itemPrice The price of the item.
 * @param int $quantity The quantity to add.
 */
function addToCart($itemName, $itemPrice, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        $item_name_key => $itemName,
        $quantity_key => $quantity
    ];
    
    // Update existing item
    foreach ($_SESSION['cart'] as &$item) {
        if ($item[$item_name_key] === $itemName) {
            $item[$quantity_key] += $quantity;
            break;
        }
    }
}

/**
 * Removes an item from the cart by name.
 *
 * @param string $itemName The name of the item to remove.
 */
function removeFromCart($itemName) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item[$item_name_key] === $itemName) {
                unset($_SESSION['cart'][$key]);
                // Re-index the cart array after removal
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }
}

/**
 * Gets the cart contents.
 *
 * @return array An array containing the cart items.
 */
function getCart() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    }
    return [];
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
    $total = 0;
    $cart = getCart();
    foreach ($cart as $item) {
        $total += $item[$item_price_key] * $item[$quantity_key];
    }
    return $total;
}

// ---  Shopping Cart Logic  ---

// Example Usage (Illustrative - Replace with your actual product data)

// Add some items to the cart
addToCart('Laptop', 1200.00, 1);
addToCart('Mouse', 25.00, 2);
addToCart('Keyboard', 75.00, 1);



// Display the cart contents
$cart = getCart();

if (empty($cart)) {
    echo "<h1>Your Cart is Empty</h1>";
} else {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item[$item_name_key] . " - $" . $item[$item_price_key] . " x " . $item[$quantity_key] . " = $" . ($item[$item_price_key] * $item[$quantity_key]) . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";

    // Example: Remove an item
    // removeFromCart('Mouse');

}


// ---  Save Cart to File (Optional - for persistence) ---
// This example saves the cart data to a file named 'cart.php'
// This file will be loaded on subsequent page loads.
//  Important:  Make sure this file is not directly accessible via web browser
//  to prevent security vulnerabilities.  Proper file permissions should be set.
//
// if (empty($_SESSION['cart'])) {
//     // Clear the cart if it's empty to avoid saving an empty cart
//     unset($_SESSION['cart']);
// }
// else {
//     file_put_contents($cart_file, json_encode($_SESSION['cart']));
// }
?>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'shopping_cart';
$dbUser = 'root';
$dbPass = '';

// Connect to the database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --------------------- Product Data ---------------------
//  This is a simplified way to store product data.  In a real application,
//  you would likely pull this from a database.
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200.00, 'image' => 'laptop.jpg'],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25.00, 'image' => 'mouse.jpg'],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75.00, 'image' => 'keyboard.jpg'],
];


// --------------------- Cart Functions ---------------------

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @return void
 */
function addToCart($product_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Product not in cart, add it
        $_SESSION['cart'][$product_id] = [
            'quantity' => 1,
            'price' => $products[$product_id]['price']
        ];
    }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float
 */
function calculateCartTotal() {
    $total = 0.00;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }
    }
    return $total;
}

// --------------------- Cart Operations (Handle Actions) ---------------------

// Add to cart (e.g., from a button click)
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure it's an integer
    addToCart($product_id);
}

// Update quantity (e.g., from a quantity input)
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateCartQuantity($product_id, $quantity);
}

// Remove from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
}

// --------------------- Display the Cart ---------------------

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Shopping Cart</title>";
echo "</head>";
echo "<body>";

echo "<h1>Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>";
        echo "<img src='$products[$item['id']]['image']' alt='$products[$item['id']]['name']' style='width:100px;'>";
        echo "<p>$products[$item['id']]['name']</p>";
        echo "<p>Quantity: $item['quantity']</p>";
        echo "<p>Price: $items[$item['id']]['price']</p>";
        echo "<form method='post'>";
        echo "<label for='quantity_$item['id']'>Update Quantity:</label>";
        echo "<input type='number' id='quantity_$item['id']' name='quantity_$item['id']' value='$item['quantity']'>";
        echo "<input type='submit' name='update_quantity' value='Update'>";
        echo "<input type='hidden' name='product_id' value='$item['id']'>";
        echo "</form>";
        echo "<input type='submit' name='remove_from_cart' value='Remove from Cart'>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculateCartTotal() . "</p>";
}

// Display checkout button
echo "<form method='post'>";
echo "<input type='submit' name='checkout' value='Checkout'>";
echo "</form>";

echo "</body>";
echo "</html>";

?>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_user";
$dbPass = "your_password";
$dbName = "your_database";

//  Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to check if a product is in the cart
function isInCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        return true;
    }
    return false;
}

// Function to update the cart quantity
function updateCartQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to add a product to the cart
function addProductToCart($productId, $quantity = 1) {
    if (isInCart($productId)) {
        updateCartQuantity($productId, $quantity);
    } else {
        // Product not in cart, add it
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = array('quantity' => $quantity, 'price' => 0); //Initialize price
        } else {
            // Product already in cart, update the quantity
            updateCartQuantity($productId, $quantity);
        }
    }
}



// Cart Functions - These are the core functions

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity is 1
    addProductToCart($productId, $quantity);
    echo "<p>Product added to cart.</p>";
}


// 2. Update Quantity
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity is 1
    updateCartQuantity($productId, $quantity);
    echo "<p>Quantity updated in cart.</p>";
}



// 3. Remove Product from Cart
if (isset($_GET['remove_from_cart'])) {
    $productId = $_GET['remove_from_cart'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    echo "<p>Product removed from cart.</p>";
}


// 4. View Cart
if (isset($_GET['view_cart'])) {
    // Display Cart Contents
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $productId => $item) {
            $productName = getProductName($productId); // Implement this function (see example below)
            echo "<li>";
            echo "<strong>$productName</strong> - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'];
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='product_id' value='$productId'>";
            echo "<input type='hidden' name='product_id' value='$productId'>";  // Double quotes are needed here for correct string concatenation
            echo "<input type='text' name='quantity' value='$item['quantity']' size='3'>";
            echo "<input type='submit' value='Update'>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ul>";

        // Calculate total price
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $productId => $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        echo "<p><strong>Total: $" . $totalPrice . "</strong></p>";

    }
}



// Helper function to get product name from database (replace with your database query)
function getProductName($productId) {
    // Example:  Assuming you have a products table with a 'id' and 'name' column
    $query = "SELECT name FROM products WHERE id = $productId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['name'];
    } else {
        return "Unknown Product";
    }
}

// Example product data (for demonstration purposes - replace with your actual data)
$products = array(
    1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
    2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
    3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75)
);

// Start the session
session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /* Basic styling - improve as needed */
        body { font-family: sans-serif; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post" action="cart.php">
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <?php
            foreach ($products as $id => $product) {
                echo "<option value='$id'>$id</option>";
            }
            ?>
        </select>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>


    <hr>

    <a href="cart.php?view_cart=1">View Cart</a>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Initialize variables
$cart = [];  // Array to store items in the cart
$total_amount = 0.00; // Total amount of the cart

// Function to add an item to the cart
function addItemToCart($conn, $product_id, $quantity) {
    global $cart, $total_amount;

    // Check if the item is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            $item['price'] = $conn->query("SELECT price FROM products WHERE id = $product_id")->fetch_assoc()['price']; // Get current price
            $total_amount = 0.00;
            foreach ($cart as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            return;
        }
    }

    // Item not in cart, add it
    $result = $conn->query("SELECT id, name, price FROM products WHERE id = $product_id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $cart[] = [
            'product_id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
        $total_amount = 0.00;
        foreach ($cart as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
    } else {
        echo "Product not found.";
    }
}

// Function to remove an item from the cart
function removeItemFromCart($conn, $product_id) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            $total_amount = 0.00;
            foreach ($cart as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
    global $cart;

    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            $item['price'] = $conn->query("SELECT price FROM products WHERE id = $product_id")->fetch_assoc()['price']; // Get current price
            $total_amount = 0.00;
            foreach ($cart as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            return;
        }
    }
}


// Handle add to cart request
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($conn)) { // Ensure the database connection is established
        addItemToCart($conn, $product_id, $quantity);
    } else {
        echo "Database connection failed.";
    }
}


// Handle remove from cart request
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    if (isset($conn)) {
        removeItemFromCart($conn, $product_id);
    }
}

// Handle update quantity request
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    if (isset($conn)) {
        updateQuantity($conn, $product_id, $new_quantity);
    }
}

// Display the cart
echo "<h2>Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Amount: $" . number_format($total_amount, 2) . "</strong></p>";
}

// Example of a button to clear the cart
echo "<br>";
echo "<a href='cart_clear.php'>Clear Cart</a>";
?>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_pass = "your_password";

// Function to connect to the database
function connect_db() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function add_to_cart($item_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    // Check if the item already exists in the cart
    $sql = "SELECT * FROM cart WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item already in cart, update quantity
        $sql = "UPDATE cart SET quantity = quantity + ? WHERE item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $quantity, $item_id);
        $stmt->execute();
    } else {
        // Item not in cart, add it
        $sql = "INSERT INTO cart (item_id, quantity) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $item_id, $quantity);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
    return true;
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $sql = "DELETE FROM cart WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    if ($quantity <= 0) {
        remove_from_cart($item_id); // If quantity is 0 or negative, remove the item
        return true;
    }

    $sql = "UPDATE cart SET quantity = ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quantity, $item_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

// Function to get the cart contents
function get_cart_contents() {
    $conn = connect_db();

    if (!$conn) {
        return [];
    }

    $sql = "SELECT * FROM cart";
    $result = $conn->query($sql);

    $cart_items = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }

    $conn->close();
    return $cart_items;
}

// Function to calculate the total cart value
function calculate_total() {
    $cart_items = get_cart_contents();
    $total = 0;

    foreach ($cart_items as $item) {
        // Assuming you have a 'products' table with 'price' column
        // Adjust this query to fetch the product's price from your product table.
        $sql = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item['item_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total += $row['price'] * $item['quantity'];
        $stmt->close();
    }

    return $total;
}

//  --------------------  Example Usage (HTML & PHP)  --------------------

// Initialize the cart session if it doesn't exist.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart (example)
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($item_id, $quantity);
    // Redirect to the same page to refresh the cart display
    header("Location: cart.php"); // Assuming you have a cart.php page
    exit();
}

// Handle removing from cart (example)
if (isset($_GET['remove_from_cart'])) {
    $item_id = $_GET['remove_from_cart'];
    remove_from_cart($item_id);
    header("Location: cart.php");
    exit();
}

// Handle updating quantity (example)
if (isset($_GET['update_quantity'])) {
    $item_id = $_GET['update_quantity'];
    $quantity = $_GET['quantity'];
    update_quantity($item_id, $quantity);
    header("Location: cart.php");
    exit();
}


// Display the cart contents (cart.php -  Simplified example)
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php
    $cart_items = get_cart_contents();

    if (empty($cart_items)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart_items as $item) {
            echo "<li>";
            echo "Item ID: " . $item['item_id'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            // Add code here to display the product name or other details from a 'products' table
            echo "Price: $5.00 per item (Example)<br>";
            echo "Total Price: $5.00 * " . $item['quantity'] . " = $";
            echo $item['quantity'] * 5.00;
            echo "<form method='get' action=''>";
            echo "<input type='hidden' name='update_quantity' value='" . $item['item_id'] . "'>";
            echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'>";
            echo "<input type='submit' value='Update'>";
            echo "<input type='hidden' name='remove_from_cart' value='" . $item['item_id'] . "'>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ul>";
        $total = calculate_total();
        echo "<p>Total: $" . $total . "</p>";
    }
    ?>

</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Helper Functions ---

/**
 * Adds an item to the cart
 *
 * @param int $product_id
 * @param int $quantity
 */
function addToCart($product_id, $quantity) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
  } else {
    $cart = [];
  }

  // Check if the product already exists in the cart
  foreach ($cart as $key => $item) {
    if ($item['product_id'] == $product_id) {
      $cart[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If product not found, add it to the cart
  $cart[$product_id] = [
    'product_id' => $product_id,
    'quantity' => $quantity,
  ];
}

/**
 * Updates the quantity of an item in the cart
 *
 * @param int $product_id
 * @param int $quantity
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];

    // Remove the product if the quantity is 0
    foreach ($cart as $key => $item) {
      if ($item['product_id'] == $product_id && $item['quantity'] <= 0) {
        unset($cart[$key]);
        break; // Exit the loop after removing the item
      }
    }

    // Update the quantity if the product exists
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] = $quantity;
    }
  }
}


/**
 * Removes an item from the cart
 *
 * @param int $product_id
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    unset($cart[$product_id]);
  }
}

/**
 * Gets all items in the cart
 *
 * @return array
 */
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return [];
}

/**
 * Calculates the total cart value
 *
 * @return float
 */
function calculateCartTotal() {
    $cartItems = getCartItems();
    $total = 0;
    foreach ($cartItems as $item) {
        // Assuming you have a product table with 'price' column
        // Adjust this to your actual product data source
        $product = getProductById($item['product_id']); // Call a function to get product details
        if ($product) {
            $total += $product['price'] * $item['quantity'];
        }
    }
    return $total;
}


/**
 * Retrieves product details by ID
 * @param int $product_id
 * @return array|null
 */
function getProductById($product_id) {
    //  Replace this with your actual database query
    //  This is just an example - adjust to your product table structure
    $query = "SELECT id, name, price FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}


// --- Cart Operations (Based on User Actions) ---

// 1. Add to Cart (e.g., from a product page)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

// 2. Update Cart Quantity (e.g., from a cart page)
if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
}

// 3. Remove Item from Cart (e.g., from a cart page)
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// 4. Get Cart Contents (e.g., display in a cart page)
$cartItems = getCartItems();

// 5. Calculate Total
$total = calculateCartTotal();

// --- Display Cart (Example - Cart Page) ---

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cartItems)) { ?>
    <p>Your cart is empty.</p>
  <?php } else { ?>
    <table border="1">
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($cartItems as $key => $item) {
          $product = getProductById($item['product_id']);
          if ($product) {
            echo "<tr>";
            echo "<td>" . $product['name'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . $product['price'] . "</td>";
            echo "<td>$" . $product['price'] * $item['quantity'] . "</td>";
            echo "<td><a href='?action=remove&product_id=" . $item['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>

    <p><strong>Total:</strong> $" . $total . "</p>
  <?php } ?>

  <a href="checkout.php">Proceed to Checkout</a>

</body>
</html>


<?php
session_start();

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'item_count' => 1
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['item_count'] = count($_SESSION['cart'][$product_id]);
    }
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        $_SESSION['cart'][$product_id]['item_count'] = count($_SESSION['cart'][$product_id]);
    }
}


// Handle adding to cart (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    $name = "Product " . $product_id; // You'd likely get this from a product ID
    $price = 10.00; // Example price
    add_to_cart($product_id, $name, $price);
}

// Handle removing an item
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    remove_from_cart($product_id);
}

// Handle updating quantity
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['update_quantity'])) {
    $product_id = $_GET['update_quantity'];
    $new_quantity = intval($_GET['quantity']); // Ensure it's an integer
    update_quantity($product_id, $new_quantity);
}


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";

$total = 0;

foreach ($_SESSION['cart'] as $item_id => $product_info) {
    echo "<li>";
    echo "<strong>" . $product_info['name'] . "</strong> - $" . $product_info['price'] . " ";
    echo "Quantity: " . $product_info['quantity'];
    echo "<form method='get' action='cart.php'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='" . $product_info['quantity'] . "' min='1'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
    $total += $product_info['price'] * $product_info['quantity'];
}

echo "</ul>";
echo "<p><strong>Total: $" . $total . "</strong></p>";

// Empty cart
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
}
?>


<?php
// This file would typically fetch product information from a database, API, etc.
// For this example, we'll just simulate some product data.

$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Hat', 'price' => 15.00],
];

// Example:  Display a product by ID
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    if (isset($products[$product_id])) {
        echo "<h2>Product Details</h2>";
        echo "<strong>" . $products[$product_id]['name'] . "</strong> - $" . $products[$product_id]['price'];
    } else {
        echo "<p>Product not found.</p>";
    }
}
?>


<?php
// In a real application, this would handle payment processing, order confirmation, etc.
// This is a simplified example.
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.  Nothing to checkout.</p>";
} else {
    echo "<h2>Checkout</h2>";
    echo "<p>Please proceed to payment.</p>";
    // Add your payment integration logic here.
}
?>


<?php
session_start();

// Configuration
$items = [
    ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Cart functions
function addItemToCart($productId, $cart) {
    if (array_key_exists($productId, $cart)) {
        $cart[$productId]['quantity'] = $cart[$productId]['quantity'] + 1;
    } else {
        $cart[$productId] = ['quantity' => 1];
    }
    
    // Save the cart to session
    $_SESSION['cart'] = $cart;
}

function removeItemFromCart($productId, &$cart) {
    if (array_key_exists($productId, $cart)) {
        unset($cart[$productId]);
    }
}

function updateQuantity($productId, $quantity, &$cart) {
    if (array_key_exists($productId, $cart)) {
        $cart[$productId]['quantity'] = $quantity;
    }
}

function getCart() {
    return $_SESSION['cart'] ?? []; // Return empty array if cart is not initialized
}

function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle Add to Cart Request
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Cast to integer for safety
    addItemToCart($productId, getCart());
}

// Handle Remove from Cart Request
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];
    removeItemFromCart($productId, getCart());
}

// Handle Update Quantity Request
if (isset($_POST['update_quantity'])) {
    $productId = (int)$_POST['product_id'];
    $newQuantity = (int)$_POST['quantity'];
    updateQuantity($productId, $newQuantity, getCart());
}


// Display the Cart
$cart = getCart();
$total = calculateCartTotal($cart);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .cart-item img {
            max-width: 100px;
            height: auto;
            float: left;
            margin-right: 10px;
        }
        .cart-item p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else { ?>
        <table class="cart-items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item) { ?>
                    <tr class="cart-item">
                        <td><img src="placeholder.jpg" alt="<?php echo $item['name']; ?>" width="50"> <?php echo $item['name']; ?></td>  <!-- Replace placeholder.jpg with actual image URL -->
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="update_quantity">Update</button>
                            </form>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove_from_cart">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
    <?php } ?>

    <hr>

    <h2>Add to Cart</h2>
    <?php foreach ($items as $item) { ?>
        <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    <?php } ?>

</body>
</html>


<?php
session_start();

// Configuration
$cart_file = 'cart.php';
$item_id_key = 'item_id';
$item_name_key = 'item_name';
$quantity_key = 'quantity';
$price_key = 'price';

// Function to initialize the cart
function initializeCart() {
    if (!file_exists($cart_file)) {
        file_put_contents($cart_file, '{}'); // Create an empty cart file
    }
}

// Function to add an item to the cart
function addItemToCart($item_id, $item_name, $quantity, $price) {
    $cart = getCartData();

    $item_id = $item_id;
    $item_name = $item_name;
    $quantity = $quantity;
    $price = $price;

    if (empty($cart)) {
        $cart = array($item_id_key => $item_id, $item_name_key => $item_name, $quantity_key => $quantity, $price_key => $price);
    } else {
        // Check if item already in cart
        if (isset($cart[$item_id])) {
            $cart[$item_id][$quantity_key] += $quantity;
        } else {
            $cart[$item_id] = array($item_name_key => $item_name, $quantity_key => $quantity, $price_key => $price);
        }
    }

    // Save the cart to the file
    file_put_contents($cart_file, json_encode($cart));
}

// Function to update an item's quantity in the cart
function updateCartItemQuantity($item_id, $quantity) {
    $cart = getCartData();

    if (isset($cart[$item_id])) {
        $cart[$item_id][$quantity_key] = $quantity;
    }

    file_put_contents($cart_file, json_encode($cart));
}

// Function to remove an item from the cart
function removeItemFromCart($item_id) {
    $cart = getCartData();

    if (isset($cart[$item_id])) {
        unset($cart[$item_id]);
    }

    file_put_contents($cart_file, json_encode($cart));
}

// Function to get the cart data from the file
function getCartData() {
    if (file_exists($cart_file)) {
        $cart = json_decode(file_get_contents($cart_file), true);
        return $cart;
    } else {
        return array(); // Return an empty array if the cart file doesn't exist
    }
}

// Function to get the total cart value
function calculateCartTotal() {
    $cart = getCartData();
    $total = 0;
    foreach ($cart as $item_id => $item) {
        $quantity = $item[$quantity_key];
        $price = $item[$price_key];
        $total_item_price = $quantity * $price;
        $total += $total_item_price;
    }
    return $total;
}

//  Example Usage (Illustrative - Replace with your actual logic)
// This is just to demonstrate how the functions would be used.
// You'd integrate this into your website's purchase flow.

// 1. Add to Cart (Example)
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    addItemToCart($item_id, $item_name, $quantity, $price);
    // Redirect or display a success message
    echo "<p>Item added to cart!</p>";
}

// 2. Update Quantity (Example)
if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];
    updateCartItemQuantity($item_id, $new_quantity);
    echo "<p>Quantity updated in cart!</p>";
}

// 3. Remove Item (Example)
if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];
    removeItemFromCart($item_id);
    echo "<p>Item removed from cart!</p>";
}

// 4. Get Cart Data for Display (Example - Display Cart Contents)
$cart = getCartData();

$total = calculateCartTotal();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Purchase Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (!empty($cart)) { ?>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cart as $item_id => $item) {
                $quantity = $item[$quantity_key];
                $item_name = $item[$item_name_key];
                $price = $item[$price_key];
                $total_item_price = $quantity * $price;
                echo "<tr>";
                echo "<td>" . $item_name . "</td>";
                echo "<td>" . $quantity . "</td>";
                echo "<td>$" . number_format($price, 2) . "</td>";
                echo "<td>$" . number_format($total_item_price, 2) . "</td>";
                echo "<td><button onclick=\"removeItemFromCart('{$item_id}')\">Remove</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <p>Total: $" . number_format($total, 2) . "</p>
<?php } else { ?>
    <p>Your cart is empty.</p>
<?php } ?>

<h2>Add to Cart</h2>
<form method="POST" action="">
    <label for="item_id">Item ID:</label>
    <input type="number" id="item_id" name="item_id" required><br><br>
    <label for="item_name">Item Name:</label>
    <input type="text" id="item_name" name="item_name" required><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required><br><br>
    <input type="submit" value="Add to Cart" name="add_to_cart">
</form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Initialize the cart (empty array if none exists)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $product_price) {
  global $_SESSION['cart'];

  if (empty($quantity)) {
    $quantity = 1; // Default to 1 if quantity is not provided
  }

  $item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => $quantity
  );

  // Check if the item is already in the cart
  foreach ($_SESSION['cart'] as $key => $cart_item) {
    if ($cart_item['id'] == $product_id) {
      // Update the quantity
      $_SESSION['cart'][$key]['quantity'] += $quantity;
      return;
    }
  }

  // Add the item to the cart
  $_SESSION['cart'][] = $item;
  return;
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    global $_SESSION['cart'];

    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            // Re-index the array to avoid gaps
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    global $_SESSION['cart'];

    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            if ($quantity > 0) {
                $_SESSION['cart'][$key]['quantity'] = $quantity;
            } else {
                // Remove the item if the quantity is set to 0
                removeItemFromCart($product_id);
                return;
            }
            return;
        }
    }
}

// Function to display the cart
function displayCart() {
  echo "<h2>Shopping Cart</h2>";

  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . "</li>";
    $total += $item['price'] * $item['quantity'];
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// ---  Product Data (Simulated - Replace with your database retrieval) ---
$products = [
  1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
  2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
  3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
  4 => ['id' => 4, 'name' => 'Monitor', 'price' => 300]
];


// ---  Handling Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $product_name = $products[$product_id]['name'];
  $product_price = $products[$product_id]['price'];

  addToCart($product_id, $quantity, $product_name, $product_price);
  // Redirect to the cart page (for better user experience)
  header("Location: cart.php");
  exit();
}

// --- Handling Remove from Cart Request ---
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeItemFromCart($product_id);
  header("Location: cart.php");
  exit();
}

// --- Handling Update Quantity Request ---
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateQuantity($product_id, $quantity);
  header("Location: cart.php");
  exit();
}

// --- Display the Cart Page (cart.php) ---
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Products</h1>
  <form method="post">
    <?php
    foreach ($products as $id => $product) {
      echo "<div>";
      echo "<strong>" . $product['name'] . " - $" . $product['price'] . "</strong><br>";
      echo "<input type='number' name='product_id[]' value='" . $id . "' min='1' style='width:50px;'>";
      echo "<button type='submit' name='add_to_cart'>Add to Cart</button> | ";
      echo "<button type='submit' name='update_quantity' style='background-color:red;'>Update Quantity</button>  | ";
      echo "<button type='submit' name='remove_from_cart' style='background-color:red;'>Remove from Cart</button>";
      echo "</div>";
    }
    ?>
  </form>

  <hr>

  <?php displayCart(); ?>

  <a href="index.php">Back to Products</a>

</body>
</html>


<?php
session_start();

// Array to store cart items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Function to display the cart contents
function displayCart() {
  echo "<div class='cart-container'>";
  echo "<h2>Shopping Cart</h2>";

  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
      $product_name = $item['name'];
      $price = $item['price'];
      $quantity = $item['quantity'];
      $total_price = $price * $quantity;

      echo "<li>";
      echo "<strong>$product_name</strong> - $price x $quantity = $total_price";
      echo "<form method='post' action='"
          . $_SERVER['PHP_SELF'] . "'>";
      echo "<label for='quantity_$product_id'>Quantity:</label>";
      echo "<input type='number' id='quantity_$product_id' value='$quantity' name='quantity_$product_id' min='1'>";
      echo "<input type='submit' value='Update'>";
      echo "</form>";
      echo "</li>";
    }
    echo "</ul>";

    echo "<form method='post' action='"
        . $_SERVER['PHP_SELF'] . "'>";
    echo "<input type='submit' value='Remove All Items'>";
    echo "</form>";
  }

  echo "</div>";
}

// Handle form submission to update quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  foreach ($_POST as $key => $value) {
    if (substr($key, 1) == '$') { // Crude check to filter out the '$'
       $key = str_replace('$','', $key);
       if (isset($_SESSION['cart'][$key])) {
          updateCartQuantity($key, $value);
       }
    }
  }
}

// Handle remove all items
if ($_POST['action'] == 'Remove All Items') {
    unset($_SESSION['cart']);
}


// Display the cart
displayCart();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-container {
      border: 1px solid #ccc;
      padding: 10px;
      margin-top: 20px;
    }
  </style>
</head>
<body>

</body>
</html>


<?php
$products = array(
    "1" => array(
        "id" => "1",
        "name" => "Laptop",
        "price" => 1200.00
    ),
    "2" => array(
        "id" => "2",
        "name" => "Mouse",
        "price" => 25.00
    ),
    "3" => array(
        "id" => "3",
        "name" => "Keyboard",
        "price" => 75.00
    )
);
?>


<?php
session_start();

// Array to store cart items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to display the cart contents
function displayCart() {
  echo "<div class='cart-container'>";
  echo "<h2>Shopping Cart</h2>";

  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
      $product_name = $item['name'];
      $price = $item['price'];
      $quantity = $item['quantity'];
      $total_price = $price * $quantity;

      echo "<li>";
      echo "<strong>$product_name</strong> - $price x $quantity = $total_price";
      echo "<form method='post' action='"
          . $_SERVER['PHP_SELF'] . "'>";
      echo "<label for='quantity_$product_id'>Quantity:</label>";
      echo "<input type='number' id='quantity_$product_id' value='$quantity' name='quantity_$product_id' min='1'>";
      echo "<input type='submit' value='Update'>";
      echo "</form>";
      echo "</li>";
    }
    echo "</ul>";

    echo "<form method='post' action='"
        . $_SERVER['PHP_SELF'] . "'>";
    echo "<input type='submit' value='Remove All Items'>";
    echo "</form>";
  }

  echo "</div>";
}

// Handle form submission to update quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  foreach ($_POST as $key => $value) {
    if (substr($key, 1) == '$') { // Crude check to filter out the '$'
       $key = str_replace('$','', $key);
       if (isset($_SESSION['cart'][$key])) {
          updateCartQuantity($key, $value);
       }
    }
  }
}

// Handle remove all items
if ($_POST['action'] == 'Remove All Items') {
    unset($_SESSION['cart']);
}

// Include the products data
include 'products.php';

// Display the cart
displayCart();
?>


<?php
// Sample product data
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00, 'description' => 'A comfortable t-shirt.', 'image' => 'tshirt.jpg'],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00, 'description' => 'Classic denim jeans.', 'image' => 'jeans.jpg'],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00, 'description' => 'Stylish hat.', 'image' => 'hat.jpg'],
];

// Function to get product details by ID
function getProductDetails($productId) {
    global $products;  // Access the global $products array

    if (isset($products[$productId])) {
        return $products[$productId];
    } else {
        return null;
    }
}
?>


<?php
session_start(); // Start the session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($productId, $quantity = 1) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'quantity' => $quantity,
            'product_id' => $productId // Store product ID for updates
        ];
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($productId, $quantity) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    } else {
        // Handle the case where the product isn't in the cart
        // You might want to log an error or display a message
        echo "Product ID " . $productId . " not found in cart.";
    }
}


// Function to remove an item from the cart
function removeCartItem($productId) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    } else {
        echo "Product ID " . $productId . " not found in cart.";
    }
}

// Function to get the cart total
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product = getProductDetails($item['product_id']); // Get product details
        if ($product) {
            $totalItemPrice = $product['price'] * $item['quantity'];
            $total += $totalItemPrice;
        }
    }
    return $total;
}

// Function to display the cart contents
function displayCart() {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        $product = getProductDetails($item['product_id']);
        if ($product) {
            $totalItemPrice = $product['price'] * $item['quantity'];
            $totalItemPrice = $totalItemPrice;
            echo "<tr>";
            echo "<td>" . $product['name'] . "</td>";
            echo "<td>$" . number_format($product['price'], 2) . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . number_format($totalItemPrice, 2) . "</td>";
            echo "<td><a href='cart.php?action=update&productId=" . $item['product_id'] . "&quantity=1'>Update</a> | <a href='cart.php?action=remove&productId=" . $item['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "<br>";
    echo "<strong>Total: $" . number_format(getCartTotal(), 2) . "</strong>";
}
?>


<?php
require_once 'cart.php';
require_once 'product.php'; // Include the product data file
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Our Products</h1>

<?php
displayCart(); // Display the cart content
?>

<br>
<a href="cart.php">View Cart</a>

</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'shopping_cart';
$dbUser = 'root';
$dbPass = '';

// Establish database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add item to cart
function addToCart($conn, $product_id, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to update quantity in cart
function updateCartQuantity($conn, $product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Item not in cart, handle it gracefully (e.g., log an error, display a message)
    // Example:
    error_log("Product ID $product_id not in cart.");
  }
}

// Function to remove item from cart
function removeFromCart($conn, $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get cart items
function getCartItems($conn) {
  $cart_items = array();
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $conn->query($product_query);
    if ($result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $cart_items[] = array(
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $item['quantity']
      );
    }
  }
  return $cart_items;
}


// --- Handle Cart Actions ---

// Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($conn, $product_id, $quantity);
  header("Location: cart.php"); // Redirect to cart page
  exit;
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($conn, $product_id, $quantity);
  header("Location: cart.php");
  exit;
}

// Remove from Cart
if (isset($_GET['remove_from_cart'])) {
  $product_id = $_GET['remove_from_cart'];
  removeFromCart($conn, $product_id);
  header("Location: cart.php");
  exit;
}


// --- Display Cart ---

// Get cart items
$cart_items = getCartItems($conn);

// --- Product Data (Example - Replace with your actual product data)---
$products = array(
  1 => array('id' => 1, 'name' => 'T-Shirt', 'price' => 20.00),
  2 => array('id' => 2, 'name' => 'Jeans', 'price' => 50.00),
  3 => array('id' => 3, 'name' => 'Shoes', 'price' => 80.00)
);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    body { font-family: sans-serif; }
    .cart-item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
    .cart-total { font-weight: bold; }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)) { ?>
    <p>Your cart is empty.</p>
  <?php } else { ?>

    <?php
    $total = 0;
    foreach ($cart_items as $item) {
      echo '<div class="cart-item">';
      echo '<strong>' . $item['name'] . '</strong> - $' . $item['price'] . ' <input type="number" value="' . $item['quantity'] . '" min="1" style="width: 50px;">';
      echo '<br>';
      $total += $item['price'] * $item['quantity'];
      echo '</strong>';
      echo '<br>';
      echo '<a href="cart.php?remove_from_cart=' . $item['id'] . '">Remove</a><br><br>';
    }
    ?>

    <div class="cart-total">Total: $<?php echo round($total, 2); ?></div>
  <?php } ?>

  <hr>

  <a href="index.php">Continue Shopping</a>

</body>
</html>


<?php
session_start();

// Database connection (replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, create a new cart array
    $_SESSION['cart'] = array();
  }

  // Check if product already exists in cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'product_id' => $product_id  // For easy updates later
    );
  }
}

// Function to update quantity of an item in the cart
function updateQuantity($conn, $product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Function to get the cart contents
function getCartContents($conn) {
  $cart_contents = array();
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $query = "SELECT product_name, price FROM products WHERE product_id = " . $product_id;
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $cart_contents[] = array(
        'product_id' => $product_id,
        'product_name' => $row['product_name'],
        'price' => $row['price'],
        'quantity' => $item['quantity']
      );
    }
  }
  return $cart_contents;
}

// --- Cart Actions ---

// Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  addToCart($conn, $product_id, $quantity);
  // Redirect to cart page
  header("Location: cart.php");
  exit();
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($conn, $product_id, $new_quantity);
  header("Location: cart.php");
  exit();
}

// Remove from Cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($conn, $product_id);
  header("Location: cart.php");
  exit();
}

// Get Cart Contents
$cart_contents = getCartContents($conn);

// --- Display Cart Page (cart.php) ---

?>
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    table {
      border-collapse: collapse;
      width: 80%;
      margin: 20px auto;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

  <h2>Shopping Cart</h2>

  <?php if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
  } else { ?>
    <table>
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($cart_contents as $item) {
          $product_name = $item['product_name'];
          $price = $item['price'];
          $quantity = $item['quantity'];
          $total_item = $price * $quantity;
          $total += $total_item;

          echo "<tr>";
          echo "<td>" . $product_name . "</td>";
          echo "<td>$" . number_format($price, 2) . "</td>";
          echo "<td>" . $quantity . "</td>";
          echo "<td>$" . number_format($total_item, 2) . "</td>";
          echo "<td><a href='cart.php?action=remove&product_id=" . $item['product_id'] . "'>Remove</a></td>";
          echo "</tr>";
        } ?>
      </tbody>
    </table>
    <p><strong>Total:</strong> $" . number_format($total, 2) . "</p>
  <?php } ?>

  <form method="post" action="cart.php">
    <input type="hidden" name="product_id" value="">
    <input type="hidden" name="action" value="add">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

</body>
</html>


<?php
session_start();

// Configuration
$items = []; // Array to store cart items
$item_id = 1; // Unique item ID
$db_file = 'cart.txt'; // File to store cart data

// Function to load cart data from file
function loadCart() {
    if (file_exists($db_file)) {
        $cartData = file_get_contents($db_file);
        if ($cartData = json_decode($cartData, true)) {
            return $cartData;
        } else {
            return [];
        }
    }
    return [];
}

// Function to save cart data to file
function saveCart($cartData) {
    file_put_contents($db_file, json_encode($cartData, JSON_PRETTY_PRINT));
}

// Load cart data
$cartData = loadCart();

// ------------------- Cart Management Functions -------------------

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    global $items, $item_id;

    // Check if the item is already in the cart
    $item_found = false;
    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] += $quantity;
            $item_found = true;
            break;
        }
    }

    // If the item isn't in the cart, add it
    if (!$item_found) {
        $items[$item_id] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ];
        $item_id++;
    }

    saveCart($items);
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    global $items;

    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($items[$key]);
            // Re-index array to prevent gaps
            $i = 0;
            foreach ($items as $k => $v) {
                $items[$i] = $v;
                $i++;
            }
            saveCart($items);
            return true;
        }
    }
    return false;
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $items;

    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] = $new_quantity;
            saveCart($items);
            return true;
        }
    }
    return false;
}


// Get the cart contents
function get_cart_contents() {
    return $items;
}


// ------------------- Display Cart -------------------

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($items as $key => $item) {
        echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";

    // Calculate total price (assuming a simple price per item)
    $total_price = 0;
    foreach ($items as $key => $item) {
        // Replace this with your actual price retrieval logic
        $price = 10; // Placeholder price
        $total_price += $price * $item['quantity'];
    }
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}

// ------------------- Example Usage (Simulating Actions) -------------------

// Example: Add a product to the cart
// add_to_cart(1, 2);

// Example: Remove a product
// remove_from_cart(1);

// Example: Update quantity
// update_quantity(1, 5);

?>

<!--  HTML form to add items to the cart (simulated) -->
<h2>Add Items to Cart</h2>
<form method="post">
  <label for="product_id">Product ID:</label>
  <select name="product_id" id="product_id">
    <?php
    //Simulate product list (replace with actual data retrieval)
    $products = [
        1 => ['name' => 'Laptop', 'price' => 2000],
        2 => ['name' => 'Mouse', 'price' => 25],
        3 => ['name' => 'Keyboard', 'price' => 75]
    ];

    foreach ($products as $id => $product) {
        echo "<option value='" . $id . "'>" . $id . "</option>";
    }
    ?>
  </select><br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity" value="1" min="1"><br><br>
  <input type="submit" value="Add to Cart">
</form>


<?php
session_start();

// Configuration
$items = []; // Array to store the shopping cart items
$database_file = "cart_data.txt"; // File to store cart data

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price of the single product.
 */
function addItemToCart(string $product_id, string $product_name, int $quantity, float $price): void
{
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $item = [
        'id' => $product_id,
        'name' => $product_name,
        'quantity' => $quantity,
        'price' => $price
    ];

    $_SESSION['cart'][] = $item;
    
    //Persist the cart data to a file (for session persistence)
    saveCartData();
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $newQuantity The new quantity of the product.
 */
function updateCartItemQuantity(string $product_id, int $newQuantity): void
{
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) { // Use &$item for modification
            if ($item['id'] === $product_id) {
                $item['quantity'] = $newQuantity;
                break;
            }
        }
        saveCartData();
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeItemFromCart(string $product_id): void
{
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] === $product_id) {
                unset($_SESSION['cart'][$key]);
                // Optionally, you can re-index the array if needed
                //  $_SESSION['cart'] = array_values($_SESSION['cart']);
                saveCartData();
                break;
            }
        }
    }
}


/**
 * Retrieves the contents of the shopping cart.
 *
 * @return array The shopping cart items.
 */
function getCartContents(): array
{
    return $_SESSION['cart'] ?? []; // Return empty array if cart is empty
}

/**
 * Saves the cart data to a file.
 */
function saveCartData(): void
{
    file_put_contents($database_file, json_encode($_SESSION['cart']));
}

/**
 * Loads the cart data from the file.
 */
function loadCartData(): void
{
    if (file_exists($database_file)) {
        $cartData = file_get_contents($database_file);
        if ($cartData = json_decode($cartData, true)) {
            $_SESSION['cart'] = $cartData;
        }
    }
}



// ---  Handle Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default to 1 if not provided
    $price = floatval($_POST['price']);  // Convert to float
    
    addItemToCart($product_id, $product_name, $quantity, $price);
    // Redirect to the cart page or display a confirmation message
    header("Location: cart.php"); // Redirect to cart.php
    exit();
}

// --- Handle Update Quantity Request ---
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $newQuantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    updateCartItemQuantity($product_id, $newQuantity);
    header("Location: cart.php");
    exit();
}

// --- Handle Remove Item Request ---
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    removeItemFromCart($product_id);
    header("Location: cart.php");
    exit();
}

// --- Load Cart Data on Page Load ---
loadCartData();

// --- Display Cart Contents (cart.php would handle this) ---
$cart_items = getCartContents();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else { ?>
    <table id="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr class="cart-item">
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                    <td>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px;">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove_item">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="checkout.php">Proceed to Checkout</a></p>
<?php } ?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$dbHost = 'localhost';
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';
$dbName = 'your_db_name';

//  Basic product data - replace with your product data source (e.g., database query)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
    4 => ['id' => 4, 'name' => 'Monitor', 'price' => 300],
];


// Function to add an item to the cart
function addItemToCart($productId, $cart) {
    if (isset($cart['items'][$productId])) {
        $cart['items'][$productId]['quantity']++;
    } else {
        $cart['items'][$productId] = ['quantity' => 1];
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity, $cart) {
    if (isset($cart['items'][$productId])) {
        $cart['items'][$productId]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function removeItemFromCart($productId, $cart) {
    unset($cart['items'][$productId]);
}

// Function to get the cart contents
function getCartContents($cart) {
    return $cart['items'];
}

// Cart initialization
$cart = ['items' => []];

// Handle form submissions (add to cart)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Validate and convert to integer

    if (isset($products[$productId])) {
        addItemToCart($productId, $cart);
    } else {
        echo "Product ID $productId not found.";
    }
}


// Handle updating quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateQuantity($productId, $quantity, $cart);
}

// Handle removing items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_item'])) {
    $productId = (int)$_POST['product_id'];
    removeItemFromCart($productId, $cart);
}


// Display the cart
echo "<h2>Shopping Cart</h2>";

if (empty($cart['items'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart['items'] as $id => $item) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $products[$id]['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $products[$id]['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . $products[$id]['price'] * $item['quantity'] . "<br>";
        echo "<form action=\"update_quantity.php\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"product_id\" value=\"".$id."\">";
        echo "<input type='number' name='quantity' value='".$item['quantity']."'>";
        echo "<button type=\"submit\">Update</button>";
        echo "</form>";
        echo "<form action=\"remove_item.php\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"product_id\" value=\"".$id."\">";
        echo "<button type=\"submit\">Remove</button>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";

    // Calculate total cart value
    $total = 0;
    foreach ($cart['items'] as $id => $item) {
        $total += $products[$id]['price'] * $item['quantity'];
    }

    echo "<p><strong>Total Cart Value:</strong> $" . $total . "</p>";
}


?>


<?php
session_start();

// Database connection (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//  Configuration
$items_per_page = 8; // Number of items to display per page
$total_items = 0;
$page = 1;

//  Shopping Cart Functions

/**
 * Add an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @return bool True if added successfully, false otherwise.
 */
function add_to_cart($product_id) {
  global $conn;

  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity']++;
  } else {
    // If not, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'id' => $product_id,
      'quantity' => 1,
      'name' =>  // Get product name from database -  See functions below for this
          "", // placeholder for now - add database query here
      'price' =>  // Get product price from database -  See functions below for this
          0.00,   // placeholder for now - add database query here
    ];
  }
  return true;
}

/**
 * Update the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return bool True if updated successfully, false otherwise.
 */
function update_cart_quantity($product_id, $new_quantity) {
  global $conn;

  if (isset($_SESSION['cart'][$product_id])) {
    if ($new_quantity > 0) {
      $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
      return true;
    } else {
      //  Remove item from cart if quantity is 0
      unset($_SESSION['cart'][$product_id]);
      return true;
    }
  }
  return false;
}

/**
 * Remove an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True if removed successfully, false otherwise.
 */
function remove_from_cart($product_id) {
  global $conn;

  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    return true;
  }
  return false;
}

/**
 * Get the total cart value.
 *
 * @return float The total cart value.
 */
function get_cart_total() {
  global $conn;
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price'];
    }
  }
  return $total;
}

/**
 *  Get cart contents.
 *
 * @return array The contents of the cart.
 */
function get_cart_contents() {
    return $_SESSION['cart'];
}

/**
 * Clear the cart
 *
 * @return bool True if cleared successfully, false otherwise.
 */
function clear_cart() {
    global $conn;
    unset($_SESSION['cart']);
    return true;
}


// Example Product Data - Replace with your database queries
$products = [
    [
        'id' => 1,
        'name' => 'Laptop',
        'price' => 1200.00
    ],
    [
        'id' => 2,
        'name' => 'Mouse',
        'price' => 25.00
    ],
    [
        'id' => 3,
        'name' => 'Keyboard',
        'price' => 75.00
    ]
];


// Shopping Cart Handling

// Add to cart functionality
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);
  add_to_cart($product_id);
}

// Update cart quantity
if (isset($_POST['update_quantity']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
  $product_id = intval($_POST['product_id']);
  $new_quantity = intval($_POST['quantity']);
  update_cart_quantity($product_id, $new_quantity);
}

// Remove from cart
if (isset($_POST['remove_from_cart']) && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);
  remove_from_cart($product_id);
}

// Clear Cart
if (isset($_POST['clear_cart'])) {
    clear_cart();
}

// Get Cart Contents
$cart_contents = get_cart_contents();

// Calculate total
$total = get_cart_total();

// Get page number
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
    //  Pagination logic goes here -  Example:
    $offset = ($page - 1) * $items_per_page;
    $limited_cart = array_slice($cart_contents, $items_per_page, $offset);
} else {
    $page = 1;
    $limited_cart = $cart_contents; // All cart contents if no page is specified.
}


// Display the Cart
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <form method="post" action="">
    <button type="submit" name="clear_cart">Clear Cart</button>
  </form>

  <?php if (empty($cart_contents)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>

    <?php foreach ($limited_cart as $item): ?>
      <div class="cart-item">
        <strong><?php echo $item['name']; ?></strong> - $<?php echo number_format($item['price'], 2); ?>
        <p>Quantity: <?php echo $item['quantity']; ?></p>
        <form method="post" action="">
          <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
          <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
          <button type="submit" name="update_quantity">Update</button>
        </form>
        <form method="post" action="">
          <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
          <button type="submit" name="remove_from_cart">Remove</button>
        </form>
      </div>
    <?php endforeach; ?>

    <p>Total: $<?php echo number_format($total, 2); ?></p>
    <a href="?page=1&page=<?php echo $page + 1; ?>">Next Page</a>
    <a href="?page=1&page=<?php echo $page - 1; ?>">Previous Page</a>

  <?php endif; ?>

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual details)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database_name";

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle adding to cart
function addToCart($productId, $quantity) {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null; // Get user ID from session

    if (!$userId) {
        return false; // User not logged in
    }

    // Check if product exists
    $productQuery = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($productQuery);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Product exists - add to cart
        $productName = $row['name'];
        $productPrice = $row['price'];

        // Check if the cart exists for this user
        $cartQuery = "SELECT id FROM carts WHERE userId = ? ";
        $stmt = $conn->prepare($cartQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $cartResult = $stmt->get_result();
        $cartId = null;

        if ($cartResult->num_rows > 0) {
            $cartId = $cartResult->fetch_assoc()['id'];
        } else {
            // Create a new cart for the user
            $newCartQuery = "INSERT INTO carts (userId) VALUES (?)";
            $stmt = $conn->prepare($newCartQuery);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $cartId = $conn->insert_id;
        }

        // Add item to cart
        $cartItemQuery = "INSERT INTO cart_items (cartId, productId, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($cartItemQuery);
        $stmt->bind_param("iii", $cartId, $productId, $quantity);
        $stmt->execute();

        return true;
    } else {
        return false; // Product not found
    }
    $stmt->close();
    $conn->close();
}


// Function to retrieve cart items
function getCartItems() {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    if (!$userId) {
        return []; // Empty cart for unauthenticated users
    }

    $cartItemsQuery = "SELECT ci.id AS cartItemId, ci.productId, ci.quantity, p.name, p.price FROM cart_items ci JOIN products p ON ci.productId = p.id WHERE ci.cartId IN (SELECT id FROM carts WHERE userId = ?) ";
    $stmt = $conn->prepare($cartItemsQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    $stmt->close();
    $conn->close();
    return $cartItems;
}


// Function to remove an item from cart
function removeFromCart($cartItemId) {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    if (!$userId) {
        return false; // User not logged in
    }

    $removeCartItemQuery = "DELETE FROM cart_items WHERE id = ?";
    $stmt = $conn->prepare($removeCartItemQuery);
    $stmt->bind_param("i", $cartItemId);
    $stmt->execute();

    return $stmt->affected_rows > 0; // Return true if item was removed
}

// Function to update quantity in cart
function updateCartQuantity($cartItemId, $newQuantity) {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    if (!$userId) {
        return false; // User not logged in
    }

    $updateCartItemQuery = "UPDATE cart_items SET quantity = ? WHERE id = ? ";
    $stmt = $conn->prepare($updateCartItemQuery);
    $stmt->bind_param("is", $newQuantity, $cartItemId);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}



// ---  Example Usage -  Frontend (JavaScript)  ---
//  This code demonstrates how you'd use the PHP functions in a JavaScript environment.
//  You'll need to adapt this to your specific frontend framework (e.g., React, Angular, Vue).
//  This is a simplified illustration.

//  1.  Add to Cart (example)
//  $productId = 1;
//  $quantity = 2;
//  if (addToCart($productId, $quantity)) {
//      console.log("Product added to cart");
//  } else {
//      console.log("Failed to add product to cart");
//  }

//  2. Retrieve Cart Items
//  let cartItems = getCartItems();
//  console.log("Cart Items:", cartItems);

//  3. Remove Item
//  let cartItemIdToRemove = 5;
//  if (removeFromCart(cartItemIdToRemove)) {
//      console.log("Item removed from cart");
//      let cartItems = getCartItems();
//      console.log("Cart Items after removal:", cartItems);
//  } else {
//      console.log("Failed to remove item from cart");
//  }

//  4. Update Quantity
//  let cartItemIdToUpdate = 3;
//  let newQuantity = 5;
//  if (updateCartQuantity(cartItemIdToUpdate, newQuantity)) {
//      console.log("Quantity updated");
//      let cartItems = getCartItems();
//      console.log("Cart Items after update:", cartItems);
//  } else {
//      console.log("Failed to update quantity");
//  }


// ---  Important Notes  ---
// 1.  Database Setup: Create the `products`, `carts`, and `cart_items` tables in your MySQL database.  The specific schema should be appropriate for your needs.

// 2.  Error Handling: This code includes minimal error handling.  In a production environment, you should implement more robust error handling and logging.

// 3.  Security: This code is a basic example and does not include all security measures.  You *must* sanitize user input, protect against SQL injection, and implement proper authentication and authorization.

// 4.  Frontend Integration: Adapt the JavaScript code to your frontend framework for proper rendering and interaction.
// 5.  Sessions: The code uses `session_start()` to maintain user sessions.  Make sure your server is configured to handle PHP sessions.

// Example database schema:

// products table:
//   id (INT, PRIMARY KEY)
//   name (VARCHAR)
//   price (DECIMAL)

// carts table:
//   id (INT, PRIMARY KEY)
//   userId (INT)

// cart_items table:
//   id (INT, PRIMARY KEY)
//   cartId (INT, FOREIGN KEY referencing carts.id)
//   productId (INT, FOREIGN KEY referencing products.id)
//   quantity (INT)
?>


<?php
session_start();

// Configuration
$items = []; // Array to store cart items
$cart_file = 'cart.json'; // File to store the cart data

// Helper function to load cart from file
function loadCartFromFile($file) {
    if (file_exists($file)) {
        $json = file_get_contents($file);
        if ($json !== false) {
            return json_decode($json, true); // Returns associative array
        } else {
            return [];
        }
    }
    return [];
}

// Helper function to save cart to file
function saveCartToFile($items, $file) {
    $json = json_encode($items);
    if ($json !== false) {
        file_put_contents($file, $json);
    } else {
        // Handle JSON encoding error
        error_log("Error encoding cart data to JSON: " . json_last_error_msg());
    }
}


// ================== Cart Functions ==================

// Add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $items;

    // Check if the item is already in the cart
    $item_exists = false;
    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] += $quantity;
            $item_exists = true;
            break;
        }
    }

    // If not in the cart, add a new item
    if (!$item_exists) {
        $items[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ];
    }
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    global $items;

    // Iterate and remove the item
    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($items[$key]);
            // Re-index the array to avoid gaps
            $items = array_values($items);
            break;
        }
    }
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    global $items;

    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] = $quantity;
            break;
        }
    }
}


// Get the contents of the cart
function get_cart_contents() {
    return $items;
}


// ==================  Cart Handling ==================

// Initialize the cart if it doesn't exist
if (!file_exists($cart_file)) {
    $items = [];
    saveCartToFile($items, $cart_file);
}

$items = loadCartFromFile($cart_file);

// ==================  Cart Actions ==================

// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// Handle removing an item
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    remove_from_cart($product_id);
    header("Location: cart.php");
    exit();
}


// Handle updating the quantity
if (isset($_GET['update'])) {
    $product_id = $_GET['update'];
    $quantity = $_POST['quantity'];
    update_quantity($product_id, $quantity);
    header("Location: cart.php");
    exit();
}


// ==================  Display Cart ==================
// You would typically display the cart contents on a separate page (cart.php)
// This is just a placeholder to demonstrate the functionality.
if (empty($items)) {
    echo "<h1>Your Cart is Empty</h1>";
} else {
    echo "<h1>Your Shopping Cart</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Quantity</th><th>Price</th></tr>"; // Assuming price is stored in the product data
    foreach ($items as $key => $item) {
        echo "<tr>";
        echo "<td>" . $item['product_id'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        //  You'll need to fetch the product's price from a database or other source
        //  Example (replace with your actual price retrieval method):
        //  $product = get_product_by_id($item['product_id']);
        //  echo "<td>" . $product['price'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Helper function to sanitize input (VERY IMPORTANT)
function sanitizeInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data); // Sanitize for HTML output
  return $data;
}

// --- Cart Functions ---

// Add item to cart
function addToCart($conn, $product_id, $quantity) {
  $product_id = sanitizeInput($product_id);
  $quantity = (int)$quantity; // Convert quantity to integer

  if ($quantity <= 0) {
    return false; // Invalid quantity
  }

  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

  // Check if user is logged in
  if (!$user_id) {
    return false; // User not logged in
  }

  // Check if item already exists in cart
  $query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Item exists, update quantity
    $row = $result->fetch_assoc();
    $quantity_in_cart = $row['quantity'] + $quantity;
    $conn->query("UPDATE cart SET quantity = '$quantity_in_cart' WHERE user_id = '$user_id' AND product_id = '$product_id'");
    return true;
  } else {
    // Item doesn't exist, add it to cart
    $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
    return true;
  }
}

// Get cart items
function getCartItems($conn, $user_id) {
  $query = "SELECT p.product_name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = '$user_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $cart_items = array();
    while($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return []; // Empty cart
  }
}

// Remove item from cart
function removeFromCart($conn, $product_id, $user_id) {
  $product_id = sanitizeInput($product_id);

  $query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
  return $conn->query($query);
}

// Update quantity in cart
function updateQuantity($conn, $product_id, $quantity, $user_id) {
  $product_id = sanitizeInput($product_id);
  $quantity = (int)$quantity;

  if ($quantity <= 0) {
    return false; // Invalid quantity
  }

  $query = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
  return $conn->query($query);
}

// --- Display Cart Page ---

// Get cart items for the logged-in user
$cart_items = getCartItems($conn, $_SESSION['user_id']);

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
  $total_price += $item['price'] * $item['quantity'];
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <table id="cartTable">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item): ?>
          <tr class="cart-item">
            <td><?php echo $item['product_name']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo $item['price'] * $item['quantity']; ?></td>
            <td>
              <a href="cart_update.php?product_id=<?php echo $item['product_id']; ?>&quantity=<?php echo $item['quantity']; ?>&action=update">Update</a> |
              <a href="cart_update.php?product_id=<?php echo $item['product_id']; ?>&action=remove">Remove</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p>Total: <?php echo $total_price; ?></p>
  <?php endif; ?>

  <p><a href="checkout.php">Proceed to Checkout</a></p>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get product_id, quantity and action from the URL
$product_id = $conn->real_escape_string($_GET['product_id']); // Escape to prevent SQL injection
$quantity = (int)$_GET['quantity'];
$action = $_GET['action'];

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Handle action - update or remove
if ($action == 'update') {
  if (updateQuantity($conn, $product_id, $quantity, $user_id)) {
    echo "Cart updated successfully.";
  } else {
    echo "Error updating cart.";
  }
} elseif ($action == 'remove') {
  removeFromCart($conn, $product_id, $user_id);
  echo "Product removed from cart.";
} else {
  die("Invalid action."); //Handle incorrect requests
}

// Redirect back to cart.php
header("Location: cart.php");
exit; //Important:  Stop further script execution
?>


<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <p>Thank you for your order!  (This is a placeholder - implement payment processing here)</p>
    <a href="cart.php">Return to Cart</a>
</body>
</html>


<?php
session_start();

// --- Database Connection (Replace with your actual credentials) ---
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Functions ---

// Add to Cart
function addToCart($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array(
            'quantity' => $quantity,
            'price' => getProductPrice($product_id) // Get price from product table
        );
    }
}

// Get Cart Contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Calculate Total Cart Value
function calculateTotal() {
    $total = 0;
    $cart = getCartContents();
    foreach ($cart as $item_id => $item_data) {
        $total_item_price = $item_data['price'] * $item_data['quantity'];
        $total += $total_item_price;
    }
    return $total;
}


// Get Product Price (Fetch from product table - adapt to your schema)
function getProductPrice($product_id) {
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $product_id = intval($product_id); // Ensure integer
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        return $row['price'];
    } else {
        return 0; // Or handle the error appropriately
    }
}


// --- Cart Management ---

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<h1>Your shopping cart is empty.</h1>";
    echo "<a href='index.php'>Browse Products</a>";
} else {

    // Display Cart Contents
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    $cart = getCartContents();
    foreach ($cart as $product_id => $item_data) {
        echo "<li>";
        echo "Product ID: " . $product_id . "<br>";
        echo "Quantity: " . $item_data['quantity'] . "<br>";
        echo "Price per item: " . $item_data['price'] . "<br>";
        echo "Total for this item: " . $item_data['price'] * $item_data['quantity'] . "<br>";
        echo "<form method='post' action='update_cart.php'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='number' name='quantity' value='" . $item_data['quantity'] . "' min='1'>";
        echo "<button type='submit'>Update</button>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";

    // Display Total
    echo "<p><strong>Total Cart Value: $" . calculateTotal() . "</strong></p>";

    // Checkout Link (Example -  Replace with your actual checkout process)
    echo "<a href='checkout.php'>Proceed to Checkout</a>";
}

// ---  Update Cart (update_cart.php -  Separated for clarity)
// This would handle the update of quantity in the cart.  It receives
// the product_id and the new quantity from the form submission.
//  It then calls the addToCart function to update the cart.
//  (Implementation details are intentionally omitted for brevity.)

?>


<?php
session_start();

// Configuration
$items = []; // Array to store items in the cart
$cart_file = 'cart.json'; // File to store cart data (JSON format)

// Function to load cart data from the file
function loadCart() {
    if (file_exists($cart_file)) {
        $data = file_get_contents($cart_file);
        if ($data) {
            $cart = json_decode($data, true);
            return $cart;
        } else {
            return [];
        }
    }
    return [];
}

// Function to save cart data to the file
function saveCart($cart) {
    file_put_contents($cart_file, json_encode($cart, JSON_PRETTY_PRINT));
}


// ------------------ Cart Operations ------------------

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    $cart = loadCart();

    if (empty($cart)) {
        $cart[$product_id] = $quantity;
    } else {
        if (isset($cart[$product_id])) {
            $cart[$product_id] += $quantity;
        } else {
            $cart[$product_id] = $quantity;
        }
    }

    saveCart($cart);
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    $cart = loadCart();
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
    saveCart($cart);
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    $cart = loadCart();
    if (isset($cart[$product_id])) {
        $cart[$product_id] = $quantity;
    }
    saveCart($cart);
}


// Get the cart contents
function get_cart_contents() {
    $cart = loadCart();
    return $cart;
}

// Calculate the total price
function calculate_total() {
    $cart = get_cart_contents();
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        // Assume we have a product price map (replace with your actual data source)
        $product_prices = [
            1 => 10,  // Product ID 1: $10
            2 => 20,  // Product ID 2: $20
            3 => 30   // Product ID 3: $30
        ];
        if (isset($product_prices[$product_id])) {
            $total_item_price = $product_prices[$product_id] * $quantity;
            $total += $total_item_price;
        }
    }
    return $total;
}



// ------------------  Example Usage (for demonstration) ------------------

// Example: Add an item to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    add_to_cart($product_id, $quantity);
    $cart_contents = get_cart_contents();
    echo "Item added to cart. Cart contents: " . json_encode($cart_contents) . "<br>";
}

// Example: Remove an item from the cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    remove_from_cart($product_id);
    $cart_contents = get_cart_contents();
    echo "Item removed from cart. Cart contents: " . json_encode($cart_contents) . "<br>";
}

// Example: Update quantity
if (isset($_GET['update_quantity'])) {
    $product_id = $_GET['update_quantity'];
    $new_quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
    update_quantity($product_id, $new_quantity);
    $cart_contents = get_cart_contents();
    echo "Quantity updated. Cart contents: " . json_encode($cart_contents) . "<br>";
}

// Show the cart contents
$cart_contents = get_cart_contents();
$total = calculate_total();

echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
}

echo "<h2>Total: $" . $total . "</h2>";

?>


<?php
session_start();

// Configuration (Adjust these for your needs)
$items = []; // Array to store cart items
$cart_key = 'cart_items';

// Function to add an item to the cart
function addItemToCart($productId, $quantity, $productName, $productPrice) {
  global $items, $cart_key;

  if (isset($_SESSION[$cart_key])) {
    $items = $_SESSION[$cart_key];
  } else {
    $items = [];
  }

  // Check if the item already exists in the cart
  $item_exists = false;
  foreach ($items as &$item) { // Use reference (&) to modify the original array
    if ($item['productId'] == $productId) {
      $item['quantity'] += $quantity;
      $item['name'] = $productName;
      $item['price'] = $productPrice;
      $item_exists = true;
      break;
    }
  }

  // If the item doesn't exist, add it to the cart
  if (!$item_exists) {
    $items[] = [
      'productId' => $productId,
      'name' => $productName,
      'price' => $productPrice,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($productId, $newQuantity) {
  global $items, $cart_key;

  foreach ($items as &$item) {
    if ($item['productId'] == $productId) {
      $item['quantity'] = $newQuantity;
      break;
    }
  }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  global $items, $cart_key;

  // Use array_filter to remove the item
  $items = array_filter($items, function ($item) use ($productId) {
    return $item['productId'] != $productId;
  });
}

// Function to get the cart total
function calculateCartTotal() {
  $total = 0;
  foreach ($items as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}

// Function to get the cart contents
function getCartContents() {
  global $items, $cart_key;
  return $items;
}


// --- Example Usage (simulated product data) ---
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Shoes', 'price' => 80]
];


// --- Handle Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
  $productId = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];
  $productName = $products[$productId]['name'];
  $productPrice = $products[$productId]['price'];

  addItemToCart($productId, $quantity, $productName, $productPrice);
  // Redirect to the cart page
  header("Location: cart.php");
  exit();
}

// --- Cart Page (cart.php) ---
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    table {
      width: 80%;
      border-collapse: collapse;
      margin: 20px auto;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
    $cart_items = getCartContents();
    $cart_total = calculateCartTotal();

    if (empty($cart_items)) {
      echo "<p>Your cart is empty.</p>";
    } else {
      echo "<table>";
      echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
      foreach ($cart_items as $item) {
        $itemTotal = $item['quantity'] * $item['price'];
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . number_format($itemTotal, 2) . "</td>";
        echo "</tr>";
      }
      echo "</table><p><strong>Total: $" . number_format($cart_total, 2) . "</strong></p>";
    }
  ?>

  <hr>

  <h2>Add Items to Cart</h2>
  <?php
  if (empty($cart_items)) {
      echo "<p>Click <a href='add_to_cart.php?product_id=1'>here</a> to add a T-Shirt.</p>";
      echo "<p>Click <a href='add_to_cart.php?product_id=2'>here</a> to add Jeans.</p>";
      echo "<p>Click <a href='add_to_cart.php?product_id=3'>here</a> to add Shoes.</p>";
  }
  ?>

  <hr>

  <p><a href="checkout.php">Checkout</a></p>

</body>
</html>


<?php
session_start();

// Configuration
$items = []; // Array to store items in the cart
$cart_file = 'cart.json'; // File to store cart data

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param int $quantity The quantity of the product being added.
 * @return void
 */
function add_to_cart(int $product_id, int $quantity) {
    // Check if the item is already in the cart
    if (isset($items[$product_id])) {
        $items[$product_id]['quantity'] += $quantity;
    } else {
        // If not, add it to the cart
        $items[$product_id] = ['quantity' => $quantity];
    }

    // Save the cart to the file
    save_cart_to_file();
}


/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product being removed.
 * @return void
 */
function remove_from_cart(int $product_id) {
    if (isset($items[$product_id])) {
        unset($items[$product_id]);
    }

    // Save the cart to the file
    save_cart_to_file();
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product being updated.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity(int $product_id, int $new_quantity) {
    if (isset($items[$product_id])) {
        $items[$product_id]['quantity'] = $new_quantity;
    }
    save_cart_to_file();
}



/**
 * Loads the cart from the JSON file.
 *
 * @return void
 */
function load_cart_from_file() {
    if (file_exists($cart_file)) {
        $json_data = file_get_contents($cart_file);
        $data = json_decode($json_data, true);

        if ($data) {
            $items = $data; // Directly assign the decoded array
        }
    }
}

/**
 * Saves the cart to the JSON file.
 *
 * @return void
 */
function save_cart_to_file() {
    file_put_contents($cart_file, json_encode($items));
}

/**
 * Displays the cart contents.
 *
 * @return void
 */
function display_cart() {
    if (empty($items)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($items as $product_id => $item) {
        $product_name = get_product_name($product_id); // Assuming you have a function to get product name
        echo "<li>" . $product_name . " - Quantity: " . $item['quantity'] . " - Price: $" . get_product_price($product_id) . "</li>";
    }
    echo "</ul>";
}


/**
 * Retrieves the product name based on its ID. (Placeholder - Implement your logic here)
 * @param int $product_id
 * @return string
*/
function get_product_name(int $product_id) {
    // Replace this with your actual product database lookup logic.
    // This is just a placeholder.
    switch ($product_id) {
        case 1: return "Awesome T-Shirt";
        case 2: return "Cool Mug";
        case 3: return "Fancy Hat";
        default: return "Unknown Product";
    }
}

/**
 * Retrieves the product price based on its ID. (Placeholder - Implement your logic here)
 * @param int $product_id
 * @return float
*/
function get_product_price(int $product_id) {
    // Replace this with your actual product database lookup logic.
    // This is just a placeholder.
    switch ($product_id) {
        case 1: return 19.99;
        case 2: return 9.99;
        case 3: return 24.99;
        default: return 0.00;
    }
}



// --- Main Execution ---

// Load the cart data
load_cart_from_file();

// --- Example Usage (for demonstration) ---
// Add some items to the cart
//add_to_cart(1, 2); // Add 2 T-shirts
//add_to_cart(2, 1); // Add 1 Mug
//add_to_cart(1, 1); //Add 1 T-shirt
//
// //Remove item
//remove_from_cart(2);
//
// //Update quantity
// update_cart_quantity(1, 3);
//
// //Display the cart
// display_cart();
?>

<!-- HTML Form for Adding Items -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <button type="submit">Add to Cart</button>
</form>


<?php
session_start();

// Configuration
$items = array(); // Array to store items in the cart
$cart_key = 'shopping_cart'; // Key to store the cart in session

// Helper Functions

// Function to add an item to the cart
function addItemToCart($product_id, $product_name, $price, $quantity) {
  global $items, $cart_key;

  $item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // Check if the item is already in the cart
  foreach ($items as $key => $existing_item) {
    if ($existing_item['id'] == $item['id']) {
      // Update the existing item's quantity
      $items[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If item not found, add it to the cart
  $items[] = $item;
}


// Function to get the cart items
function getCartItems() {
  global $items, $cart_key;
  return $items;
}

// Function to update the quantity of an item
function updateCartItemQuantity($product_id, $quantity) {
  global $items, $cart_key;

  // Find the item in the cart
  foreach ($items as $key => $item) {
    if ($item['id'] == $product_id) {
      $items[$key]['quantity'] = $quantity;
      return true;
    }
  }
  return false; // Item not found
}


// Function to remove an item from the cart
function removeItemFromCart($product_id) {
  global $items, $cart_key;

  // Iterate through the items and remove the item with the matching product_id
  for ($i = 0; $i < count($items); $i++) {
    if ($items[$i]['id'] == $product_id) {
      unset($items[$i]);
      // Re-index the array to avoid gaps
      $items = array_values($items);
      return true;
    }
  }
  return false;
}

// Function to calculate the cart total
function calculateCartTotal() {
    global $items;
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// ----  Handle incoming requests  ----

// Check if the request is adding an item to the cart
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addItemToCart($product_id, $product_name, $price, $quantity);
  // Redirect to the cart page
  header("Location: cart.php");
  exit();
}


// Check if the request is updating the quantity of an item
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateCartItemQuantity($product_id, $quantity);
    header("Location: cart.php");
    exit();
}

// Check if the request is removing an item from the cart
if (isset($_POST['action']) && $_POST['action'] == 'remove_item') {
    $product_id = $_POST['product_id'];
    removeItemFromCart($product_id);
    header("Location: cart.php");
    exit();
}



// ----  Display the cart contents  ----

// Get the cart items
$cart_items = getCartItems();

// Calculate the total
$total = calculateCartTotal();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else { ?>
    <table id="cart-table">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($cart_items as $key => $item) {
          $total_item_price = $item['price'] * $item['quantity'];
          echo '<tr class="cart-item">
                <td>' . $item['name'] . '</td>
                <td>$' . number_format($item['price'], 2) . '</td>
                <td>' . $item['quantity'] . '</td>
                <td>$' . number_format($total_item_price, 2) . '</td>
                <td>
                  <form action="cart.php" method="post">
                    <input type="hidden" name="action" value="update_quantity">
                    <input type="hidden" name="product_id" value="' . $item['id'] .'">
                    <input type="number" name="quantity" value="' . $item['quantity'] .'" min="1" style="width:50px;">
                    <button type="submit">Update</button>
                  </form>
                  <form action="cart.php" method="post">
                    <input type="hidden" name="action" value="remove_item">
                    <input type="hidden" name="product_id" value="' . $item['id'] .'">
                    <button type="submit">Remove</button>
                  </form>
                </td>
              </tr>';
        } ?>
      </tbody>
    </table>
    <p><strong>Total:</strong> $' . number_format($total, 2) . '</p>
  <?php } ?>

  <a href="checkout.php">Checkout</a>  <!--  Link to checkout page -->

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "shopping_cart";

// Check if the connection is successful
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ------------------------------------------------------------------
// Product Data (Simulated Database for Example)
// In a real application, you'd retrieve this from a database.
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];

// ------------------------------------------------------------------
// Functions
// ------------------------------------------------------------------

// Add to Cart Function
function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }
}

// Remove from Cart Function
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Get Cart Items
function getCartItems() {
    return $_SESSION['cart'];
}

// Calculate Total Cart Value
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalItemPrice = $item['price'] * $item['quantity'];
            $total = $total + $totalItemPrice;
        }
    }
    return $total;
}


// ------------------------------------------------------------------
// Handle Actions (Adding to Cart, etc.)
// ------------------------------------------------------------------

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    addToCart($productId, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $productId = $_POST['product_id'];
    removeFromCart($productId);
}


// ------------------------------------------------------------------
// Display Cart Page
// ------------------------------------------------------------------
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
// Display Cart Items
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

    foreach ($_SESSION['cart'] as $productId => $item) {
        $product = $products[$productId]; // Get product details
        $totalItemPrice = $product['price'] * $item['quantity'];
        echo "<tr class='cart-item'>";
        echo "<td>" . $product['name'] . "</td>";
        echo "<td>$" . $product['price'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . $totalItemPrice . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<div class='cart-total'>Total: $" . calculateTotal() . "</div>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>

<hr>

<h2>Add Items to Cart</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>Product ID: " . $product['id'] . " - " . $product['name'] . " - Price: $" . $product['price'] . " <button onclick='addToCart(" . $product['id'] . ", 1)'>Add to Cart</button></li>";
    }
    ?>
</ul>


</body>
</html>


<?php
session_start();

// Configuration
$cart_file = 'cart.php';

// Function to get the cart data
function get_cart_data() {
    if (file_exists($cart_file)) {
        $cart = unserialize(file_get_contents($cart_file));
        if (!$cart) {
            return []; // Return an empty array if the file is empty or corrupted
        }
        return $cart;
    } else {
        return [];
    }
}

// Function to save the cart data
function save_cart_data($cart) {
    file_put_contents($cart_file, serialize($cart));
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    $cart = get_cart_data();

    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }

    // If the product is not in the cart, add it
    if (!isset($item['product_id'])) {
        $cart[] = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    save_cart_data($cart);
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    $cart = get_cart_data();

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            break;
        }
    }

    save_cart_data($cart);
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    $cart = get_cart_data();

    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            break;
        }
    }

    save_cart_data($cart);
}

// Example Usage (For demonstration purposes - Replace with your product/database logic)
// Simulate product information
$products = [
    1 => ['product_id' => 1, 'product_name' => 'Laptop', 'price' => 1200],
    2 => ['product_id' => 2, 'product_name' => 'Mouse', 'price' => 25],
    3 => ['product_id' => 3, 'product_name' => 'Keyboard', 'price' => 75],
];


// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    add_to_cart($product_id, $product_name, $price, $quantity);
    echo "<p>Item added to cart.</p>";
}

// Handle removing an item from the cart
if (isset($_GET['remove_item'])) {
    $product_id = $_GET['remove_item'];
    remove_from_cart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// Handle updating quantity
if (isset($_GET['update_quantity'])) {
    $product_id = $_GET['update_quantity'];
    $new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; //Default to 1 if not provided
    update_quantity($product_id, $new_quantity);
    echo "<p>Quantity updated.</p>";
}


// Display the cart
$cart = get_cart_data();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
    echo "<a href='cart.php?action=empty'>Empty Cart</a>"; //Link to empty the cart
}

?>


<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Initialize the cart
$_SESSION['cart'] = array();

// Function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return true;
        }
    }
    return false;
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return true;
    }
  }
  return false;
}


// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total = $total + ($item['price'] * $item['quantity']);
  }
  return $total;
}


// ---  Example Usage & Handling (Illustrative - Adapt to your product/database system) ---

// 1. Adding an item to the cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $product_name, $price, $quantity);
  echo "<p>Item added to cart!</p>";
}

// 2. Updating quantity (e.g., from a form submission)
if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $new_quantity);
  echo "<p>Quantity updated!</p>";
}

// 3. Removing an item from the cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeCartItem($product_id);
  echo "<p>Item removed from cart!</p>";
}

// 4. Displaying the cart contents
$cart_contents = getCartContents();

if (!empty($cart_contents)) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart_contents as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>


<?php
session_start();

// --- Database Connection (Replace with your database details) ---
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// --------------------------------------------------------------

// --- Product Data (Replace with your actual product data source) ---
$products = array(
  1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200, 'description' => 'Powerful laptop for work and play.'),
  2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25, 'description' => 'Ergonomic wireless mouse.'),
  3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75, 'description' => 'Mechanical keyboard for a superior typing experience.'),
  4 => array('id' => 4, 'name' => 'Monitor', 'price' => 300, 'description' => '27-inch LED monitor.'),
);
// --------------------------------------------------------------

// --- Cart Functionality ---

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to Cart
function addToCart($product_id, $quantity) {
  global $conn, $products;

  // Check if the product exists
  if (isset($products[$product_id])) {
    $product = $products[$product_id];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      $_SESSION['cart'][$product_id] = array(
        'id' => $product_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'description' => $product['description']
      );
    }
  } else {
    echo "Product ID $product_id not found.";
  }
}

// Remove from Cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  } else {
    echo "Product ID $product_id not found in cart.";
  }
}

// Update Quantity in Cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        echo "Product ID $product_id not found in cart.";
    }
}

// Get Cart Contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Calculate Total Cart Value
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Displaying the Cart ---

// Check if there are items in the cart
$cart_items = getCartContents();
$total_value = calculateTotal();

echo "<h1>Shopping Cart</h1>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total_value . "</strong></p>";
}

// --- Example Buttons & Forms ---

echo "<br>";
echo "<a href='add_to_cart.php?product_id=1'>Add Laptop to Cart</a> | ";
echo "<a href='add_to_cart.php?product_id=2'>Add Mouse to Cart</a> | ";
echo "<a href='add_to_cart.php?product_id=3'>Add Keyboard to Cart</a>";

?>

<!--
Add to Cart Form (add_to_cart.php)
<!DOCTYPE html>
<html>
<head>
    <title>Add to Cart</title>
</head>
<body>

    <h1>Add Product to Cart</h1>

    <form method="post" action="shopping_cart.php">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1">
        <button type="submit">Add to Cart</button>
    </form>

</body>
</html>
-->


<?php
session_start();

// Configuration
$items = array(
    "Product 1" => array("price" => 10.00, "quantity" => 1),
    "Product 2" => array("price" => 20.00, "quantity" => 1),
    "Product 3" => array("price" => 5.00, "quantity" => 2)
);

// Cart initialization
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Helper Functions ---

// Add item to cart
function add_to_cart($product_name, $quantity = 1) {
    global $items;

    if (isset($items[$product_name])) {
        if (isset($_SESSION['cart'][$product_name])) {
            $_SESSION['cart'][$product_name]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_name] = array("price" => $items[$product_name]['price'], "quantity" => $quantity);
        }
    } else {
        echo "<p style='color:red;'>Product '$product_name' not found.</p>";
    }
}


// Calculate total cart value
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Cart Actions based on user input ---

// Add item to cart (handled via form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        add_to_cart($_POST['product_name'], $_POST['quantity']);
    }
}

// Remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $product_name = $_POST['remove_item'];
    if (isset($_SESSION['cart'][$product_name])) {
        unset($_SESSION['cart'][$product_name]);
    }
}

// Clear the entire cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
}

// --- Display Cart Content ---

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['quantity'] . " x " . $item['price'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . number_format(calculate_total(), 2) . "</strong></p>";

    // Remove Item Button (for each item)
    echo "<form method='post'>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<label for='remove_" . $item['price'] . "'>Remove " . $item['price'] . "</label> ";
        echo "<input type='submit' name='remove_item' value='Remove' class='remove-button' id='remove_" . $item['price'] . "'>";
    }
    echo "</form>";

    // Clear Cart Button
    echo "<form method='post'>";
    echo "<input type='submit' name='clear_cart' value='Clear Cart'>";
    echo "</form>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Shopping Cart</title>
    <style>
        .remove-button {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Products</h2>
<form method="post">
    <label for="product_name">Product Name:</label>
    <select name="product_name" id="product_name">
        <?php
        foreach (array_keys($items) as $product_name) {
            echo "<option value='" . $product_name . "'>" . $product_name . "</option>";
        }
        ?>
    </select>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

</body>
</html>


<?php
session_start();

// Configuration
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 1],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 1],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 2],
];

$cart = []; // Initialize an empty cart
$cart_count = 0; // Initialize cart count

// Handle Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure it's an integer

    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer

        if (isset($cart[$product_id])) {
            // Product already in cart, increase quantity
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            // Product not in cart, add it
            $cart[$product_id] = ['name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
        }
        $cart_count = count($cart);
    }
}

// Handle Remove from Cart functionality
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure it's an integer

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        $cart_count = count($cart);
    }
}


// Display Cart
echo "<h2>Shopping Cart</h2>";

if (count($cart) > 0) {
    echo "<ul>";
    foreach ($cart as $product_id => $product) {
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "  (Quantity: " . $product['quantity'] . ")</li>";
    }
    echo "</ul>";

    echo "<p>Total: $" . number_format(array_sum(array_map(function($product) { return $product['price'] * $product['quantity']; }, $cart)), 2) . "</p>";

} else {
    echo "<p>Your cart is empty.</p>";
}

// Display Products for Purchase
echo "<h2>Available Products</h2>";
echo "<ul>";
foreach ($products as $id => $product) {
    echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "<br>";
    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
    echo "<label for='product_" . $id . "'>Quantity:</label>";
    echo "<input type='number' id='product_" . $id . "' min='1' value='" . (isset($_POST['product_' . $id]) ? $_POST['product_' . $id] : 1) . "'>";
    echo "<input type='hidden' name='product_id' value='" . $id . "'>";
    echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
    echo "</form></li>";
}
echo "</ul>";
?>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'root';
$db_password = '';

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    $conn = connectDB();

    if (!$conn) {
        return false;
    }

    // Check if the product exists
    $product_query = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product_name = $row['name'];
        $product_price = $row['price'];

        // Get the cart or create a new one
        $cart_key = 'cart';
        if (!isset($_SESSION[$cart_key])) {
            $_SESSION[$cart_key] = [];
        }

        // Check if the product is already in the cart
        if (isset($_SESSION[$cart_key][$product_id])) {
            // Increment quantity
            $_SESSION[$cart_key][$product_id]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $_SESSION[$cart_key][$product_id] = [
                'quantity' => $quantity,
                'name' => $product_name,
                'price' => $product_price
            ];
        }
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false; // Product not found
    }
}


// Function to get the cart contents
function getCartContents() {
    $cart_key = 'cart';
    if (isset($_SESSION[$cart_key])) {
        return $_SESSION[$cart_key];
    } else {
        return [];
    }
}


// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    $cart_key = 'cart';

    if (isset($_SESSION[$cart_key][$product_id])) {
        $_SESSION[$cart_key][$product_id]['quantity'] = $quantity;
        return true;
    } else {
        return false; // Product not in cart
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    $cart_key = 'cart';

    if (isset($_SESSION[$cart_key][$product_id])) {
        unset($_SESSION[$cart_key][$product_id]);
        return true;
    } else {
        return false; // Product not in cart
    }
}

// Function to clear the entire cart
function clearCart() {
    unset($_SESSION['cart']);
}

// Example Usage (Handle Add to Cart Button)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (addToCart($product_id, $quantity)) {
        echo "<p>Item added to cart!</p>";
    } else {
        echo "<p>Failed to add item to cart.</p>";
    }
}


// Example Usage (Handle Remove from Cart Button)
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    if (removeFromCart($product_id)) {
        echo "<p>Item removed from cart!</p>";
    } else {
        echo "<p>Failed to remove item from cart.</p>";
    }
}

// Example Usage (Handle Update Quantity Button)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    if (updateCartItemQuantity($product_id, $new_quantity)) {
        echo "<p>Quantity updated!</p>";
    } else {
        echo "<p>Failed to update quantity.</p>";
    }
}

// Example Usage (Handle Clear Cart Button)
if (isset($_POST['clear_cart'])) {
    clearCart();
    echo "<p>Cart cleared!</p>";
}


// Display the Cart Contents
$cart_contents = getCartContents();

// HTML for the Cart Display
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (!empty($cart_contents)): ?>
    <form method="post">
        <label for="clear_cart">Clear Cart:</label>
        <button type="submit" name="clear_cart">Clear</button>
    </form>

    <h2>Cart Items:</h2>
    <?php
    $total = 0;
    foreach ($cart_contents as $item_id => $item_data):
        $product_name = $item_data['name'];
        $product_price = $item_data['price'];
        $quantity = $item_data['quantity'];
        $item_total = $product_price * $quantity;

        $total += $item_total;

        echo "<h3>" . $product_name . "</h3>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price per item: $" . $product_price . "</p>";
        echo "<p>Item Total: $" . $item_total . "</p>";
        echo "<input type='number' name='quantity[{$item_id}]' value='{$quantity}' min='1' />"; // Use this for updating quantity
        echo "<br>";
    endforeach;
    ?>
    <br>
    <h2>Total: $<?php echo $total; ?></h2>
    <form method="post">
        <input type="hidden" name="product_id" value="<?php echo $cart_contents ? array_key_first($cart_contents); ?>" />
        <input type="submit" name="add_to_cart" value="Update Cart" />
    </form>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

</body>
</html>


<?php
session_start();

// Define cart items as an array
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        // Item already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it with quantity 1
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the total cart value
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to display the cart contents
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $item['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        echo "<form method='post'>";
        echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
        echo "<input type='number' id='quantity_" . $product_id . "' value='" . $item['quantity'] . "' min='1' max='10'>"; //Example max quantity limit
        echo "<input type='submit' value='Update'>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total:</strong> $" . number_format(calculateTotal(), 2) . "</p>";
}

// --- Example Product Data (For Demonstration) ---
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];


// --- Handle Form Submission (Add to Cart) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    $quantity = (int)$_POST['quantity'];

    if (isset($products[$product_id])) {
        addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    } else {
        echo "<p style='color:red;'>Invalid Product ID.</p>";
    }
}

// --- Display the Cart ---
displayCart();

?>


<?php
session_start();

// Database connection details (Replace with your actual details)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Function to connect to the database
function connectDB($host, $user, $pass, $name) {
    $conn = new mysqli($host, $user, $pass, $name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to create a new cart
function createCart($conn) {
  // Check if cart exists for the user. If not, create it.
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Get user ID from session
  if ($user_id) {
    // Check if cart exists for this user.  If not, create it.
    $sql = "SELECT id FROM carts WHERE user_id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Cart exists, do nothing (it's already there)
    } else {
        $sql = "INSERT INTO carts (user_id) VALUES ('$user_id')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['cart_id'] = $conn->insert_id; // Get the newly created cart ID
            echo "<p>New cart created for you!</p>";
        } else {
            echo "<p>Error creating cart: " . $conn->error . "</p>";
        }
    }
} else {
    // No user logged in, so create a default cart
    $sql = "INSERT INTO carts (user_id) VALUES (NULL)"; // No user ID, create default cart
    if ($conn->query($sql) === TRUE) {
        $_SESSION['cart_id'] = $conn->insert_id; // Get the newly created cart ID
        echo "<p>Default cart created for you!</p>";
    } else {
        echo "<p>Error creating default cart: " . $conn->error . "</p>";
    }
}
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;

    if (!$user_id || !$cart_id) {
        echo "<p>Please log in or create a cart before adding items.</p>";
        return false;
    }

    $sql = "INSERT INTO cart_items (cart_id, product_id, quantity)
            VALUES ('$cart_id', '$product_id', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "<p>Error adding item to cart: " . $conn->error . "</p>";
        return false;
    }
}

// Function to get cart items
function getCartItems($conn) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;
    $cart_items = [];

    if (!$user_id || !$cart_id) {
        return $cart_items; // Return empty array if no cart or user
    }

    $sql = "SELECT ci.product_id, p.name, p.price, ci.quantity
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = '$cart_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }
    return $cart_items;
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id, $cart_id) {
    $sql = "DELETE FROM cart_items WHERE product_id = '$product_id' AND cart_id = '$cart_id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "<p>Error removing item from cart: " . $conn->error . "</p>";
        return false;
    }
}

// Function to update quantity of an item in the cart
function updateQuantity($conn, $product_id, $cart_id, $quantity) {
    $sql = "UPDATE cart_items
            SET quantity = '$quantity'
            WHERE product_id = '$product_id' AND cart_id = '$cart_id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "<p>Error updating quantity in cart: " . $conn->error . "</p>";
        return false;
    }
}


// --- Example Usage (Simulated for demonstration) ---

// Connect to the database
$conn = connectDB($dbHost, $dbUser, $dbPass, $dbName);

// Initialize Cart (Run this once per session)
createCart($conn);

// Get cart items
$cart_items = getCartItems($conn);

// Display cart items
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// --- Example Cart Operations (Simulated - Replace with your actual form handling) ---

// Assuming a form to update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($conn, $product_id, $_SESSION['cart_id'], $new_quantity);
    // Optionally redirect to the cart page after updating.
}

// Example: Remove Item
if (isset($_GET['remove_item'])) {
    $product_id = $_GET['remove_item'];
    removeFromCart($conn, $product_id, $_SESSION['cart_id']);
    // Redirect to the cart page to refresh.
}

?>


<?php
session_start();

// Database connection (Replace with your actual database credentials)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// ------------------------------------------------------------------
// Helper Functions
// ------------------------------------------------------------------

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}

// Function to sanitize input (basic example, enhance for production)
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// ------------------------------------------------------------------
// Cart Management Functions
// ------------------------------------------------------------------

// Function to initialize the cart
function initializeCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Function to add an item to the cart
function addItemToCart($product_id, $name, $price, $quantity) {
    initializeCart();

    $item = [
        'product_id' => $product_id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    ];

    $_SESSION['cart'][] = $item;
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    initializeCart();
    
    // Find the index of the item to update
    $index = -1;
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['product_id'] == $product_id) {
            $index = $i;
            break;
        }
    }

    if ($index !== -1) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    initializeCart();
    
    $key = array_search($product_id, $_SESSION['cart']);
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
        // Optionally, re-index the array to prevent gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to get the cart total
function getCartTotal() {
    initializeCart();
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to get the cart items
function getCartItems() {
    initializeCart();
    return $_SESSION['cart'];
}

// ------------------------------------------------------------------
// Cart Handling based on user request
// ------------------------------------------------------------------

// Example: Handle adding to cart (replace with your actual form handling)
if (isset($_POST['add_to_cart'])) {
    $product_id = sanitizeInput($_POST['product_id']);
    $name = sanitizeInput($_POST['name']);
    $price = sanitizeInput($_POST['price']);
    $quantity = sanitizeInput($_POST['quantity']);

    addItemToCart($product_id, $name, $price, $quantity);
}

// Example: Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $product_id = sanitizeInput($_POST['product_id']);
    $quantity = sanitizeInput($_POST['quantity']);

    updateQuantity($product_id, $quantity);
}

// Example: Handle removing an item
if (isset($_POST['remove_item'])) {
    $product_id = sanitizeInput($_POST['product_id']);
    removeItemFromCart($product_id);
}

// ------------------------------------------------------------------
// Display Cart Function
// ------------------------------------------------------------------

function displayCart() {
    echo '<h2>Your Shopping Cart</h2>';

    if (empty($_SESSION['cart'])) {
        echo '<p>Your cart is empty.</p>';
        return;
    }

    echo '<table border="1">';
    echo '<thead><tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr></thead>';
    echo '<tbody>';

    $cartItems = getCartItems();

    foreach ($cartItems as $item) {
        $total = $item['price'] * $item['quantity'];
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>' . $item['price'] . '</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '<td>' . $total . '</td>';
        echo '<td><a href="?action=remove&product_id=' . $item['product_id'] . '">Remove</a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<br>';
    echo '<strong>Total: ' . getCartTotal() . '</strong>';
}

// ------------------------------------------------------------------
// Output
// ------------------------------------------------------------------

?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php displayCart(); ?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'shop_db';
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

// Function to check if a product is in the cart
function isInCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return true;
    }
    return false;
}

// Function to update the cart
function updateCart($product_id, $quantity) {
    if (isInCart($product_id)) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to get the cart items
function getCartItems() {
    $cart_items = array();
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_info = mysqli_fetch_assoc(mysqli_query(connectToDatabase(), "SELECT * FROM products WHERE id = '$product_id'"));
        $cart_items[] = array(
            'id' => $product_info['id'],
            'name' => $product_info['name'],
            'price' => $product_info['price'],
            'quantity' => $quantity
        );
    }
    return $cart_items;
}

// Function to calculate the total price
function calculateTotalPrice() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_info = mysqli_fetch_assoc(mysqli_query(connectToDatabase(), "SELECT * FROM products WHERE id = '$product_id'"));
        $total += $product_info['price'] * $quantity;
    }
    return $total;
}


// --- Handle Add to Cart Request ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    updateCart($product_id, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// --- Handle Remove from Cart Request ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}


// --- Display Cart Items ---
$cart_items = getCartItems();
$total_price = calculateTotalPrice();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else { ?>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>
                        <form action="cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove_from_cart">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Total Price:</strong> $<?php echo number_format($total_price, 2); ?></p>
<?php } ?>

<br>
<a href="checkout.php">Checkout</a>

</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    // Check if the product exists
    $product_query = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product_name = $row['name'];
        $product_price = $row['price'];

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'quantity' => $quantity,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price
            ];
        }
    } else {
        // Product not found - you might want to log this
        echo "Product with ID " . $product_id . " not found.";
    }

    $stmt->close();
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
    $total = 0;
    $cart = getCartContents();
    foreach ($cart as $item) {
        $total += $item['product_price'] * $item['quantity'];
    }
    return $total;
}

// ---------------------  Handle Cart Actions  ---------------------

// Check if the action is "add"
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
}

// Check if the action is "remove"
if (isset($_POST['action']) && $_POST['action'] == 'remove') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Check if the action is "update"
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateQuantity($product_id, $quantity);
}


// --------------------- Display the Cart  ---------------------

// Get the cart contents
$cart = getCartContents();

// Calculate the total
$total = calculateTotal();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>

        <table id="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($cart as $item): ?>
                    <tr class="cart-item">
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo $item['product_price']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <button type="submit">Update</button>
                            </form>
                            <form method="post">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total:</strong> <?php echo number_format($total, 2); ?></p>
    <?php endif; ?>

    <hr>
    <p>
        <a href="index.php">Continue Shopping</a>
    </p>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$dbHost = 'localhost';
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';
$dbName = 'your_db_name';

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($conn, $userId, $productId, $quantity) {
    // Check if the user has a cart yet
    if (!isset($_SESSION['cart'][$productId])) {
        // If not, create a new entry in the cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($userId, $productId, $quantity)";
        if ($conn->query($query) === TRUE) {
            $_SESSION['cart'][$productId] = $quantity;
        } else {
            echo "Error adding to cart: " . $conn->error;
        }
    } else {
        // If the item already exists, update the quantity
        $_SESSION['cart'][$productId] += $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($conn, $userId, $productId) {
    // Delete the item from the cart
    $query = "DELETE FROM cart WHERE user_id = $userId AND product_id = $productId";
    if ($conn->query($query) === TRUE) {
        unset($_SESSION['cart'][$productId]);
    } else {
        echo "Error removing from cart: " . $conn->error;
    }
}

// Function to get the cart contents
function getCartContents($conn, $userId) {
    $cartContents = [];

    // Retrieve all cart items for the user
    $query = "SELECT product_id, quantity FROM cart WHERE user_id = $userId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            // Get product details (you'll need a products table)
            $product_query = "SELECT product_id, product_name, price FROM products WHERE product_id = $product_id";
            $product_result = $conn->query($product_result);

            if ($product_result->num_rows > 0) {
                $product = $product_result->fetch_assoc();
                $cartContents[] = [
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'total' => $product['price'] * $quantity
                ];
            } else {
                // Handle the case where the product is not found
                echo "Product ID $product_id not found.";
            }
        }
    }

    return $cartContents;
}

// ------  Shopping Cart Logic  ------

// 1. Add to Cart (Handle form submission)
if (isset($_POST['add_to_cart'])) {
    $userId = $_SESSION['user_id']; // Assuming user is logged in
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    addToCart($conn, $userId, $productId, $quantity);
    header("Location: cart.php"); // Redirect to cart page
    exit();
}

// 2. Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $userId = $_SESSION['user_id'];
    $productId = $_GET['remove_from_cart'];
    removeFromCart($conn, $userId, $productId);
    header("Location: cart.php");
    exit();
}

// 3. Get Cart Contents (Load cart data)
$cartContents = getCartContents($conn, $_SESSION['user_id']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (empty($cartContents)) {
        echo "<p>Your cart is empty.</p>";
    } else { ?>

        <?php foreach ($cartContents as $item): ?>
            <div class="cart-item">
                <strong>Product:</strong> <?php echo $item['product_name']; ?> (ID: <?php echo $item['product_id']; ?>)
                <br>
                <strong>Price:</strong> $<?php echo number_format($item['price'], 2); ?>
                <br>
                <strong>Quantity:</strong> <?php echo $item['quantity']; ?>
                <br>
                <strong>Total:</strong> $<?php echo number_format($item['total'], 2); ?>
                <br>
                <form action="cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                    <button type="submit">Remove from Cart</button>
                </form>
            </div>
        <?php } ?>

    <a href="checkout.php">Checkout</a> <!-- Link to checkout page -->

</body>
</html>


<?php
session_start();

// Configuration
$items = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

$cart = []; // Initialize an empty cart
$total = 0;
$cart_id = "cart_" . md5(time()); // Unique cart ID


// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $itemId The ID of the item to add.
 * @param int $quantity The quantity of the item to add.
 */
function addToCart(int $itemId, int $quantity = 1)
{
    global $cart, $itemId, $cart_id;

    if (isset($items[$itemId])) {
        $item = $items[$itemId];
        $item_id = $itemId;

        // Check if the item is already in the cart
        if (isset($cart[$item_id][$item_id])) {
            $cart[$item_id][$item_id]['quantity'] += $quantity;
            $cart[$item_id][$item_id]['quantity'] = $cart[$item_id][$item_id]['quantity'];
        } else {
            $cart[$item_id][$item_id] = ['name' => $item['name'], 'price' => $item['price'], 'quantity' => $quantity];
        }

        // Update the total
        $total += $item['price'] * $quantity;
    } else {
        // Item not found
        echo "Item ID " . $itemId . " not found.";
    }
}


/**
 * Removes an item from the cart.
 *
 * @param int $itemId The ID of the item to remove.
 */
function removeFromCart(int $itemId)
{
    global $cart, $cart_id;

    if (isset($cart[$cart_id][$itemId])) {
        unset($cart[$cart_id][$itemId]);

        // Update the total
        $total -= $items[$itemId]['price'] * $items[$itemId]['price'];

        // If the cart is now empty, reset the total to 0
        if (empty($cart[$cart_id])) {
            $total = 0;
        }

    } else {
        // Item not found in the cart
        echo "Item ID " . $itemId . " not found in the cart.";
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $itemId The ID of the item to update.
 * @param int $quantity The new quantity of the item.
 */
function updateQuantity(int $itemId, int $quantity)
{
    global $cart, $cart_id, $items;

    if (isset($items[$itemId])) {
        if (isset($cart[$cart_id][$itemId])) {
            $cart[$cart_id][$itemId]['quantity'] = $quantity;
            $total -= $items[$itemId]['price'] * ($cart[$cart_id][$item_id]['quantity'] - $quantity);
            $total += $items[$itemId]['price'] * $quantity;

        } else {
            // Item not found in the cart
            echo "Item ID " . $itemId . " not found in the cart.";
        }
    } else {
        // Item not found
        echo "Item ID " . $itemId . " not found.";
    }
}



/**
 * Gets the cart contents.
 *
 * @return array  An array representing the cart contents.
 */
function getCart()
{
    return $cart;
}


/**
 * Gets the total cart value.
 *
 * @return float The total value of the cart.
 */
function getTotal()
{
    return $total;
}



// --- Handling Cart Actions (Example) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $itemId = (int)$_POST['item_id'];
    $quantity = (int)$_POST['quantity'] ?? 1; // Default to 1 if quantity not provided
    addToCart($itemId, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $itemId = (int)$_POST['item_id'];
    removeFromCart($itemId);
}

if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $itemId = (int)$_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    updateQuantity($itemId, $quantity);
}

// --- Displaying the Cart ---
$cart_contents = getCart();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .cart-total { font-weight: bold; }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (!empty($cart_contents)) { ?>
    <table class="cart-items">
        <?php foreach ($cart_contents as $cart_id => $items_in_cart): ?>
            <?php foreach ($items_in_cart as $item_id => $item_data): ?>
                <tr class="cart-item">
                    <td><?php echo $item_data['name']; ?></td>
                    <td><?php echo $item_data['quantity']; ?></td>
                    <td><?php echo $item_data['price']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="remove_from_cart">
                            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>

    <div class="cart-total">Total: $<?php echo number_format($total, 2); ?></div>
<?php } else {
    echo "<p>Your cart is empty.</p>";
} ?>


<hr>

<h2>Add Items to Cart</h2>
<?php
foreach ($items as $item_id => $item) {
    echo "<p><a href='?action=add_to_cart&item_id=" . $item_id . "&quantity=1'>Add " . $item['name'] . " to Cart</a></p>";
}
?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Function to connect to the database
function connectDB() {
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// ---------------------  Cart Functionality ---------------------

// Add item to cart
function addToCart($product_id, $quantity) {
  global $conn;

  // Check if the product exists
  $stmt = $conn->prepare("SELECT id, product_name, price FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $product_name = $row['product_name'];
    $product_id = $row['id'];
    $price = $row['price'];

    // Check if the cart already exists for this user
    $cart_id = session_id();

    // Prepare the cart query
    $query = "SELECT id FROM cart WHERE cart_id = ? AND product_id = ?";

    // Check if the product is already in the cart
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $cart_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Update quantity if product is already in cart
      $query_update = "UPDATE cart SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?";
      $stmt_update = $conn->prepare($query_update);
      $stmt_update->bind_param("sss", $quantity, $cart_id, $product_id);
      $stmt_update->execute();
    } else {
      // Add product to cart
      $query_insert = "INSERT INTO cart (cart_id, product_id, quantity) VALUES (?, ?, ?)";
      $stmt_insert = $conn->prepare($query_insert);
      $stmt_insert->bind_param("sss", $cart_id, $product_id, $quantity);
      $stmt_insert->execute();
    }

  } else {
    echo "Product not found.";
  }
}

// Remove item from cart
function removeFromCart($product_id) {
  global $conn;

  // Prepare the query
  $query = "DELETE FROM cart WHERE product_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
}

// Get cart contents
function getCartContents() {
  global $conn;

  $query = "SELECT p.product_name, c.quantity, c.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.cart_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", session_id());
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return [];
  }
}

// Update cart quantity
function updateCartQuantity($product_id, $quantity) {
    global $conn;

    // Prepare the query
    $query = "SELECT id FROM cart WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $product_id, session_id());
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the quantity
        $query_update = "UPDATE cart SET quantity = ? WHERE product_id = ? AND cart_id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("iss", $quantity, $product_id, session_id());
        $stmt_update->execute();
    } else {
        echo "Product not found in cart.";
    }
}


// ---------------------  Shopping Cart Functions ---------------------

// Initialize cart (add a default item if it doesn't exist)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $quantity);
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// Handle updating cart quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
}

// Get cart contents for display
$cart_items = getCartContents();


// ---------------------  Checkout (Simple Example) ---------------------

// Total price calculation
$total_price = 0;
if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        $total_price += $item['quantity'] * $item['price'];
    }
}

// ---------------------  Display Cart Contents ---------------------

echo "<h2>Shopping Cart</h2>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Price: $" . $total_price . "</p>";
}

?>


<?php
session_start();

// Database Connection (Replace with your actual database details)
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

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $price = $row['price'];

        // Check if the product is already in the cart
        $cart_key = "cart_" . session_id(); // Unique cart identifier

        if (isset($_SESSION[$cart_key]['items'][$product_id])) {
            // Product already in cart, update the quantity
            $_SESSION[$cart_key]['items'][$product_id]['quantity'] += $quantity;
            $_SESSION[$cart_key]['total_price'] += $price * $quantity;
        } else {
            // Product not in cart, add it
            $_SESSION[$cart_key]['items'][$product_id] = array(
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity
            );
            $_SESSION[$cart_key]['total_price'] = $price * $quantity;
        }
    } else {
        // Product not found, you might want to handle this error differently
        echo "Product with ID " . $product_id . " not found.";
    }

    $stmt->close();
    $conn->close();
}

// Function to get cart contents
function getCartContents() {
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key])) {
        return $_SESSION[$cart_key];
    } else {
        return array(); // Return an empty array if cart is empty
    }
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key]['items'][$product_id])) {
        unset($_SESSION[$cart_key]['items'][$product_id]);
        $_SESSION[$cart_key]['total_price'] -= $_SESSION[$cart_key]['items'][$product_id]['price'] * $_SESSION[$cart_key]['items'][$product_id]['quantity'];
    }
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $quantity) {
  $cart_key = "cart_" . session_id();

  if (isset($_SESSION[$cart_key]['items'][$product_id])) {
    if ($quantity > 0) {
      $_SESSION[$cart_key]['items'][$product_id]['quantity'] = $quantity;
      $_SESSION[$cart_key]['total_price'] = $_SESSION[$cart_key]['items'][$product_id]['price'] * $quantity;
    } else {
      removeItemFromCart($product_id); // If quantity is 0, remove the item
    }
  }
}

// --- Example Usage (handle form submissions) ---

// Add item to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    addItemToCart($product_id, $quantity);
}

// Remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_from_cart"])) {
    $product_id = $_POST["product_id"];
    removeItemFromCart($product_id);
}

// Update quantity
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_quantity"])) {
    $product_id = $_POST["product_id"];
    $new_quantity = $_POST["quantity"];
    updateQuantity($product_id, $new_quantity);
}

// --- Display Cart Contents (Example) ---

$cart = getCartContents();

if (!empty($cart)) {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart['items'] as $id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " ";
        echo "Quantity: " . $item['quantity'];
        echo "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . number_format($cart['total_price'], 2) . "</p>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>


<?php
session_start();

// Initialize the shopping cart (array)
$cart = [];

// Helper functions:
// - add_to_cart($item_id, $name, $price, $quantity)
// - display_cart()
// - update_cart($item_id, $quantity)
// - remove_from_cart($item_id)


// ------------------ Helper Functions ------------------

/**
 * Adds an item to the shopping cart.
 *
 * @param int $item_id The ID of the item to add.
 * @param string $name The name of the item.
 * @param float $price The price of the item.
 * @param int $quantity The quantity of the item to add.
 */
function add_to_cart($item_id, $name, $price, $quantity) {
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    $cart[$item_id] = [
      'name' => $name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

/**
 * Displays the contents of the shopping cart.
 */
function display_cart() {
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $item_id => $item_details) {
    echo "<li>";
    echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " x " . $item_details['quantity'] . " = $" . ($item_details['price'] * $item_details['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate total
  $total = 0;
  foreach ($cart as $item_id => $item_details) {
    $total += ($item_details['price'] * $item_details['quantity']);
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

/**
 * Updates the quantity of an item in the shopping cart.
 *
 * @param int $item_id The ID of the item to update.
 * @param int $quantity The new quantity of the item.
 */
function update_cart($item_id, $quantity) {
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $quantity;
  }
}

/**
 * Removes an item from the shopping cart.
 *
 * @param int $item_id The ID of the item to remove.
 */
function remove_from_cart($item_id) {
  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  }
}


// ------------------ Main Script Logic ------------------

// Handle adding items to the cart
if (isset($_POST['add_to_cart'])) {
  $item_id = (int)$_POST['item_id'];
  $name = $_POST['name'];
  $price = (float)$_POST['price'];
  $quantity = (int)$_POST['quantity'];

  add_to_cart($item_id, $name, $price, $quantity);
}

// Handle updating item quantities
if (isset($_POST['update_cart'])) {
  $item_id = (int)$_POST['item_id'];
  $quantity = (int)$_POST['quantity'];
  update_cart($item_id, $quantity);
}

// Handle removing items from the cart
if (isset($_POST['remove_from_cart'])) {
  $item_id = (int)$_POST['item_id'];
  remove_from_cart($item_id);
}

// Display the shopping cart
display_cart();

// Example Product Data (You would typically get this from a database)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200.00],
  2 => ['name' => 'Mouse', 'price' => 25.00],
  3 => ['name' => 'Keyboard', 'price' => 75.00],
];

// Display product listing (for adding to cart)
echo "<h2>Available Products</h2>";
echo "<ul>";
foreach ($products as $id => $product) {
  echo "<li>";
  echo "<strong>" . $product['name'] . "</strong> - $" . $product['price'] . "<br>";
  echo "<form method='post'>";
  echo "<input type='hidden' name='item_id' value='" . $id . "'>";
  echo "<label for='quantity'>" . $product['name'] . " Quantity:</label>";
  echo "<input type='number' id='quantity' name='quantity' value='1' min='1'>";
  echo "<input type='submit' value='Add to Cart'>";
  echo "</form>";
  echo "</li>";
}
echo "</ul>";

?>


<?php
session_start();

// Configuration
$product_data = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'stock' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'stock' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'stock' => 20],
];

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart total
function getCartTotal()
{
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            if (isset($product_data[$product_id])) {
                $total_price = $product_data[$product_id]['price'] * $quantity;
                $total += $total_price;
            }
        }
    }
    return $total;
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Cast to integer
    $quantity = (int)$_POST['quantity']; // Cast to integer

    if (isset($product_data[$product_id]) && $quantity > 0) {
        addToCart($product_id, $quantity);
    }
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
}

// Display the cart
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .cart-item.removed {
            border: 1px solid red;
            padding: 10px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<div class='cart-items'>";
    $cart_total = getCartTotal();
    echo "<strong>Total: $" . number_format($cart_total, 2) . "</strong>";
    echo "</div>";

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($product_data[$product_id])) {
            echo "<div class='cart-item'>";
            echo "<h3>" . $product_data[$product_id]['name'] . "</h3>";
            echo "<p>Quantity: " . $quantity . "</p>";
            echo "<p>Price: $" . number_format($product_data[$product_id]['price'], 2) . "</p>";
            echo "<form method='post'>";
            echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
            echo "<input type='number' id='quantity_" . $product_id . "' value='" . $quantity . "' min='1' max='" . $product_data[$product_id]['stock'] . "' name='quantity_" . $product_id . "'>";
            echo "<button type='submit' name='update_cart'>" . (empty($product_data[$product_id]['stock']) ? "Remove" : "Update") . "</button>";
            echo "</form>";
            echo "<br>";
        }
    }
    echo "</div>";
}
?>

<br>
<a href="index.php">Back to Products</a>

</body>
</html>


<?php
session_start();

// Configuration (same as cart.php)
$product_data = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'stock' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'stock' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'stock' => 20],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>

<h1>Products</h1>

<?php
foreach ($product_data as $product_id => $product) {
    echo "<h2>" . $product['name'] . " - $" . number_format($product['price'], 2) . "</h2>";
    echo "<p>Stock: " . $product['stock'] . "</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='1' min='1' max='" . $product_data[$product_id]['stock'] . "' >";
    echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
    echo "</form>";
    echo "<br>";
}
?>

<br>
<a href="cart.php">View Cart</a>

</body>
</html>


<?php
session_start();

// Cart data (simplified - for demonstration)
$cart = [];

// Helper functions
function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
  if (!isset($cart[$item_id])) {
    $cart[$item_id] = ['name' => $item_name, 'price' => $price, 'quantity' => $quantity];
  } else {
    $cart[$item_id]['quantity'] += $quantity;
  }
}

function get_cart_total($cart) {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

function display_cart($cart) {
  echo "<h2>Shopping Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>";
    echo "Item: " . $item['name'] . "<br>";
    echo "Price: $" . number_format($item['price'], 2) . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Total for item: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
    echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . number_format(get_cart_total($cart), 2) . "</strong></p>";
}

// ---  Example items (replace with database access in a real application) ---
$items = [
  ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];

// ---  Handling Updates (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $item_id = $_POST['item_id'];
  $quantity = $_POST['quantity'];

  if (isset($items[$item_id])) {
    $items[$item_id]['quantity'] = $quantity; // Update quantity in the item array
    // Optional:  You could also update the cart array if you are storing it in a session.
  } else {
    echo "<p>Item not found.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Display the Cart -->
  <?php display_cart($cart); ?>

  <!-- Add to Cart Buttons (for demonstration -  you'd typically use a form) -->
  <?php
  echo "<h2>Add to Cart</h2>";
  foreach ($items as $item) {
    echo "<p><a href='?add=" . $item['id'] . "&name=" . urlencode($item['name']) . "&price=" . $item['price'] . "'>Add " . $item['name'] . " to Cart</a></p>";
  }
  ?>

</body>
</html>


<?php
session_start();

// Cart data (can be stored in a database for a real-time application)
$cart = [];

// Helper functions
function addItemToCart($productId, $productName, $price, $quantity = 1) {
    if (!isset($cart[$productId])) {
        $cart[$productId] = [
            'name' => $productName,
            'price' => $price,
            'quantity' => $quantity
        ];
    } else {
        $cart[$productId]['quantity'] += $quantity;
    }
}

function removeItemFromCart($productId) {
    if (isset($cart[$productId])) {
        unset($cart[$productId]);
    }
}

function updateQuantity($productId, $quantity) {
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $quantity;
    }
}

function getCartTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// --- Cart Handling Functions (Called by the frontend) ---

// Add to cart (example usage:  'add_to_cart' form submission)
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['product_price'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default quantity is 1

    addItemToCart($productId, $productName, $price, $quantity);
    // Redirect to cart page or display success message
    echo '<script>alert("Product added to cart!");</script>';
}



// Remove from cart (example: 'remove_from_cart' form submission)
if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    removeItemFromCart($productId);
    echo '<script>alert("Product removed from cart!");</script>';
}

// Update quantity (example: 'update_quantity' form submission)
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];
    updateQuantity($productId, $newQuantity);
    echo '<script>alert("Quantity updated!");</script>';
}



// Display Cart (example:  'view_cart' form submission)
if (isset($_GET['action']) && $_GET['action'] === 'view_cart') {
    echo '<h2>Your Shopping Cart</h2>';

    if (empty($cart)) {
        echo '<p>Your cart is empty.</p>';
    } else {
        echo '<ul>';
        foreach ($cart as $item) {
            echo '<li>' . $item['name'] . ' - $' . number_format($item['price'], 2) . ' x ' . $item['quantity'] . ' = $' . number_format($item['price'] * $item['quantity'], 2) . '</li>';
        }
        echo '</ul>';
        echo '<p><strong>Total: $' . number_format(getCartTotal(), 2) . '</strong></p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Products</h1>

<!-- Example products (replace with your actual product list) -->
<ul>
    <li>
        <form method="post" action="">
            <input type="hidden" name="product_id" value="1">
            <label for="product_name">Product 1</label>
            <input type="text" name="product_name" value="Product 1" readonly>
            <label for="product_price">Price: $10.00</label>
            <input type="number" name="quantity" value="1" min="1" max="10">
            <input type="submit" name="add_to_cart" value="Add to Cart">
        </form>
    </li>
    <li>
        <form method="post" action="">
            <input type="hidden" name="product_id" value="2">
            <label for="product_name">Product 2</label>
            <input type="text" name="product_name" value="Product 2" readonly>
            <label for="product_price">Price: $20.00</label>
            <input type="number" name="quantity" value="1" min="1" max="10">
            <input type="submit" name="add_to_cart" value="Add to Cart">
        </form>
    </li>
</ul>

<br>

<a href="?action=view_cart">View Cart</a>
</body>
</html>


<?php
session_start();

// Database connection (Replace with your actual database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['quantity' => $quantity];
        }
    } else {
        $cart[$product_id] = ['quantity' => $quantity];
    }

    $_SESSION['cart'] = json_encode($cart);
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);
        }
        $_SESSION['cart'] = json_encode($cart);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] = $quantity;
        }
        $_SESSION['cart'] = json_encode($cart);
    }
}

// Function to display the cart contents
function displayCart($conn) {
    if (!isset($_SESSION['cart'])) {
        echo "<h2>Your cart is empty.</h2>";
        return;
    }

    $cart = json_decode($_SESSION['cart'], true);
    $total_price = 0;

    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";

    foreach ($cart as $product_id => $item) {
        // Assuming you have a 'products' table with product_id and price
        $product_query = "SELECT product_name, price FROM products WHERE product_id = " . $product_id;
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product_name = $product_result->fetch_assoc()['product_name'];
            $price = $product_result->fetch_assoc()['price'];
            $item_total = $price * $item['quantity'];
            $total_price += $item_total;

            echo "<tr>";
            echo "<td>" . $product_name . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . number_format($price, 2) . "</td>";
            echo "<td>$" . number_format($item_total, 2) . "</td>";
            echo "<td><a href='update_cart.php?product_id=" . $product_id . "&quantity=" . $item['quantity'] . "'>Update</a> | <a href='remove_from_cart.php?product_id=" . $product_id . "'>Remove</a></td>";
            echo "</tr>";
        } else {
            echo "<tr><td>Error: Product not found</td><td></td><td></td><td></td><td></td></tr>";
        }
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format($total_price, 2) . "</strong></p>";
}

// ------  Handling Cart Actions  ------

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($conn, $product_id, $quantity);
    header("Location: cart.php"); // Redirect to cart page
    exit();
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateQuantity($conn, $product_id, $quantity);
    header("Location: cart.php");
    exit();
}

// Remove from Cart
if (isset($_GET['product_id'])) {
    removeFromCart($conn, $_GET['product_id']);
    header("Location: cart.php");
    exit();
}


// ------  Display Cart  ------
displayCart($conn);

// ------  Close Connection  ------
$conn->close();
?>


<?php
session_start();

// Database Connection (Replace with your actual database credentials)
$dbHost = "localhost";
$dbName = "shopping_cart";
$dbUser = "root";
$dbPass = "";

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Function to get all items in the cart
function getCartItems($conn) {
    $cart_items = array();
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $item) {
            // Fetch product details from the database
            $product_query = "SELECT id, name, price FROM products WHERE id = $product_id";
            $product_result = $conn->query($product_query);

            if ($product_result->num_rows > 0) {
                $product = $product_result->fetch_assoc();
                $cart_items[] = array(
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $item['quantity']
                );
            }
        }
    }
    return $cart_items;
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// ---  Shopping Cart Operations  ---

// 1. Add to Cart (Handle POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($conn, $product_id, $quantity);
}

// 2. Get Cart Items (Display the cart content)
$cart_items = getCartItems($conn);

// 3. Remove Item from Cart (Handle DELETE request)
if ($_SERVER["REQUEST_METHOD"] == "DELETE" && isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    removeFromCart($conn, $product_id);
}

// 4. Update Quantity (Handle PUT request)
if ($_SERVER["REQUEST_METHOD"] == "PUT" && isset($_GET['update'])) {
    $product_id = $_GET['update'];
    $quantity = $_GET['quantity'];
    updateQuantity($conn, $product_id, $quantity);
}


// --- Product Data (Simulated for demonstration) ---
// In a real application, this would come from your database.
$products = array(
    1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
    2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
    3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75),
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>

    <table id="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($cart_items as $item) {
                $product = $products[$item['id']];
                $item_total = $item['quantity'] * $product['price'];
                $total += $item_total;
                ?>
                <tr class="cart-item">
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item_total; ?></td>
                    <td>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                        <form action="cart.php" method="PUT">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                            <button type="submit">Update Quantity</button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <div class="cart-total">
        Total: <?php echo $total; ?>
    </div>
<?php
}
?>

<br>

<a href="checkout.php">Checkout</a>

</body>
</html>


<?php
session_start();

// Define cart data
$cart = [];

// --- Helper Functions ---

// Function to add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    $cart[$item_id] = [
      'name' => $item_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($item_id, $quantity) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $quantity;
  } else {
    // Item not found - handle appropriately (e.g., display error)
    echo "Error: Item with ID $item_id not found in cart.";
  }
}

// Function to remove an item from the cart
function removeFromCart($item_id) {
  global $cart;

  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  } else {
    // Item not found - handle appropriately (e.g., display error)
    echo "Error: Item with ID $item_id not found in cart.";
  }
}

// Function to calculate the total cart value
function calculateTotal() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Cart Actions (handled by form submissions) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $item_id = $_POST['item_id'];
  $item_name = $_POST['item_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addToCart($item_id, $item_name, $price, $quantity);
  // Optionally, redirect to the cart page
  header("Location: cart.php"); // Redirects to the cart.php page
  exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
  $item_id = $_POST['item_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($item_id, $new_quantity);
  header("Location: cart.php");
  exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $item_id = $_POST['item_id'];
  removeFromCart($item_id);
  header("Location: cart.php");
  exit();
}

// --- Display Cart Content (cart.php) ---

// Example cart.php content
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
    .cart-total {
      font-weight: bold;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  $cart_total = calculateTotal();
  ?>

  <?php
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<h2>Cart Items:</h2>";
    foreach ($cart as $item) {
      echo "<div class='cart-item'>";
      echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " ";
      echo "Quantity: " . $item['quantity'];
      echo "<form method='post' action='cart.php'>";
      echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
      echo "<input type='submit' value='Update Quantity'>";
      echo "</form>";
      echo "<form method='post' action='cart.php'>";
      echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
      echo "<input type='submit' value='Remove from Cart'>";
      echo "</form>";
      echo "</div>";
    }
    echo "<div class='cart-total'>Total: $" . $cart_total . "</div>";
  }
  ?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// -------------------- Product Handling --------------------

// Function to get product details by ID
function getProductDetails($productId) {
    $conn = connectToDatabase();
    $result = $conn->query("SELECT id, name, description, price, image FROM products WHERE id = '$productId'");

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        return $product;
    } else {
        return null;
    }
}


// -------------------- Cart Functions --------------------

// Add item to cart
function addToCart($productId, $quantity) {
    $conn = connectToDatabase();

    // Check if the product exists
    $product = getProductDetails($productId);
    if (!$product) {
        return false; // Product not found
    }

    $itemName = $product['name'];
    $itemPrice = $product['price'];

    if (isset($_SESSION['cart'][$productId])) {
        // Product already in cart, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$productId] = array(
            'quantity' => $quantity,
            'name' => $itemName,
            'price' => $itemPrice
        );
    }

    return true;
}

// Remove item from cart
function removeItemFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    return true;
}

// Update quantity of item in cart
function updateQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
    return true;
}

// Get cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Calculate total cart value
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// -------------------- Displaying the Cart --------------------

// Display the cart contents
function displayCart() {
    $cartContents = getCartContents();

    echo "<div class='cart-container'>";
    echo "<h2>Shopping Cart</h2>";

    if (empty($cartContents)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cartContents as $productId => $item) {
            $product = getProductDetails($productId);
            echo "<li>";
            echo "<img src='" . $product['image'] . "' alt='" . $product['name'] . "' width='100'>";
            echo "<h3>" . $product['name'] . "</h3>";
            echo "<p>Price: $" . $product['price'] . "</p>";
            echo "<form method='post'>";
            echo "<label for='quantity'>" . $product['name'] . " Quantity:</label>";
            echo "<input type='number' id='quantity' name='quantity' value='" . $item['quantity'] . "' min='1'>";
            echo "<button type='submit' name='update_cart'>Update Cart</button>";
            echo "</form>";

            echo "<form method='post'>";
            echo "<button type='submit' name='remove_item_" . $productId . "'>Remove from Cart</button>";
            echo "</form>";
            echo "<br>";
        }
        echo "</ul>";
        echo "<p>Total: $" . calculateTotal() . "</p>";
    }

    echo "</div>";
}


// -------------------- Handling Cart Updates --------------------

// Update cart when the "Update Cart" button is clicked
if (isset($_POST['update_cart'])) {
    $productId = isset($_POST['update_cart']) ? $_POST['update_cart'] : ""; // get product id from form
    if (!empty($productId)) {
        addToCart($productId, 1); // Update quantity by 1 for simplicity
    }
}

// Remove item from cart when "Remove from Cart" button is clicked
if (isset($_POST['remove_item_' . $productId])) {
    removeItemFromCart($productId);
}



// -------------------- Displaying the Cart (Simplified for demonstration) --------------------
displayCart();


// -------------------- Example Product Data (for demonstration) --------------------
$products = array(
    1 => array('id' => 1, 'name' => 'T-Shirt', 'description' => 'A comfortable cotton t-shirt', 'price' => 20, 'image' => 'tshirt.jpg'),
    2 => array('id' => 2, 'name' => 'Jeans', 'description' => 'Classic blue jeans', 'price' => 50, 'image' => 'jeans.jpg'),
    3 => array('id' => 3, 'name' => 'Hat', 'description' => 'Warm wool hat', 'price' => 30, 'image' => 'hat.jpg')
);

?>


<?php
session_start();

// Database connection (replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart variables (initialize if not already set)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 */
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

/**
 * Get the total quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product.
 * @return int The quantity of the product in the cart, or 0 if not found.
 */
function getCartItemQuantity($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return $_SESSION['cart'][$product_id];
    } else {
        return 0;
    }
}

/**
 * Remove an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


/**
 * Calculate the total number of items in the cart.
 *
 * @return int The total number of items in the cart.
 */
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $total = $total + getCartItemQuantity($product_id) * getProductPrice($product_id); // Assuming product price is available
    }
    return $total;
}

/**
 * Get the price of a product
 *
 * @param int $product_id The ID of the product
 * @return int The price of the product
 */
function getProductPrice($product_id) {
    //  Replace with your logic to retrieve product prices from the database.
    // This is just an example.  It's crucial to replace with your actual product data retrieval.
    //  You could use a database query here.
    if ($product_id == 1) {
        return 10;
    } elseif ($product_id == 2) {
        return 20;
    } else {
        return 0; // Default price or handle error appropriately
    }
}


// ---  Cart Actions based on HTTP Methods ---

// 1. GET - Display the cart contents
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Output the cart contents
    echo "<h1>Shopping Cart</h1>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product_name = getProductName($product_id); // Assumes a function to get product name
            $total_price = getCartItemQuantity($product_id) * getProductPrice($product_id); //Calculates total price
            echo "<li>Product: " . $product_name . ", Quantity: " . $quantity . ", Total: $" . $total_price . "</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total: $" . getCartTotal() . "</strong></p>";
        echo "<a href='cart.php?action=empty'>Clear Cart</a>"; // Link to empty cart
    }
}

// 2. POST - Add an item to the cart
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        addToCart($product_id, $quantity);
        // Redirect back to the cart page
        header("Location: cart.php");
        exit();
    }
}


// 3.  POST - Remove item from cart
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_product_id'])) {
    $product_id = intval($_POST['remove_product_id']);
    removeFromCart($product_id);
    header("Location: cart.php");
    exit();
}



?>


<?php
session_start();  // Start the session to store cart data

// Check if 'cart' session variable exists. If not, initialize it.
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// If the user adds an item to the cart:
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id']; // Ensure it's an integer
  $quantity = (int)$_POST['quantity'];

  // Find the product
  $product = null;
  foreach ($products as $p) {
    if ($p['id'] == $product_id) {
      $product = $p;
      break;
    }
  }

  if ($product) {
    // Add to cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
      ];
    }
  }
}


//If the user removes an item from cart
if (isset($_POST['remove_item'])) {
    $item_id = (int)$_POST['item_id'];

    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


// Display the cart
echo '<h2>Your Shopping Cart</h2>';
if (empty($_SESSION['cart'])) {
  echo '<p>Your cart is empty.</p>';
} else {
  echo '<ul>';
  foreach ($_SESSION['cart'] as $item_id => $cart_item) {
    echo '<li>' . $cart_item['name'] . ' - $' . $cart_item['price'] . ' x ' . $cart_item['quantity'] . ' = $' . ($cart_item['price'] * $cart_item['quantity']) . '</li>';
  }
  echo '</ul>';

  // Calculate total
  $total = 0;
  foreach ($_SESSION['cart'] as $item_id => $cart_item) {
    $total += ($cart_item['price'] * $cart_item['quantity']);
  }
  echo '<p>Total: $' . $total . '</p>';
}
?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
  global $session;

  if (isset($session['cart'][$product_id])) {
    $session['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $session['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($session['cart'][$product_id])) {
        $session['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($session['cart'][$product_id])) {
    unset($session['cart'][$product_id]);
  }
}

// Function to calculate the total cart value
function calculate_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['quantity'] * $item['price']; // Assuming 'price' is in each item
  }
  return $total;
}


// Example usage (This part would be handled by the add_to_cart.php or view_cart.php)
if (isset($_GET['action']) && $_GET['action'] == 'update') {
  $product_id = $_GET['product_id'];
  $quantity = $_GET['quantity'];
  update_cart_quantity($product_id, $quantity);
}

if (isset($_GET['action']) && $_GET['action'] == 'remove') {
  $product_id = $_GET['product_id'];
  remove_from_cart($product_id);
}

?>


<?php
session_start();

// You would fetch product details (ID, name, price) from a database or other source.
// For this example, we'll assume the product_id is passed as a GET parameter.

$product_id = $_GET['product_id'];
$quantity = $_GET['quantity'];

// Validate the quantity (e.g., ensure it's a positive integer)
if (is_numeric($quantity) && $quantity > 0) {
    add_to_cart($product_id, $quantity);
} else {
    // Handle invalid quantity (e.g., display an error message)
    echo "Invalid quantity.  Please enter a positive integer.";
}
?>


<?php
session_start();

$total = calculate_total();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart</h1>

    <?php if (empty($_SESSION['cart'])) { ?>
        <p>Your cart is empty.</p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    echo "<tr>";
                    echo "<td>" . $item['name'] . "</td>"; // Assume 'name' is stored with the item
                    echo "<td>" . $item['quantity'] . "</td>";
                    echo "<td>$" . $item['price'] . "</td>";
                    echo "<td>$" . ($item['quantity'] * $item['price']) . "</td>";
                    echo "<td><a href='add_to_cart.php?product_id=" . $product_id . "&quantity=1'>Add</a> | <a href='add_to_cart.php?product_id=" . $product_id . "&quantity=1'>Update</a> | <a href='add_to_cart.php?product_id=" . $product_id . "&quantity=1'>Remove</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <p><strong>Total:</strong> $" . $total . "</p>
    <?php } ?>
</body>
</html>


    <?php
    $products = array(
        1 => array('id' => 1, 'name' => 'Shirt', 'price' => 20),
        2 => array('id' => 2, 'name' => 'Pants', 'price' => 30),
        3 => array('id' => 3, 'name' => 'Shoes', 'price' => 50)
    );
    ?>
    

3.  **Modify `view_cart.php` to Fetch Product Details:**  In `view_cart.php`, you need to fetch the product details based on the `$product_id` (which is passed from `add_to_cart.php` and stored in `$_SESSION['cart']`).  Example:

    
    <?php
    session_start();

    $total = calculate_total();

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product_name = 'Product ' . $product_id;  // Example:  Replace with your data source
        echo "<td>" . $product_name . "</td>";
        // ... rest of the table rows
    }
    ?>
    

4.  **Create a Link to `view_cart.php`:** Add a link on your main page (e.g., a button that says "View Cart") that points to `view_cart.php`.  You'll need to pass the product ID to `view_cart.php` so that the correct product information is displayed.

**Example Main Page (index.php):**



<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Define cart items
$cart = [];

// Function to add item to cart
function addToCart($product_id, $product_name, $price, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Exit function if product already exists
        }
    }

    // If product not in cart, add it
    $cart[] = [
        'product_id' => $product_id,
        'product_name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    ];
}

// Function to update cart item quantity
function updateCartQuantity($product_id, $new_quantity) {
  global $cart;

  foreach ($cart as &$item) {
    if ($item['product_id'] == $product_id) {
      $item['quantity'] = $new_quantity;
      return;
    }
  }
  // Product not found, you might want to handle this differently
  // e.g., display an error message
  echo "Product ID " . $product_id . " not found in cart.";
}


// Function to remove item from cart
function removeCartItem($product_id) {
    global $cart;

    // Iterate through the cart and remove the item
    $new_cart = [];
    foreach ($cart as $item) {
        if ($item['product_id'] != $product_id) {
            $new_cart[] = $item;
        }
    }
    $cart = $new_cart; // Update the cart array
}

// Function to get cart contents
function getCartContents() {
    return $cart;
}

// Function to calculate total cart value
function calculateTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// ---  Example Usage (Simulated Product Data - Replace with your database query) ---
$products = [
    1 => ['product_id' => 1, 'product_name' => 'Laptop', 'price' => 1200],
    2 => ['product_id' => 2, 'product_name' => 'Mouse', 'price' => 25],
    3 => ['product_id' => 3, 'product_name' => 'Keyboard', 'price' => 75]
];

// ---  Handle Add to Cart Request (Simulated) ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    addToCart($product_id, $product_name, $price, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

//--- Handle Remove Cart Item Request (Simulated)
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($product_id);
    header("Location: cart.php");
    exit();
}

//--- Handle Update Quantity Request (Simulated)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateCartQuantity($product_id, $new_quantity);
    header("Location: cart.php");
    exit();
}

// --- Display Cart Contents (cart.php) ---
// (This is a basic example, you'll need to implement more complex cart display logic)
if (count($cart) > 0) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";
    echo "<form method='post' action='cart.php'>
                <input type='hidden' name='product_id' value='" . implode( "','" , array_map(function($item) {return $item['product_id'];}, $cart)) . "'>
                <input type='submit' value='Remove Item'>
              </form>";

} else {
    echo "<p>Your cart is empty.</p>";
}


?>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the cart is empty, initialize it if necessary
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

/**
 * Remove an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Get the cart contents.
 *
 * @return array The cart contents as an array.
 */
function getCart() {
  return $_SESSION['cart'];
}

/**
 * Calculate the cart total.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // You'll need to fetch product prices from your database here
    // For demonstration purposes, let's assume product prices are hardcoded
    $product_price = getProductPrice($product_id); // Implement this function
    $total_for_product = $product_price * $quantity;
    $total = $total + $total_for_product;
  }
  return $total;
}

/**
 *  Placeholder function to get product price from DB.  Replace with your actual DB query.
 * @param int $product_id
 * @return float The product price
 */
function getProductPrice($product_id) {
    //  Replace this with your database query to fetch the price based on product_id
    // Example using a dummy hardcoded price:
    switch ($product_id) {
        case 1: return 10.00;
        case 2: return 25.50;
        case 3: return 5.00;
        default: return 0.00; // Or throw an error if the product doesn't exist
    }
}


// --- Handling Add to Cart Request ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
    // Optionally, redirect back to the product page or cart page.
    // header("Location: product.php");
}

// --- Handling Remove from Cart Request ---

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
    // Optionally, redirect back to the product page or cart page.
    // header("Location: product.php");
}


// --- Displaying the Cart ---

// Get the cart contents
$cart_contents = getCart();

// Calculate the cart total
$total = calculateTotal();

// Start the HTML output
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_contents)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table class="cart-items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cart_contents as $product_id => $quantity) {
                $product_price = getProductPrice($product_id);
                $total_for_product = $product_price * $quantity;
                ?>
                <tr class="cart-item">
                    <td><?php echo $product_id; ?></td> <!--  Replace with product name based on $product_id -->
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo $product_price; ?></td>
                    <td><?php echo $total_for_product; ?></td>
                    <td>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="action" value="remove_from_cart">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <p><strong>Total:</strong> <?php echo $total; ?></p>
<?php endif; ?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


// Cart functions

function initializeCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function addItemToCart($productId, $quantity) {
    initializeCart();

    // Check if the product is already in the cart
    $product_id = $productId;
    $item = array_search($product_id, $_SESSION['cart'], true);

    if ($item !== false) {
        // Product exists, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'quantity' => $quantity,
            'price' => getProductPrice($product_id)  // Get the price
        );
    }
}


function getCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

function removeItemFromCart($productId) {
    initializeCart();
    unset($_SESSION['cart'][$productId]);
}

function updateQuantity($productId, $quantity) {
    initializeCart();
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}


function getCartContents() {
    return $_SESSION['cart'];
}

function clearCart() {
    unset($_SESSION['cart']);
}

// Helper function to get product details from the database (replace with your database query)
function getProductPrice($productId) {
  // Example using a simple array for demonstration
  $products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
  ];

  if (isset($products[$productId])) {
    return $products[$productId]['price'];
  } else {
    return 0; // Or handle the error appropriately
  }
}

// Example usage (demonstration)
// You would typically get this information from a form submission.
// For example:
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addItemToCart($productId, $quantity);
}


if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    removeItemFromCart($productId);
}

if (isset($_POST['update_quantity'])) {
  $productId = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateQuantity($productId, $quantity);
}


// Display the cart contents
$cartContents = getCartContents();

if (!empty($cartContents)) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";

    $cartTotal = getCartTotal($cartContents);

    foreach ($cartContents as $item) {
        $product_id = $item['id'];
        $product_name = getProductName($product_id);
        $price = $item['price'];
        $total = $item['quantity'] * $price;
        echo "<tr>";
        echo "<td>$product_id</td>";
        echo "<td>$product_name</td>";
        echo "<td>$item['quantity']</td>";
        echo "<td>$price</td>";
        echo "<td>$total</td>";
        echo "</tr>";
    }

    echo "<tr><td colspan='4'><strong>Total: $" . $cartTotal . "</strong></td></tr>";

    echo "</table>";

    echo "<br>";
    echo "<a href='checkout.php'>Proceed to Checkout</a>"; // Link to your checkout page
} else {
    echo "<p>Your cart is empty.</p>";
}

// Example: Clear Cart Button
echo "<br>";
echo "<form method='post'>
        <button type='submit' name='clear_cart'>Clear Cart</button>
      </form>";

?>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Initialize the cart (if not already initialized)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param float $price The price of the product.
 * @param int $quantity The quantity to add.
 */
function addToCart($product_id, $name, $price, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 */
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


/**
 * Returns the cart contents.
 */
function getCartContents() {
    return $_SESSION['cart'];
}

// --- Handle Form Submission (Add to Cart) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        addToCart($product_id, $name, $price, $quantity);
        // Redirect to the cart page
        header("Location: cart.php");
        exit();
    }

    // Handle quantity updates (if any)
    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = $_POST['quantity'];
        updateQuantity($product_id, $new_quantity);
        header("Location: cart.php");
        exit();
    }

    // Handle remove from cart
    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        removeFromCart($product_id);
        header("Location: cart.php");
        exit();
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /* Basic styling - improve this! */
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; padding: 10px; margin: 10px; }
        .cart-total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
// Display cart contents
$cart_contents = getCartContents();

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<h2>Cart Items</h2>";
    foreach ($cart_contents as $product_id => $item) {
        echo '<div class="cart-item">
                <strong>Product Name:</strong> ' . $item['name'] . '<br>
                <strong>Price:</strong> $' . $item['price'] . '<br>
                <strong>Quantity:</strong> ' . $item['quantity'] . '<br>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="' . $product_id . '">
                    <input type="submit" value="Update Quantity">
                </form>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="' . $product_id . '">
                    <input type="submit" value="Remove from Cart">
                </form>
            </div>';
    }

    // Calculate and display the total
    $total = calculateTotal();
    echo '<div class="cart-total"><strong>Total:</strong> $' . $total . '</div>';
}
?>

<hr>

<h2>Add to Cart</h2>
<form method="post" action="cart.php">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="price">Price:</label>
    <input type="number" step="0.01" id="price" name="price" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <input type="submit" value="Add to Cart">
</form>

</body>
</html>


<?php
session_start();

// (Include your database connection code here - same as cart.php)

// (Include your helper functions - addToCart, updateQuantity, removeFromCart, calculateTotal, getCartContents)

// Display cart contents - identical to the main cart.php page
?>


<?php
session_start();

// Database Connection (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add to cart
function addToCart($product_id, $quantity) {
    global $db;

    // Get product details
    $product_query = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $db->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Update the quantity if the product is already in the cart
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product_name,
                'quantity' => $quantity,
                'price' => $product_price
            ];
        }
    } else {
        // Product not found
        return false;
    }
    return true;
}

// Function to remove from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    return true;
}

// Function to update quantity in cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
  return true;
}

// Function to get cart total
function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

// --- Example Usage (Product Listing -  Replace with your product data source) ---
$db = connectToDatabase();


// Dummy Product Data (Replace with your database query)
$products = [
    [ 'id' => 1, 'name' => 'Laptop', 'price' => 1200 ],
    [ 'id' => 2, 'name' => 'Mouse', 'price' => 25 ],
    [ 'id' => 3, 'name' => 'Keyboard', 'price' => 75 ]
];

// Function to display product listing (for demonstration)
function displayProductList($products) {
    echo "<h2>Available Products</h2>";
    echo "<ul>";
    foreach ($products as $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "</li>";
    }
    echo "</ul>";
}

// Display product list
displayProductList($products);

// --- Cart Display and Form ---
echo "<h2>Shopping Cart</h2>";

// Display Cart Items
echo "<ul>";
$cart_total = calculateCartTotal();
foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['quantity'] * $item['price'] . "</li>";
}
echo "</ul>";
echo "<p><strong>Total: $" . $cart_total . "</strong></p>";

// Add to Cart Form
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
echo "<h3>Add to Cart</h3>";
displayProductList($products); // Display the same product list

echo "<ul>";
foreach ($products as $product) {
    echo "<li>";
    echo "<label for='product_" . $product['id'] . "'>" . $product['name'] . ":</label>";
    echo "<input type='number' id='product_" . $product['id'] . "' name='quantity_" . $product['id'] . "' value='1' min='1'>";
    echo "<button type='submit' name='add_" . $product['id'] . "'><img src='add_to_cart.png' width='20'></button>";
    echo "</li>";
}
echo "</ul>";

echo "</form>";


// Handle Add to Cart Submission
if (isset($_POST['add_'])) {
    $product_id = $_POST['add_'];
    $quantity = 1; // Default quantity
    if (isset($_POST['quantity_' . $product_id])) {
        $quantity = intval($_POST['quantity_' . $product_id]); // Ensure it's an integer
    }

    if (addToCart($product_id, $quantity)) {
        echo "<p>Product added to cart.</p>";
    } else {
        echo "<p>Error adding product to cart.</p>";
    }
}

// Handle Remove from Cart (Example - Implement Remove buttons)
// This is just a placeholder. In a real application, you'd have a button to remove.

// Handle Update Quantity
if (isset($_POST['update_'])) {
    $product_id = $_POST['update_'];
    $quantity = intval($_POST['quantity_' . $product_id]);  // Ensure integer
    updateQuantity($product_id, $quantity);
    echo "<p>Quantity updated.</p>";
}


?>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

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
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = [];
    }

    // Check if the product already exists in the cart
    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $cart[$key]['quantity'] += $quantity;
            return;
        }
    }

    // If product not found, add it to the cart
    $cart[] = [
        'product_id' => $product_id,
        'quantity' => $quantity
    ];
}

// Function to get the cart total
function getCartTotal($conn) {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $item) {
            $product_id = $item['product_id'];
            $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, price FROM products WHERE id = $product_id"));
            $total_price = $product_name['price'] * $item['quantity'];
            $total = $total + $total_price;
        }
    }
    return $total;
}

// Function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// Function to get product details by ID
function getProductDetails($conn, $product_id) {
    $result = mysqli_query($conn, "SELECT id, name, price, description FROM products WHERE id = $product_id");
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart button
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart(connectDB(), $product_id, $quantity);
}

// Handle Remove from Cart button (basic implementation)
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    removeFromCart(connectDB(), $product_id);
}

//Remove from cart function
function removeFromCart($conn, $product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart[$key]);
                //remove all keys greater than the current key
                $keys = array_keys($cart);
                $new_cart = array();
                foreach ($keys as $key) {
                    $new_cart[] = $cart[$key];
                }
                $_SESSION['cart'] = $new_cart;
                break;
            }
        }
    }
}

// Display the cart
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $product_name = mysqli_fetch_assoc(mysqli_query(connectDB(), "SELECT name, price FROM products WHERE id = $product_id"));
        $total_price = $product_name['price'] * $item['quantity'];
        echo "<li>" . $product_name['name'] . " - $" . $product_name['price'] . " x " . $item['quantity'] . " = $" . $total_price . "</li>";
        $total = $total + $total_price;
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
    echo "<a href='checkout.php'>Proceed to Checkout</a>";
}
?>


<?php
session_start();

// Database connection (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Cart data (stored in the session)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
  global $db_host, $db_user, $db_password, $db_name;

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it with initial quantity
    $_SESSION['cart'][$product_id] = [
      'id' => $product_id,
      'quantity' => $quantity,
      'name' => 'Product Name (Replace with actual product name)',  //Important: Replace with the real product name.
      'price' => 0  // Replace with the real product price
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
  unset($_SESSION['cart'][$product_id]);
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to display the cart
function displayCart() {
  echo "<h2>Shopping Cart</h2>";
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Name:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Subtotal:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='" . $product_details['quantity'] . "' min='1' max='100' style='width:50px;'>"; //Added min/max for quantity
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total price
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $total += ($product_details['price'] * $product_details['quantity']);
  }
  echo "<p><strong>Total:</strong> $" . $total . "</p>";

  // Checkout button (placeholder)
  echo "<form method='post' action='checkout.php'>";
  echo "<button type='submit'>Checkout</button>";
  echo "</form>";
}

// Handle form submission for adding items to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

    addToCart($conn, $product_id, $quantity);
  }
}

// Handle form submission for removing items from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product_id'])) {
    $product_id = $_POST['remove_product_id'];
    removeFromCart($conn, $product_id);
}


// Display the cart
displayCart();
?>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add a product to the cart
function addToCart($product_id, $quantity) {
  $conn = connectToDatabase();

  // Check if the product exists
  $query = "SELECT * FROM products WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $product_name = $row['name'];
    $product_price = $row['price'];

    // Check if the cart exists for the user
    $cart_id = 'cart_' . session_id(); // Use session ID for cart identification

    // Create the cart entry if it doesn't exist
    $query = "INSERT INTO carts (user_id, product_id, quantity, cart_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isd", null, $product_id, $quantity, $cart_id); //user_id is null since it's for the session
    $stmt->execute();

  } else {
    // Handle product not found (e.g., log an error)
    echo "Product not found in the database.";
  }

  $stmt->close();
  $conn->close();
}

// Function to get the cart contents
function getCartContents() {
  $conn = connectToDatabase();

  // Get the cart ID for the current session
  $cart_id = 'cart_' . session_id();

  $query = "SELECT p.name, p.price, c.quantity FROM carts c JOIN products p ON c.product_id = p.id WHERE c.cart_id = ? ";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $cart_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $cart_items = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $cart_items[] = [
        'id' => $row['product_id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $row['quantity']
      ];
    }
  }

  $stmt->close();
  $conn->close();
  return $cart_items;
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    $conn = connectToDatabase();

    // Get the cart ID for the current session
    $cart_id = 'cart_' . session_id();

    $query = "UPDATE carts SET quantity = ? WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iids", $quantity, $product_id, $cart_id);

    if ($stmt->execute()) {
        // Optionally, you could also implement logic to remove the item if quantity becomes 0.
    } else {
        echo "Error updating cart.";
    }

    $stmt->close();
    $conn->close();
}


// Function to remove an item from the cart
function removeFromCart($product_id, $cart_id) {
    $conn = connectToDatabase();

    $query = "DELETE FROM carts WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $product_id, $cart_id);

    if ($stmt->execute()) {
        // Optionally, you can also delete the cart entry if it's the last item
    } else {
        echo "Error removing item from cart.";
    }

    $stmt->close();
    $conn->close();
}


// Example usage (This would be in your HTML form)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $cart_id = $_POST['cart_id'];
    removeFromCart($product_id, $cart_id);
}


// Display the cart contents (This would be in your HTML)
$cart_items = getCartContents();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else { ?>
    <table>
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item): ?>
          <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>
              <form action="" method="post">
                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="cart_id" value="cart_<?php echo session_id(); ?>">
                <button type="submit" name="update_cart">Update Quantity</button>
              </form>
              <form action="" method="post">
                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="cart_id" value="cart_<?php echo session_id(); ?>">
                <button type="submit" name="remove_from_cart">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p>Total items in cart: <?php echo count($cart_items); ?></p>
  <?php } ?>

  <form action="" method="post">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}


// Cart class
class Cart {
  public $cart = [];

  public function __construct() {
    // Load cart from session on initialization
    if (session_start()) {
      $this->cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    }
  }

  public function addProduct($product_id, $product_name, $price, $quantity = 1) {
    $product_id = $product_id;
    $product_name = $product_name;
    $price = $price;
    $quantity = (int)$quantity; // Convert quantity to integer

    if (!isset($this->cart[$product_id])) {
      $this->cart[$product_id] = [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity,
      ];
    } else {
      $this->cart[$product_id]['quantity'] += $quantity;
    }
    // Ensure quantity doesn't exceed a reasonable limit (optional)
    $this->cart[$product_id]['quantity'] = min($this->cart[$product_id]['quantity'], 100); // Limit to 100
  }

  public function removeProduct($product_id) {
    unset($this->cart[$product_id]);
  }

  public function updateQuantity($product_id, $new_quantity) {
    $new_quantity = (int)$new_quantity; //Convert to integer

    if (isset($this->cart[$product_id])) {
      $this->cart[$product_id]['quantity'] = $new_quantity;
    }
  }


  public function getCartContents() {
    return $this->cart;
  }

  public function getTotal($conn) {
    $total = 0;
    foreach ($this->getCartContents() as $item) {
      $total_item = $item['price'] * $item['quantity'];
      $total += $total_item;
    }
    return $total;
  }

  public function clearCart() {
    unset($_SESSION['cart']);
    session_destroy();
  }
}

// --- Cart operations ---

// Initialize the cart
$cart = new Cart();

// --- Handle Form Submission (Add to Cart) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity']; // Get quantity from form
    $cart->addProduct($product_id, $product_name, $price, $quantity);
    $_SESSION['cart'] = $cart->getCartContents(); // Update session
    // Redirect to cart page
    header("Location: cart.php");
    exit;
  }
}


// --- Display Cart Contents ---
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  // Display Cart Items
  $cart_contents = $cart->getCartContents();
  if (count($cart_contents)) {
    echo "<h2>Cart Items:</h2>";
    foreach ($cart_contents as $item_id => $product) {
      echo '<div class="cart-item">';
      echo 'Product: ' . $product['name'] . '<br>';
      echo 'Price: $' . number_format($product['price'], 2) . '<br>';
      echo 'Quantity: ' . $product['quantity'] . '<br>';
      // Optional: Add a remove button to the cart
      echo '<a href="cart.php?remove=' . $item_id . '" style="color: red; text-decoration: underline;">Remove</a><br>';
      echo '--------------------<br>';
    }

    // Calculate and display total
    $total = $cart->getTotal($conn);
    echo '<h2>Total: $' . number_format($total, 2) . '</h2>';

  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>

  <hr>

  <h2>Add to Cart</h2>
  <?php
    // Example Products (Replace with your actual product data)
    $products = [
        'product1' => ['id' => 'product1', 'name' => 'Laptop', 'price' => 1200],
        'product2' => ['id' => 'product2', 'name' => 'Mouse', 'price' => 25],
        'product3' => ['id' => 'product3', 'name' => 'Keyboard', 'price' => 75]
    ];

    foreach ($products as $id => $product) {
        echo '<form method="post" action="cart.php">';
        echo '<label for="' . $id . '">Product ID:</label> <input type="hidden" id="' . $id . '" name="product_id" value="' . $id . '">';
        echo '<label for="' . $id . '">Product Name:</label> <input type="text" id="' . $id . '" name="product_name" value="' . $product['name'] . '" readonly>';
        echo '<label for="' . $id . '">Price:</label> <input type="text" id="' . $id . '" name="price" value="' . $product['price'] . '" readonly>';
        echo '<label for="' . $id . '">Quantity:</label> <input type="number" id="' . $id . '" name="quantity" value="1" min="1" max="100">';
        echo '<input type="submit" name="add_to_cart" value="Add to Cart">';
        echo '</form><br>';
    }
  ?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to sanitize input (prevent SQL injection)
function sanitizeInput($data) {
  global $conn; // Access the database connection
  return mysqli_real_escape_string($conn, $data);
}

// ---------------------- Cart Functions ----------------------

// Add item to cart
function addToCart($product_id, $quantity) {
  global $conn;

  $product_id = sanitizeInput($product_id);
  $quantity = sanitizeInput($quantity);

  // Check if the product exists in the cart
  $cart_key = "cart_" . session_id();

  if (!isset($_SESSION[$cart_key])) {
    // If not, initialize the cart array
    $_SESSION[$cart_key] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION[$cart_key][$product_id])) {
    // Increment quantity
    $_SESSION[$cart_key][$product_id]['quantity'] += $quantity;
  } else {
    // Add new product to cart
    $_SESSION[$cart_key][$product_id] = [
      'quantity' => $quantity,
      'price' => getProductPrice($product_id) // Get product price
    ];
  }
}

// Get cart total
function getCartTotal($cart_key) {
  $total = 0;
  if (isset($_SESSION[$cart_key])) {
    foreach ($_SESSION[$cart_key] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return round($total, 2);
}

// Remove item from cart
function removeFromCart($product_id) {
    global $conn;
    $product_id = sanitizeInput($product_id);

    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key][$product_id])) {
        unset($_SESSION[$cart_key][$product_id]);
    }
}


// Get cart contents
function getCartContents($cart_key) {
  return $_SESSION[$cart_key] ?? []; // Return empty array if cart doesn't exist
}


// ----------------------  Product Data (For Demo) ----------------------
// Replace this with your actual database query to fetch products

$products = [
  1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
  2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
  3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];


// Helper function to get product price (for demonstration)
function getProductPrice($product_id) {
  global $products;
  return $products[$product_id]['price'];
}

// ----------------------  Shopping Cart Handling ----------------------

// Initialize the shopping cart session
if (!isset($_SESSION["cart_"])){
  session_start(); // start session
}

// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

// Handle removing from cart (e.g., from a form submission)
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// ---------------------- Displaying the Cart ----------------------

// Get cart contents
$cartContents = getCartContents("cart_" . session_id());

$cartTotal = getCartTotal("cart_" . session_id());


?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cartContents)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <table border="1">
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($cartContents as $product_id => $item) {
          ?>
          <tr class="cart-item">
            <td><?php echo $products[$product_id]['name']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td><?php echo $item['price'] * $item['quantity']; ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="submit" name="remove_from_cart" value="Remove" style="background-color: red;">
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <p>Total: <?php echo $cartTotal; ?></p>
  <?php endif; ?>

  <hr>

  <h2>Add Items</h2>
  <form method="post">
    <?php
    foreach ($products as $product_id => $product) {
      ?>
      <div>
        <label for="product_id_<?php echo $product_id; ?>">Product: <?php echo $product['name']; ?></label>
        <input type="hidden" id="product_id_<?php echo $product_id; ?>" name="product_id" value="<?php echo $product_id; ?>">
        <label for="quantity_<?php echo $product_id; ?>">Quantity:</label>
        <input type="number" id="quantity_<?php echo $product_id; ?>" name="quantity" value="1" min="1">
        <input type="submit" name="add_to_cart" value="Add to Cart">
      </div>
    <?php } ?>
  </form>

</body>
</html>


<?php
session_start();

// Database connection (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database";

// Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart data (stored in session)
$cart = array();

// Function to add an item to the cart
function addItemToCart($conn, $userId, $product_id, $name, $price, $quantity) {
  global $cart; // Access the global $cart array

  // Check if the item is already in the cart
  $sql = "SELECT id FROM cart WHERE user_id = '$userId' AND product_id = '$product_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Item already exists, update the quantity
    $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$userId' AND product_id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
      //echo "Quantity updated successfully";
    } else {
      echo "Error updating quantity: " . $conn->error;
    }
  } else {
    // Item not in cart, add a new entry
    $sql = "INSERT INTO cart (user_id, product_id, name, price, quantity)
            VALUES ('$userId', '$product_id', '$name', $price, $quantity)";

    if ($conn->query($sql) === TRUE) {
      //echo "New item added to cart successfully";
    } else {
      echo "Error adding item to cart: " . $conn->error;
    }
  }
}

// Function to get cart items
function getCartItems($conn) {
  global $cart; // Access the global $cart array

  $sql = "SELECT c.cart_id, c.user_id, p.product_id, p.name, p.price, c.quantity
          FROM cart c
          JOIN products p ON c.product_id = p.product_id";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $cart_items = array();
    while($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return array();
  }
}


// Function to remove an item from the cart
function removeItemFromCart($conn, $cart_id) {
  $sql = "DELETE FROM cart WHERE cart_id = '$cart_id'";

  if ($conn->query($sql) === TRUE) {
    //echo "Item removed successfully";
  } else {
    echo "Error removing item from cart: " . $conn->error;
  }
}


// Function to update the quantity of an item in the cart
function updateCartItemQuantity($conn, $cart_id, $quantity) {
  $sql = "UPDATE cart SET quantity = '$quantity' WHERE cart_id = '$cart_id'";

  if ($conn->query($sql) === TRUE) {
    //echo "Quantity updated successfully";
  } else {
    echo "Error updating quantity: " . $conn->error;
  }
}


// --- Purchase Cart Actions (Example - this is just a placeholder) ---

// Add to Cart (Example)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $name = $_POST['name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addItemToCart($conn, 1, $product_id, $name, $price, $quantity); // Assuming user ID 1
  header("Location: ".$_SERVER['PHP_SELF']."?add_to_cart=success");
  exit();
}

// Display Cart Items
$cart_items = getCartItems($conn);

// Get Cart Total
$total = 0;
foreach ($cart_items as $item) {
  $total += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Purchase Cart</title>
</head>
<body>

  <h1>Purchase Cart</h1>

  <?php if (count($cart_items) > 0): ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table>
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart_items as $item): ?>
            <tr>
              <td><?php echo $item['name']; ?></td>
              <td><?php echo $item['price']; ?></td>
              <td>
                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
              </td>
              <td><?php echo $item['price'] * $item['quantity']; ?></td>
              <td>
                <a href="?update_cart=<?php echo $item['cart_id']; ?>">Update</a> |
                <a href="?remove_cart=<?php echo $item['cart_id']; ?>">Remove</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <br>
      <input type="submit" name="submit_cart" value="Checkout" >
    </form>
  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>

  <p>Total: <?php echo $total; ?></p>

  <hr>

  <a href="index.php">Continue Shopping</a>

</body>
</html>


<?php
session_start();

// Database connection details
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';
$db_name = 'your_db_name';

// --- Functions ---

/**
 * Adds an item to the shopping cart
 *
 * @param int $product_id The ID of the product to add
 * @return bool True on success, false on failure
 */
function add_to_cart($product_id) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity
    $_SESSION['cart'][$product_id]['quantity']++;
  } else {
    // Add product to cart
    $_SESSION['cart'][$product_id] = [
      'id' => $product_id,
      'name' => 'Product Name (From Database or Elsewhere)', // Replace with actual product name
      'price' => 10.00, // Replace with actual product price
      'quantity' => 1
    ];
  }
  return true;
}


/**
 * Updates the quantity of an item in the cart
 *
 * @param int $product_id The ID of the product to update
 * @param int $new_quantity The new quantity of the product
 * @return bool True on success, false on failure
 */
function update_cart_quantity($product_id, $new_quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    return false; // Product not in cart
  }

  if ($new_quantity <= 0) {
      // Remove item if quantity is zero or negative
      unset($_SESSION['cart'][$product_id]);
      return true;
  }

  $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  return true;
}


/**
 * Removes an item from the cart
 *
 * @param int $product_id The ID of the product to remove
 * @return bool True on success, false on failure
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    return false;
}


/**
 * Get the cart contents
 *
 * @return array The cart contents
 */
function get_cart() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart is not set
}



// --- Example Usage / Cart Handling (For the Shopping Cart Page) ---

// Check if the 'add_to_cart' action was triggered (e.g., from a POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

  if ($product_id > 0) {
    add_to_cart($product_id);
    // Redirect back to the product page (or the cart page)
    header("Location: product_page.php"); // Replace with your product page URL
    exit();
  } else {
    // Handle invalid product ID
    echo "Invalid product ID.";
  }

}


// Example: Updating cart quantity (Assume this is on a page where you can adjust the quantity)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    $new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    if ($product_id > 0 && $new_quantity > 0) {
        update_cart_quantity($product_id, $new_quantity);
        header("Location: cart.php"); // Redirect to cart page
        exit();
    }
}

// Example: Removing an item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove_item') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    if($product_id > 0) {
        remove_from_cart($product_id);
        header("Location: cart.php");
        exit();
    }
}



// --- Cart Page (cart.php) ---
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  $cart_items = get_cart();

  if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart_items as $item_id => $item) {
      echo "<li>";
      echo "Product: " . $item['name'] . "<br>";
      echo "Quantity: " . $item['quantity'] . "<br>";
      echo "Price: $" . $item['price'] . "<br>";
      // Add an 'Remove' button or link here.  For simplicity, we'll just print a link.
      echo "<a href='cart.php?action=remove_item&product_id=" . $item_id . "'>Remove</a>";
      echo "<br>";
      echo "</li>";
    }
    echo "</ul>";
  }

  // Example: Display total
  $total = 0;
  foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p>Total: $" . $total . "</p>";
  ?>


  <!-- Add a 'Checkout' button here -->
  <a href="checkout.php">Checkout</a>


</body>
</html>


<?php
session_start();

// --- Cart Data ---
$cart = [];

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
    if (!isset($cart[$productId])) {
        $cart[$productId] = [
            'name' => $productName,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity
        ];
    } else {
        $cart[$productId]['quantity'] += $quantity;
        $cart[$productId]['total'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $newQuantity;
        $cart[$productId]['total'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
    }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
    if (isset($cart[$productId])) {
        unset($cart[$productId]);
    }
}

// Function to calculate the total cart value
function calculateCartTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total = $total + $item['total'];
    }
    return $total;
}

// --- Product Data (Simulated Database - Replace with your actual database) ---
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
    4 => ['name' => 'Monitor', 'price' => 300]
];


// --- Handle Cart Actions ---
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addItemToCart($productId, $products[$productId]['name'], $products[$productId]['price'], $quantity);
} elseif (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];
    updateQuantity($productId, $newQuantity);
} elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $productId = $_POST['product_id'];
    removeItemFromCart($productId);
}
// --- Display Cart ---

$cartTotal = calculateCartTotal();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['total'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $cartTotal . "</strong></p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Online Store</h1>

    <h2>Products</h2>
    <ul>
        <li><a href="?action=view_cart&product_id=1">Laptop</a> - $1200</li>
        <li><a href="?action=view_cart&product_id=2">Mouse</a> - $25</li>
        <li><a href="?action=view_cart&product_id=3">Keyboard</a> - $75</li>
        <li><a href="?action=view_cart&product_id=4">Monitor</a> - $300</li>
    </ul>

    <form method="post" action="cart.php" >
        <h2>Add to Cart</h2>
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1">
        <input type="submit" name="action" value="add_to_cart">
    </form>

</body>
</html>


<?php
session_start();

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ------------------- Functions -------------------

/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0; // Initialize count for new products
    }
    $_SESSION['cart'][$product_id] += $quantity;
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


/**
 * Removes an item from the shopping cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


/**
 * Calculates the subtotal for a single item in the cart.
 *
 * @param int $product_id The ID of the product.
 * @return float
 */
function calculate_subtotal($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return $_SESSION['cart'][$product_id] * $product_price[$product_id]; // Assuming $product_price is defined
    }
    return 0;
}

/**
 * Calculates the total price of the cart.
 *
 * @return float
 */
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $total_item = calculate_subtotal($product_id);
        $total += $total_item;
    }
    return $total;
}

/**
 *  Gets the product price
 * @param int $product_id
 * @return float
 */
function get_product_price($product_id) {
    // Assuming you have a product price array (e.g., $product_price) defined elsewhere
    // This is just a placeholder - you'll need to populate this
    // For example:
    // $product_price = [
    //     1 => 10.00,
    //     2 => 20.00,
    //     3 => 15.00,
    // ];
    return $product_price[$product_id];
}

// ------------------- Example Product Data (Replace with your actual data) -------------------
$product_price = [
    1 => 10.00,
    2 => 20.00,
    3 => 15.00,
];



// ------------------- Handling Actions -------------------

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    add_to_cart($product_id, $quantity);
}

// Update Cart (Quantity Change)
if (isset($_POST['update_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    update_cart($product_id, $quantity);
}

// Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = (int)$_GET['remove_from_cart'];
    remove_from_cart($product_id);
}



// ------------------- Cart Display -------------------

// Output Cart Contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_name = "Product " . $product_id; // Replace with your actual product name retrieval
        echo "<li>" . $product_name . " - Quantity: " . $quantity . " - Price: $" . get_product_price($product_id) . " - Subtotal: $" . calculate_subtotal($product_id) . " <button onclick=\"remove_item(" . $product_id . ")\" class=\"remove\">Remove</button></li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}



// ------------------- JavaScript for Remove Button (Optional) -------------------

echo "<script>
function remove_item(product_id) {
  window.location.href = 'cart.php?remove_from_cart=" . $product_id . "'";
}
</script>";

?>


<?php
session_start();

// Configuration
$items = []; // Array to store the items in the cart
$cart_file = 'cart.json'; // File to store the cart data

// Helper functions to read and write to the JSON file
function readCart() {
    if (file_exists($cart_file)) {
        $data = file_get_contents($cart_file);
        return json_decode($data, true); // Decode to associative array
    }
    return [];
}

function writeCart($cart) {
    file_put_contents($cart_file, json_encode($cart, JSON_PRETTY_PRINT));
}


// Cart functions

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    $cart = readCart();

    // Check if the product is already in the cart
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] += $quantity;
    } else {
        $cart[$product_id] = ['quantity' => $quantity];
    }

    writeCart($cart);
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    $cart = readCart();
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }

    writeCart($cart);
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    $cart = readCart();
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
    writeCart($cart);
}


// Display the cart
function display_cart() {
    $cart = readCart();

    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        $product_name = get_product_name($product_id); //Get product name from a database
        echo "<li>";
        echo "<strong>$product_name</strong> - Quantity: $item['quantity'] - Price: $item['price'] (Assuming you have a database for product prices)";
        echo "<form method='post'>";
        echo "<label for='$product_id'>Quantity:</label>";
        echo "<input type='number' id='$product_id' name='$product_id' value='$item['quantity']' min='1'>";
        echo "<input type='submit' name='$product_id' value='Update'>";
        echo "<a href='?remove=$product_id'>Remove</a>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";

    // Calculate the total
    $total = 0;
    foreach ($cart as $product_id => $item) {
        $total += $item['quantity'] * $item['price']; // Assuming you have a database for product prices
    }

    echo "<p><strong>Total: $total</strong></p>";
}

// Get product name from database (example - modify to fit your setup)
function get_product_name($product_id) {
    //Replace this with your actual database query
    $products = [
        1 => ['name' => 'Laptop'],
        2 => ['name' => 'Mouse'],
        3 => ['name' => 'Keyboard']
    ];
    if (isset($products[$product_id])) {
        return $products[$product_id]['name'];
    } else {
        return "Unknown Product";
    }
}



// Handle form submission (Update Quantity)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = htmlspecialchars($_POST['product_id']);  // Sanitize input
    if (isset($product_id)) {
        update_quantity($product_id, $_POST['quantity']);
    }
}

// Handle Remove Item
if (isset($_GET['remove'])) {
    remove_from_cart($_GET['remove']);
}

// Initialize the cart (if not already initialized)
if (!readCart()) {
    $cart = [];
    writeCart($cart);
}


//Display the cart
display_cart();
?>


<?php
session_start();

// Define product data (replace with your actual data source)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add item to cart
function addToCart($productId, $quantity = 1) {
    if (isset($products[$productId])) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $productId,
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => $quantity
            ];
        }
    }
}

// Function to update quantity
function updateQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to remove item from cart
function removeCartItem($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to get cart total
function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle adding to cart (from a form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    addToCart($productId, $quantity);
}

// Handle updating quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    updateQuantity($productId, $quantity);
}

// Handle removing item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    removeCartItem($productId);
}

// Get cart contents for display
$cart_items = $_SESSION['cart'];

// Calculate cart total
$total = calculateCartTotal();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; margin: 10px; padding: 10px; }
        .cart-total { font-weight: bold; }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post" action="">
        <h2>Items in Cart:</h2>
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php
            foreach ($cart_items as $item):
                ?>
                <div class="cart-item">
                    <strong><?php echo $item['name']; ?></strong> - $<?php echo number_format($item['price'], 2); ?>
                    <br>
                    Quantity: <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                    <br>
                    <button type="submit" name="update_quantity" value="<?php echo $item['id']; ?>">Update</button>
                    <button type="submit" name="remove_from_cart" value="<?php echo $item['id']; ?>">Remove</button>
                </div>
            <?php
        endforeach;
        ?>
        <input type="hidden" name="product_id" value="">
        <?php
    }
    ?>
    </form>

    <p class="cart-total">Total: $<?php echo number_format($total, 2); ?></p>

    <a href="index.php">Continue Shopping</a> <!-- Link to the product listing page -->

</body>
</html>


<?php
// cart.php

$cart_file = 'cart.txt'; // Name of the file to store the cart

// Function to read the cart
function readCart() {
    if (file_exists($cart_file)) {
        $cart = array();
        $lines = file($cart_file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            list($product_id, $quantity) = explode(',', $line);
            $cart[$product_id] = $quantity;
        }
        return $cart;
    } else {
        return array(); // Return an empty array if the file doesn't exist
    }
}

// Function to write the cart
function writeCart($cart) {
    $data = '';
    foreach ($cart as $product_id => $quantity) {
        $data .= $product_id . ',' . $quantity . '
';
    }
    file_put_contents($cart_file, $data);
}
?>


<?php
// index.php

require_once 'cart.php'; // Include the cart.php file

$cart = readCart();  // Read the cart data from the file

// Product data (for example purposes)
$products = array(
    'product1' => array('name' => 'T-Shirt', 'price' => 20),
    'product2' => array('name' => 'Jeans', 'price' => 50),
    'product3' => array('name' => 'Hat', 'price' => 15)
);

echo '<h1>Shopping Cart</h1>';

if (empty($cart)) {
    echo '<p>Your cart is empty.</p>';
} else {
    echo '<ul>';
    foreach ($cart as $product_id => $quantity) {
        $product_name = $products[$product_id]['name'];
        $product_price = $products[$product_id]['price'];
        $total_price = $product_price * $quantity;
        echo '<li>' . $product_name . ' - $' . $product_price . ' x ' . $quantity . ' = $' . $total_price . '</li>';
    }
    echo '</ul>';
    echo '<p><strong>Total: $' . array_sum(array_map(function($qty) { return $products[$key]['price'] * $qty; }, $cart)) . '</strong></p>';
}

echo '<br><a href="add_to_cart.php">Add to Cart</a> | <a href="remove_from_cart.php">Remove from Cart</a> | <a href="checkout.php">Checkout</a>';
?>


<?php
// add_to_cart.php

require_once 'cart.php';

$product_id = $_GET['product_id'];
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1; // Default to 1 if not provided.

if (empty($cart)) {
    $cart[$product_id] = $quantity;
} else {
    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
}

writeCart($cart); // Save the updated cart data
header("Location: index.php"); // Redirect back to the main page.
exit();
?>


<?php
// remove_from_cart.php

require_once 'cart.php';

$product_id = $_GET['product_id'];

if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
}

writeCart($cart); // Save the updated cart data
header("Location: index.php"); // Redirect back to the main page
exit();
?>


<?php
// checkout.php

require_once 'cart.php';

if (empty($cart)) {
    echo '<p>Your cart is empty.  Nothing to checkout.</p>';
} else {
    echo '<h1>Checkout</h1>';
    echo '<p>Thank you for your order!</p>';
    echo '<p><strong>Total: $' . array_sum(array_map(function($qty) { return $products[$key]['price'] * $qty; }, $cart)) . '</strong></p>';
}
?>


<?php
session_start();

// Database connection (Replace with your actual database credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "ecommerce_db";

// Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart Initialization - Check if cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Add item to cart
function addToCart($conn, $product_id, $quantity) {
    $product_id = $conn->real_escape_string($product_id);  //Escape string
    $quantity = $conn->real_escape_string($quantity);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
    }
}

// Update item quantity in cart
function updateCartQuantity($conn, $product_id, $quantity) {
    $product_id = $conn->real_escape_string($product_id);
    $quantity = $conn->real_escape_string($quantity);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // Handle case where product is no longer in cart (e.g., delete)
        //  You might want to log this or take another action.
        // For this example, we'll just do nothing.
        return false; // Indicate failure
    }
    return true; // Indicate success
}

// Remove item from cart
function removeCartItem($conn, $product_id) {
    $product_id = $conn->real_escape_string($product_id);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Get cart items
function getCartItems($conn) {
    $items = [];
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $item_data = getProductDetails($conn, $product_id); //Fetch product details
        if ($item_data) {
           $item_data['quantity'] = $item_data['quantity'];
           $items[] = $item_data;
        }
    }
    return $items;
}

// Fetch product details from the database
function getProductDetails($conn, $product_id) {
    $product_id = $conn->real_escape_string($product_id);

    $query = "SELECT id, name, price, image FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    }
    return null;
}

// Calculate total cart value
function calculateTotal($conn) {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product_data = getProductDetails($conn, $product_id);
        if($product_data) {
            $total += $product_data['price'] * $item['quantity'];
        }
    }
    return $total;
}

// --- Cart Functions (Called from the form) ---

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($conn, $product_id, $quantity);
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateCartQuantity($conn, $product_id, $quantity);
}

// Remove from Cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($conn, $product_id);
}


// --- Display Cart ---

// Get cart items
$cart_items = getCartItems($conn);

// Calculate total
$total = calculateTotal($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .cart-item img {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>

    <div id="cart-items">
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" >
                <p><strong>Name:</strong> <?php echo $item['name']; ?></p>
                <p><strong>Price:</strong> $<?php echo number_format($item['price'], 2); ?></p>
                <p><strong>Quantity:</strong> <?php echo $item['quantity']; ?></p>
                <form method="post" action="">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 50px;">
                    <button type="submit" name="update_quantity">Update</button>
                </form>
                <form method="post" action="">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" name="remove_from_cart">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>

<?php endif; ?>

</body>
</html>


<?php
session_start();

// Database connection (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to fetch products from the database
function fetchProducts($conn) {
  $sql = "SELECT * FROM products";
  $result = $conn->query($sql);
  $products = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $products[] = $row;
    }
  }
  return $products;
}

// --- Cart Management Functions ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Add to cart function
function addToCart($conn, $product_id, $quantity) {
  global $db_host, $db_user, $db_password, $db_name;

  // Check if the product exists
  $sql = "SELECT * FROM products WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if (!$product) {
    echo "Product with ID " . $product_id . " not found.";
    return false;
  }

  // Check if the product is already in the cart
  foreach($_SESSION['cart'] as $index => $item) {
    if ($item['id'] == $product_id) {
      // Update the quantity
      $_SESSION['cart'][$index]['quantity'] += $quantity;
      return true;
    }
  }

  // Add the product to the cart
  $_SESSION['cart'][] = $product;
  return true;
}

// Remove from cart function
function removeFromCart($product_id) {
  // Iterate through the cart and remove the item with the matching product_id
  foreach($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      // Re-index the cart array
      $_SESSION['cart'] = array_values($_SESSION['cart']);
      return true;
    }
  }
  return false;
}

// Get cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Calculate cart total
function calculateCartTotal($conn) {
  $total = 0;
  $cartItems = getCartItems();

  foreach ($cartItems as $item) {
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $total += $product['price'] * $item['quantity'];
  }
  return $total;
}

// ---  Display the Cart ---

// Fetch products
$conn = connectToDatabase();
$products = fetchProducts($conn);


// --- Handle form submission ---
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($conn, $product_id, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// Get Cart items for display
$cartItems = getCartItems();

// Calculate total
$cartTotal = calculateCartTotal($conn);

// --- Output the HTML ---
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cartItems)) { ?>
    <p>Your cart is empty.</p>
  <?php } else { ?>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($cartItems as $item) {
          echo "<tr class='cart-item'>";
          echo "<td>" . $item['name'] . "</td>";
          echo "<td>$" . $item['price'] . "</td>";
          echo "<td>" . $item['quantity'] . "</td>";
          echo "<td>$" . $item['quantity'] * $item['price'] . "</td>";
          echo "<td><a href='?action=remove_cart&product_id=" . $item['id'] . "'>Remove</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <p><strong>Total:</strong> $" . $cartTotal . "</p>
    <?php
    }
    ?>

  <form action="" method="post">
    <?php
        foreach ($products as $product) {
        ?>
      <div>
        <label for="product_id_<?php echo $product['id']; ?>">
          <input type="checkbox" id="product_id_<?php echo $product['id']; ?>" name="product_id" value="<?php echo $product['id']; ?>" <?php if (in_array($product['id'], $cartItems)) echo 'checked'; ?> >
          <?php echo $product['name']; ?> (Price: <?php echo $product['price']; ?>)
        </label>
      </div>
    <?php } ?>

  </form>

</body>
</html>


<?php
session_start();

// Cart data (in a real application, this would likely come from a database)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $cart[$product_id]['quantity'] += $quantity;
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    unset($cart[$product_id]);
}

// Function to get the cart total
function get_cart_total() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to display the cart
function display_cart() {
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($cart as $product_id => $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . number_format($item['price'] * $item['quantity'], 2) . "</td>";
        echo "<td><a href='update_cart.php?product_id=" . $product_id . "&quantity=1'>-</a> <a href='update_cart.php?product_id=" . $product_id . "&quantity=1'>Update</a> <a href='remove_from_cart.php?product_id=" . $product_id . "'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format(get_cart_total(), 2) . "</p>";
}

// ---  Handling Cart Updates (update_cart.php) ---

//If the update_cart.php is accessed, let's handle the quantity updates
if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = $_GET['product_id'];
    $quantity = (int)$_GET['quantity']; // Ensure it's an integer

    if ($quantity > 0) {
      update_cart_quantity($product_id, $quantity);
    } else {
      // Handle invalid quantity, e.g., display an error message
      echo "<p>Invalid quantity. Please enter a positive number.</p>";
    }
    header("Location: cart.php"); // Redirect to the cart page
    exit(); // Important to stop further execution
}



// ---  Removing Items (remove_from_cart.php) ---

//If the remove_from_cart.php is accessed, let's handle the removal
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    remove_from_cart($product_id);
    header("Location: cart.php"); // Redirect to the cart page
    exit();
}

// --- Initial Cart Display (cart.php) ---

// Ensure the cart is started.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = $cart;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<!-- Display the cart -->
<?php display_cart(); ?>

<hr>

<!-- Add to Cart Button (Assuming you have a product listing) -->
<?php
// Example Product listing (Replace with your actual product data)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15]
];

echo "<h2>Add to Cart</h2>";
foreach ($products as $product_id => $product_data) {
    echo "<form method='post' action='cart.php'>";
    echo "<label for='product_" . $product_id . "'>Product: " . $product_data['name'] . " ($" . number_format($product_data['price'], 2) . ")</label><br>";
    echo "<input type='number' id='quantity_" . $product_id . "' name='quantity_" . $product_id . "' value='1' min='1' max='10'><br>"; //Added max to limit quantity
    echo "<input type='submit' value='Add to Cart'>";
    echo "</form><br>";
}
?>

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

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_contents)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_contents as $item): ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td>$<?php echo number_format($item['product_price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <form method="get" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="hidden" name="remove_item" value="<?php echo $item['product_id']; ?>">
                            <input type="submit" value="Remove">
                        </form>
                    </td>
                </tr>
            <?php
            } // end foreach
            ?>
        </tbody>
    </table>
<?php endif; ?>


</body>
</html>


<?php
session_start();

// Database connection (replace with your actual credentials)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database_name';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to check if an item is already in the cart
function isInCart($cart_id, $product_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmt->execute([$cart_id, $product_id]);
    return (int)$stmt->fetchColumn() > 0;
}

// Function to update the cart
function updateCart($cart_id, $product_id, $quantity) {
    $stmt = $pdo->prepare("
        UPDATE cart_items
        SET quantity = :quantity
        WHERE cart_id = :cart_id AND product_id = :product_id
    ");
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
}

// Function to add an item to the cart
function addItemToCart($cart_id, $product_id, $quantity) {
    if (isInCart($cart_id, $product_id)) {
        updateCart($cart_id, $product_id, $quantity);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$cart_id, $product_id, $quantity]);
    }
}

// Cart ID based on session
$cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;

if ($cart_id === null) {
    // Create a new cart if one doesn't exist
    $cart_id = bin2hex(random_bytes(16));
    $_SESSION['cart_id'] = $cart_id;
}

// Get products (replace with your product retrieval logic)
$products = [
    ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Handle add to cart request
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate quantity (optional, but recommended)
    if (isset($quantity) && is_numeric($quantity) && $quantity > 0) {
        addItemToCart($cart_id, $product_id, $quantity);
    }
}

// Display the cart contents
$cartItems = [];
if ($cart_id !== null) {
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart_items WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calculate total price
$total_price = 0;
if ($cart_id !== null) {
    foreach ($cartItems as $item) {
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] == $item['product_id']) {
                $product = $p;
                break;
            }
        }
        if ($product) {
            $total_price += $product['price'] * $item['quantity'];
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <h2>Cart ID: <?php echo $cart_id; ?></h2>

    <form method="post">
        <?php if ($cart_id !== null && !empty($cartItems)) { ?>
            <h2>Cart Items</h2>
            <?php foreach ($cartItems as $item) { ?>
                <div class="cart-item">
                    <strong><?php $product = null; foreach ($products as $p) { if ($p['id'] == $item['product_id']) {$product = $p; break;} } ?></strong>
                    <?php if ($product) { ?>
                        <?php echo $product['name'] ?> - <?php echo $product['price'] ?>
                    <?php } ?>
                    Quantity: <input type="number" name="quantity" value="<?php echo $item['quantity'] ?>">
                    <br>
                    <button type="submit" name="update_cart">Update Cart</button>
                    <br>

                </div>
            <?php } ?>
        <?php } ?>

        <h2>Add to Cart</h2>
        <?php foreach ($products as $product) { ?>
            <label for="product_<?php echo $product['id'] ?>">
                <input type="number" name="product_id", value="<?php echo $product['id'] ?>" min="1" >
                <?php echo $product['name'] ?> - <?php echo $product['price'] ?>
            </label>
            <br>
        <?php } ?>

    </form>

    <p>Total Price: <?php echo number_format($total_price, 2); ?></p>

    <a href="checkout.php">Checkout</a>  <!-- Link to checkout page (not implemented) -->

</body>
</html>


<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// Function to add to cart
function addToCart($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to get cart items
function getCartItems() {
  return isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
}

// Function to update cart quantity
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Handle the case where the product is not in the cart
    // You might want to add it with the given quantity or handle it differently
    // For this example, we'll just return an empty array to indicate no update
    return array();
  }
  return $_SESSION['cart'];
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
  return $_SESSION['cart'];
}

// Function to calculate cart total
function calculateCartTotal() {
  $total = 0;
  $cartItems = getCartItems();
  foreach ($cartItems as $product_id => $item) {
    $product = getProductById($product_id); // Assuming you have a function to get product details
    if ($product) {
      $totalPrice = $product['price'] * $item['quantity'];
      $total += $totalPrice;
    }
  }
  return $total;
}

//Example Product Retrieval Function - Replace with your actual database query
function getProductById($product_id) {
    // Replace this with your database query to retrieve product details
    // based on the product_id
    $products = array(
        1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
        2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
        3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75)
    );
    if (isset($products[$product_id])) {
        return $products[$product_id];
    }
    return null;
}

// Handling Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
  // Optionally, display a success message
  echo "<p>Item added to cart!</p>";
}

// Handling Update Quantity
if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
  // Optionally, display a success message
  echo "<p>Cart updated!</p>";
}

// Handling Remove Item
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeItemFromCart($product_id);
  // Optionally, display a success message
  echo "<p>Item removed from cart!</p>";
}

// Display Cart Items
$cartItems = getCartItems();
$cartTotal = calculateCartTotal();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    $product = getProductById($product_id);
    if ($product) {
      echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $item['quantity'] . " = $" . ($product['price'] * $item['quantity']) . "</li>";
    }
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $cartTotal . "</strong></p>";
}
?>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle adding to cart
function addToCart($productId, $quantity) {
    $conn = connectToDatabase();

    // Check if product exists in the cart
    $cartKey = 'cart_' . session_id();

    if (!isset($_SESSION[$cartKey])) {
        $_SESSION[$cartKey] = array();
    }

    if (isset($_SESSION[$cartKey][$productId])) {
        $_SESSION[$cartKey][$productId] += $quantity;
    } else {
        $_SESSION[$cartKey][$productId] = $quantity;
    }

    $conn->close();
}

// Function to get the cart contents
function getCartContents() {
    $cartKey = 'cart_' . session_id();

    if (!isset($cartKey)) {
        return []; // Return an empty array if cart doesn't exist
    }

    return $_SESSION[$cartKey];
}

// Function to remove a product from the cart
function removeFromCart($productId) {
    $cartKey = 'cart_' . session_id();

    if (!isset($cartKey)) {
        return; // Cart doesn't exist
    }

    unset($_SESSION[$cartKey][$productId]);
}


// Handling Add to Cart Request
if (isset($_POST['action']) && $_POST['action'] == 'addToCart') {
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];

    addToCart($productId, $quantity);
}

// Handling Remove from Cart Request
if (isset($_POST['action']) && $_POST['action'] == 'removeFromCart') {
    $productId = $_POST['productId'];
    removeFromCart($productId);
}


// Displaying the Cart Contents
$cart = getCartContents();

$total = 0;
foreach ($cart as $productId => $quantity) {
    // Get product details from the database
    $product = getProductDetails($productId);

    if ($product) {
        $total += $product['price'] * $quantity;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>

        <?php
        // Display cart items
        foreach ($cart as $productId => $quantity) {
            $product = getProductDetails($productId);

            if ($product) {
                echo '<div class="cart-item">';
                echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
                echo '<h3>' . $product['name'] . '</h3>';
                echo '<p>Quantity: ' . $quantity . '</p>';
                echo '<p>Price: $' . $product['price'] . '</p>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="action" value="removeFromCart">';
                echo '<input type="hidden" name="productId" value="' . $productId . '">';
                echo '<button type="submit">Remove</button>';
                echo '</form>';
                echo '</div>';
            }
        }
        ?>

        <p>Total: $<?php echo round($total, 2); ?></p>
    <?php endif; ?>

    <hr>

    <h2>Add to Cart</h2>
    <ul>
        <li><form method="post" action="">
            <input type="hidden" name="action" value="addToCart">
            <input type="hidden" name="productId" value="1">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit">Add Product 1 to Cart</button>
        </form></li>

        <li><form method="post" action="">
            <input type="hidden" name="action" value="addToCart">
            <input type="hidden" name="productId" value="2">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit">Add Product 2 to Cart</button>
        </form></li>
    </ul>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Database connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Cart Data
$cart = array();


// Function to add an item to the cart
function add_to_cart($conn, $product_id, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            // Update the quantity
            $cart[$key]['quantity'] += $quantity;
            return;
        }
    }

    // If not in the cart, add it
    $cart[] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($conn, $product_id) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }
}


// Function to update the quantity of an item in the cart
function update_cart_quantity($conn, $product_id, $quantity) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $cart[$key]['quantity'] = $quantity;
            return;
        }
    }
}


// ---  Handling Requests (GET/POST) ---

// 1. Add to Cart (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    add_to_cart($conn, $product_id, $quantity);
}


// 2. Remove from Cart (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($conn, $product_id);
}


// 3. Update Quantity (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_cart_quantity($conn, $product_id, $new_quantity);
}


// --- Display Cart Contents ---
echo "<h2>Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $key => $item) {
        $product_id = $item['product_id'];

        // Fetch product details from the database (replace with your query)
        $product_query = "SELECT * FROM products WHERE id = $product_id";
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            echo "<li>";
            echo "Product: " . $product['name'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "Price: $" . $product['price'] . "<br>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
            echo "<input type='hidden' name='quantity' value='" . $item['quantity'] . "'>";
            echo "<input type='submit' name='update_quantity' value='Update Quantity'>&nbsp; ";
            echo "<input type='submit' name='remove_from_cart' value='Remove from Cart'> ";
            echo "</form>";
            echo "</li>";
        } else {
            echo "<li>Product ID: " . $product_id . " not found.</li>";
        }
    }
    echo "</ul>";
}


// Close the database connection
$conn->close();
?>


<?php
session_start();

// Database connection (replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// --- Product Data (Simulated for Example) ---
$products = [
    1 => ["id" => 1, "name" => "Laptop", "price" => 1200, "quantity" => 1],
    2 => ["id" => 2, "name" => "Mouse", "price" => 25, "quantity" => 5],
    3 => ["id" => 3, "name" => "Keyboard", "price" => 75, "quantity" => 3],
];

// --- Functions ---

/**
 * Adds an item to the cart.
 * @param int $product_id
 * @param int $quantity
 */
function add_to_cart($product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] ?? 0;
        $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Updates the quantity of an item in the cart.
 * @param int $product_id
 * @param int $quantity
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


/**
 * Removes an item from the cart.
 * @param int $product_id
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the cart contents
 * @return array An array containing the cart data.
 */
function get_cart_contents() {
    return $_SESSION['cart'] ?? []; //Return empty array if session not set
}

// --- Handling Add to Cart Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];

    if (isset($products[$product_id])) {
        add_to_cart($product_id, $quantity);
    } else {
        echo "Product ID $product_id not found.";
    }
}


// --- Handling Update Quantity Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_quantity"])) {
    $product_id = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];

    update_cart_quantity($product_id, $quantity);
}

// --- Handling Remove from Cart Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_from_cart"])) {
    $product_id = (int)$_POST["product_id"];
    remove_from_cart($product_id);
}

// --- Display Cart Contents ---
$cart_contents = get_cart_contents();

echo "<h2>Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item_quantity) {
        $product = $products[$product_id];
        echo "<li>" . $product['name'] . " - Quantity: " . $item_quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";

    // --- Calculate Total Price ---
    $total_price = 0;
    foreach ($cart_contents as $product_id => $item_quantity) {
        $product = $products[$product_id];
        $total_price += $product['price'] * $item_quantity;
    }
    echo "<p><strong>Total Price: $" . number_format($total_price, 2) . "</strong></p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Products</h1>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - Price: $" . $product['price'] . "</li>";
    }
    ?>
</ul>

<h2>Add to Cart</h2>
<form method="post">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"$id\">$id - $product['name']</option>";
        }
        ?>
    </select>
    <br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1">
    <br><br>
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

</body>
</html>

