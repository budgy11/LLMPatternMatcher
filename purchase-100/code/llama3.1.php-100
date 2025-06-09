
<?php
// Initialize session
session_start();

// Check if cart is already in session, otherwise initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($product_id) {
    global $_SESSION;
    $exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity']++;
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $_SESSION['cart'][] = array('id' => $product_id, 'name' => '', 'price' => 0, 'quantity' => 1);
    }
}

// Remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Update quantity of item in cart
function update_item_quantity($product_id, $quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}

// Display cart contents
function display_cart() {
    global $_SESSION;
    echo '<table>';
    echo '<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>$' . number_format($item['price'], 2) . '</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '<td>$' . number_format($item['quantity'] * $item['price'], 2) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Calculate total cart value
function calculate_cart_total() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return number_format($total, 2);
}
?>


<?php include 'cart.php'; ?>

<!-- Assume we have a products array with product info -->
$products = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 9.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 19.99),
    // ...
);

// Display products
echo '<h1>Products</h1>';
foreach ($products as $product) {
    echo '<p><a href="#" onclick="add_item_to_cart(' . $product['id'] . ')">' . $product['name'] . '</a> - $' . number_format($product['price'], 2) . '</p>';
}

// Display cart
echo '<h1>Cart</h1>';
display_cart();

// Display total
echo '<p>Total: $' . calculate_cart_total() . '</p>';

?>


<?php

// Initialize the session
session_start();

// Set default values for cart
$_SESSION['cart'] = array();

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
  global $_SESSION;
  
  // Check if product already exists in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $product_id) {
      $item['quantity'] += $quantity;
      return;
    }
  }

  // Add new item to cart
  $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
  global $_SESSION;
  
  // Find the index of the product in the cart
  foreach (array_keys($_SESSION['cart']) as $index) {
    if ($_SESSION['cart'][$index]['product_id'] == $product_id) {
      unset($_SESSION['cart'][$index]);
      return;
    }
  }

  // If not found, do nothing
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
  global $_SESSION;
  
  // Find the index of the product in the cart
  foreach (array_keys($_SESSION['cart']) as $index) {
    if ($_SESSION['cart'][$index]['product_id'] == $product_id) {
      $_SESSION['cart'][$index]['quantity'] = $new_quantity;
      return;
    }
  }

  // If not found, do nothing
}

// Function to calculate total cost of cart
function calculate_total_cost() {
  global $_SESSION;
  
  $total_cost = 0;
  foreach ($_SESSION['cart'] as $item) {
    $product_price = get_product_price($item['product_id']); // Assume this function is defined elsewhere
    $total_cost += $product_price * $item['quantity'];
  }
  return $total_cost;
}

// Function to display cart contents
function display_cart() {
  global $_SESSION;
  
  echo "<h2>Cart Contents:</h2>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<p>Product ID: {$item['product_id']} | Quantity: {$item['quantity']}</p>";
  }
}

// Function to add a product to the cart from a form submission
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  add_item_to_cart($product_id, $quantity);
}

?>


<?php
session_start();

// Check if cart is already created in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($id, $name, $price) {
    // Create a new array for the item
    $item = array(
        'id' => $id,
        'name' => $name,
        'price' => $price
    );
    
    // Add item to cart
    $_SESSION['cart'][] = $item;
}

// Function to update item quantity in cart
function updateItemQuantity($id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}

// Function to remove item from cart
function removeItemFromCart($id) {
    // Remove item by ID
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($id) {
        return $item['id'] != $id;
    });
}

// Function to calculate total cost of cart
function calculateTotalCost() {
    $totalCost = 0;
    foreach ($_SESSION['cart'] as &$item) {
        $totalCost += $item['price'];
    }
    return $totalCost;
}

// Example usage:
$productId = 1;
$productName = 'Product A';
$productPrice = 9.99;

addItemToCart($productId, $productName, $productPrice);

echo "Total Cost: $" . calculateTotalCost();
?>


<?php
session_start();

// Display cart items and total cost
?>

<h1>Cart Items:</h1>

<table>
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th></th>
    </tr>
    
    <?php foreach ($_SESSION['cart'] as $item) : ?>
    <tr>
        <td><?php echo $item['name']; ?></td>
        <td><?php echo "$" . $item['price']; ?></td>
        <td>
            <a href="#" class="remove-item" data-id="<?php echo $item['id']; ?>">Remove</a>
        </td>
    </tr>
    
    <?php endforeach; ?>
</table>

<h2>Total Cost: $<?php echo calculateTotalCost(); ?></h2>


<?php
// Initialize the cart array
$cart = array();

// Function to add item to cart
function addItemToCart($item, $quantity) {
  global $cart;
  if (array_key_exists($item['id'], $cart)) {
    $cart[$item['id']]['quantity'] += $quantity;
  } else {
    $cart[$item['id']] = array(
      'name' => $item['name'],
      'price' => $item['price'],
      'quantity' => $quantity
    );
  }
}

// Function to remove item from cart
function removeItemFromCart($itemId) {
  global $cart;
  if (array_key_exists($itemId, $cart)) {
    unset($cart[$itemId]);
  }
}

// Function to update quantity of an item in cart
function updateQuantity($itemId, $newQuantity) {
  global $cart;
  if (array_key_exists($itemId, $cart)) {
    $cart[$itemId]['quantity'] = $newQuantity;
  }
}

// Function to calculate total cost of items in cart
function calculateTotal() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}
?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <?php
  // Sample products array
  $products = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 10.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 5.99),
    array('id' => 3, 'name' => 'Product C', 'price' => 7.99)
  );

  // Display products
  foreach ($products as $product) {
    ?>
    <div>
      <h2><?php echo $product['name']; ?></h2>
      <p>Price: <?php echo $product['price']; ?></p>
      <button onclick="addItemToCart(<?php echo json_encode($product); ?>, 1)">Add to Cart</button>
    </div>
    <?php
  }

  // Display cart contents
  ?>
  <h2>Cart Contents:</h2>
  <?php foreach ($cart as $item) { ?>
    <p><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?></p>
  <?php } ?>

  <button onclick="removeItemFromCart(1)">Remove Item</button>

  <script>
    function addItemToCart(item, quantity) {
      // Send AJAX request to server to add item to cart
      fetch('/cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({item: item, quantity: quantity})
      })
      .then(response => response.json())
      .then(data => console.log(data));
    }

    function removeItemFromCart(itemId) {
      // Send AJAX request to server to remove item from cart
      fetch('/cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'remove', itemId: itemId})
      })
      .then(response => response.json())
      .then(data => console.log(data));
    }
  </script>
</body>
</html>


<?php
// ...

// Function to handle AJAX requests from client-side
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  if ($data) {
    switch ($data['action']) {
      case 'add':
        addItemToCart($data['item'], $data['quantity']);
        break;
      case 'remove':
        removeItemFromCart($data['itemId']);
        break;
    }
  }

  // Calculate total cost of items in cart and send response back to client
  header('Content-Type: application/json');
  echo json_encode(array(
    'total' => calculateTotal(),
    'cart' => $cart
  ));
}
?>


<?php
session_start();

// Check if the cart is empty, if so set it to an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add items to the cart
function addToCart($itemId) {
    global $itemId;
    // Check if the item already exists in the cart
    if (in_array($itemId, $_SESSION['cart'])) {
        echo "Item is already in your cart.";
    } else {
        $_SESSION['cart'][] = $itemId;
        echo "Item added to your cart.";
    }
}

// Function to remove items from the cart
function removeFromCart($itemId) {
    global $itemId;
    // Check if the item exists in the cart
    if (in_array($itemId, $_SESSION['cart'])) {
        $key = array_search($itemId, $_SESSION['cart']);
        unset($_SESSION['cart'][$key]);
        echo "Item removed from your cart.";
    } else {
        echo "Item is not in your cart.";
    }
}

// Function to display the contents of the cart
function displayCart() {
    global $itemId;
    if (count($_SESSION['cart']) > 0) {
        echo "<h2>Your Cart</h2>";
        foreach ($_SESSION['cart'] as $item) {
            // You can add a database query here to retrieve item details
            echo "Item ID: $item";
            // Add remove button
            echo '<button class="remove-button" onclick="removeFromCart(' . $item . ')">Remove</button>';
        }
    } else {
        echo "<h2>Your cart is empty.</h2>";
    }
}

// Function to calculate the total cost of items in the cart
function calculateTotal() {
    global $itemId;
    // You can add a database query here to retrieve item prices
    // For this example, let's assume each item costs $10
    $total = count($_SESSION['cart']) * 10;
    return $total;
}

// Add event listener for adding items to the cart
?>


<?php
include 'cart.php';

// Display the contents of the cart
displayCart();

// If you want to add items to the cart, call the addToCart function
if (isset($_GET['add'])) {
    addToCart($_GET['id']);
}

// If you want to remove an item from the cart, call the removeFromCart function
if (isset($_GET['remove'])) {
    removeFromCart($_GET['id']);
}
?>

<!-- Add a form to add items to the cart -->
<form action="index.php" method="get">
    <input type="hidden" name="add" value="1">
    <input type="text" name="id" placeholder="Item ID">
    <button type="submit">Add to Cart</button>
</form>

<!-- Display the total cost of items in the cart -->
<p>Total: <?php echo calculateTotal(); ?></p>


<?php
// Initialize cart array
$cart = array();

// Function to add item to cart
function add_to_cart($id, $name, $price) {
    global $cart;
    if (array_key_exists($id, $cart)) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = array('name' => $name, 'price' => $price, 'quantity' => 1);
    }
}

// Function to remove item from cart
function remove_from_cart($id) {
    global $cart;
    if (array_key_exists($id, $cart)) {
        unset($cart[$id]);
    }
}

// Function to update quantity of item in cart
function update_quantity($id, $quantity) {
    global $cart;
    if (array_key_exists($id, $cart)) {
        $cart[$id]['quantity'] = $quantity;
    }
}

// Function to display cart contents
function display_cart() {
    global $cart;
    echo "<h2>Cart Contents:</h2>";
    foreach ($cart as $item) {
        echo "$item[name] x $item[quantity] @ $" . number_format($item['price'], 2) . " = $" . number_format($item['quantity'] * $item['price'], 2) . "<br>";
    }
    echo "<p>Total: $" . number_format(array_sum(array_column($cart, 'quantity') * array_column($cart, 'price')), 2) . "</p>";
}

// Function to checkout
function checkout() {
    global $cart;
    // Process payment and clear cart
    echo "Checkout successful!";
    unset($cart);
}
?>


<?php
include 'cart.php';

// Add some items to the cart
add_to_cart(1, 'Product 1', 9.99);
add_to_cart(2, 'Product 2', 19.99);

// Display cart contents
display_cart();

// Remove an item from the cart
remove_from_cart(1);

// Update quantity of another item in the cart
update_quantity(2, 3);

// Display updated cart contents
display_cart();

// Checkout
checkout();
?>


<?php

// Session variables to store cart data
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    // Check if product exists in cart already
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If the product is already in cart, increase its quantity by the new amount
            $item['quantity'] += $quantity;
            return true;  // Return to avoid adding same product again
        }
    }

    // Add product to cart if not present
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function view_cart() {
    echo '<pre>';
    print_r($_SESSION['cart']);
    echo '</pre>';
}

function update_quantity($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true;  // Update successful
        }
    }

    echo "Product not found in cart.";
}

function remove_product($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;  // Product removed successfully
        }
    }

    echo "Product not found in cart.";
}

// Example usage:

// Adding a product to the cart
add_to_cart(1, 2); // Add product with id 1 in quantity of 2

// Viewing the contents of the cart
view_cart();

// Updating the quantity of a product in the cart
update_quantity(1, 5);

// Removing a product from the cart
remove_product(1);


<?php

require_once 'cart.php';

?>

<h2>Shopping Cart</h2>

<form action="add_to_cart.php" method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" name="product_id" id="product_id"><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity"><br><br>
    <input type="submit" value="Add to Cart">
</form>

<form action="update_quantity.php" method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" name="product_id" id="product_id"><br><br>
    <label for="new_quantity">New Quantity:</label>
    <input type="number" name="new_quantity" id="new_quantity"><br><br>
    <input type="submit" value="Update Quantity">
</form>

<form action="remove_product.php" method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" name="product_id" id="product_id"><br><br>
    <input type="submit" value="Remove Product">
</form>

<?php
// Display cart contents
view_cart();
?>


<?php

require_once 'cart.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

// For other operations like update_quantity and remove_product, follow similar logic.


// Configuration
require_once 'config.php';

// Database Connection
$db = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

class Cart {
    private $db;

    public function __construct() {
        $this->db = $GLOBALS['db'];
    }

    // Get all products from database
    public function getProducts() {
        $query = "SELECT * FROM products";
        $result = $this->db->query($query);
        return $result;
    }

    // Add product to cart
    public function addProduct($user_id, $product_id) {
        $quantity = 1; // Default quantity

        // Check if product is already in cart
        $existingProductQuery = "SELECT * FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $existingResult = $this->db->query($existingProductQuery);

        if ($existingResult->num_rows > 0) {
            // Update existing product quantity
            $updateQuantityQuery = "UPDATE carts SET quantity = quantity + '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $this->db->query($updateQuantityQuery);
        } else {
            // Insert new product into cart
            $insertProductQuery = "INSERT INTO carts (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
            $this->db->query($insertProductQuery);
        }
    }

    // Remove product from cart
    public function removeProduct($user_id, $product_id) {
        // Check if product exists in cart
        $existingProductQuery = "SELECT * FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $existingResult = $this->db->query($existingProductQuery);

        if ($existingResult->num_rows > 0) {
            // Delete product from cart
            $deleteProductQuery = "DELETE FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $this->db->query($deleteProductQuery);
        }
    }

    // Update product quantity in cart
    public function updateQuantity($user_id, $product_id, $new_quantity) {
        // Check if product exists in cart and is being updated with a valid new quantity
        if ($existingResult = $this->db->query("SELECT * FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'")) {
            if ($existingResult->num_rows > 0 && (int)$new_quantity >= 1) {
                // Update product quantity in cart
                $updateQuantityQuery = "UPDATE carts SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
                $this->db->query($updateQuantityQuery);
            }
        }
    }

    // Calculate total cost of cart
    public function calculateTotalCost() {
        $totalCost = 0;

        // Query cart for products and their quantities
        if ($cartProducts = $this->db->query("SELECT product_id, quantity FROM carts WHERE user_id = '$user_id'")) {
            while ($row = $cartProducts->fetch_assoc()) {
                // Fetch corresponding product price from database
                $productPriceQuery = "SELECT price FROM products WHERE id = '" . (int)$row['product_id'] . "'";
                if ($priceResult = $this->db->query($productPriceQuery)) {
                    if ($priceRow = $priceResult->fetch_assoc()) {
                        // Add product cost to total cost
                        $totalCost += (float)$priceRow['price'] * $row['quantity'];
                    }
                }
            }

            return $totalCost;
        } else {
            return null; // No products in cart
        }
    }
}


require_once 'cart.php';

// Initialize Cart class instance
$cart = new Cart();

// User ID
$user_id = 1;

// Product IDs to add/remove/update
$product_ids = array(1, 2, 3);

// Quantities for products 2 and 3 (optional)
$new_quantities = array(null, 2, 3);

// Add/Remove products from cart
foreach ($product_ids as $i => $id) {
    if ($new_quantities[$i]) {
        // Update quantity
        $cart->updateQuantity($user_id, $id, $new_quantities[$i]);
    } else {
        // Add or remove product based on existence in cart
        if (isset($existingProducts[$id])) {
            // Remove product from cart
            $cart->removeProduct($user_id, $id);
        } else {
            // Add product to cart
            $cart->addProduct($user_id, $id);
        }
    }
}

// Calculate total cost of cart
$totalCost = $cart->calculateTotalCost();

echo "Total Cost: $" . number_format((float)$totalCost, 2);

// Display products in cart (optional)
if ($cartProducts = $cart->db->query("SELECT * FROM carts WHERE user_id = '$user_id'")) {
    echo "<ul>";
    while ($row = $cartProducts->fetch_assoc()) {
        echo "<li>Product ID: " . $row['product_id'] . ", Quantity: " . $row['quantity'] . "</li>";
    }
    echo "</ul>";
}


class Cart {
  private $userId;
  private $products;

  public function __construct($userId = null) {
    if ($userId !== null) {
      $this->userId = $userId;
      $this->loadProducts();
    } else {
      $this->products = array();
    }
  }

  public function loadProducts() {
    global $db; // assume a database connection is established
    $query = "SELECT * FROM cart WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $this->userId);
    $stmt->execute();
    $this->products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addProduct($productId, $quantity) {
    global $db; // assume a database connection is established
    $query = "INSERT INTO cart (user_id, product_id, quantity)
              VALUES (:user_id, :product_id, :quantity)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $this->userId);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
  }

  public function removeProduct($productId) {
    global $db; // assume a database connection is established
    $query = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $this->userId);
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();
  }

  public function updateQuantity($productId, $quantity) {
    global $db; // assume a database connection is established
    $query = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $this->userId);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
  }

  public function getTotalCost() {
    global $db; // assume a database connection is established
    $query = "SELECT SUM(c.quantity * p.price) AS total_cost FROM cart c
              INNER JOIN products p ON c.product_id = p.id WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $this->userId);
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  public function getProducts() {
    return $this->products;
  }
}


// Create a new cart instance for user ID 1
$cart = new Cart(1);

// Add products to the cart
$cart->addProduct(1, 2); // product ID 1 with quantity 2
$cart->addProduct(2, 3); // product ID 2 with quantity 3

// Remove a product from the cart
$cart->removeProduct(2);

// Update the quantity of a product in the cart
$cart->updateQuantity(1, 4);

// Get the total cost of the products in the cart
$totalCost = $cart->getTotalCost();

// Print the products in the cart
print_r($cart->getProducts());


class Cart {
  private $items = array();

  public function add_item($product_id, $quantity) {
    if (array_key_exists($product_id, $this->items)) {
      $this->items[$product_id] += $quantity;
    } else {
      $this->items[$product_id] = $quantity;
    }
  }

  public function remove_item($product_id) {
    unset($this->items[$product_id]);
  }

  public function get_items() {
    return $this->items;
  }

  public function get_total() {
    $total = 0;
    foreach ($this->items as $product_id => $quantity) {
      // assume we have a function to get the price of a product
      $price = get_product_price($product_id);
      $total += $price * $quantity;
    }
    return $total;
  }

  public function display_cart() {
    echo "<h2>Shopping Cart</h2>";
    foreach ($this->items as $product_id => $quantity) {
      // assume we have a function to get the name and price of a product
      $name = get_product_name($product_id);
      $price = get_product_price($product_id);
      echo "Product: $name, Quantity: $quantity, Price: $" . number_format($price, 2) . "<br>";
    }
    echo "<p>Total: $" . number_format($this->get_total(), 2) . "</p>";
  }
}


$cart = new Cart();

// Add some products to the cart
$cart->add_item(1, 2); // add 2 of product 1
$cart->add_item(3, 1); // add 1 of product 3

// Display the cart
$cart->display_cart();


class Cart {
  private $db;

  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'username', 'password');
  }

  public function add_item($product_id, $quantity) {
    $stmt = $this->db->prepare("INSERT INTO cart (product_id, quantity) VALUES (:product_id, :quantity)");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
  }

  public function remove_item($product_id) {
    $this->db->query("DELETE FROM cart WHERE product_id = '$product_id'");
  }

  // ...
}


<?php
// Initialize an empty array to store cart data
$cart = [];

// Function to add item to cart
function addItem($item_id, $quantity) {
    global $cart;
    
    // Check if item is already in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, update quantity
            $item['quantity'] += $quantity;
            return true;
        }
    }
    
    // If not, add new item to cart
    array_push($cart, ['id' => $item_id, 'name' => getitemName($item_id), 'price' => getItemPrice($item_id), 'quantity' => $quantity]);
    
    return false;
}

// Function to view cart contents
function viewCart() {
    global $cart;
    
    // Display each item in the cart with its quantity and total cost
    echo "<h2>Shopping Cart</h2>";
    foreach ($cart as $item) {
        echo "Item: " . $item['name'] . " (x" . $item['quantity'] . ") = $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
    }
    
    // Display total cost of all items in cart
    $totalCost = array_sum(array_map(function($item) {
        return $item['price'] * $item['quantity'];
    }, $cart));
    
    echo "Total: $" . number_format($totalCost, 2);
}

// Function to remove item from cart
function removeItem($item_id) {
    global $cart;
    
    // Find and remove the specified item from the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            array_splice($cart, array_search($item, $cart), 1);
            return true;
        }
    }
    
    return false;
}

// Function to update quantity of item in cart
function updateQuantity($item_id, $new_quantity) {
    global $cart;
    
    // Find the specified item and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return true;
        }
    }
    
    return false;
}

// Simulated database functions for demonstration purposes
function getitemName($item_id) {
    // Return item name based on its ID (for example)
    switch ($item_id) {
        case 1:
            return "Apple Watch";
        case 2:
            return "iPhone 13 Pro";
        default:
            return "";
    }
}

function getItemPrice($item_id) {
    // Return price of item based on its ID (for example)
    switch ($item_id) {
        case 1:
            return 299.99;
        case 2:
            return 799.99;
        default:
            return 0.00;
    }
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                addItem($_POST['item_id'], $_POST['quantity']);
                break;
            case 'remove':
                removeItem($_POST['item_id']);
                break;
            case 'update':
                updateQuantity($_POST['item_id'], $_POST['new_quantity']);
                break;
        }
    }
}

?>
<form action="" method="post">
    <input type="hidden" name="action" value="">
    
    <!-- Add items to cart -->
    <label>Item ID:</label>
    <input type="number" name="item_id">
    <label>Quantity:</label>
    <input type="number" name="quantity">
    <input type="submit" value="Add Item">
</form>

<form action="" method="post">
    <input type="hidden" name="action" value="">
    
    <!-- View cart contents -->
    <input type="submit" value="View Cart">
</form>

<?php
// Display viewCart() function if user clicks 'View Cart' button
if (isset($_POST['action']) && $_POST['action'] == "View Cart") {
    viewCart();
}
?>


<?php
// Initialize session data if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($id, $name, $price) {
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // If existing item found, increment quantity
            $item['quantity'] += 1;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
}

// Function to remove item from cart
function removeFromCart($id) {
    // Find the index of the item in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            unset($item);
            break;
        }
    }

    // Re-index array to remove empty elements
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Function to update cart total
function updateCartTotal() {
    // Calculate total cost of items in cart
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// Display the cart contents and total cost
?>
<table>
    <tr><th>Item Name</th><th>Quantity</th><th>Price per item</th><th>Total for item</th></tr>
    <?php foreach ($_SESSION['cart'] as $item): ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

Total cost: $<?php echo number_format(updateCartTotal(), 2); ?>

<!-- Add buttons to the page for users to interact with cart -->
<form action="cart.php" method="post">
    <button type="submit" name="removeItem">Remove item from cart</button>
    <input type="hidden" name="itemIdToRemove" value="">
</form>

<form action="checkout.php" method="post">
    <button type="submit">Proceed to Checkout</button>
</form>

<?php
if (isset($_POST['removeItem'])) {
    removeFromCart($_POST['itemIdToRemove']);
}

// Display error messages for users if an item is removed from the cart
if (count($_SESSION['cart']) == 0) {
    echo "<p>Cart is empty.</p>";
}
?>


<?php
// Retrieve user's cart data from session
$cart = $_SESSION['cart'];

// Display a confirmation message before proceeding to checkout
echo "You are about to proceed with the following items in your cart:
";
foreach ($cart as $item) {
    echo "  - {$item['name']} x {$item['quantity']}
";
}

// Process payment using Stripe or another payment gateway
// (This example uses a placeholder function for demonstration purposes)
processPayment($cart);

function processPayment($cart) {
    // Implement your actual payment processing logic here
    // For this example, we'll just display a success message
    echo "Payment processed successfully!";
}
?>


<?php
// Add an item to the cart with ID 1, name "Apple", and price $2.99
addItemToCart(1, 'Apple', 2.99);
?>


<?php
// Remove the item with ID 1 from the cart
removeFromCart(1);
?>


<?php

// Initialize an empty cart array
$cart = [];

function add_item_to_cart($product_id, $quantity) {
    global $cart;

    // Check if product already exists in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // If product is found, update its quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If product not found, add it to the cart with given quantity
    $cart[] = ['id' => $product_id, 'name' => '', 'price' => 0, 'quantity' => $quantity];
}

function remove_item_from_cart($product_id) {
    global $cart;

    // Find and remove product from the cart
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }
}

function update_item_quantity($product_id, $new_quantity) {
    global $cart;

    // Find product in the cart and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

function calculate_total_cost() {
    global $cart;

    $total_cost = 0;
    // Calculate total cost by multiplying price of each item by its quantity
    foreach ($cart as $item) {
        $total_cost += $item['price'] * $item['quantity'];
    }

    return $total_cost;
}

// Example usage:
add_item_to_cart(1, 2); // Add product with id 1 to the cart in a quantity of 2
add_item_to_cart(3, 1); // Add product with id 3 to the cart in a quantity of 1

print_r($cart);

remove_item_from_cart(1);
echo "
";

update_item_quantity(3, 5);
echo "Total cost: " . calculate_total_cost();

?>


<?php
// Cart class to handle cart operations
class Cart {
  private $items;

  public function __construct() {
    $this->items = array();
  }

  // Add item to cart
  public function addItem($item_id, $quantity) {
    if (array_key_exists($item_id, $this->items)) {
      $this->items[$item_id]['quantity'] += $quantity;
    } else {
      $this->items[$item_id] = array('price' => 0, 'quantity' => $quantity);
    }
  }

  // Remove item from cart
  public function removeItem($item_id) {
    if (array_key_exists($item_id, $this->items)) {
      unset($this->items[$item_id]);
    }
  }

  // Update quantity of an item in cart
  public function updateQuantity($item_id, $quantity) {
    if (array_key_exists($item_id, $this->items)) {
      $this->items[$item_id]['quantity'] = $quantity;
    }
  }

  // Get total price of items in cart
  public function getTotalPrice() {
    $total_price = 0;
    foreach ($this->items as $item) {
      $total_price += $item['price'] * $item['quantity'];
    }
    return $total_price;
  }

  // Display cart contents
  public function displayCart() {
    echo '<h2>Shopping Cart</h2>';
    echo '<table border="1">';
    echo '<tr><th>Item ID</th><th>Price</th><th>Quantity</th></tr>';
    foreach ($this->items as $item) {
      echo '<tr><td>' . $item['item_id'] . '</td><td>' . $item['price'] . '</td><td>' . $item['quantity'] . '</td></tr>';
    }
    echo '</table>';
  }

  // Checkout
  public function checkout() {
    // Process payment and update database
    echo 'Thank you for your order!';
  }
}
?>


<?php
require_once 'cart.php';

// Create cart instance
$cart = new Cart();

// Add items to cart
$cart->addItem(1, 2);
$cart->addItem(2, 3);

// Display cart contents
$cart->displayCart();

// Get total price of items in cart
echo 'Total price: $' . $cart->getTotalPrice() . '<br>';

// Checkout
$cart->checkout();
?>


<?php
// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($item_id, $quantity) {
    global $db;
    
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $item_id) {
            // If it does, increment quantity
            $cart_item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'name' => $item_name,
        'price' => $item_price,
        'quantity' => $quantity
    );
}

// Function to display cart contents
function displayCart() {
    global $db;
    
    echo '<table border="1">';
    echo '<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>';
    
    // Loop through each item in cart
    foreach ($_SESSION['cart'] as &$cart_item) {
        $item = get_item($db, $cart_item['id']);
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>$' . number_format($item['price'], 2) . '</td>';
        echo '<td>' . $cart_item['quantity'] . '</td>';
        echo '<td>$' . number_format($cart_item['price'] * $cart_item['quantity'], 2) . '</td>';
        echo '</tr>';
    }
    
    // Calculate total cost of all items in cart
    $total = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart']));
    echo '<tr><th colspan="3">Total:</th><td>$' . number_format($total, 2) . '</td></tr>';
    
    echo '</table>';
}

// Function to remove item from cart
function removeItemFromCart($item_id) {
    global $db;
    
    // Find the index of the item in cart and unset it
    foreach (array_keys($_SESSION['cart']) as $i) {
        if ($_SESSION['cart'][$i]['id'] == $item_id) {
            unset($_SESSION['cart'][$i]);
        }
    }
}

// Function to update quantity of item in cart
function updateQuantity($item_id, $new_quantity) {
    global $db;
    
    // Find the item in cart and update its quantity
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $item_id) {
            $cart_item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to checkout (empty cart)
function checkout() {
    global $db;
    
    // Empty the cart
    unset($_SESSION['cart']);
}


<?php
require_once 'cart.php';

if (isset($_POST['add_to_cart'])) {
    addItemToCart($_POST['item_id'], $_POST['quantity']);
}

if (isset($_GET['remove_item'])) {
    removeItemFromCart($_GET['remove_item']);
}

if (isset($_POST['update_quantity'])) {
    updateQuantity($_POST['item_id'], $_POST['new_quantity']);
}

// Display cart contents
displayCart();

?>

<form action="index.php" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    <label>Item ID:</label>
    <input type="text" name="item_id"><br><br>
    <label>Quantity:</label>
    <input type="number" name="quantity"><br><br>
    <input type="submit" value="Add to Cart">
</form>

<form action="index.php" method="get">
    <input type="hidden" name="remove_item" value="">
    <label>Item ID:</label>
    <input type="text" name="remove_item"><br><br>
    <input type="submit" value="Remove Item">
</form>

<form action="index.php" method="post">
    <input type="hidden" name="update_quantity" value="1">
    <label>Item ID:</label>
    <input type="text" name="item_id"><br><br>
    <label>New Quantity:</label>
    <input type="number" name="new_quantity"><br><br>
    <input type="submit" value="Update Quantity">
</form>

<form action="index.php" method="post">
    <input type="hidden" name="checkout" value="1">
    <input type="submit" value="Checkout">
</form>


<?php
session_start();

// Initialize cart array in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    echo "<table>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Assume we have a function to get product details from database
        $product_details = get_product_details($product_id);
        echo "<tr><td>$product_details[name]</td><td>$quantity x $product_details[price] = $" . ($quantity * $product_details['price']) . "</td></tr>";
    }
    echo "</table>";
}

// Function to calculate total cost of cart
function calculate_total() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Assume we have a function to get product details from database
        $product_details = get_product_details($product_id);
        $total += ($quantity * $product_details['price']);
    }
    return $total;
}


<?php
include 'cart.php';

// Assume we have a function to display products
function display_products() {
    echo "<ul>";
    // Connect to database and retrieve product list
    // Display each product with its price and add_to_cart button
    echo "</ul>";
}

// Handle form submission to add or remove item from cart
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        add_to_cart($product_id, $quantity);
    } elseif ($_POST['action'] == 'remove') {
        $product_id = $_POST['product_id'];
        remove_from_cart($product_id);
    }
}

// Display cart contents and total cost
display_cart();
echo "Total: $" . calculate_total();

?>


class Product {
  public $id;
  public $name;
  public $price;

  function __construct($id, $name, $price) {
    $this->id = $id;
    $this->name = $name;
    $this->price = $price;
  }
}

class Cart {
  private $products = array();

  function addProduct($product) {
    array_push($this->products, $product);
  }

  function removeProduct($product_id) {
    foreach ($this->products as $key => $product) {
      if ($product->id == $product_id) {
        unset($this->products[$key]);
      }
    }
  }

  function getProducts() {
    return $this->products;
  }

  function calculateTotal() {
    $total = 0;
    foreach ($this->products as $product) {
      $total += $product->price * $this->getQuantity($product);
    }
    return $total;
  }

  private function getQuantity($product) {
    $count = 0;
    foreach ($this->products as $item) {
      if ($item == $product) {
        $count++;
      }
    }
    return $count;
  }
}

// Function to add product to cart
function addToCart($user_id, $product_id, $quantity) {
  global $db;
  $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
  $stmt = $db->prepare($query);
  $stmt->execute(array($user_id, $product_id, $quantity));
}

// Function to remove product from cart
function removeFromCart($user_id, $product_id) {
  global $db;
  $query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
  $stmt = $db->prepare($query);
  $stmt->execute(array($user_id, $product_id));
}

// Function to get products in cart
function getCartProducts($user_id) {
  global $db;
  $query = "SELECT p.id, p.name, c.quantity FROM products p JOIN cart c ON p.id = c.product_id WHERE c.user_id = ?";
  $stmt = $db->prepare($query);
  $stmt->execute(array($user_id));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to calculate total cost of items in cart
function getCartTotal($user_id) {
  global $db;
  $query = "SELECT SUM(c.quantity * p.price) AS total FROM products p JOIN cart c ON p.id = c.product_id WHERE c.user_id = ?";
  $stmt = $db->prepare($query);
  $stmt->execute(array($user_id));
  return $stmt->fetchColumn();
}


$user_id = 1;
$product_id = 1;
$quantity = 2;

addToCart($user_id, $product_id, $quantity);


$user_id = 1;
$product_id = 1;

removeFromCart($user_id, $product_id);


$user_id = 1;
$products = getCartProducts($user_id);

foreach ($products as $product) {
  echo $product['name'] . " x " . $product['quantity'];
}


$user_id = 1;
$total = getCartTotal($user_id);
echo "Total: $" . number_format($total, 2);


<?php
// Connect to the database
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Display products on the webpage
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>$row[name]</td><td>$row[price]</td></tr>";
}
echo "</table>";

// Close the database connection
$conn->close();
?>


<?php
// Connect to the database
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the user is adding a product to their cart...
if (isset($_POST['add_to_cart'])) {
    // Get the product ID and quantity from the form data
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product already exists in the cart
    $sql = "SELECT * FROM cart WHERE user_id = 1 AND product_id = '$product_id'";
    $result = $conn->query($sql);

    // If it does, update the quantity; otherwise, insert a new row
    if ($result->num_rows > 0) {
        // Update the quantity
        $sql = "UPDATE cart SET quantity = quantity + '$quantity' WHERE user_id = 1 AND product_id = '$product_id'";
    } else {
        // Insert a new row into the cart table
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (1, '$product_id', '$quantity')";
    }

    // Execute the query
    if ($conn->query($sql)) {
        echo "Product added to cart successfully!";
    } else {
        echo "Error adding product to cart: " . $conn->error;
    }
}

// Display cart contents on the webpage
$sql = "SELECT * FROM cart WHERE user_id = 1";
$result = $conn->query($sql);

echo "<h2>Cart Contents:</h2>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Quantity</th></tr>";
while ($row = $result->fetch_assoc()) {
    // Get the product name from the products table
    $product_sql = "SELECT * FROM products WHERE id = '$row[product_id]'";
    $product_result = $conn->query($product_sql);
    $product_row = $product_result->fetch_assoc();

    echo "<tr><td>$product_row[name]</td><td>$row[quantity]</td></tr>";
}
echo "</table>";

// Close the database connection
$conn->close();
?>


<?php

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function add_item_to_cart($item_id) {
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it exists, increment its quantity
            $item['quantity']++;
            return;
        }
    }

    // Add the new item to the cart with a quantity of 1
    $_SESSION['cart'][] = ['id' => $item_id, 'price' => 0.00, 'quantity' => 1];
}

function remove_item_from_cart($item_id) {
    // Filter out the item from the cart array if it exists
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($item_id) {
        return $item['id'] != $item_id;
    });
}

function update_item_quantity($item_id, $new_quantity) {
    // Find the item in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // If the item is not found, add it to the cart with the new quantity
    add_item_to_cart($item_id);
}

function calculate_total_cost() {
    $total = 0.00;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return number_format($total, 2); // round to two decimal places
}

?>


<?php require_once 'cart.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Cart</title>
</head>
<body>

    <!-- Form to add items to the cart -->
    <form action="" method="post">
        <input type="hidden" name="item_id" value="1">
        <button type="submit">Add Item 1 to Cart</button>
    </form>

    <!-- Form to remove an item from the cart -->
    <form action="" method="post">
        <input type="hidden" name="remove_item_id" value="2">
        <button type="submit">Remove Item 2 from Cart</button>
    </form>

    <!-- Form to update an item's quantity in the cart -->
    <form action="" method="post">
        <input type="hidden" name="update_item_id" value="3">
        <label for="new_quantity">New Quantity:</label>
        <input type="number" id="new_quantity" name="new_quantity">
        <button type="submit">Update Item 3's Quantity</button>
    </form>

    <!-- Display the current cart contents -->
    <?php if (!empty($_SESSION['cart'])) : ?>
        <h2>Cart Contents:</h2>
        <ul>
            <?php foreach ($_SESSION['cart'] as $item) : ?>
                <li><?php echo $item['id']; ?> (Quantity: <?php echo $item['quantity']; ?>, Price: <?php echo number_format($item['price'], 2); ?>)</li>
            <?php endforeach; ?>
        </ul>

        <!-- Display the total cost -->
        <p>Total Cost: <?php echo calculate_total_cost(); ?></p>
    <?php else : ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

</body>
</html>


<?php
// Initialize session variables
session_start();

// Define cart array to store items
$cart = [];

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $cart;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += $quantity;
                return;
            }
        }
    }
    $cart[] = ['product_id' => $product_id, 'quantity' => $quantity];
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $cart;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['product_id'] == $product_id) {
                unset($cart[$key]);
                break;
            }
        }
    }
}

// Function to update item quantity in cart
function update_quantity($product_id, $new_quantity) {
    global $cart;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] = $new_quantity;
                break;
            }
        }
    }
}

// Function to get cart contents
function get_cart_contents() {
    global $cart;
    return $_SESSION['cart'] ?? [];
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    remove_from_cart($_POST['product_id']);
}

// Update quantity in cart
if (isset($_POST['update_quantity'])) {
    update_quantity($_POST['product_id'], $_POST['new_quantity']);
}

// Display cart contents
$cart_contents = get_cart_contents();
?>
<div class="cart">
    <h2>Cart Contents</h2>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
        <?php foreach ($cart_contents as $item) : ?>
            <tr>
                <td><?= $item['product_id'] ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= calculate_total($item['price'], $item['quantity']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>Total: $<?= calculate_total(get_total_price()) ?></p>
</div>

<script>
    // Example JavaScript code to update cart quantities using AJAX
    document.addEventListener('DOMContentLoaded', function () {
        const updateQuantityForm = document.getElementById('update-quantity-form');
        updateQuantityForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const productId = this.productId.value;
            const newQuantity = parseInt(this.newQuantity.value);
            fetch('/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update_quantity', product_id: productId, new_quantity: newQuantity }),
            })
                .then((response) => response.json())
                .then((data) => console.log(data))
                .catch((error) => console.error(error));
        });
    });
</script>

<form id="add-to-cart-form" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<form id="remove-from-cart-form" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <button type="submit">Remove from Cart</button>
</form>

<form id="update-quantity-form" method="post">
    <label for="productId">Product ID:</label>
    <input type="number" id="productId" name="productId" required>
    <br>
    <label for="newQuantity">New Quantity:</label>
    <input type="number" id="newQuantity" name="newQuantity" required>
    <button type="submit">Update Quantity</button>
</form>

<?php
function calculate_total($price, $quantity) {
    return $price * $quantity;
}

function get_total_price() {
    global $cart;
    return array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $cart));
}
?>


// Define constants for cart items and session name
define('CART_ITEMS', 'cart_items');
define('CART_SESSION_NAME', 'cart_session');

// Initialize the cart array if it doesn't exist in the session
if (!isset($_SESSION[CART_SESSION_NAME])) {
    $_SESSION[CART_SESSION_NAME] = [];
}


class Product {
    public $id;
    public $name;
    public $price;

    function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}


function addProductToCart($productId, $quantity) {
    // Check if product exists in session
    foreach ($_SESSION[CART_SESSION_NAME] as &$item) {
        if ($item['product_id'] == $productId) {
            // Update quantity
            $item['quantity'] += $quantity;
            return true;
        }
    }

    // Add new item to cart
    $_SESSION[CART_SESSION_NAME][] = ['product_id' => $productId, 'quantity' => $quantity];

    return true;
}

function removeProductFromCart($productId) {
    // Get index of product in session
    foreach ($_SESSION[CART_SESSION_NAME] as &$item) {
        if ($item['product_id'] == $productId) {
            unset($item);
            break;
        }
    }

    // Re-index cart items to maintain correct indices
    $_SESSION[CART_SESSION_NAME] = array_values($_SESSION[CART_SESSION_NAME]);

    return true;
}

function updateQuantityInCart($productId, $newQuantity) {
    // Update quantity of product in session
    foreach ($_SESSION[CART_SESSION_NAME] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] = $newQuantity;
            return true;
        }
    }

    return false;
}

function displayCartContents() {
    // Display contents of cart
    echo "Cart Contents:
";
    foreach ($_SESSION[CART_SESSION_NAME] as $item) {
        echo "Product: " . getProductById($item['product_id'])->name . ", Quantity: " . $item['quantity'] . "
";
    }
}

function displayTotalCost() {
    // Calculate and display total cost
    $total = 0;
    foreach ($_SESSION[CART_SESSION_NAME] as $item) {
        $total += getProductById($item['product_id'])->price * $item['quantity'];
    }

    echo "Total Cost: $" . number_format($total, 2) . "
";
}

function displayAllProducts() {
    // Display all products
    global $db;
    $query = "SELECT * FROM products";
    $result = mysqli_query($db, $query);

    while ($product = mysqli_fetch_assoc($result)) {
        echo "Product ID: " . $product['id'] . ", Name: " . $product['name'] . ", Price: $" . number_format($product['price'], 2) . "
";
    }
}

function getProductById($productId) {
    global $db;
    $query = "SELECT * FROM products WHERE id = '$productId'";
    $result = mysqli_query($db, $query);

    return mysqli_fetch_assoc($result);
}


<?php
// Check if the user is logged in (optional)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the products from the database (replace with your own database connection code)
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'price' => 9.99),
    array('id' => 2, 'name' => 'Product 2', 'price' => 19.99),
    array('id' => 3, 'name' => 'Product 3', 'price' => 29.99)
);

// Get the cart contents from the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Display the cart contents
echo '<h2>Your Cart</h2>';
echo '<table>';
echo '<tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr>';

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    foreach ($products as $product) {
        if ($product['id'] == $product_id) {
            echo '<tr>';
            echo '<td>' . $product['name'] . '</td>';
            echo '<td>$' . number_format($product['price'], 2) . '</td>';
            echo '<td>' . $quantity . '</td>';
            echo '</tr>';
        }
    }
}

echo '</table>';

// Display the total cost
echo '<p>Total: $';
echo number_format(calculate_total($_SESSION['cart']), 2);
echo '</p>';

// Form to add products to cart
echo '<h2>Add Products to Cart</h2>';
echo '<form method="post">';
foreach ($products as $product) {
    echo '<input type="checkbox" name="add_' . $product['id'] . '" value="' . $product['id'] . '"> ' . $product['name'] . ' - $' . number_format($product['price'], 2) . '</br>';
}
echo '<button type="submit">Add to Cart</button>';
echo '</form>';

// Function to calculate the total cost
function calculate_total($cart_contents) {
    $total = 0;
    foreach ($cart_contents as $product_id => $quantity) {
        $product = get_product_from_database($product_id);
        if ($product['price'] !== null) {
            $total += $product['price'] * $quantity;
        }
    }
    return $total;
}

// Function to get a product from the database (replace with your own database connection code)
function get_product_from_database($id) {
    // Replace with your own database connection code
    return array('price' => 0);
}


<?php
// Get the cart contents from the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add products to cart
foreach ($_POST as $key => $value) {
    if (strpos($key, 'add_') === 0) {
        $product_id = substr($key, 4);
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 1;
        } else {
            $_SESSION['cart'][$product_id]++;
        }
    }
}

// Redirect back to cart page
header('Location: cart.php');
exit;


<?php
session_start();

// Check if the session is empty, and if so, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($productId, $quantity) {
    global $_SESSION;
    // Check if the product ID already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // If it does, increment the quantity
            $item['quantity'] += $quantity;
            return true; // Item was added successfully
        }
    }
    // If not, add the item to the cart with the specified quantity
    $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
    return false; // Item was added successfully
}

// Function to remove an item from the cart
function removeFromCart($productId) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return true; // Item was removed successfully
        }
    }
    return false; // Item not found in cart
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity;
            return true; // Quantity updated successfully
        }
    }
    return false; // Item not found in cart
}

// Function to get the total cost of the items in the cart
function getTotalCost() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        // Assume that we have a function to get the price of an item by ID
        $price = getItemPrice($item['id']);
        $total += $price * $item['quantity'];
    }
    return $total;
}

// Function to display the contents of the cart
function displayCart() {
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Product ID</th><th>Quantity</th><th>Price</th></tr>';
    foreach ($_SESSION['cart'] as $item) {
        // Assume that we have a function to get the price of an item by ID
        $price = getItemPrice($item['id']);
        echo '<tr>';
        echo '<td>' . $item['id'] . '</td>';
        echo '<td>' . $item['quantity'] . '</th>';
        echo '<td>$' . $price * $item['quantity'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Function to get the price of an item by ID (this is a placeholder, you would need to implement this function)
function getItemPrice($id) {
    // For demonstration purposes only
    return 10.99;
}
?>


<?php require_once 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart Example</title>
</head>
<body>

<form action="" method="post">
    <input type="hidden" name="productId" value="<?php echo $productId; ?>">
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<?php
if (isset($_POST['productId'])) {
    addToCart($_POST['productId'], $_POST['quantity']);
}
?>

<a href="cart.php">View Cart</a>

<?php if (isset($_SESSION['cart'])) { ?>
    <h2>Cart Contents:</h2>
    <table border="1">
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <tr>
                <td><?php echo $item['id']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo getItemPrice($item['id']) * $item['quantity']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <p>Total Cost: $<?php echo getTotalCost(); ?></p>

    <form action="" method="post">
        <input type="hidden" name="productId" value="<?php echo $productId; ?>">
        <button type="submit">Remove from Cart</button>
    </form>

    <?php foreach ($_SESSION['cart'] as $key => $item) { ?>
        <form action="" method="post">
            <input type="hidden" name="productId" value="<?php echo $item['id']; ?>">
            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>">
            <button type="submit">Update Quantity</button>
        </form>
    <?php } ?>
<?php } ?>

</body>
</html>


<?php
// Session initialization
session_start();

// Get the product ID and quantity from the form data
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Update the cart array in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    $_SESSION['cart'][$product_id] = $quantity;

    // Redirect to the cart page
    header('Location: cart.php');
    exit;
}

// Display the cart contents
if (isset($_SESSION['cart'])) {
    echo '<h1>Cart Contents:</h1>';
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Retrieve product details from database
        $product = getProductDetails($product_id);
        echo '<p>' . $product['name'] . ' x ' . $quantity . '</p>';
        echo '<p>Price: ' . $product['price'] . '</p>';
    }
} else {
    echo '<h1>Your cart is empty.</h1>';
}

// Form to add products to cart
echo '<form action="" method="post">';
echo '<input type="hidden" name="product_id" value="">';
echo '<select name="product_id">';
echo '<option value="1">Product 1</option>';
echo '<option value="2">Product 2</option>';
echo '<option value="3">Product 3</option>';
echo '</select>';
echo '<input type="number" name="quantity" value="1">';
echo '<button type="submit">Add to Cart</button>';
echo '</form>';

// Function to retrieve product details from database
function getProductDetails($product_id) {
    // Replace with your own database connection and query
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $stmt = $db->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>


<?php
// Get the product ID and quantity from the form data
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Update the cart array in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
$_SESSION['cart'][$product_id] = $quantity;

// Redirect to the cart page
header('Location: cart.php');
exit;
?>


<?php

// Initialize session
session_start();

// Check if cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($productId, $productName, $price) {
    // Get current quantity of product in cart
    $quantity = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId]['quantity'] : 0;
    
    // Update quantity of product in cart
    $_SESSION['cart'][$productId] = array(
        'name' => $productName,
        'price' => $price,
        'quantity' => $quantity + 1
    );
}

// Function to update quantity of item in cart
function updateItemInCart($productId, $newQuantity) {
    // Update quantity of product in cart
    $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
}

// Function to remove item from cart
function removeItemFromCart($productId) {
    // Remove product from cart
    unset($_SESSION['cart'][$productId]);
}

// Function to calculate total cost of items in cart
function calculateTotalCost() {
    // Initialize total cost
    $totalCost = 0;
    
    // Loop through each item in cart
    foreach ($_SESSION['cart'] as $item) {
        // Add product price times quantity to total cost
        $totalCost += $item['price'] * $item['quantity'];
    }
    
    return $totalCost;
}

// Function to display contents of cart
function displayCart() {
    // Get current cart contents
    $cartContents = $_SESSION['cart'];
    
    // Display cart contents
    echo "Cart Contents:
";
    foreach ($cartContents as $productId => $item) {
        echo "$item[name] x $item[quantity] = $" . number_format($item['price'] * $item['quantity'], 2) . "
";
    }
    
    // Display total cost
    echo "Total Cost: $" . number_format(calculateTotalCost(), 2) . "

";
}

// Add item to cart if form submitted
if (isset($_POST['add'])) {
    addItemToCart($_POST['productId'], $_POST['productName'], $_POST['price']);
} elseif (isset($_POST['update'])) {
    updateItemInCart($_POST['productId'], $_POST['newQuantity']);
} elseif (isset($_POST['remove'])) {
    removeItemFromCart($_POST['productId']);
}

// Display cart contents
displayCart();

?>


<?php
// Initialize the cart array
$cart = array();

// Function to add an item to the cart
function add_to_cart($item_id, $quantity) {
  global $cart;
  if (!isset($cart[$item_id])) {
    $cart[$item_id] = array('quantity' => 0, 'price' => 0);
  }
  $cart[$item_id]['quantity'] += $quantity;
  $cart[$item_id]['price'] = calculate_price($item_id, $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;
  unset($cart[$item_id]);
}

// Function to calculate the price of an item based on its ID and quantity
function calculate_price($item_id, $quantity) {
  // Replace this with your own database query or logic to retrieve the price
  return rand(1, 100); // Example: random price between 1 and 100
}

// Function to display the cart contents
function display_cart() {
  global $cart;
  echo '<h2>Cart Contents:</h2>';
  foreach ($cart as $item_id => $item) {
    echo "Item ID: $item_id, Quantity: $item[quantity], Price: $" . $item['price'] . "<br>";
  }
}

// Function to calculate the total cost of all items in the cart
function calculate_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'];
  }
  return $total;
}
?>


class Cart {
    private $items;

    public function __construct() {
        $this->items = array();
    }

    // Add an item to the cart
    public function add_item($product_id, $quantity) {
        if (isset($this->items[$product_id])) {
            $this->items[$product_id] += $quantity;
        } else {
            $this->items[$product_id] = $quantity;
        }
    }

    // Remove an item from the cart
    public function remove_item($product_id) {
        if (isset($this->items[$product_id])) {
            unset($this->items[$product_id]);
        }
    }

    // Get the contents of the cart
    public function get_items() {
        return $this->items;
    }

    // Calculate the total cost of the items in the cart
    public function calculate_total_cost() {
        $total = 0;
        foreach ($this->items as $product_id => $quantity) {
            // Assuming we have a database or data source for product prices
            $price = get_product_price($product_id);
            $total += $price * $quantity;
        }
        return $total;
    }

    // Print out the contents of the cart and total cost
    public function print_cart() {
        echo "Cart Contents:
";
        foreach ($this->items as $product_id => $quantity) {
            echo "$product_id: $quantity
";
        }
        echo "Total Cost: $" . $this->calculate_total_cost() . "
";
    }
}


// Create a new cart
$cart = new Cart();

// Add some products to the cart
$cart->add_item("product1", 2);
$cart->add_item("product2", 3);

// Print out the contents of the cart and total cost
$cart->print_cart();


// Assuming we have an HTML form with the following fields:
// <input type="text" name="product_id" value="...">
// <input type="number" name="quantity" value="...">

if (isset($_POST['add_to_cart'])) {
    $cart = new Cart();
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate the input
    if (!empty($product_id) && is_numeric($quantity)) {
        $cart->add_item($product_id, $quantity);
    } else {
        echo "Invalid input!";
    }
}


<?php
// Initialize the cart session variable
session_start();

// Define the products array
$products = [
    1 => ['name' => 'Product 1', 'price' => 10.99],
    2 => ['name' => 'Product 2', 'price' => 5.99],
    3 => ['name' => 'Product 3', 'price' => 7.99]
];

// Function to add product to cart
function add_to_cart($product_id, $quantity) {
    // Check if the product exists in the cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove product from cart
function remove_from_cart($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Function to update product quantity in cart
function update_quantity($product_id, $new_quantity) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
}

// Function to calculate total cost of items in cart
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        $total += $products[$product_id]['price'] * $product_data['quantity'];
    }
    return $total;
}

// Add a product to the cart (example usage)
// add_to_cart(1, 2);

// Display the current cart contents
echo "Current Cart Contents:
";
foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "$product_id: {$products[$product_id]['name']} x {$product_data['quantity']} = $" . ($products[$product_id]['price'] * $product_data['quantity']) . "
";
}

// Display the total cost of items in cart
echo "Total Cost: $" . calculate_total() . "
";

?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<form action="" method="post">
    <?php foreach ($products as $product_id => $product_data) { ?>
        <p>
            <input type="checkbox" name="add_to_cart[]" value="<?php echo $product_id; ?>">
            <label for="<?php echo $product_id; ?>"><?php echo $product_data['name']; ?></label>
        </p>
    <?php } ?>

    <p>
        <button type="submit">Add to Cart</button>
    </p>
</form>

<?php if (isset($_POST['add_to_cart'])) { ?>
    <?php foreach ($_POST['add_to_cart'] as $product_id) { ?>
        add_to_cart($product_id, 1);
    <?php } ?>
<?php } ?>

<?php echo "Current Cart Contents:
"; ?>
<?php foreach ($_SESSION['cart'] as $product_id => $product_data) { ?>
    <p><?php echo "$product_id: {$products[$product_id]['name']} x {$product_data['quantity']} = $" . ($products[$product_id]['price'] * $product_data['quantity']) . "</p>
"; ?>
<?php } ?>

<p>Total Cost: <?php echo calculate_total(); ?></p>

</body>
</html>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get current cart contents
$cart = $_SESSION['cart'];

// Display cart contents
?>

<div class="cart">
  <h2>Cart Contents:</h2>
  <table border="1">
    <tr>
      <th>Product</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Total</th>
    </tr>
    <?php
    // Display each item in the cart
    foreach ($cart as $product_id => $details) {
        echo "<tr>";
        echo "<td>" . $details['name'] . "</td>";
        echo "<td>" . $details['quantity'] . "</td>";
        echo "<td>$" . number_format($details['price'], 2) . "</td>";
        echo "<td>$" . number_format($details['total'], 2) . "</td>";
        echo "</tr>";
    }
    ?>
  </table>
</div>

<?php
// Calculate total cart value
$total = array_sum(array_column($cart, 'total'));
echo "<p>Total: $" . number_format($total, 2) . "</p>";

?>


<?php
function add_to_cart($product_id, $quantity) {
    global $cart;
    
    // Check if product is already in cart
    foreach ($cart as &$details) {
        if ($details['id'] == $product_id) {
            // If it is, update its quantity and recalculate total
            $details['quantity'] += $quantity;
            $details['total'] = $details['price'] * $details['quantity'];
            return true; // Update successful
        }
    }

    // If product not in cart, add it with new details
    $new_product = array(
        'id' => $product_id,
        'name' => get_product_name($product_id), // Function to retrieve name of product from database or elsewhere
        'price' => get_product_price($product_id), // Function to retrieve price of product from database or elsewhere
        'quantity' => $quantity,
        'total' => get_product_price($product_id) * $quantity // Calculate total immediately for new item
    );
    
    $cart[$product_id] = $new_product;
    return true; // Add successful
    
}

function remove_from_cart($product_id) {
    global $cart;
    
    unset($cart[$product_id]);
}

// Function to update cart based on form submission (e.g., quantity changes)
function update_cart() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST as $key => $value) {
            if ($key == 'remove') {
                remove_from_cart($value);
            } elseif ($key == 'quantity') {
                add_to_cart($_POST['product_id'], (int)$value); // Update quantity only, no need to recheck product existence
            }
        }
    }
}
?>


<?php
include_once "cart_controller.php";
?>

<!-- Display cart form for user input -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="product_id" value="[insert product ID here]"> <!-- Replace with actual product IDs -->
  <label>Quantity:</label>
  <input type="number" name="quantity" value="[initial quantity value]"> <!-- Replace with initial quantity for each product -->
  <button type="submit" name="add_to_cart">Add to Cart</button>
  <button type="submit" name="remove">Remove from Cart</button>
  <br><br>

  <?php
  update_cart(); // Call this after form submission to process any changes

  // Display cart button and link for adding more products
  echo "<a href='add_product.php'>Add More Products...</a>";
  ?>
</form>


<?php
include_once "cart_controller.php";
?>

<!-- Display full cart view (already shown in `cart.php`) -->
<div class="cart">
    <!-- Cart contents already displayed here, no need to repeat code -->
</div>

<p>Total: $<?php echo number_format(array_sum(array_column($cart, 'total')), 2); ?></p>


<?php

// Define the cart array to store items
$cart = [];

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $cart;
    if (!isset($cart[$item_id])) {
        $cart[$item_id] = ['quantity' => 0];
    }
    $cart[$item_id]['quantity'] += $quantity;
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $cart;
    if (isset($cart[$item_id])) {
        unset($cart[$item_id]);
    }
}

// Function to update quantity of item in cart
function update_quantity_in_cart($item_id, $new_quantity) {
    global $cart;
    if (isset($cart[$item_id])) {
        $cart[$item_id]['quantity'] = $new_quantity;
    }
}

// Function to get total cost of items in cart
function get_total_cost() {
    global $cart;
    $total_cost = 0;
    foreach ($cart as $item) {
        $total_cost += $item['price'] * $item['quantity'];
    }
    return $total_cost;
}

// Function to display cart contents
function display_cart_contents() {
    global $cart;
    echo "<h2>Cart Contents:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item ID</th><th>Quantity</th><th>Price</th></tr>";
    foreach ($cart as $item) {
        echo "<tr><td>$item_id</td><td>$item['quantity']</td><td>\$".number_format($item['price'])."</td></tr>";
    }
    echo "</table>";
}

// Add items to cart (example data)
add_item_to_cart(1, 2); // Item ID 1, quantity 2
add_item_to_cart(2, 3); // Item ID 2, quantity 3

?>


<?php require_once 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart Example</title>
</head>
<body>

<h1>Cart Example</h1>

<!-- Display cart contents -->
<?php display_cart_contents(); ?>

<!-- Add item to cart form -->
<form action="" method="post">
    <input type="hidden" name="item_id" value="<?php echo $_POST['item_id']; ?>">
    <input type="hidden" name="quantity" value="<?php echo $_POST['quantity']; ?>">
    <button type="submit">Add to Cart</button>
</form>

<!-- Update quantity form -->
<form action="" method="post">
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <label for="new_quantity">New Quantity:</label>
    <input type="number" id="new_quantity" name="new_quantity"><br><br>
    <button type="submit">Update Quantity</button>
</form>

<!-- Remove item from cart form -->
<form action="" method="post">
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <button type="submit">Remove from Cart</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
        add_item_to_cart($_POST['item_id'], $_POST['quantity']);
    } elseif (isset($_POST['item_id']) && isset($_POST['new_quantity'])) {
        update_quantity_in_cart($_POST['item_id'], $_POST['new_quantity']);
    } elseif (isset($_POST['item_id'])) {
        remove_item_from_cart($_POST['item_id']);
    }
}
?>

</body>
</html>


class Cart {
  private $items;
  private $subtotal;
  private $taxRate;

  public function __construct() {
    $this->items = array();
    $this->subtotal = 0;
    $this->taxRate = 8; // default tax rate (8% in this example)
  }

  public function addItem($item, $quantity) {
    if (isset($this->items[$item])) {
      $this->items[$item] += $quantity;
    } else {
      $this->items[$item] = $quantity;
    }
    $this->updateSubtotal();
  }

  public function removeItem($item) {
    unset($this->items[$item]);
    $this->updateSubtotal();
  }

  public function updateSubtotal() {
    $this->subtotal = 0;
    foreach ($this->items as $item => $quantity) {
      $price = getItemPrice($item); // assume a function `getItemPrice()` exists
      $this->subtotal += $price * $quantity;
    }
  }

  public function getSubtotal() {
    return $this->subtotal;
  }

  public function getTaxAmount() {
    return ($this->subtotal * $this->taxRate / 100);
  }

  public function getTotal() {
    return $this->getSubtotal() + $this->getTaxAmount();
  }
}


function getItemPrice($item) {
  // simulate retrieving item price from database
  $prices = array(
    'product1' => 19.99,
    'product2' => 29.99,
    'product3' => 39.99,
  );
  return isset($prices[$item]) ? $prices[$item] : 0;
}


// cart.php (continued)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $item = $_POST['item'];
  $quantity = $_POST['quantity'];

  if ($item && $quantity) {
    $cart->addItem($item, $quantity);
  }
}


<?php
// Initialize the cart array
$cart = [];

// Function to add item to cart
function add_to_cart($item_id, $quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    // If item is already in cart, increment quantity
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    // Add new item to cart
    $cart[$item_id] = ['price' => get_price($item_id), 'quantity' => $quantity];
  }
}

// Function to remove item from cart
function remove_from_cart($item_id) {
  global $cart;
  if (isset($cart[$item_id])) {
    // Remove item from cart
    unset($cart[$item_id]);
  }
}

// Function to get total cost of items in cart
function get_total_cost() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Function to display cart contents
function display_cart() {
  global $cart;
  ?>
  <h2>Cart Contents:</h2>
  <table>
    <tr>
      <th>Item ID</th>
      <th>Price</th>
      <th>Quantity</th>
    </tr>
    <?php foreach ($cart as $item_id => $item) { ?>
    <tr>
      <td><?= $item_id ?></td>
      <td><?= $item['price'] ?></td>
      <td><?= $item['quantity'] ?></td>
    </tr>
    <?php } ?>
  </table>
  <?php
}

// Function to update quantity of item in cart
function update_quantity($item_id, $new_quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    // Update quantity of item in cart
    $cart[$item_id]['quantity'] = $new_quantity;
  }
}
?>


<?php require 'cart.php'; ?>

<h1>Purchase Cart</h1>

<!-- Display existing items in cart -->
<div id="existing-items">
  <?php display_cart(); ?>
</div>

<!-- Add item to cart form -->
<form action="" method="post">
  <label for="item_id">Item ID:</label>
  <input type="text" id="item_id" name="item_id"><br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity"><br><br>
  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<!-- Remove item from cart form -->
<form action="" method="post">
  <label for="item_id_remove">Item ID:</label>
  <input type="text" id="item_id_remove" name="item_id"><br><br>
  <button type="submit" name="remove_from_cart">Remove from Cart</button>
</form>

<!-- Update quantity of item in cart form -->
<form action="" method="post">
  <label for="item_id_update">Item ID:</label>
  <input type="text" id="item_id_update" name="item_id"><br><br>
  <label for="new_quantity">New Quantity:</label>
  <input type="number" id="new_quantity" name="new_quantity"><br><br>
  <button type="submit" name="update_quantity">Update Quantity</button>
</form>

<!-- Display total cost of items in cart -->
<p>Total Cost: <?php echo get_total_cost(); ?></p>

<?php
if (isset($_POST['add_to_cart'])) {
  $item_id = $_POST['item_id'];
  $quantity = $_POST['quantity'];
  add_to_cart($item_id, $quantity);
}

if (isset($_POST['remove_from_cart'])) {
  $item_id = $_POST['item_id'];
  remove_from_cart($item_id);
}

if (isset($_POST['update_quantity'])) {
  $item_id = $_POST['item_id'];
  $new_quantity = $_POST['new_quantity'];
  update_quantity($item_id, $new_quantity);
}
?>


<?php
// Define prices of items
$prices = [
  'item1' => 10.99,
  'item2' => 5.99,
  'item3' => 7.99,
];

function get_price($item_id) {
  global $prices;
  return isset($prices[$item_id]) ? $prices[$item_id] : 0;
}
?>


<?php
// Initialize the session
session_start();

// Define the products array
$products = array(
    'product1' => array('name' => 'Product 1', 'price' => 19.99),
    'product2' => array('name' => 'Product 2', 'price' => 29.99),
    'product3' => array('name' => 'Product 3', 'price' => 39.99)
);

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add product to cart
function add_product_to_cart($product_id) {
    global $products;
    if (in_array($product_id, $_SESSION['cart'])) {
        echo "Product already in cart.";
    } else {
        $_SESSION['cart'][] = $product_id;
        echo "Product added to cart.";
    }
}

// Function to remove product from cart
function remove_product_from_cart($product_id) {
    global $products;
    if (in_array($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][array_search($product_id, $_SESSION['cart'])]);
        echo "Product removed from cart.";
    } else {
        echo "Product not in cart.";
    }
}

// Function to calculate total cost
function calculate_total() {
    global $products;
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id) {
        $total += $products[$product_id]['price'];
    }
    return $total;
}

// Display cart contents
echo "<h2>Cart Contents:</h2>";
foreach ($_SESSION['cart'] as $product_id) {
    echo "<p>" . $products[$product_id]['name'] . " (x1) = $" . number_format($products[$product_id]['price'], 2) . "</p>";
}

// Display total cost
echo "<p>Total Cost: $" . number_format(calculate_total(), 2) . "</p>";

// Add product to cart button
echo "<button onclick=\"add_product_to_cart('product1')\">Add Product 1</button> ";
echo "<button onclick=\"remove_product_from_cart('product1')\">Remove Product 1</button>";


<?php
// Initialize session
session_start();

// Set default values for cart
$_SESSION['cart'] = array();
$_SESSION['subtotal'] = 0;
$_SESSION['tax_rate'] = 0.08; // example tax rate of 8%
$_SESSION['shipping_rate'] = 5.00; // example shipping rate

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = $quantity;
    }

    calculate_subtotal();
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;

    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);

        calculate_subtotal();
    }
}

// Function to update quantity of an item in the cart
function update_quantity_in_cart($item_id, $new_quantity) {
    global $_SESSION;

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = $new_quantity;

        calculate_subtotal();
    }
}

// Function to calculate subtotal and total prices
function calculate_subtotal() {
    global $_SESSION, $_products; // assume products are stored in an array

    $_SESSION['subtotal'] = 0;
    foreach ($_SESSION['cart'] as $item_id => $quantity) {
        $price = $_products[$item_id]['price'];
        $discounted_price = $_products[$item_id]['price'] * (1 - $_products[$item_id]['discount']);
        $_SESSION['subtotal'] += $quantity * $discounted_price;
    }

    // calculate tax and shipping
    $_SESSION['tax'] = $_SESSION['subtotal'] * $_SESSION['tax_rate'];
    $_SESSION['shipping'] = $_SESSION['shipping_rate'];

    // calculate total price
    $_SESSION['total'] = $_SESSION['subtotal'] + $_SESSION['tax'] + $_SESSION['shipping'];
}

// Function to display cart contents
function display_cart_contents() {
    global $_SESSION;

    echo "<h2>Cart Contents:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item ID</th><th>Quantity</th><th>Price</th></tr>";

    foreach ($_SESSION['cart'] as $item_id => $quantity) {
        $price = $_products[$item_id]['price'];
        $discounted_price = $price * (1 - $_products[$item_id]['discount']);
        echo "<tr>";
        echo "<td>$item_id</td>";
        echo "<td>$quantity</td>";
        echo "<td>\$$discounted_price</td>";
        echo "</tr>";
    }

    echo "</table>";

    calculate_subtotal();
}

// Example products array
$_products = array(
    'product1' => array('price' => 10.99, 'discount' => 0),
    'product2' => array('price' => 5.99, 'discount' => 0),
    'product3' => array('price' => 7.99, 'discount' => 0.1) // 10% discount
);

// Display cart contents initially
display_cart_contents();
?>


<?php include 'cart.php'; ?>

<form action="cart.php" method="post">
    <h2>Add Item to Cart:</h2>
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity"><br><br>
    <input type="submit" value="Add to Cart">
</form>

<?php
if (isset($_POST['item_id'])) {
    add_item_to_cart($_POST['item_id'], $_POST['quantity']);
    display_cart_contents();
}
?>


<?php include 'cart.php'; ?>

<form action="remove_item.php" method="post">
    <h2>Remove Item from Cart:</h2>
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <input type="submit" value="Remove from Cart">
</form>

<?php
if (isset($_POST['item_id'])) {
    remove_item_from_cart($_POST['item_id']);
    display_cart_contents();
}
?>


<?php include 'cart.php'; ?>

<form action="update_quantity.php" method="post">
    <h2>Update Quantity of Item in Cart:</h2>
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <label for="quantity">New Quantity:</label>
    <input type="number" id="quantity" name="quantity"><br><br>
    <input type="submit" value="Update Quantity">
</form>

<?php
if (isset($_POST['item_id'])) {
    update_quantity_in_cart($_POST['item_id'], $_POST['quantity']);
    display_cart_contents();
}
?>


<?php
// Initialize the cart array
$cart = array();

// Function to add item to cart
function add_item($item_id, $quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    $cart[$item_id] += $quantity;
  } else {
    $cart[$item_id] = $quantity;
  }
}

// Function to remove item from cart
function remove_item($item_id) {
  global $cart;
  unset($cart[$item_id]);
}

// Function to update quantity of item in cart
function update_quantity($item_id, $new_quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    $cart[$item_id] = $new_quantity;
  }
}

// Function to get total cost of items in cart
function get_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item_id => $quantity) {
    // Assume prices are stored in a separate array for simplicity
    $prices = array('product1' => 9.99, 'product2' => 19.99, 'product3' => 29.99);
    if (isset($prices[$item_id])) {
      $total += $prices[$item_id] * $quantity;
    }
  }
  return $total;
}

// Function to display cart contents
function display_cart() {
  global $cart;
  echo "<h2>Cart Contents:</h2>";
  foreach ($cart as $item_id => $quantity) {
    // Assume item names are stored in a separate array for simplicity
    $names = array('product1' => 'Apple Watch', 'product2' => 'Samsung TV', 'product3' => 'Nintendo Switch');
    if (isset($names[$item_id])) {
      echo "<p>$quantity x " . $names[$item_id] . "</p>";
    }
  }
}

// Example usage:
// Add items to cart
add_item('product1', 2);
add_item('product2', 3);

// Display cart contents
display_cart();

// Get total cost of items in cart
echo "<p>Total: $" . get_total() . "</p>";

// Remove item from cart
remove_item('product2');

// Update quantity of item in cart
update_quantity('product1', 4);


add_item('product1', 2);


remove_item('product1');


update_quantity('product1', 4);


$total = get_total();
echo "Total: $" . $total;


display_cart();


<?php
// Initialize session
session_start();

// Check if cart is empty, if so set it to an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    // Check if product is already in cart, if so increment quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return true;
        }
    }
    // If not, add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return true;
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
    return false;
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true;
        }
    }
    return false;
}

// Function to get total cost of items in cart
function get_total_cost() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        // Assuming products table has a column called 'price'
        $query = "SELECT price FROM products WHERE id = '$item[id]'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $total += $row['price'] * $item['quantity'];
    }
    return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    remove_from_cart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
    update_quantity($_POST['product_id'], $_POST['new_quantity']);
}

?>


<?php include 'cart.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Cart</title>
</head>
<body>
    <!-- Display cart contents -->
    <?php if (!empty($_SESSION['cart'])) : ?>
        <h1>Cart Contents:</h1>
        <ul>
            <?php foreach ($_SESSION['cart'] as $item) : ?>
                <li><?= $item['id'] ?> x <?= $item['quantity'] ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <!-- Form to add items to cart -->
    <form action="" method="post">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id"><br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity"><br><br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

    <!-- Form to remove items from cart -->
    <form action="" method="post">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id"><br><br>
        <input type="submit" name="remove_from_cart" value="Remove from Cart">
    </form>

    <!-- Form to update quantity of items in cart -->
    <form action="" method="post">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id"><br><br>
        <label for="new_quantity">New Quantity:</label>
        <input type="number" id="new_quantity" name="new_quantity"><br><br>
        <input type="submit" name="update_quantity" value="Update Quantity">
    </form>

    <!-- Display total cost -->
    <?php echo 'Total Cost: $' . get_total_cost(); ?>
</body>
</html>


<?php
// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $quantity) {
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // Update quantity if item exists
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to update item in cart
function updateItemInCart($item_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to remove item from cart
function removeFromCart($item_id) {
    foreach (array_keys($_SESSION['cart']) as $index) {
        if ($_SESSION['cart'][$index]['id'] == $item_id) {
            unset($_SESSION['cart'][$index]);
            break;
        }
    }
}

// Function to calculate total cost of cart
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        // Assuming item prices are stored in a database or array
        $price = getPrice($item['id']); // Replace with actual price retrieval logic
        $total += $price * $item['quantity'];
    }
    return $total;
}

// Function to display cart contents
function displayCart() {
    ?>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    <?php
    foreach ($_SESSION['cart'] as $item) {
        ?>
        <tr>
            <td><?= getItemName($item['id']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= getPrice($item['id']) ?></td>
            <td><?= $item['quantity'] * getPrice($item['id']) ?></td>
        </tr>
    <?php
    }
    ?>
    </table>
    <?php
}

// Function to display cart total
function displayTotal() {
    echo "Subtotal: $" . calculateTotal();
}


// Add item to cart
addToCart(1, 2); // Add item with ID 1 and quantity 2

// Display cart contents
displayCart();

// Update item in cart
updateItemInCart(1, 3);

// Remove item from cart
removeFromCart(1);

// Display cart total
displayTotal();


<?php
// Initialize the cart array
$cart = [];

// Function to add item to cart
function add_item($item_id, $quantity) {
  global $cart;
  if (!isset($cart[$item_id])) {
    $cart[$item_id] = ['quantity' => $quantity];
  } else {
    $cart[$item_id]['quantity'] += $quantity;
  }
}

// Function to remove item from cart
function remove_item($item_id) {
  global $cart;
  unset($cart[$item_id]);
}

// Function to update quantity of item in cart
function update_quantity($item_id, $new_quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $new_quantity;
  }
}

// Function to calculate total cost
function calculate_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Display cart contents
function display_cart() {
  global $cart;
  echo '<h2>Cart Contents:</h2>';
  echo '<ul>';
  foreach ($cart as $item_id => $item) {
    echo '<li>' . $item_id . ' x' . $item['quantity'] . '</li>';
  }
  echo '</ul>';
}

// Display cart summary
function display_cart_summary() {
  global $cart;
  echo '<h2>Cart Summary:</h2>';
  echo 'Total Items: ' . count($cart) . '<br>';
  echo 'Subtotal: $' . calculate_total() . '<br>';
}
?>


<?php
// Example item data (replace with actual database or API call)
$item_data = [
  1 => ['price' => 9.99, 'name' => 'Example Item'],
  2 => ['price' => 19.99, 'name' => 'Another Example Item']
];

// Function to retrieve item price from cart ID
function get_item_price($item_id) {
  global $item_data;
  return isset($item_data[$item_id]) ? $item_data[$item_id]['price'] : 0;
}

// Function to calculate total cost with tax (example)
function calculate_total_with_tax() {
  global $cart;
  $total = calculate_total();
  return $total + ($total * 0.08); // Example: 8% sales tax
}
?>


<?php
// session_start() starts a new session or resumes an existing one
session_start();

// If there's no cart in the session, create a new one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($product_id) {
    global $wpdb;
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['product_id'] == $product_id) {
            return; // Product is already in cart, so do nothing
        }
    }
    
    // Get product details from database
    $product = get_product($wpdb, $product_id);
    
    // Add item to cart array with quantity set to 1
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => 1);
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    global $wpdb;
    // Check if product is in cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // Product removed from cart, so do nothing
        }
    }
}

// Function to view cart contents
function viewCart() {
    global $wpdb;
    
    // Get product details for each item in cart
    foreach ($_SESSION['cart'] as &$value) {
        $product = get_product($wpdb, $value['product_id']);
        
        // Add price and quantity to item array
        $value['price'] = $product['price'];
        $value['quantity'] = $value['quantity'];
    }
}

// Function to calculate total cost of items in cart
function calculateTotalCost() {
    global $wpdb;
    
    // Initialize total cost to 0
    $total_cost = 0;
    
    // Loop through each item in cart and add price multiplied by quantity to total cost
    foreach ($_SESSION['cart'] as &$value) {
        $total_cost += $value['price'] * $value['quantity'];
    }
    
    return $total_cost;
}

// Function to display cart contents
function displayCart() {
    global $wpdb;
    
    // View cart contents
    viewCart();
    
    // Calculate total cost of items in cart
    $total_cost = calculateTotalCost();
    
    ?>
    <h1>Cart Contents</h1>
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $value) { ?>
            <tr>
                <td><?php echo $value['product_id']; ?></td>
                <td><?php echo $value['quantity']; ?></td>
                <td><?php echo $value['price']; ?></td>
            </tr>
        <?php } ?>
    </table>
    
    <p>Total cost: <?php echo $total_cost; ?></p>
    <?php
}

// Function to display add item form
function displayAddItemForm() {
    global $wpdb;
    
    // Get products from database
    $products = get_products($wpdb);
    
    ?>
    <h1>Add Item</h1>
    <form action="" method="post">
        <select name="product_id">
            <?php foreach ($products as $product) { ?>
                <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
            <?php } ?>
        </select>
        
        <input type="submit" value="Add to Cart">
    </form>
    <?php
}

// Function to display remove item form
function displayRemoveItemForm() {
    global $wpdb;
    
    // Get products from database
    $products = get_products($wpdb);
    
    ?>
    <h1>Remove Item</h1>
    <form action="" method="post">
        <select name="product_id">
            <?php foreach ($products as $product) { ?>
                <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
            <?php } ?>
        </select>
        
        <input type="submit" value="Remove from Cart">
    </form>
    <?php
}

// Display cart contents and add item form
displayCart();
displayAddItemForm();

?>


<?php

// Function to get product details from database
function get_product($wpdb, $product_id) {
    global $wpdb;
    
    // Query database for product with given ID
    $result = $wpdb->get_row("SELECT * FROM products WHERE id = '$product_id'");
    
    return $result;
}

// Function to get all products from database
function get_products($wpdb) {
    global $wpdb;
    
    // Query database for all products
    $results = $wpdb->get_results("SELECT * FROM products");
    
    return $results;
}


class Cart {
  private $cartId;
  private $items;

  public function __construct($cartId = null) {
    if ($cartId) {
      $this->cartId = $cartId;
    } else {
      // Generate a new cart ID if none is provided
      $this->cartId = uniqid();
    }
    $this->items = array();
  }

  public function addItem($productId, $quantity) {
    if (!isset($this->items[$productId])) {
      $this->items[$productId] = array('quantity' => 0);
    }
    $this->items[$productId]['quantity'] += $quantity;
    // Insert the item into the cart_items table
    db::insert('cart_items', array(
      'product_id' => $productId,
      'quantity' => $quantity,
      'cart_id' => $this->cartId
    ));
  }

  public function removeItem($productId) {
    if (isset($this->items[$productId])) {
      unset($this->items[$productId]);
      // Delete the item from the cart_items table
      db::delete('cart_items', array(
        'product_id' => $productId,
        'cart_id' => $this->cartId
      ));
    }
  }

  public function getItems() {
    return $this->items;
  }

  public function getCartTotal() {
    $total = 0;
    foreach ($this->items as $item) {
      $total += $item['quantity'] * db::select('products', array(
        'id' => $item['product_id']
      ))[0]['price'];
    }
    return $total;
  }

  public function isEmpty() {
    return empty($this->items);
  }
}


class db {
  private static $connection;

  public static function connect() {
    self::$connection = new mysqli('localhost', 'username', 'password', 'database');
    if (self::$connection->connect_error) {
      die('Connection failed: ' . self::$connection->connect_error);
    }
  }

  public static function select($table, $conditions) {
    $query = "SELECT * FROM $table WHERE ";
    foreach ($conditions as $key => $value) {
      $query .= "$key = '$value' AND ";
    }
    $query = rtrim($query, ' AND ');
    return self::$connection->query($query)->fetch_assoc();
  }

  public static function insert($table, $data) {
    $fields = implode(', ', array_keys($data));
    $values = implode(', ', array_map(function($v){ return "'$v'"; }, array_values($data)));
    return self::$connection->query("INSERT INTO $table ($fields) VALUES ($values)");
  }

  public static function delete($table, $conditions) {
    $query = "DELETE FROM $table WHERE ";
    foreach ($conditions as $key => $value) {
      $query .= "$key = '$value' AND ";
    }
    $query = rtrim($query, ' AND ');
    return self::$connection->query($query);
  }
}


$cart = new Cart();
$cart->addItem(1, 2); // Add product with ID 1 and quantity 2 to the cart
$cart->addItem(3, 1); // Add product with ID 3 and quantity 1 to the cart

print_r($cart->getItems()); // Output: Array ( [1] => Array ( [product_id] => 1 [quantity] => 2 ) [3] => Array ( [product_id] => 3 [quantity] => 1 ) )

echo $cart->getCartTotal(); // Output: Total price of the cart

$cart->removeItem(1); // Remove product with ID 1 from the cart

print_r($cart->getItems()); // Output: Array ( [3] => Array ( [product_id] => 3 [quantity] => 1 ) )

echo $cart->isEmpty(); // Output: boolean true


<?php

// Set session variables
session_start();
$_SESSION['cart'] = array();

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array('quantity' => 0);
  }
  $_SESSION['cart'][$product_id]['quantity'] += $quantity;
}

// Function to remove item from cart
function remove_from_cart($product_id) {
  unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get contents of cart
function get_cart_contents() {
  return $_SESSION['cart'];
}

?>


<?php include 'cart.php'; ?>

<h1>Cart Contents:</h1>

<ul>
  <?php foreach (get_cart_contents() as $product_id => $item) { ?>
    <li>
      <?php echo get_product_name($product_id); ?> x <?php echo $item['quantity']; ?>
      (<a href="?remove=<?php echo $product_id; ?>">Remove</a>)
      (<a href="?update=<?php echo $product_id; ?>">Update Quantity</a>)
    </li>
  <?php } ?>
</ul>

<h2>Total:</h2>
<?php echo calculate_total(); ?>

<a href="checkout.php">Checkout</a>

<?php
// Assume we have a function to get the product name and price from the database
function get_product_name($product_id) {
  // Query database for product name
}

function calculate_total() {
  $total = 0;
  foreach (get_cart_contents() as $item) {
    $total += $item['quantity'] * get_product_price($item['product_id']);
  }
  return $total;
}
?>


<?php include 'cart.php'; ?>

<?php
$product_id = $_GET['id'];
$quantity = $_POST['quantity'];

if ($product_id && $quantity) {
  add_to_cart($product_id, $quantity);
} else {
  echo "Error adding to cart.";
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="id" value="<?php echo $product_id; ?>">
  Quantity: <input type="text" name="quantity" value="<?php echo $quantity; ?>">
  <input type="submit" value="Add to Cart">
</form>


<?php include 'cart.php'; ?>

<?php
$product_id = $_GET['id'];

if ($product_id) {
  remove_from_cart($product_id);
} else {
  echo "Error removing from cart.";
}
?>


<?php include 'cart.php'; ?>

<?php
$product_id = $_GET['id'];
$new_quantity = $_POST['quantity'];

if ($product_id && $new_quantity) {
  update_quantity($product_id, $new_quantity);
} else {
  echo "Error updating quantity.";
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="id" value="<?php echo $product_id; ?>">
  Quantity: <input type="text" name="quantity" value="<?php echo $new_quantity; ?>">
  <input type="submit" value="Update Quantity">
</form>


<?php
// Initialize session
session_start();

// Define cart array to store items
$cart = array();

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
  global $cart;
  if (!isset($cart[$item_id])) {
    $cart[$item_id] = array('quantity' => $quantity);
  } else {
    $cart[$item_id]['quantity'] += $quantity;
  }
}

// Function to update cart item quantity
function update_cart_item_quantity($item_id, $new_quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $new_quantity;
  }
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
  global $cart;
  unset($cart[$item_id]);
}

// Function to calculate total cost of items in cart
function calculate_total_cost() {
  global $cart;
  $total = 0;
  foreach ($cart as $item_id => $item) {
    // Retrieve item price from database or a cache (e.g. using `$items = array(1 => 'Item 1' => 9.99, 2 => 'Item 2' => 19.99);`)
    $item_price = $items[$item_id];
    $total += $item_price * $item['quantity'];
  }
  return $total;
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
  $item_id = $_POST['item_id'];
  $quantity = $_POST['quantity'];
  add_item_to_cart($item_id, $quantity);
}

// Update cart item quantity
if (isset($_POST['update_quantity'])) {
  $item_id = $_POST['item_id'];
  $new_quantity = $_POST['new_quantity'];
  update_cart_item_quantity($item_id, $new_quantity);
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
  $item_id = $_POST['item_id'];
  remove_item_from_cart($item_id);
}
?>


<?php
// Retrieve item prices from database or a cache (e.g. using `$items = array(1 => 'Item 1' => 9.99, 2 => 'Item 2' => 19.99);`)
$items = array(
  1 => 'Item 1' => 9.99,
  2 => 'Item 2' => 19.99
);

// Retrieve item IDs from database or a cache (e.g. using `$item_ids = array(1, 2);`)
$item_ids = array(1, 2);
?>


<?php

// Initialize the cart array
$cart = array();

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
  global $cart;
  
  // Check if item is already in cart
  foreach ($cart as $key => $value) {
    if ($key == $item_id) {
      // If item is already in cart, update quantity
      $cart[$key]['quantity'] += $quantity;
      break;
    }
  }

  // Add new item to cart if not already present
  if (!isset($cart[$item_id])) {
    $cart[$item_id] = array('name' => '', 'price' => 0, 'quantity' => $quantity);
  }
}

// Function to update quantity of an item in the cart
function update_item_quantity($item_id, $new_quantity) {
  global $cart;
  
  // Check if item is already in cart
  foreach ($cart as $key => $value) {
    if ($key == $item_id) {
      // Update quantity
      $cart[$item_id]['quantity'] = $new_quantity;
      break;
    }
  }
}

// Function to remove an item from the cart
function remove_item_from_cart($item_id) {
  global $cart;
  
  // Remove item from cart
  unset($cart[$item_id]);
}

// Function to calculate total cost of items in cart
function calculate_total_cost() {
  global $cart;
  
  $total_cost = 0;
  
  foreach ($cart as $key => $value) {
    $total_cost += $value['price'] * $value['quantity'];
  }
  
  return $total_cost;
}

// Function to display cart contents
function display_cart_contents() {
  global $cart;
  
  echo '<table>';
  echo '<tr><th>Item</th><th>Price</th><th>Quantity</th></tr>';
  
  foreach ($cart as $key => $value) {
    echo '<tr>';
    echo '<td>' . $value['name'] . '</td>';
    echo '<td>$' . number_format($value['price'], 2) . '</td>';
    echo '<td>' . $value['quantity'] . '</td>';
    echo '</tr>';
  }
  
  echo '</table>';
}

// Function to display cart total cost
function display_cart_total_cost() {
  global $cart;
  
  echo 'Total Cost: $' . number_format(calculate_total_cost(), 2);
}

?>


<?php

include 'cart.php';

// Assume we have a database with products and their prices
$products = array(
  1 => array('name' => 'Product A', 'price' => 9.99),
  2 => array('name' => 'Product B', 'price' => 19.99),
  3 => array('name' => 'Product C', 'price' => 29.99)
);

// Display products
echo '<h1>Products</h1>';
echo '<ul>';
foreach ($products as $key => $value) {
  echo '<li><a href="#" onclick="add_item_to_cart(' . $key . ', 1)">Add to Cart (' . $value['name'] . ' - $' . number_format($value['price'], 2) . ')</a></li>';
}
echo '</ul>';

// Display cart
echo '<h1>Cart</h1>';
display_cart_contents();
display_cart_total_cost();

?>


<?php

// Initialize cart array
$cart = [];

// Function to add item to cart
function addToCart($itemId, $itemName, $itemPrice) {
  global $cart;
  if (array_key_exists($itemId, $cart)) {
    // Item already in cart, increment quantity
    $cart[$itemId]['quantity']++;
  } else {
    // New item, add it to cart
    $cart[$itemId] = ['name' => $itemName, 'price' => $itemPrice, 'quantity' => 1];
  }
}

// Function to remove item from cart
function removeFromCart($itemId) {
  global $cart;
  if (array_key_exists($itemId, $cart)) {
    unset($cart[$itemId]);
  }
}

// Function to update quantity of item in cart
function updateQuantity($itemId, $newQuantity) {
  global $cart;
  if (array_key_exists($itemId, $cart)) {
    $cart[$itemId]['quantity'] = $newQuantity;
  }
}

// Function to calculate total cost of items in cart
function calculateTotal() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

?>


<?php
require_once 'cart.php';

// Check if the cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Display the cart contents
cartDisplay();

// Add items to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['id'] == $product_id) {
            // Increase quantity of existing item
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            break;
        }
    }

    // Add new product to the cart if not already present
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('id' => $product_id, 'quantity' => $quantity);
    }

    header('Location: index.php');
}

// Remove items from the cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    header('Location: index.php');
}

// Update quantity of items in the cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['id'] == $product_id) {
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;
            break;
        }
    }

    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
</head>
<body>
    <h1>Cart Contents:</h1>

    <?php cartDisplay(); ?>

    <form action="" method="post">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id"><br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity"><br><br>

        <!-- Add item to cart button -->
        <input type="submit" value="Add to Cart" name="add_to_cart">
    </form>

    <?php
    // Display remove and update buttons for each item in the cart
    foreach ($_SESSION['cart'] as $product) {
        ?>
        <h2>Product ID: <?= $product['id']; ?></h2>
        Quantity: <?= $product['quantity']; ?><br><br>
        <!-- Remove item from cart button -->
        <input type="submit" value="Remove from Cart" name="remove_from_cart" formaction="" product_id="<?= $product['id']; ?>">
        <!-- Update quantity of item in cart button -->
        <input type="submit" value="Update Quantity" name="update_quantity" formaction="" product_id="<?= $product['id']; ?>" quantity="<?= $product['quantity']; ?>">
    <?php } ?>
</body>
</html>


<?php
function cartDisplay() {
    // Display the contents of the cart
    foreach ($_SESSION['cart'] as $product) {
        echo '<h2>Product ID: ' . $product['id'] . '</h2>';
        echo 'Quantity: ' . $product['quantity'] . '<br><br>';
    }
}
?>


<?php
// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($id, $name, $price) {
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // Increase the quantity of the item
            $item['quantity'] += 1;
            return;
        }
    }

    // Add new item to the cart
    $_SESSION['cart'][] = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
}

// Function to update an item in the cart
function updateItemInCart($id, $newQuantity) {
    // Find the index of the item in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // Update the quantity of the item
            $item['quantity'] = $newQuantity;
            return;
        }
    }

    // Item not found, do nothing
}

// Function to remove an item from the cart
function removeItemFromCart($id) {
    // Find the index of the item in the cart
    foreach (array_keys($_SESSION['cart']) as $index) {
        if ($_SESSION['cart'][$index]['id'] == $id) {
            // Remove the item from the cart
            unset($_SESSION['cart'][$index]);
            return;
        }
    }

    // Item not found, do nothing
}

// Function to calculate the total cost of the cart items
function calculateTotalCost() {
    $totalCost = 0;

    foreach ($_SESSION['cart'] as &$item) {
        $totalCost += $item['price'] * $item['quantity'];
    }

    return $totalCost;
}


<?php
// Include the cart functionality file
include 'cart.php';

// Display the shopping cart
?>

<h1>Shopping Cart</h1>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="action" value="add">
    <input type="text" name="id" placeholder="Product ID">
    <input type="text" name="name" placeholder="Product Name">
    <input type="number" name="price" placeholder="Price">
    <button type="submit">Add to Cart</button>
</form>

<?php
// Display the cart items
foreach ($_SESSION['cart'] as $item) {
    ?>
    <p>
        <?php echo $item['name']; ?> x <?php echo $item['quantity']; ?>
        = <?php echo $item['price'] * $item['quantity']; ?>
    </p>
    <?php
}

// Display the total cost of the cart items
?>

<p>Total Cost: <?php echo calculateTotalCost(); ?></p>

<?php
// Handle add item to cart form submission
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    addItemToCart($_POST['id'], $_POST['name'], $_POST['price']);
}

// Handle remove item from cart link click
?>

<a href="<?php echo $_SERVER['PHP_SELF']; ?>?remove=<?php echo $_GET['id']; ?>">Remove</a>

<?php
// Remove item from cart if the remove link was clicked
if (isset($_GET['remove'])) {
    removeItemFromCart($_GET['remove']);
}
?>


<?php
// Define the cart array to store items
$cart = array();

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $cart;
    
    // Check if item already exists in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // If item exists, increment quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If item does not exist, add it to cart
    $cart[] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $cart;
    
    // Find index of item in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Remove item from cart
            unset($item);
            return;
        }
    }
}

// Function to update quantity of item in cart
function update_item_quantity($item_id, $new_quantity) {
    global $cart;
    
    // Find index of item in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Update quantity
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $cart;
    
    echo "<h2>Cart Contents:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item ID</th><th>Quantity</th></tr>";
    
    foreach ($cart as $item) {
        echo "<tr><td>" . $item['id'] . "</td><td>" . $item['quantity'] . "</td></tr>";
    }
    
    echo "</table>";
}

// Function to calculate total cost of cart
function calculate_total_cost() {
    global $cart;
    
    $total_cost = 0;
    
    // Assume item prices are stored in an array (replace with actual data)
    $item_prices = array(
        'item1' => 9.99,
        'item2' => 19.99,
        'item3' => 29.99
    );
    
    foreach ($cart as $item) {
        $total_cost += $item['quantity'] * $item_prices[$item['id']];
    }
    
    return $total_cost;
}

// Test the functions
add_item_to_cart('item1', 2);
add_item_to_cart('item2', 3);
display_cart();
echo "<p>Total Cost: $" . calculate_total_cost() . "</p>";
remove_item_from_cart('item2');
update_item_quantity('item1', 4);
display_cart();
echo "<p>Total Cost: $" . calculate_total_cost() . "</p>";

?>


<?php
session_start();

// If the session doesn't exist, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id) {
    global $db;
    
    // Get product details from database
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db, $query);
    $product = mysqli_fetch_assoc($result);

    // Check if product is already in cart
    if (in_array($product['id'], $_SESSION['cart'])) {
        echo 'Product is already in cart!';
    } else {
        // Add product to cart
        array_push($_SESSION['cart'], $product['id']);
        
        // Update session cart count
        $_SESSION['cart_count'] = count($_SESSION['cart']);
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $db;
    
    // Check if product is in cart
    if (in_array($product_id, $_SESSION['cart'])) {
        // Remove product from cart
        unset($_SESSION['cart'][array_search($product_id, $_SESSION['cart'])]);
        
        // Update session cart count
        $_SESSION['cart_count'] = count($_SESSION['cart']);
    }
}

// Function to update item quantity in cart
function update_item_quantity($product_id, $quantity) {
    global $db;
    
    // Check if product is in cart
    if (in_array($product_id, $_SESSION['cart'])) {
        // Get product details from database
        $query = "SELECT * FROM products WHERE id = '$product_id'";
        $result = mysqli_query($db, $query);
        $product = mysqli_fetch_assoc($result);

        // Update quantity in cart
        for ($i = 0; $i < count($_SESSION['cart']); $i++) {
            if ($_SESSION['cart'][$i] == $product_id) {
                $_SESSION['cart'][$i]['quantity'] = $quantity;
                break;
            }
        }

        // Update session cart count
        $_SESSION['cart_count'] = count($_SESSION['cart']);
    }
}

// Function to view cart contents
function view_cart_contents() {
    global $db;
    
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Product ID</th><th>Product Name</th><th>Quantity</th></tr>';

    foreach ($_SESSION['cart'] as $product_id) {
        // Get product details from database
        $query = "SELECT * FROM products WHERE id = '$product_id'";
        $result = mysqli_query($db, $query);
        $product = mysqli_fetch_assoc($result);

        echo '<tr>';
        echo '<td>' . $product['id'] . '</td>';
        echo '<td>' . $product['name'] . '</td>';
        // If product has quantity in cart, display it
        if (isset($_SESSION['cart'][$product_id]['quantity'])) {
            echo '<td>' . $_SESSION['cart'][$product_id]['quantity'] . '</td>';
        } else {
            echo '<td>1</td>'; // Default quantity is 1
        }
        echo '</tr>';
    }

    echo '</table>';
}

// Add items to cart
add_item_to_cart(1);
add_item_to_cart(2);

// View cart contents
view_cart_contents();

// Remove item from cart
remove_item_from_cart(1);

?>


<?php
// Initialize session data if it doesn't exist yet
if (!isset($_SESSION)) {
    session_start();
}

// Set default values for cart and total cost
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
if (!isset($_SESSION['total_cost'])) {
    $_SESSION['total_cost'] = 0;
}

// Function to add item to cart
function addItemToCart($id, $name, $price) {
    global $_SESSION;

    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // If it is, increment the quantity and update the total cost
            $item['quantity'] += 1;
            $_SESSION['total_cost'] -= $item['price'];
            $item['price'] = $price;
            $_SESSION['total_cost'] += $item['price'];
            return;
        }
    }

    // If not, add a new item to the cart
    array_push($_SESSION['cart'], array(
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => 1
    ));

    $_SESSION['total_cost'] += $price;
}

// Function to display cart contents
function displayCart() {
    global $_SESSION;

    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as &$item) {
        echo $item['name'] . ' x ' . $item['quantity'] . ' = $' . $item['price'] * $item['quantity'] . '<br>';
    }
    echo '<p>Total Cost: $' . $_SESSION['total_cost'] . '</p>';
}

// Display cart contents
displayCart();

// Example usage:
// Add item to cart with id 1, name "Product A", and price 10.99
addItemToCart(1, 'Product A', 10.99);

?>


<?php
include 'cart.php';

// Example usage:
// Add a few items to the cart
addItemToCart(1, 'Product A', 10.99);
addItemToCart(2, 'Product B', 5.99);
addItemToCart(3, 'Product C', 7.99);

?>


// Start the session
session_start();

// Create an array to hold the cart items
$cart = $_SESSION['cart'] ?? [];


function add_to_cart($product_id, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // If it's already in the cart, increment its quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add a new item to the cart
    $cart[] = ['id' => $product_id, 'quantity' => $quantity];
}


function update_cart_quantity($product_id, $new_quantity) {
    global $cart;

    // Find the item to update
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // If not found, add a new item to the cart with the updated quantity
    $cart[] = ['id' => $product_id, 'quantity' => $new_quantity];
}


function remove_from_cart($product_id) {
    global $cart;

    // Find the item to remove
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }

    // If not found, do nothing
}


function display_cart() {
    global $cart;

    echo "<h2>Cart:</h2>";
    foreach ($cart as $item) {
        echo "Product ID: $item[id] - Quantity: $item[quantity]<br>";
    }
}


function checkout() {
    global $cart;

    // Process the payment and update the database (not shown)
    echo "Checkout successful!";
}


// Start the session
session_start();

// Add some products to the cart
add_to_cart(1, 2);
add_to_cart(2, 3);

// Display the cart
display_cart();

// Update the quantity of item 1
update_cart_quantity(1, 4);

// Remove item 2 from the cart
remove_from_cart(2);

// Display the updated cart
display_cart();

// Checkout the cart
checkout();


class Cart {
  private $items;

  public function __construct() {
    $this->items = array();
  }

  /**
   * Add an item to the cart
   *
   * @param string $item_name
   * @param float $price
   * @return void
   */
  public function addItem($item_name, $price) {
    $this->items[] = array('name' => $item_name, 'price' => $price);
  }

  /**
   * Remove an item from the cart
   *
   * @param string $item_name
   * @return void
   */
  public function removeItem($item_name) {
    foreach ($this->items as $key => $item) {
      if ($item['name'] == $item_name) {
        unset($this->items[$key]);
        break;
      }
    }
  }

  /**
   * Get the total price of all items in the cart
   *
   * @return float
   */
  public function getTotalPrice() {
    $total = 0;
    foreach ($this->items as $item) {
      $total += $item['price'];
    }
    return $total;
  }

  /**
   * Display the contents of the cart
   *
   * @return string
   */
  public function displayCart() {
    $output = '<h2>Shopping Cart</h2>';
    foreach ($this->items as $item) {
      $output .= sprintf('<p>%s: $%.2f</p>', $item['name'], $item['price']);
    }
    return $output;
  }

  /**
   * Empty the cart
   *
   * @return void
   */
  public function emptyCart() {
    $this->items = array();
  }
}


$cart = new Cart();

// Add items to the cart
$cart->addItem('Apple', 1.99);
$cart->addItem('Banana', 0.50);

// Display the contents of the cart
echo $cart->displayCart();

// Remove an item from the cart
$cart->removeItem('Apple');

// Get the total price of all items in the cart
$totalPrice = $cart->getTotalPrice();
echo sprintf('Total Price: $%.2f', $totalPrice);

// Empty the cart
$cart->emptyCart();


<?php
// Initialize session variables
session_start();

// Set default values for cart and items arrays
$_SESSION['cart'] = array();
$_SESSION['items'] = array();

// Function to add item to cart
function addItemToCart($itemId, $itemName, $itemPrice) {
  // Check if item already exists in cart
  foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['id'] == $itemId) {
      $_SESSION['cart'][$key]['quantity']++;
      break;
    }
  }

  // Add new item to cart if it doesn't exist
  else {
    $_SESSION['cart'][] = array('id' => $itemId, 'name' => $itemName, 'price' => $itemPrice, 'quantity' => 1);
  }
}

// Function to remove item from cart
function removeItemFromCart($itemId) {
  // Find index of item in cart array
  foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['id'] == $itemId) {
      unset($_SESSION['cart'][$key]);
      break;
    }
  }

  // If no items left, reset cart and items arrays
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
    $_SESSION['items'] = array();
  }
}

// Function to update quantity of item in cart
function updateQuantity($itemId, $newQuantity) {
  // Find index of item in cart array
  foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['id'] == $itemId) {
      $_SESSION['cart'][$key]['quantity'] = $newQuantity;
      break;
    }
  }
}

// Function to calculate total cost of items in cart
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}
?>


<?php
include 'cart.php';

// Display cart contents and total cost
echo '<h2>Cart:</h2>';
echo '<table border="1">';
echo '<tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th></tr>';

foreach ($_SESSION['cart'] as $item) {
  echo '<tr>';
  echo '<td>' . $item['id'] . '</td>';
  echo '<td>' . $item['name'] . '</td>';
  echo '<td>$' . number_format($item['price'], 2) . '</td>';
  echo '<td>' . $item['quantity'] . '</td>';
  echo '</tr>';
}

echo '</table>';
echo 'Total: $"' . number_format(calculateTotal(), 2) . '"';

// Add buttons to add, remove and update items
if (isset($_GET['add'])) {
  addItemToCart($_GET['id'], $_GET['name'], $_GET['price']);
} elseif (isset($_GET['remove'])) {
  removeItemFromCart($_GET['id']);
} elseif (isset($_GET['update'])) {
  updateQuantity($_GET['id'], $_GET['quantity']);
}

// Display items to add to cart
echo '<h2>Items:</h2>';
echo '<table border="1">';
echo '<tr><th>ID</th><th>Name</th><th>Price</th></tr>';

foreach ($_SESSION['items'] as $item) {
  echo '<tr>';
  echo '<td>' . $item['id'] . '</td>';
  echo '<td>' . $item['name'] . '</td>';
  echo '<td>$' . number_format($item['price'], 2) . '</td>';
  echo '<td><a href="?add&id=' . $item['id'] . '&name=' . $item['name'] . '&price=' . $item['price'] . '">Add to Cart</a></td>';
  echo '</tr>';
}

echo '</table>';
?>


<?php

// Initialize session
session_start();

// Define cart array in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // Increment quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    // Find index of item in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            unset($item);
            return;
        }
    }

    // Remove empty element
    array_splice($_SESSION['cart'], array_search(false, $_SESSION['cart']), 1);
}

// Function to update quantity of item in cart
function update_item_quantity($item_id, $new_quantity) {
    // Find index of item in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to calculate total cost of items in cart
function calculate_total_cost() {
    $total_cost = 0;

    // Iterate over each item in cart
    foreach ($_SESSION['cart'] as $item) {
        // Assume price is stored in a database or array for simplicity
        $price = get_price($item['id']);
        $total_cost += $price * $item['quantity'];
    }

    return $total_cost;
}

// Function to display cart contents
function display_cart_contents() {
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Item ID</th><th>Quantity</th><th>Price</th></tr>';

    // Iterate over each item in cart
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr>';
        echo '<td>' . $item['id'] . '</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '<td>$' . get_price($item['id']) . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}

// Function to process checkout
function process_checkout() {
    // Assume payment processing is handled separately for simplicity
    echo 'Thank you for your purchase!';
}

?>


<?php

// Assume price is stored in a database or array for simplicity
function get_price($item_id) {
    // Replace with actual database query or array lookup
    $prices = array(
        '1' => 9.99,
        '2' => 19.99,
        '3' => 29.99
    );
    return $prices[$item_id];
}

?>


// functions.php

function add_item_to_cart($user_id, $product_id, $quantity = 1) {
    // Check if product exists and is available in stock
    $product = get_product($product_id);
    if (!$product || $product['stock'] < $quantity) {
        return false;
    }

    // Add item to cart
    $cart_item = array(
        'cart_id' => get_cart_id($user_id),
        'product_id' => $product_id,
        'quantity' => $quantity
    );
    add_cart_item($cart_item);

    return true;
}

function view_cart_contents($user_id) {
    // Get cart items for the user
    $carts = get_carts_for_user($user_id);
    if (!$carts) {
        return array();
    }

    // Calculate total cost of cart contents
    $total_cost = 0;
    foreach ($carts as $cart_item) {
        $product = get_product($cart_item['product_id']);
        $total_cost += $product['price'] * $cart_item['quantity'];
    }

    return array(
        'cart_items' => $carts,
        'total_cost' => $total_cost
    );
}

function update_quantity($user_id, $product_id, $new_quantity) {
    // Update quantity of product in cart
    update_cart_item_quantity($user_id, $product_id, $new_quantity);
}

function remove_item_from_cart($user_id, $product_id) {
    // Remove item from cart
    delete_cart_item($user_id, $product_id);
}


// db.php

function get_product($id) {
    global $db;
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_cart_id($user_id) {
    global $db;
    $query = "SELECT id FROM carts WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function get_carts_for_user($user_id) {
    global $db;
    $query = "SELECT ci.* FROM cart_items ci JOIN carts c ON ci.cart_id = c.id WHERE c.user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_cart_item($cart_item) {
    global $db;
    $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':cart_id', $cart_item['cart_id']);
    $stmt->bindParam(':product_id', $cart_item['product_id']);
    $stmt->bindParam(':quantity', $cart_item['quantity']);
    $stmt->execute();
}

function update_cart_item_quantity($user_id, $product_id, $new_quantity) {
    global $db;
    $query = "UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_id = :user_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $new_quantity);
    $stmt->execute();
}

function delete_cart_item($user_id, $product_id) {
    global $db;
    $query = "DELETE FROM cart_items WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_id = :user_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
}


// index.php

require_once 'functions.php';
require_once 'db.php';

// ...

if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    add_item_to_cart($user_id, $product_id, $quantity);
}

if (isset($_POST['update_quantity'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    update_quantity($user_id, $product_id, $new_quantity);
}

if (isset($_POST['remove_item'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    remove_item_from_cart($user_id, $product_id);
}

// ...

?>

<form action="" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    <select name="product_id">
        <?php foreach (get_products() as $product): ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<?php if ($cart_contents = view_cart_contents($_SESSION['user_id'])): ?>

<form action="" method="post">
    <?php foreach ($cart_contents['cart_items'] as $cart_item): ?>
        <p>
            <?php echo $cart_item['product_name']; ?> (x<?php echo $cart_item['quantity']; ?>) - <?php echo format_price($cart_item['price'] * $cart_item['quantity']); ?>
            <button type="submit" name="remove_item" value="<?php echo $cart_item['id']; ?>">Remove</button>
        </p>
    <?php endforeach; ?>

    <p>Total: <?php echo format_price($cart_contents['total_cost']); ?></p>

    <?php if (isset($_POST['update_quantity'])): ?>
        <form action="" method="post">
            <input type="hidden" name="update_quantity" value="1">
            <select name="product_id">
                <?php foreach ($cart_contents['cart_items'] as $cart_item): ?>
                    <option value="<?php echo $cart_item['id']; ?>"><?php echo $cart_item['product_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="new_quantity">
            <button type="submit">Update Quantity</button>
        </form>
    <?php endif; ?>
</form>

<?php endif; ?>


class Cart {
  private $db;

  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
  }

  public function add($user_id, $product_id, $quantity) {
    try {
      $stmt = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price)
        SELECT id, ?, ? * price FROM carts
        WHERE user_id = ?
          AND IFNULL(quantity, 0) + ? <= quantity");
      $stmt->execute([$product_id, $quantity, $user_id, $quantity]);
      return $this->db->lastInsertId();
    } catch (PDOException $e) {
      echo 'Error adding item to cart: ' . $e->getMessage();
    }
  }

  public function getCartItems($cart_id) {
    try {
      $stmt = $this->db->prepare("SELECT ci.id, p.name AS product_name, p.price, ci.quantity
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = ?");
      $stmt->execute([$cart_id]);
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo 'Error fetching cart items: ' . $e->getMessage();
    }
  }

  public function updateQuantity($item_id, $new_quantity) {
    try {
      $stmt = $this->db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
      $stmt->execute([$new_quantity, $item_id]);
      return true;
    } catch (PDOException $e) {
      echo 'Error updating item quantity: ' . $e->getMessage();
    }
  }

  public function deleteItem($item_id) {
    try {
      $stmt = $this->db->prepare("DELETE FROM cart_items WHERE id = ?");
      $stmt->execute([$item_id]);
      return true;
    } catch (PDOException $e) {
      echo 'Error deleting item: ' . $e->getMessage();
    }
  }

  public function getTotalCost($cart_id) {
    try {
      $stmt = $this->db->prepare("SELECT SUM(ci.quantity * p.price) AS total_cost
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = ?");
      $stmt->execute([$cart_id]);
      return $stmt->fetchColumn();
    } catch (PDOException $e) {
      echo 'Error fetching total cost: ' . $e->getMessage();
    }
  }
}


class CartController {
  private $cart;

  public function __construct() {
    $this->cart = new Cart();
  }

  public function addProduct($user_id, $product_id) {
    // Validate user input and product ID
    // ...
    $quantity = $_POST['quantity'];
    $result = $this->cart->add($user_id, $product_id, $quantity);
    if ($result !== false) {
      echo 'Item added to cart successfully!';
    } else {
      echo 'Error adding item to cart.';
    }
  }

  public function viewCart() {
    // Validate user input and cart ID
    // ...
    $cart_id = $_GET['id'];
    $items = $this->cart->getCartItems($cart_id);
    echo json_encode($items); // Return JSON response for client-side rendering
  }

  public function updateQuantity($item_id, $new_quantity) {
    // Validate user input and item ID
    // ...
    $result = $this->cart->updateQuantity($item_id, $new_quantity);
    if ($result !== false) {
      echo 'Item quantity updated successfully!';
    } else {
      echo 'Error updating item quantity.';
    }
  }

  public function deleteProduct($item_id) {
    // Validate user input and item ID
    // ...
    $result = $this->cart->deleteItem($item_id);
    if ($result !== false) {
      echo 'Item deleted from cart successfully!';
    } else {
      echo 'Error deleting item.';
    }
  }

  public function getTotalCost() {
    // Validate user input and cart ID
    // ...
    $cart_id = $_GET['id'];
    $total_cost = $this->cart->getTotalCost($cart_id);
    echo json_encode(['total_cost' => $total_cost]); // Return JSON response for client-side rendering
  }
}


<?php
// Initialize cart session
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $quantity) {
  global $_SESSION;
  if (array_key_exists($item_id, $_SESSION['cart'])) {
    // If item is already in cart, increment quantity
    $_SESSION['cart'][$item_id] += $quantity;
  } else {
    // Add new item to cart with specified quantity
    $_SESSION['cart'][$item_id] = $quantity;
  }
}

// Function to update item quantity in cart
function updateItemQuantity($item_id, $new_quantity) {
  global $_SESSION;
  if (array_key_exists($item_id, $_SESSION['cart'])) {
    // Update item quantity
    $_SESSION['cart'][$item_id] = $new_quantity;
  }
}

// Function to remove item from cart
function removeFromCart($item_id) {
  global $_SESSION;
  if (array_key_exists($item_id, $_SESSION['cart'])) {
    // Remove item from cart
    unset($_SESSION['cart'][$item_id]);
  }
}

// Function to display cart contents
function displayCart() {
  global $_SESSION;
  echo "<h2>Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item ID</th><th>Name</th><th>Quantity</th><th>Price</th></tr>";
  foreach ($_SESSION['cart'] as $item_id => $quantity) {
    // Display item details from database (or other data source)
    // For example:
    // $db = new PDO('sqlite:example.db');
    // $stmt = $db->prepare("SELECT * FROM products WHERE id=:id");
    // $stmt->bindParam(':id', $item_id);
    // $stmt->execute();
    // $row = $stmt->fetch();
    // echo "<tr><td>" . $item_id . "</td><td>" . $row['name'] . "</td><td>" . $quantity . "</td><td>" . $row['price'] . "</td></tr>";
  }
  echo "</table>";
}

// Display cart contents
displayCart();

// Example usage:
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="item_id" value="123">
  <input type="text" name="quantity" placeholder="Quantity">
  <button type="submit">Add to Cart</button>
</form>

<?php
if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
  addToCart($_POST['item_id'], $_POST['quantity']);
}

// Display cart contents again after adding item
displayCart();
?>


<?php
// Initialize session
session_start();

// Cart configuration
$cart = array();
$maxItems = 10;

// Function to add item to cart
function addItemToCart($item, $quantity) {
    global $cart;
    if (array_key_exists($item['id'], $cart)) {
        // Update existing item in cart
        $cart[$item['id']]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $cart[$item['id']] = array('name' => $item['name'], 'price' => $item['price'], 'quantity' => $quantity);
    }
}

// Function to update item in cart
function updateItemInCart($itemId, $newQuantity) {
    global $cart;
    if (array_key_exists($itemId, $cart)) {
        // Update quantity of existing item in cart
        $cart[$itemId]['quantity'] = $newQuantity;
    }
}

// Function to remove item from cart
function removeItemFromCart($itemId) {
    global $cart;
    if (array_key_exists($itemId, $cart)) {
        // Remove item from cart
        unset($cart[$itemId]);
    }
}

// Display cart contents
function displayCart() {
    global $cart;
    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item</th><th>Quantity</th><th>Price</th></tr>";
    foreach ($cart as $item) {
        echo "<tr><td>" . $item['name'] . "</td><td>" . $item['quantity'] . "</td><td>$" . number_format($item['price'], 2) . "</td></tr>";
    }
    echo "</table>";
}

// Check if user has added items to cart
if (isset($_POST['add'])) {
    // Get item ID and quantity from form submission
    $itemId = $_POST['id'];
    $quantity = $_POST['quantity'];
    addItemToCart(array('id' => $itemId, 'name' => $_POST['name'], 'price' => $_POST['price']), $quantity);
}

// Check if user has updated item in cart
if (isset($_POST['update'])) {
    // Get item ID and new quantity from form submission
    $itemId = $_POST['id'];
    $newQuantity = $_POST['quantity'];
    updateItemInCart($itemId, $newQuantity);
}

// Check if user has removed item from cart
if (isset($_POST['remove'])) {
    // Get item ID from form submission
    $itemId = $_POST['id'];
    removeItemFromCart($itemId);
}

// Display cart contents and add item form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="add-item">Add Item:</label>
    <select name="id" id="add-item">
        <?php
        // Assume we have a database or array of items to display in the select box
        $items = array(
            array('id' => 1, 'name' => 'Item 1', 'price' => 9.99),
            array('id' => 2, 'name' => 'Item 2', 'price' => 19.99),
            // Add more items here...
        );
        foreach ($items as $item) {
            echo "<option value='" . $item['id'] . "'>" . $item['name'] . "</option>";
        }
        ?>
    </select>
    <input type="number" name="quantity" id="quantity">
    <input type="submit" name="add" value="Add to Cart">
</form>

<?php
// Display cart contents and update form (if user has items in cart)
if (!empty($cart)) {
    ?>
    <h2>Shopping Cart Contents:</h2>
    <?php displayCart(); ?>

    <h2>Update Items:</h2>
    <table border='1'>
        <tr><th>Item</th><th>Quantity</th></tr>
        <?php
        foreach ($cart as $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td><input type='number' name='" . $item['id'] . "' value='" . $item['quantity'] . "'>";
            echo "<button name='update'>Update</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Remove Item:</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <select name="id" id="remove-item">
            <?php
            foreach ($cart as $item) {
                echo "<option value='" . $item['id'] . "'>" . $item['name'] . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="remove">Remove</button>
    </form>

<?php
}
?>


// item.php
class Item {
    public $id;
    public $name;
    public $price;

    function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}

// cart.php
class Cart {
    private $user_id;
    private $items;

    function __construct($user_id) {
        $this->user_id = $user_id;
        $this->items = array();
    }

    // Add item to cart
    public function addItem(Item $item, $quantity) {
        if (array_key_exists($item->id, $this->items)) {
            $this->items[$item->id]->quantity += $quantity;
        } else {
            $this->items[$item->id] = new ItemInCart($item->id, $item->name, $item->price, $quantity);
        }
    }

    // Remove item from cart
    public function removeItem($item_id) {
        if (array_key_exists($item_id, $this->items)) {
            unset($this->items[$item_id]);
        }
    }

    // Update quantity of an item in cart
    public function updateQuantity($item_id, $quantity) {
        if (array_key_exists($item_id, $this->items)) {
            $this->items[$item_id]->quantity = $quantity;
        }
    }

    // Get total cost of items in cart
    public function getTotalCost() {
        $total_cost = 0;
        foreach ($this->items as $item) {
            $total_cost += $item->price * $item->quantity;
        }
        return $total_cost;
    }

    // Display all items in cart with quantities and prices
    public function displayCart() {
        echo "Your Cart:
";
        foreach ($this->items as $item) {
            echo "$item->name: $item->quantity x £$item->price = £" . ($item->price * $item->quantity) . "
";
        }
        echo "Total cost: £" . $this->getTotalCost() . "

";
    }
}

class ItemInCart {
    public $id;
    public $name;
    public $price;
    public $quantity;

    function __construct($id, $name, $price, $quantity) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}


// item_list.php (example items in store)
$items = array(
    new Item(1, "Apple", 0.50),
    new Item(2, "Banana", 0.25),
    new Item(3, "Orange", 0.75)
);

// user adds some items to cart
$user_id = 1; // replace with actual user ID
$cart = new Cart($user_id);
foreach ($items as $item) {
    $quantity = 2; // quantity to add for each item
    if (isset($_GET['add_item'])) {
        if ($_GET['add_item'] == $item->id) {
            $cart->addItem($item, $quantity);
        }
    }
}

// display current cart contents and total cost
$cart->displayCart();


<form action="" method="get">
    <?php foreach ($items as $item): ?>
        <input type="checkbox" name="add_item[]" value="<?php echo $item->id; ?>">
        <label for="<?php echo $item->name; ?>"><?php echo $item->name; ?> (<?php echo $item->price; ?>)</label>
    <?php endforeach; ?>
    <button type="submit">Add to Cart</button>
</form>


<form action="" method="get">
    <input type="checkbox" name="remove_item[]" value="<?php echo $item->id; ?>">
    <label for="<?php echo $item->name; ?>"><?php echo $item->name; ?></label>
</form>

<button type="submit">Remove from Cart</button>


<?php
// Initialize the cart array
$cart = [];

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
  global $cart;
  if (!isset($cart[$product_id])) {
    $cart[$product_id] = ['quantity' => 0];
  }
  $cart[$product_id]['quantity'] += $quantity;
}

// Function to remove item from cart
function remove_from_cart($product_id) {
  global $cart;
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update quantity in cart
function update_quantity($product_id, $new_quantity) {
  global $cart;
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Function to display cart contents
function display_cart() {
  global $cart;
  ?>
  <h2>Cart Contents</h2>
  <table border="1">
    <tr>
      <th>Product ID</th>
      <th>Quantity</th>
      <th>Total Price</th>
    </tr>
  <?php
  foreach ($cart as $product_id => $details) {
    // Get product details from database (e.g. price, name)
    $product = get_product_details($product_id);
    ?>
    <tr>
      <td><?= $product['name'] ?></td>
      <td><?= $details['quantity'] ?></td>
      <td><?= $details['quantity'] * $product['price'] ?></td>
    </tr>
  <?php
  }
  ?>
  </table>
  <?php
}

// Function to calculate total cost of cart contents
function calculate_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $product_id => $details) {
    // Get product details from database (e.g. price, name)
    $product = get_product_details($product_id);
    $total += $details['quantity'] * $product['price'];
  }
  return $total;
}

// Example usage:
add_to_cart(1, 2); // Add 2 units of product with ID 1 to cart
display_cart(); // Display current cart contents
remove_from_cart(1); // Remove product with ID 1 from cart
update_quantity(2, 3); // Update quantity of product with ID 2 to 3 units
echo calculate_total(); // Output total cost of current cart contents

?>


<?php
// Session variables for cart data
$_SESSION['cart'] = array();

// Function to add item to cart
function addToCart($item, $quantity) {
  global $_SESSION;
  if (isset($_SESSION['cart'][$item])) {
    $_SESSION['cart'][$item] += $quantity;
  } else {
    $_SESSION['cart'][$item] = $quantity;
  }
}

// Function to remove item from cart
function removeFromCart($item) {
  global $_SESSION;
  unset($_SESSION['cart'][$item]);
}

// Function to update quantity of item in cart
function updateQuantity($item, $new_quantity) {
  global $_SESSION;
  if (isset($_SESSION['cart'][$item])) {
    $_SESSION['cart'][$item] = $new_quantity;
  }
}

// Function to calculate total cost of items in cart
function calculateTotal() {
  global $_SESSION;
  $total = 0;
  foreach ($_SESSION['cart'] as $item => $quantity) {
    // Assuming prices are stored in an array
    if (isset($prices[$item])) {
      $total += $prices[$item] * $quantity;
    }
  }
  return $total;
}

// Function to display cart contents
function displayCart() {
  global $_SESSION;
  echo '<h2>Shopping Cart</h2>';
  foreach ($_SESSION['cart'] as $item => $quantity) {
    echo '<p>' . $item . ': ' . $quantity . '</p>';
  }
}


<?php
// Include cart functions
include 'cart.php';

// Initialize session
session_start();

// Prices of items (example)
$prices = array(
  'apple' => 1.00,
  'banana' => 0.50,
  'orange' => 1.25,
);

// Display cart contents and buttons to add/remove items
displayCart();
echo '<button onclick="addToCart(\'apple\', 2)">Add Apple (2)</button>';
echo '<button onclick="removeFromCart(\'banana\')">Remove Banana</button>';

// JavaScript code to handle adding/removing items from cart
?>
<script>
function addToCart(item, quantity) {
  <?php addToCart($item, $quantity); ?>
  displayCart();
}

function removeFromCart(item) {
  <?php removeFromCart($item); ?>
  displayCart();
}
</script>


<?php
// Initialize session variables
session_start();

// Set default values for cart and items
$_SESSION['cart'] = array();
$_SESSION['total_cost'] = 0;
?>

<!-- HTML to display the cart -->
<h2>Shopping Cart</h2>
<ul>
    <?php foreach ($_SESSION['cart'] as $item_id => $item) { ?>
        <li>
            <?php echo $item['name']; ?> x <?php echo $item['quantity']; ?>
            <button class="remove-item" data-id="<?php echo $item_id; ?>">Remove</button>
        </li>
    <?php } ?>
    <p>Total Cost: <?php echo $_SESSION['total_cost']; ?></p>
</ul>

<!-- Form to add items to cart -->
<form action="cart.php" method="post">
    <label>Product Name:</label>
    <input type="text" name="product_name"><br><br>
    <label>Quantity:</label>
    <input type="number" name="quantity"><br><br>
    <button type="submit">Add to Cart</button>
</form>

<?php
// If form is submitted, add item to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get product name and quantity from form
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] == $product_name) {
            // If it does, increment quantity and update total cost
            $item['quantity'] += $quantity;
            $_SESSION['total_cost'] += (floatval($item['price']) * $quantity);
            break;
        }
    }

    // If product doesn't exist in cart, add new item
    if (!isset($_SESSION['cart'][$product_name])) {
        $_SESSION['cart'][$product_name] = array(
            'name' => $product_name,
            'price' => 0.00, // replace with actual price
            'quantity' => $quantity
        );
        $_SESSION['total_cost'] += (floatval($_SESSION['cart'][$product_name]['price']) * $quantity);
    }
}
?>

<script>
// Remove item from cart
$('.remove-item').on('click', function() {
    var id = $(this).data('id');
    delete $_SESSION['cart'][id];
    updateCart();
});

function updateCart() {
    // Update cart display and total cost
    $('.cart-list').html('');
    $.each($_SESSION['cart'], function(key, value) {
        $('.cart-list').append('<li>' + value.name + ' x ' + value.quantity + '</li>');
    });
    $('.total-cost').text('Total Cost: ' + $_SESSION['total_cost']);
}
</script>


class Product {
    private $id;
    private $name;
    private $price;

    public function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }
}


class Cart {
    private $cart_items;

    public function __construct() {
        $this->cart_items = array();
    }

    public function addItem($product_id, $quantity) {
        if (isset($this->cart_items[$product_id])) {
            $this->cart_items[$product_id]['quantity'] += $quantity;
        } else {
            $this->cart_items[$product_id] = array('product' => new Product($product_id, '', 0), 'quantity' => $quantity);
        }
    }

    public function removeItem($product_id) {
        if (isset($this->cart_items[$product_id])) {
            unset($this->cart_items[$product_id]);
        }
    }

    public function getItems() {
        return $this->cart_items;
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->cart_items as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }
}


class CartController {
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function addItem($product_id, $quantity) {
        $this->cart->addItem($product_id, $quantity);
    }

    public function removeItem($product_id) {
        $this->cart->removeItem($product_id);
    }

    public function getItems() {
        return $this->cart->getItems();
    }

    public function getTotal() {
        return $this->cart->getTotal();
    }
}


// index.php

require_once 'CartController.php';

$controller = new CartController();

?>
<form action="" method="post">
    <?php foreach ($controller->getItems() as $item): ?>
        <input type="hidden" name="product_id[]" value="<?= $item['product']->getId() ?>">
        <input type="number" name="quantity[]" value="<?= $item['quantity'] ?>" class="form-control">
        <span><?= $item['product']->getName() ?></span>
        <a href="#" class="btn btn-danger">Remove</a>
    <?php endforeach; ?>
    <button class="btn btn-primary">Update Cart</button>
</form>

<?php
if (isset($_POST['update'])) {
    foreach ($_POST['product_id'] as $key => $value) {
        if (isset($_POST['quantity'][$key])) {
            $controller->addItem($value, $_POST['quantity'][$key]);
        }
    }
}
?>


$controller = new CartController();
$controller->addItem(1, 2); // Add product with ID 1 and quantity 2.


$controller = new CartController();
$controller->removeItem(1);


$controller = new CartController();
echo $controller->getTotal(); // Output: Total cost of all items.


class Cart {
    private $items;

    public function __construct() {
        $this->items = array();
    }

    public function addItem($product_id, $quantity) {
        if (!isset($this->items[$product_id])) {
            $this->items[$product_id] = array('quantity' => 0);
        }
        $this->items[$product_id]['quantity'] += $quantity;
    }

    public function removeItem($product_id) {
        unset($this->items[$product_id]);
    }

    public function updateQuantity($product_id, $new_quantity) {
        if (isset($this->items[$product_id])) {
            $this->items[$product_id]['quantity'] = $new_quantity;
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotalCost() {
        $total_cost = 0;
        foreach ($this->items as $item) {
            $total_cost += $item['quantity'] * $this->getProductPrice($item['product_id']);
        }
        return $total_cost;
    }

    private function getProductPrice($product_id) {
        // For demonstration purposes, assume we have a static array of product prices.
        // In a real-world application, you'd fetch the price from your database or external API.
        $products = array(
            '1' => 9.99,
            '2' => 19.99,
            '3' => 29.99
        );
        return isset($products[$product_id]) ? $products[$product_id] : 0;
    }
}


class CartController {
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function addProductToCart() {
        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $product_id = $_POST['product_id'];
            $quantity = (int) $_POST['quantity'];
            $this->cart->addItem($product_id, $quantity);
            echo 'Product added to cart.';
        } else {
            echo 'Invalid request.';
        }
    }

    public function viewCart() {
        $items = $this->cart->getItems();
        echo '<h2>Cart Contents:</h2>';
        foreach ($items as $item) {
            echo '<p>ID: ' . $item['product_id'] . ', Quantity: ' . $item['quantity'] . '</p>';
        }
        echo '<p>Total Cost: $' . number_format($this->cart->getTotalCost(), 2) . '</p>';
    }

    public function removeProductFromCart() {
        if (isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
            $this->cart->removeItem($product_id);
            echo 'Product removed from cart.';
        } else {
            echo 'Invalid request.';
        }
    }

    public function updateQuantity() {
        if (isset($_POST['product_id']) && isset($_POST['new_quantity'])) {
            $product_id = $_POST['product_id'];
            $new_quantity = (int) $_POST['new_quantity'];
            $this->cart->updateQuantity($product_id, $new_quantity);
            echo 'Quantity updated.';
        } else {
            echo 'Invalid request.';
        }
    }

    public function emptyCart() {
        $this->cart = new Cart();
        echo 'Cart emptied.';
    }
}


$cartController = new CartController();
echo $cartController->viewCart();


$cartController = new CartController();
echo $cartController->removeProductFromCart();


$cartController = new CartController();
echo $cartController->updateQuantity();


$cartController = new CartController();
echo $cartController->emptyCart();


class Cart {
  private $sessionName;

  public function __construct() {
    $this->sessionName = 'cart';
  }

  // Get the current cart contents
  public function getContents() {
    $contents = $_SESSION[$this->sessionName];
    if (!$contents) {
      $contents = array();
    }
    return $contents;
  }

  // Add an item to the cart
  public function addItem($productId, $quantity = 1) {
    $contents = $this->getContents();
    if (!isset($contents[$productId])) {
      $contents[$productId] = array('product_id' => $productId, 'quantity' => $quantity);
    } else {
      $contents[$productId]['quantity'] += $quantity;
    }
    $_SESSION[$this->sessionName] = $contents;
  }

  // Remove an item from the cart
  public function removeItem($productId) {
    $contents = $this->getContents();
    if (isset($contents[$productId])) {
      unset($contents[$productId]);
    }
    $_SESSION[$this->sessionName] = $contents;
  }

  // Update the quantity of an item in the cart
  public function updateQuantity($productId, $quantity) {
    $contents = $this->getContents();
    if (isset($contents[$productId])) {
      $contents[$productId]['quantity'] = $quantity;
    }
    $_SESSION[$this->sessionName] = $contents;
  }

  // Get the total cost of items in the cart
  public function getTotalCost() {
    $contents = $this->getContents();
    $totalCost = 0;
    foreach ($contents as $item) {
      $price = getPrice($item['product_id']); // Assuming a getPrice function exists
      $totalCost += $price * $item['quantity'];
    }
    return $totalCost;
  }

  // Display the cart contents
  public function displayContents() {
    $contents = $this->getContents();
    echo '<ul>';
    foreach ($contents as $item) {
      echo '<li>Product ID: ' . $item['product_id'] . ', Quantity: ' . $item['quantity'] . '</li>';
    }
    echo '</ul>';
  }
}


// Create a new cart instance
$cart = new Cart();

// Add an item to the cart
$cart->addItem(1, 2);

// Remove an item from the cart
$cart->removeItem(1);

// Update the quantity of an item in the cart
$cart->updateQuantity(1, 3);

// Display the cart contents
$cart->displayContents();

// Get the total cost of items in the cart
echo 'Total Cost: ' . $cart->getTotalCost();


<?php
session_start();

// ... rest of your code ...
?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
  // Establish database connection
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
  echo "Error connecting to database: " . $e->getMessage();
  exit;
}

// Function to add product to cart
function add_to_cart($user_id, $product_id, $quantity) {
  global $conn;

  // Check if product exists in cart
  $stmt = $conn->prepare("SELECT * FROM carts WHERE user_id = :user_id AND product_id = :product_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();
  $existing_cart_item = $stmt->fetch();

  if ($existing_cart_item) {
    // Update quantity of existing cart item
    $stmt = $conn->prepare("UPDATE carts SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
  } else {
    // Add new cart item
    $stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
  }
}

// Function to view cart contents
function view_cart($user_id) {
  global $conn;

  // Get all cart items for user
  $stmt = $conn->prepare("SELECT * FROM carts WHERE user_id = :user_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  $cart_items = $stmt->fetchAll();

  return $cart_items;
}

// Function to remove product from cart
function remove_from_cart($user_id, $product_id) {
  global $conn;

  // Delete cart item by user ID and product ID
  $stmt = $conn->prepare("DELETE FROM carts WHERE user_id = :user_id AND product_id = :product_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();
}
?>


<?php
require_once 'cart.php';

// Initialize cart variables
$cart = array();

// Check if user is logged in (for simplicity, we'll use a hardcoded example)
$user_id = 1;

// Add product to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  add_to_cart($user_id, $product_id, $quantity);
}

// View cart contents
$cart_items = view_cart($user_id);

// Remove product from cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  remove_from_cart($user_id, $product_id);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Purchase Cart</title>
</head>
<body>

  <!-- Add product to cart form -->
  <form action="" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    <select name="product_id">
      <?php
      $stmt = $conn->prepare("SELECT * FROM products");
      $stmt->execute();
      $products = $stmt->fetchAll();

      foreach ($products as $product) {
        echo "<option value='{$product['id']}'>{$product['name']}</option>";
      }
      ?>
    </select>
    <input type="number" name="quantity">
    <button type="submit">Add to Cart</button>
  </form>

  <!-- View cart contents table -->
  <h2>Cart Contents:</h2>
  <table border="1">
    <?php
    foreach ($cart_items as $item) {
      echo "<tr><td>{$item['product_id']}</td><td>{$item['quantity']}</td></tr>";
    }
    ?>
  </table>

  <!-- Remove product from cart form -->
  <form action="" method="post">
    <input type="hidden" name="remove_from_cart" value="1">
    <select name="product_id">
      <?php
      foreach ($cart_items as $item) {
        echo "<option value='{$item['id']}'>{$item['product_id']}</option>";
      }
      ?>
    </select>
    <button type="submit">Remove from Cart</button>
  </form>

</body>
</html>


// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'name' => 'your_database'
);

// Database connection function
function connectDB() {
    $conn = new mysqli($GLOBALS['database']['host'], $GLOBALS['database']['username'], $GLOBALS['database']['password'], $GLOBALS['database']['name']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Get all products
function getAllProducts($conn) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add product to cart
function addProductToCart($user_id, $product_id, $quantity) {
    $conn = connectDB();
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $result = $stmt->execute();
    return $result;
}

// Get user's cart
function getUserCart($user_id) {
    $conn = connectDB();
    $sql = "SELECT * FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $result = $stmt->execute();
    return $result;
}

// Update product quantity in cart
function updateProductQuantityInCart($cart_id, $new_quantity) {
    $conn = connectDB();
    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_quantity, $cart_id);
    $result = $stmt->execute();
    return $result;
}

// Example usage
$user_id = 1; // Replace with actual user ID

// Get all products
$products = getAllProducts(connectDB());
foreach ($products as $product) {
    echo $product['name'] . ' - ' . $product['price'] . '<br>';
}

// Add product to cart
$product_id = 1; // Replace with actual product ID
$new_quantity = 2;
$result = addProductToCart($user_id, $product_id, $new_quantity);
echo $result ? 'Added to cart successfully!' : 'Failed to add to cart.';

// Get user's cart
$cart = getUserCart($user_id);
if ($cart) {
    foreach ($cart as $item) {
        echo $item['name'] . ' - ' . $item['quantity'] . '<br>';
    }
} else {
    echo 'No items in cart.';
}

// Update product quantity in cart
$cart_id = 1; // Replace with actual cart ID
$new_quantity = 3;
$result = updateProductQuantityInCart($cart_id, $new_quantity);
echo $result ? 'Updated successfully!' : 'Failed to update.';


class Cart {
  private $db;

  public function __construct() {
    $this->db = new Database(); // Assuming we have a `Database` class for database connection
  }

  public function addProduct($userId, $productId) {
    $query = "INSERT INTO cart (user_id, product_id, quantity, total_price)
              VALUES (:user_id, :product_id, 1, 0)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':product_id', $productId);
    return $stmt->execute();
  }

  public function updateQuantity($cartId, $newQuantity) {
    $query = "UPDATE cart SET quantity = :quantity WHERE id = :cart_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':quantity', $newQuantity);
    $stmt->bindParam(':cart_id', $cartId);
    return $stmt->execute();
  }

  public function removeProduct($cartId) {
    $query = "DELETE FROM cart WHERE id = :cart_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':cart_id', $cartId);
    return $stmt->execute();
  }

  public function getCartItems($userId) {
    $query = "SELECT * FROM cart WHERE user_id = :user_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTotalCost($cartId) {
    $query = "SELECT total_price FROM cart WHERE id = :cart_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':cart_id', $cartId);
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  public function placeOrder() {
    // This method will calculate the total cost, update cart items, and insert a new order into the database
    // For simplicity, we'll assume this is done elsewhere in your codebase
  }
}


$cart = new Cart();
// Add product to cart
$userId = 1;
$productId = 5;
$success = $cart->addProduct($userId, $productId);
if ($success) {
  echo "Product added to cart successfully!";
}

// Update quantity of an item in the cart
$cartId = 10;
$newQuantity = 3;
$success = $cart->updateQuantity($cartId, $newQuantity);
if ($success) {
  echo "Quantity updated successfully!";
}

// Get all items in the cart for a user
$userId = 1;
$items = $cart->getCartItems($userId);
print_r($items);

// Remove an item from the cart
$cartId = 10;
$success = $cart->removeProduct($cartId);
if ($success) {
  echo "Item removed from cart successfully!";
}


<?php
// Initialize session
session_start();

// Set default values for cart
$_SESSION['cart'] = array();
$cart = $_SESSION['cart'];

// Function to add item to cart
function add_to_cart($item_id, $quantity) {
  global $cart;
  if (array_key_exists($item_id, $cart)) {
    $cart[$item_id] += $quantity;
  } else {
    $cart[$item_id] = $quantity;
  }
}

// Function to remove item from cart
function remove_from_cart($item_id) {
  global $cart;
  unset($cart[$item_id]);
}

// Function to update quantity in cart
function update_quantity($item_id, $new_quantity) {
  global $cart;
  if (array_key_exists($item_id, $cart)) {
    $cart[$item_id] = $new_quantity;
  }
}

// Function to get total cost of items in cart
function get_total_cost() {
  global $cart;
  $total_cost = 0;
  foreach ($cart as $item_id => $quantity) {
    // Assume prices are stored in a database or an array for simplicity
    $prices = array(
      'product1' => 9.99,
      'product2' => 14.99,
      // Add more products here...
    );
    if (array_key_exists($item_id, $prices)) {
      $total_cost += $quantity * $prices[$item_id];
    }
  }
  return $total_cost;
}

// Function to display cart contents
function display_cart() {
  global $cart;
  echo '<h2>Cart Contents:</h2>';
  foreach ($cart as $item_id => $quantity) {
    // Assume product names are stored in a database or an array for simplicity
    $product_names = array(
      'product1' => 'Product 1',
      'product2' => 'Product 2',
      // Add more products here...
    );
    if (array_key_exists($item_id, $product_names)) {
      echo '<p>' . $quantity . ' x ' . $product_names[$item_id] . '</p>';
    }
  }
}
?>


<?php
require_once 'cart.php';

// Display cart contents and add item to cart form
display_cart();

echo '<form action="add_to_cart.php" method="post">';
echo '<select name="product">';
foreach (array('product1', 'product2') as $product) {
  echo '<option value="' . $product . '">' . $product . '</option>';
}
echo '</select>';
echo '<input type="number" name="quantity" min="1">';
echo '<button type="submit">Add to Cart</button>';
echo '</form>';

// Display total cost of items in cart
echo '<p>Total Cost: ' . get_total_cost() . '</p>';
?>


<?php
require_once 'cart.php';

// Get product ID and quantity from form submission
$product_id = $_POST['product'];
$quantity = (int) $_POST['quantity'];

// Add item to cart
add_to_cart($product_id, $quantity);

// Redirect back to index.php with updated cart contents
header('Location: index.php');
exit;
?>


<?php
// Initialize session
session_start();

// Define the cart array
$cart = [];

// Function to add item to cart
function add_item($product_id, $quantity) {
  global $cart;
  if (!isset($cart[$product_id])) {
    $cart[$product_id] = ['quantity' => 0, 'price' => 0];
  }
  $cart[$product_id]['quantity'] += $quantity;
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
  global $cart;
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove item from cart
function remove_item($product_id) {
  global $cart;
  unset($cart[$product_id]);
}

// Function to get total cost of items in cart
function get_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}

// Initialize cart with sample data
$products = [
  '1' => ['name' => 'Product A', 'price' => 19.99],
  '2' => ['name' => 'Product B', 'price' => 29.99],
  '3' => ['name' => 'Product C', 'price' => 39.99]
];

?>


<?php include_once 'cart.php'; ?>

<!-- Display cart contents -->
<h1>Cart Contents:</h1>
<table border="1">
  <tr>
    <th>Product</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Total</th>
  </tr>
  <?php foreach ($cart as $product_id => $item) { ?>
    <tr>
      <td><?php echo $products[$product_id]['name']; ?></td>
      <td><?php echo $item['quantity']; ?></td>
      <td>$<?php echo number_format($items['price'], 2); ?></td>
      <td>$<?php echo number_format($item['quantity'] * $items['price'], 2); ?></td>
    </tr>
  <?php } ?>
</table>

<!-- Add item to cart form -->
<form action="" method="post">
  <label for="product_id">Product:</label>
  <select name="product_id" id="product_id">
    <?php foreach ($products as $product_id => $item) { ?>
      <option value="<?php echo $product_id; ?>"><?php echo $item['name']; ?></option>
    <?php } ?>
  </select>

  <label for="quantity">Quantity:</label>
  <input type="number" name="quantity" id="quantity" min="1">

  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<!-- Update quantity form -->
<form action="" method="post">
  <label for="product_id">Product:</label>
  <select name="product_id" id="product_id">
    <?php foreach ($products as $product_id => $item) { ?>
      <option value="<?php echo $product_id; ?>"><?php echo $item['name']; ?></option>
    <?php } ?>
  </select>

  <label for="new_quantity">New Quantity:</label>
  <input type="number" name="new_quantity" id="new_quantity">

  <button type="submit" name="update_quantity">Update Quantity</button>
</form>

<!-- Remove item from cart form -->
<form action="" method="post">
  <label for="product_id">Product:</label>
  <select name="product_id" id="product_id">
    <?php foreach ($products as $product_id => $item) { ?>
      <option value="<?php echo $product_id; ?>"><?php echo $item['name']; ?></option>
    <?php } ?>
  </select>

  <button type="submit" name="remove_item">Remove from Cart</button>
</form>

<!-- Display total cost of items in cart -->
<h2>Total Cost: $<?php echo number_format(get_total(), 2); ?></h2>


<?php

// Add item to cart
if (isset($_POST['add_to_cart'])) {
  add_item($_POST['product_id'], $_POST['quantity']);
}

// Update quantity of item in cart
if (isset($_POST['update_quantity'])) {
  update_quantity($_POST['product_id'], $_POST['new_quantity']);
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
  remove_item($_POST['product_id']);
}

?>


<?php
// Initialize the session
session_start();

// Array to hold product information
$products = array(
    'product1' => array('name' => 'Product 1', 'price' => 9.99),
    'product2' => array('name' => 'Product 2', 'price' => 19.99),
    'product3' => array('name' => 'Product 3', 'price' => 29.99)
);

// Function to add product to cart
function add_product_to_cart($product_id) {
    // Get the current session data
    $cart = $_SESSION['cart'] ?? [];

    // Add product to cart if not already there
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = 1; // default quantity is 1
    } else {
        $cart[$product_id]++;
    }

    // Update session data with new cart contents
    $_SESSION['cart'] = $cart;

    // Redirect to cart page for updated view
    header('Location: cart.php');
}

// Function to remove product from cart
function remove_product_from_cart($product_id) {
    // Get the current session data
    $cart = $_SESSION['cart'] ?? [];

    // Remove product from cart if it exists
    unset($cart[$product_id]);

    // Update session data with new cart contents
    $_SESSION['cart'] = $cart;

    // Redirect to cart page for updated view
    header('Location: cart.php');
}

// Function to update quantity of product in cart
function update_quantity_in_cart($product_id, $quantity) {
    // Get the current session data
    $cart = $_SESSION['cart'] ?? [];

    // Update quantity of product if it exists
    if (isset($cart[$product_id])) {
        $cart[$product_id] = max(1, $quantity); // ensure quantity is at least 1
    }

    // Update session data with new cart contents
    $_SESSION['cart'] = $cart;

    // Redirect to cart page for updated view
    header('Location: cart.php');
}

// Display the cart content
?>
<table>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>
<?php
// Get the current session data
$cart = $_SESSION['cart'] ?? [];

// Loop through each product in cart and display its details
foreach ($products as $product_id => $product) {
    if (isset($cart[$product_id])) {
        ?>
        <tr>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $cart[$product_id]; ?></td>
            <td>$<?php echo number_format($product['price'] * $cart[$product_id], 2); ?></td>
        </tr>
        <?php
    }
}
?>
</table>

<a href="#" onclick="add_product_to_cart('product1')">Add Product 1 to Cart</a> |
<a href="#" onclick="add_product_to_cart('product2')">Add Product 2 to Cart</a> |
<a href="#" onclick="remove_product_from_cart('product3')">Remove Product 3 from Cart</a>

<form action="" method="post">
    <input type="hidden" name="product_id" value="product1">
    <input type="number" name="quantity" value="2">
    <button type="submit">Update Quantity of Product 1 in Cart</button>
</form>


<?php
// Initialize session array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId) {
    global $db;
    // Check if product is in stock
    $query = "SELECT * FROM products WHERE id = '$productId'";
    $result = mysqli_query($db, $query);
    $productInfo = mysqli_fetch_assoc($result);

    // Add item to cart array if it's not already there
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 1;
    } else {
        // If product is already in cart, increment quantity
        $_SESSION['cart'][$productId]++;
    }

    // Update stock levels (optional)
    $query = "UPDATE products SET stock_level = stock_level - ".$_SESSION['cart'][$productId]." WHERE id = '$productId'";
    mysqli_query($db, $query);
}

// Function to view cart contents
function viewCart() {
    global $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    echo "<table border='1'>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $productInfo = getProductInfo($productId);
        echo "<tr><td>" . $productInfo['name'] . "</td><td>$" . number_format($productInfo['price'] * $quantity) . "</td></tr>";
    }
    echo "</table>";
}

// Function to calculate total cost
function calculateTotal() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $productInfo = getProductInfo($productId);
        $total += $productInfo['price'] * $quantity;
    }
    return number_format($total);
}

// Function to clear cart
function clearCart() {
    global $_SESSION;
    unset($_SESSION['cart']);
}
?>


<?php
// Database connection settings
$db = mysqli_connect('localhost', 'username', 'password', 'database');

// Function to get product info
function getProductInfo($productId) {
    $query = "SELECT * FROM products WHERE id = '$productId'";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}
?>


<?php
require_once 'cart.php';
require_once 'cartFunctions.php';

// Add item to cart
addToCart(1);

// View cart contents
viewCart();

// Calculate total cost
echo "Total: $" . calculateTotal();

// Clear cart
clearCart();
?>


<?php
// Initialize the cart array
$cart = [];

// Function to add item to cart
function addItemToCart($itemId, $itemName, $price) {
  global $cart;
  if (!isset($cart[$itemId])) {
    $cart[$itemId] = [
      'name' => $itemName,
      'price' => $price,
      'quantity' => 1
    ];
  } else {
    $cart[$itemId]['quantity']++;
  }
}

// Function to remove item from cart
function removeItemFromCart($itemId) {
  global $cart;
  if (isset($cart[$itemId])) {
    unset($cart[$itemId]);
  }
}

// Function to update quantity of item in cart
function updateQuantity($itemId, $newQuantity) {
  global $cart;
  if (isset($cart[$itemId]) && $newQuantity > 0) {
    $cart[$itemId]['quantity'] = $newQuantity;
  } else {
    // If new quantity is invalid, remove item from cart
    removeItemFromCart($itemId);
  }
}

// Function to calculate total cost of items in cart
function calculateTotalCost() {
  global $cart;
  $totalCost = 0;
  foreach ($cart as $item) {
    $totalCost += $item['price'] * $item['quantity'];
  }
  return $totalCost;
}

// Function to display cart contents
function displayCart() {
  global $cart;
  ?>
  <h2>Cart Contents:</h2>
  <table border="1">
    <tr>
      <th>Item</th>
      <th>Quantity</th>
      <th>Price</th>
    </tr>
  <?php
  foreach ($cart as $item) {
    ?>
    <tr>
      <td><?= $item['name'] ?></td>
      <td><?= $item['quantity'] ?></td>
      <td><?= $item['price'] ?></td>
    </tr>
  <?php
  }
  ?>
  </table>
  <p>Total Cost: <?= calculateTotalCost() ?></p>
  <?php
}

// Example usage:
addItemToCart(1, "Apple", 0.99);
addItemToCart(2, "Banana", 0.59);

displayCart();

?>


// Start session
session_start();

// Initialize cart array
$cart = array();

// Function to add item to cart
function add_item($id, $name, $price) {
  global $cart;
  if (isset($_SESSION['cart'])) {
    $cart = unserialize($_SESSION['cart']);
  }
  $cart[] = array('id' => $id, 'name' => $name, 'price' => $price);
  $_SESSION['cart'] = serialize($cart);
}

// Function to remove item from cart
function remove_item($id) {
  global $cart;
  if (isset($_SESSION['cart'])) {
    $cart = unserialize($_SESSION['cart']);
    foreach ($cart as $key => $item) {
      if ($item['id'] == $id) {
        unset($cart[$key]);
      }
    }
    $_SESSION['cart'] = serialize(array_values($cart));
  }
}

// Function to update item quantity
function update_quantity($id, $quantity) {
  global $cart;
  if (isset($_SESSION['cart'])) {
    $cart = unserialize($_SESSION['cart']);
    foreach ($cart as &$item) {
      if ($item['id'] == $id) {
        $item['quantity'] = $quantity;
      }
    }
    $_SESSION['cart'] = serialize($cart);
  }
}

// Function to display cart contents
function display_cart() {
  global $cart;
  if (isset($_SESSION['cart'])) {
    $cart = unserialize($_SESSION['cart']);
    echo "<table border='1'>";
    foreach ($cart as $item) {
      echo "<tr>";
      echo "<td>" . $item['name'] . "</td>";
      echo "<td>Price: $" . number_format($item['price'], 2) . "</td>";
      echo "<td>Quantity:</td>";
      echo "<td><input type='number' value='" . (isset($item['quantity']) ? $item['quantity'] : 1) . "'></td>";
      echo "<td><button class='remove'>Remove</button></td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "Your cart is empty!";
  }
}


<?php include 'cart.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Cart</title>
</head>
<body>
  <h1>Purchase Cart</h1>
  <?php display_cart(); ?>

  <form action="add_item.php" method="post">
    <input type="text" name="id" placeholder="Item ID">
    <input type="text" name="name" placeholder="Item Name">
    <input type="number" name="price" step="0.01">
    <button type="submit">Add to Cart</button>
  </form>

  <?php if (isset($_SESSION['cart'])) : ?>
    <form action="update_quantity.php" method="post">
      <input type="hidden" name="id" value="<?= $_POST['id']; ?>">
      <input type="number" name="quantity" step="1">
      <button type="submit">Update Quantity</button>
    </form>

    <?php foreach ($_SESSION['cart'] as $key => $item) : ?>
      <button class="remove" onclick="removeItem(<?= $key; ?>)">Remove Item <?= $key; ?></button>
    <?php endforeach; ?>
  <?php endif; ?>

  <script>
    function removeItem(key) {
      fetch('/remove_item.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ key: key })
      });
    }
  </script>

</body>
</html>


<?php
  $id = $_POST['id'];
  $name = $_POST['name'];
  $price = floatval($_POST['price']);

  add_item($id, $name, $price);
?>


class Cart {
    private $products;

    public function __construct() {
        $this->products = array();
    }

    public function addProduct($productId, $quantity) {
        if (isset($this->products[$productId])) {
            $this->products[$productId]['quantity'] += $quantity;
        } else {
            $this->products[$productId] = array('id' => $productId, 'name' => '', 'price' => 0, 'quantity' => $quantity);
        }
    }

    public function removeProduct($productId) {
        if (isset($this->products[$productId])) {
            unset($this->products[$productId]);
        }
    }

    public function updateQuantity($productId, $newQuantity) {
        if (isset($this->products[$productId])) {
            $this->products[$productId]['quantity'] = $newQuantity;
        }
    }

    public function getProducts() {
        return $this->products;
    }
}


class Product {
    private $id;
    private $name;
    private $price;

    public function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }
}


class CartController {
    private $cart;

    public function __construct(Cart $cart) {
        $this->cart = $cart;
    }

    public function addProduct($productId, $quantity) {
        $product = new Product($productId, '', 0);
        $this->cart->addProduct($productId, $quantity);
    }

    public function removeProduct($productId) {
        $this->cart->removeProduct($productId);
    }

    public function updateQuantity($productId, $newQuantity) {
        $this->cart->updateQuantity($productId, $newQuantity);
    }

    public function getCart() {
        return $this->cart;
    }
}


// index.php

$cart = new Cart();
$controller = new CartController($cart);

if (isset($_POST['add'])) {
    $controller->addProduct($_POST['product_id'], $_POST['quantity']);
}

if (isset($_POST['remove'])) {
    $controller->removeProduct($_POST['product_id']);
}

if (isset($_POST['update'])) {
    $controller->updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

$products = $controller->getCart()->getProducts();

?>


<form action="" method="post">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <label>Quantity:</label>
    <input type="number" name="quantity"><br><br>
    <input type="submit" name="add" value="Add to Cart">
</form>


<?php
// session variables to store cart contents
$_SESSION['cart'] = array();

// function to add item to cart
function add_item_to_cart($product_id, $product_name, $price) {
    global $_SESSION;
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => 1
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity']++;
    }
}

// function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// function to calculate total cost of items in cart
function calculate_total_cost() {
    global $_SESSION;
    $total_cost = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_cost += $item['price'] * $item['quantity'];
    }
    return $total_cost;
}

// function to display contents of cart
function display_cart() {
    global $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "$item[name] x $item[quantity] = $" . ($item['price'] * $item['quantity']) . "<br>";
    }
}

// function to display cart summary
function display_cart_summary() {
    global $_SESSION;
    $total_cost = calculate_total_cost();
    echo "<h2>Cart Summary:</h2>";
    echo "Total Cost: $" . $total_cost . "<br>";
    echo "Number of Items: " . count($_SESSION['cart']) . "<br>";
}
?>


// add item to cart
add_item_to_cart(1, 'Product A', 10.99);
add_item_to_cart(2, 'Product B', 5.99);

// display cart contents
display_cart();

// update quantity of item in cart
update_quantity(1, 3);

// remove item from cart
remove_item_from_cart(2);

// display updated cart contents
display_cart();

// calculate total cost of items in cart
$total_cost = calculate_total_cost();
echo "Total Cost: $" . $total_cost;

// display cart summary
display_cart_summary();


<?php
// Initialize session
session_start();

// Set cart as array if not set before
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $db;
    
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id && $item['quantity'] < 10) { // max quantity is 10 for example
            $item['quantity'] += $quantity;
            return true; // item added successfully
        }
    }
    
    // Item not in cart, add it
    $db->query("SELECT * FROM products WHERE id = '$item_id'");
    $product = $db->fetch_array();
    
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
    );
    
    return true;
}

// Function to update quantity of item in cart
function update_quantity($item_id, $new_quantity) {
    global $db;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

// Function to remove item from cart
function remove_item($item_id) {
    global $db;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Function to calculate total cost of items in cart
function calculate_total() {
    global $db;
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product = $db->query("SELECT * FROM products WHERE id = '$item[id]'");
        $total += $product['price'] * $item['quantity'];
    }
    
    return $total;
}

// Function to display cart contents
function display_cart() {
    global $db;
    
    echo '<table>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>Quantity: ' . $item['quantity'] . '</td>';
        echo '<td>Price: $' . number_format($item['price'], 2) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Example usage
if (isset($_GET['add'])) {
    add_item_to_cart($_GET['id'], $_GET['quantity']);
} elseif (isset($_GET['update'])) {
    update_quantity($_GET['id'], $_GET['new_quantity']);
} elseif (isset($_GET['remove'])) {
    remove_item($_GET['id']);
}

?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart Example</title>
</head>
<body>

<h1>Purchase Cart</h1>

<form action="cart.php" method="get">
    <input type="hidden" name="add" value="true">
    <select name="id">
        <?php
            global $db;
            $products = $db->query("SELECT * FROM products");
            foreach ($products as $product) {
                echo '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
            }
        ?>
    </select>
    Quantity: <input type="number" name="quantity">
    <button type="submit">Add to Cart</button>
</form>

<form action="cart.php" method="get">
    <input type="hidden" name="update" value="true">
    <select name="id">
        <?php
            global $db;
            $products = $db->query("SELECT * FROM products");
            foreach ($products as $product) {
                echo '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
            }
        ?>
    </select>
    New Quantity: <input type="number" name="new_quantity">
    <button type="submit">Update Quantity</button>
</form>

<form action="cart.php" method="get">
    <input type="hidden" name="remove" value="true">
    <select name="id">
        <?php
            global $db;
            $products = $db->query("SELECT * FROM products");
            foreach ($products as $product) {
                echo '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
            }
        ?>
    </select>
    <button type="submit">Remove from Cart</button>
</form>

<h2>Cart Contents:</h2>
<?php display_cart(); ?>

<h2>Total Cost: $<?php echo number_format(calculate_total(), 2); ?></h2>

</body>
</html>


<?php
// Set session variables for cart data
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return true;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return true;
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    // Check if product is in cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }

    // Product not found, do nothing
    return false;
}

// Function to update item quantity in cart
function update_item_quantity($product_id, $new_quantity) {
    // Check if product is in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true;
        }
    }

    // Product not found, do nothing
    return false;
}

// Function to get total cart cost
function get_total_cart_cost() {
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        // Assuming product prices are stored in a database or array
        // For this example, we'll use a simple hardcoded price array
        $prices = array(
            1 => 10.99,
            2 => 9.99,
            3 => 12.99,
            // ...
        );
        $total += $item['quantity'] * $prices[$item['id']];
    }

    return $total;
}

// Example usage: add item to cart
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    $quantity = 1; // Default quantity
    if (isset($_GET['quantity'])) {
        $quantity = $_GET['quantity'];
    }
    add_item_to_cart($product_id, $quantity);
}

// Example usage: remove item from cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    remove_item_from_cart($product_id);
}

// Example usage: update item quantity in cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['update_quantity'];
    $new_quantity = $_POST['quantity'];
    update_item_quantity($product_id, $new_quantity);
}
?>


<?php include 'cart.php'; ?>

<!-- Display cart contents -->
<h2>Cart Contents</h2>
<table border="1">
  <tr>
    <th>Product ID</th>
    <th>Quantity</th>
    <th>Total Cost</th>
  </tr>
  <?php foreach ($_SESSION['cart'] as $item): ?>
  <tr>
    <td><?= $item['id']; ?></td>
    <td><?= $item['quantity']; ?></td>
    <td><?= number_format(get_total_cart_cost() / count($_SESSION['cart']), 2); ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<!-- Display total cart cost -->
<h3>Total Cart Cost: <?= number_format(get_total_cart_cost(), 2); ?></h3>

<!-- Add item to cart form -->
<form action="" method="get">
  <input type="hidden" name="add_to_cart" value="<?= $_GET['product_id']; ?>">
  <label>Quantity:</label>
  <input type="number" name="quantity" value="1">
  <button type="submit">Add to Cart</button>
</form>

<!-- Remove item from cart form -->
<form action="" method="get">
  <input type="hidden" name="remove_from_cart" value="<?= $_GET['product_id']; ?>">
  <button type="submit">Remove from Cart</button>
</form>

<!-- Update item quantity in cart form -->
<form action="" method="post">
  <label>Update Quantity:</label>
  <input type="number" name="quantity" value="">
  <input type="hidden" name="update_quantity" value="<?= $_POST['product_id']; ?>">
  <button type="submit">Update Quantity</button>
</form>


// Define cart configuration constants
define('CART_SESSION_NAME', 'cart');


function cartSessionCreate() {
    // Create a new cart session if it doesn't exist
    if (!isset($_SESSION[CART_SESSION_NAME])) {
        $_SESSION[CART_SESSION_NAME] = array();
    }
}

function cartSessionDestroy() {
    // Destroy the cart session
    unset($_SESSION[CART_SESSION_NAME]);
}


function cartAddProduct($id, $quantity) {
    // Add a product to the cart
    if (isset($_SESSION[CART_SESSION_NAME][$id])) {
        $_SESSION[CART_SESSION_NAME][$id]['quantity'] += $quantity;
    } else {
        $_SESSION[CART_SESSION_NAME][$id] = array('product_id' => $id, 'quantity' => $quantity);
    }
}

function cartRemoveProduct($id) {
    // Remove a product from the cart
    if (isset($_SESSION[CART_SESSION_NAME][$id])) {
        unset($_SESSION[CART_SESSION_NAME][$id]);
    }
}

function cartUpdateQuantity($id, $new_quantity) {
    // Update the quantity of a product in the cart
    if (isset($_SESSION[CART_SESSION_NAME][$id])) {
        $_SESSION[CART_SESSION_NAME][$id]['quantity'] = $new_quantity;
    }
}

function cartGetContents() {
    // Get the contents of the cart
    return $_SESSION[CART_SESSION_NAME];
}


// Create a new cart session
cartSessionCreate();

// Add some products to the cart
cartAddProduct(1, 2); // Product ID: 1, Quantity: 2
cartAddProduct(2, 3); // Product ID: 2, Quantity: 3

// Update the quantity of a product in the cart
cartUpdateQuantity(1, 4);

// Remove a product from the cart
cartRemoveProduct(2);

// Get the contents of the cart
$cart_contents = cartGetContents();

// Print the contents of the cart
print_r($cart_contents);


function cartCalculateTotalCost() {
    // Calculate the total cost of the items in the cart
    $total_cost = 0;
    foreach (cartGetContents() as $item) {
        // Get the price of the product from the database
        $price = get_product_price($item['product_id']);
        $total_cost += $price * $item['quantity'];
    }
    return $total_cost;
}


<?php
// Initialize the cart array
$cart = [];

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
  global $cart;
  if (!isset($cart[$item_id])) {
    $cart[$item_id] = ['quantity' => 0];
  }
  $cart[$item_id]['quantity'] += $quantity;
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
  global $cart;
  unset($cart[$item_id]);
}

// Function to update quantity of an item in the cart
function update_quantity_in_cart($item_id, $new_quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $new_quantity;
  }
}

// Function to display items in cart
function display_cart() {
  global $cart;
  echo '<h2>Your Cart:</h2>';
  foreach ($cart as $item_id => $item_data) {
    echo '<p>' . get_item_name($item_id) . ' x ' . $item_data['quantity'] . '</p>';
  }
}

// Function to calculate total cost of items in cart
function calculate_total_cost() {
  global $cart;
  $total = 0;
  foreach ($cart as $item_id => $item_data) {
    $price = get_item_price($item_id);
    $total += $price * $item_data['quantity'];
  }
  return $total;
}

// Function to display total cost
function display_total_cost() {
  echo '<p>Total: £' . calculate_total_cost() . '</p>';
}

// Function to process checkout ( dummy implementation )
function process_checkout() {
  // TO DO: implement actual payment processing
  echo '<h2>Thank you for your order!</h2>';
}


<?php include 'cart.php'; ?>

<h1>Shop</h1>

<!-- Example product data -->
<?php $products = [
  ['id' => 1, 'name' => 'Product A', 'price' => 10.99],
  ['id' => 2, 'name' => 'Product B', 'price' => 5.99],
  ['id' => 3, 'name' => 'Product C', 'price' => 7.99],
]; ?>

<!-- Display products -->
<?php foreach ($products as $product) : ?>
  <p>
    <?php echo $product['name']; ?> (£<?php echo $product['price']; ?>)
    <button onclick="add_item_to_cart(<?php echo $product['id']; ?>, 1)">Add to Cart</button>
  </p>
<?php endforeach; ?>

<!-- Display cart -->
<?php display_cart(); ?>
<?php display_total_cost(); ?>

<button onclick="process_checkout()">Checkout</button>

<script>
  function add_item_to_cart(item_id, quantity) {
    // TO DO: implement JavaScript equivalent of PHP's add_item_to_cart()
    console.log('Adding item to cart:', item_id, quantity);
  }
</script>


<?php
// Initialize the cart session variable
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($id, $name, $price) {
    global $_SESSION;
    if (array_key_exists($id, $_SESSION['cart'])) {
        // If the item already exists in the cart, increment its quantity
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        // Add new item to the cart with initial quantity of 1
        $_SESSION['cart'][$id] = array('name' => $name, 'price' => $price, 'quantity' => 1);
    }
}

// Function to remove item from cart
function removeFromCart($id) {
    global $_SESSION;
    unset($_SESSION['cart'][$id]);
}

// Function to update quantity of an item in the cart
function updateQuantity($id, $newQuantity) {
    global $_SESSION;
    if (array_key_exists($id, $_SESSION['cart'])) {
        // Update the quantity of the existing item
        $_SESSION['cart'][$id]['quantity'] = $newQuantity;
    }
}

// Function to display cart contents
function displayCart() {
    global $_SESSION;
    echo "<h2>Shopping Cart</h2>";
    if (!empty($_SESSION['cart'])) {
        // Display each item in the cart with its quantity and price
        foreach ($_SESSION['cart'] as $item) {
            echo "<p>$item[name] x $item[quantity] = $" . ($item['price'] * $item['quantity']) . "</p>";
        }
    } else {
        echo "<p>No items in the cart.</p>";
    }

    // Display total cost
    $totalCost = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalCost += ($item['price'] * $item['quantity']);
    }
    echo "<h3>Total Cost: $" . number_format($totalCost, 2) . "</h3>";
}

// Add some example items to the cart
addToCart(1, "Item 1", 10.99);
addToCart(2, "Item 2", 5.99);

// Display initial cart contents
displayCart();
?>


<?php include 'cart.php'; ?>

<!-- Add a form to update the quantity of an item in the cart -->
<form action="" method="post">
    <select name="item_id">
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
        <?php } ?>
    </select>
    Quantity: <input type="number" name="quantity">
    <input type="submit" name="update_quantity" value="Update Quantity">
</form>

<!-- Display the cart contents again after updating quantity -->
<?php if (isset($_POST['update_quantity'])) { ?>
    updateQuantity($_POST['item_id'], $_POST['quantity']);
    displayCart();
<?php } ?>


<?php include 'cart.php'; ?>

<!-- Add a form to remove an item from the cart -->
<form action="" method="post">
    <select name="item_id">
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
        <?php } ?>
    </select>
    <input type="submit" name="remove_item" value="Remove Item">
</form>

<!-- Display the cart contents again after removing an item -->
<?php if (isset($_POST['remove_item'])) { ?>
    removeFromCart($_POST['item_id']);
    displayCart();
<?php } ?>


<?php
session_start();

// Set default values for the cart
$_SESSION['cart'] = array();
$_SESSION['total_cost'] = 0;

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // Update quantity if product is already in cart
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to update cart
function update_cart($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find product in cart and update quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    
    // Find product in cart and remove it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to calculate total cost
function calculate_total_cost() {
    global $_SESSION;
    
    // Calculate total cost by summing up all product prices multiplied by quantity
    $total_cost = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product_price = // Get product price from database (replace with your own logic)
        $total_cost += $product_price * $item['quantity'];
    }
    
    $_SESSION['total_cost'] = $total_cost;
}

// Function to display cart contents
function display_cart_contents() {
    global $_SESSION;
    
    // Display each item in the cart, including product name and price
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        $product_name = // Get product name from database (replace with your own logic)
        $product_price = // Get product price from database (replace with your own logic)
        
        echo "$product_name ($item[quantity]) x $product_price = $" . $product_price * $item['quantity'] . "<br>";
    }
}

// Add item to cart
add_item_to_cart(1, 2); // Product ID 1, Quantity 2

// Update cart
update_cart(1, 3);

// Remove item from cart
remove_item_from_cart(1);

// Display cart contents
display_cart_contents();

// Calculate total cost
calculate_total_cost();
?>


<?php
include 'cart.php';

// Display cart contents and calculate total cost on page load
display_cart_contents();
calculate_total_cost();
?>

<!-- HTML for adding items to cart -->
<form action="" method="post">
    <input type="text" name="product_id" value="1"> // Product ID 1
    <input type="number" name="quantity" value="2"> // Quantity 2
    <button type="submit">Add to Cart</button>
</form>

<!-- HTML for updating cart -->
<form action="" method="post">
    <input type="text" name="product_id" value="1"> // Product ID 1
    <input type="number" name="new_quantity" value="3"> // New quantity 3
    <button type="submit">Update Cart</button>
</form>

<!-- HTML for removing item from cart -->
<form action="" method="post">
    <input type="text" name="product_id" value="1"> // Product ID 1
    <button type="submit">Remove from Cart</button>
</form>


<?php
// Initialize session
session_start();

// Define cart array to store items
$_SESSION['cart'] = array();

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
  global $_SESSION;
  // Check if product is already in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // Increase quantity of existing item
      $item['quantity'] += $quantity;
      return;
    }
  }
  // Add new item to cart
  $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_from_cart($product_id) {
  global $_SESSION;
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return;
    }
  }
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
  global $_SESSION;
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] = $new_quantity;
      return;
    }
  }
}

// Function to display cart contents
function display_cart() {
  global $_SESSION;
  echo '<h2>Cart Contents:</h2>';
  foreach ($_SESSION['cart'] as $item) {
    echo 'Product ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
  }
}
?>


<?php
require_once 'cart.php';
?>

<!-- HTML form to add items to cart -->
<form action="" method="post">
  <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
  <input type="text" name="quantity" placeholder="Quantity">
  <button type="submit">Add to Cart</button>
</form>

<?php
if (isset($_POST['product_id'])) {
  add_to_cart($_POST['product_id'], $_POST['quantity']);
}
?>

<!-- Display cart contents -->
<?php display_cart(); ?>


<?php
require_once 'cart.php';
?>

<!-- HTML form to update quantity of item in cart -->
<form action="" method="post">
  <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
  <input type="text" name="new_quantity" placeholder="New Quantity">
  <button type="submit">Update Quantity</button>
</form>

<?php
if (isset($_POST['product_id'])) {
  update_quantity($_POST['product_id'], $_POST['new_quantity']);
}
?>

<!-- Display cart contents -->
<?php display_cart(); ?>


<?php
require_once 'cart.php';
?>

<!-- HTML form to remove item from cart -->
<form action="" method="post">
  <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
  <button type="submit">Remove from Cart</button>
</form>

<?php
if (isset($_POST['product_id'])) {
  remove_from_cart($_POST['product_id']);
}
?>

<!-- Display cart contents -->
<?php display_cart(); ?>


// cart.php

class Cart {
    private $items = array();
    private $subtotal = 0;
    private $tax_rate = 0.08; // default tax rate is 8%

    public function add_item($item_id, $quantity) {
        if (isset($this->items[$item_id])) {
            $this->items[$item_id]['quantity'] += $quantity;
        } else {
            $this->items[$item_id] = array('price' => 0, 'quantity' => $quantity);
        }
    }

    public function remove_item($item_id) {
        if (isset($this->items[$item_id])) {
            unset($this->items[$item_id]);
        }
    }

    public function update_quantity($item_id, $new_quantity) {
        if (isset($this->items[$item_id])) {
            $this->items[$item_id]['quantity'] = $new_quantity;
        }
    }

    public function get_subtotal() {
        foreach ($this->items as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        return $this->subtotal;
    }

    public function get_tax() {
        return $this->get_subtotal() * $this->tax_rate;
    }

    public function get_total() {
        return $this->get_subtotal() + $this->get_tax();
    }

    public function get_items() {
        return $this->items;
    }
}


// cart.php

class CartView {
    private $cart;

    public function __construct($cart) {
        $this->cart = $cart;
    }

    public function display_cart() {
        echo '<h1>Shopping Cart</h1>';
        echo '<table border="1">';
        echo '<tr><th>Item ID</th><th>Quantity</th><th>Price</th></tr>';

        foreach ($this->cart->get_items() as $item_id => $item) {
            echo '<tr>';
            echo '<td>' . $item_id . '</td>';
            echo '<td>' . $item['quantity'] . '</td>';
            echo '<td>$' . number_format($item['price'] * $item['quantity'], 2) . '</td>';
            echo '</tr>';
        }

        echo '</table>';

        echo 'Subtotal: $' . number_format($this->cart->get_subtotal(), 2);
        echo '<br> Tax (8%): $' . number_format($this->cart->get_tax(), 2);
        echo '<br> Total: $' . number_format($this->cart->get_total(), 2);
    }
}


// cart.php

class CartController {
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function add_item($item_id, $quantity) {
        $this->cart->add_item($item_id, $quantity);
    }

    public function remove_item($item_id) {
        $this->cart->remove_item($item_id);
    }

    public function update_quantity($item_id, $new_quantity) {
        $this->cart->update_quantity($item_id, $new_quantity);
    }

    public function display_cart() {
        $view = new CartView($this->cart);
        $view->display_cart();
    }
}


// products.php

class Products {
    private $items = array(
        'item1' => array('price' => 9.99, 'name' => 'Product 1'),
        'item2' => array('price' => 19.99, 'name' => 'Product 2'),
        // ...
    );

    public function get_items() {
        return $this->items;
    }
}


// index.php

require_once 'cart.php';
require_once 'products.php';

$products = new Products();
$product_list = $products->get_items();

$cart_controller = new CartController();

echo '<h1>Shopping Cart</h1>';
echo '<form action="index.php" method="post">';

foreach ($product_list as $item_id => $item) {
    echo '<input type="checkbox" name="items[]" value="' . $item_id . '">';
    echo '<label>' . $item['name'] . '</label> <br>';
}

echo '<button type="submit">Add to Cart</button>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['items'] as $item_id) {
        $cart_controller->add_item($item_id, 1);
    }
}

$cart_controller->display_cart();

?>


class Cart {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    }

    // Add a product to the cart
    public function addProduct($userId, $productId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error adding product to cart: " . $e->getMessage();
        }
    }

    // Remove a product from the cart
    public function removeProduct($cartId) {
        try {
            $this->db->exec("DELETE FROM cart WHERE id = :id");
            $stmt = $this->db->prepare("UPDATE products SET quantity = quantity + 1 WHERE id = (SELECT product_id FROM cart WHERE id = :id)");
            $stmt->bindParam(':id', $cartId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error removing product from cart: " . $e->getMessage();
        }
    }

    // Get the contents of the cart
    public function getCart($userId) {
        try {
            $stmt = $this->db->prepare("SELECT c.id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching cart contents: " . $e->getMessage();
        }
    }

    // Update the quantity of a product in the cart
    public function updateQuantity($cartId, $newQuantity) {
        try {
            $this->db->exec("UPDATE cart SET quantity = :quantity WHERE id = :id");
            $stmt = $this->db->prepare("UPDATE products SET quantity = quantity - 1 WHERE id = (SELECT product_id FROM cart WHERE id = :id)");
            $stmt->bindParam(':quantity', $newQuantity);
            $stmt->bindParam(':id', $cartId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error updating quantity: " . $e->getMessage();
        }
    }

    // Calculate the total cost of the cart
    public function calculateTotal($userId) {
        try {
            $stmt = $this->db->prepare("SELECT SUM(p.price * c.quantity) AS total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo "Error calculating total: " . $e->getMessage();
        }
    }
}


$cart = new Cart();

// Add a product to the cart
$userId = 1;
$productIds = array(1, 2, 3);
foreach ($productIds as $productId) {
    $cart->addProduct($userId, $productId);
}

// Get the contents of the cart
$cartContents = $cart->getCart($userId);

// Update the quantity of a product in the cart
$cartId = 1;
$newQuantity = 2;
$cart->updateQuantity($cartId, $newQuantity);

// Calculate the total cost of the cart
$totalCost = $cart->calculateTotal($userId);


<?php

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productID, $productName, $price) {
    $item = array(
        'id' => $productID,
        'name' => $productName,
        'price' => $price
    );
    $_SESSION['cart'][] = $item;
}

// Function to display cart contents
function showCart() {
    if (count($_SESSION['cart']) == 0) {
        echo "Your cart is empty.";
        return;
    }
    
    echo "<h2>Your Cart:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th></tr>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr><td>$item[name]</td><td>$item[price]</td></tr>";
    }
    echo "</table>";
}

// Function to remove item from cart
function removeFromCart($productID) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productID) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to calculate total cost of items in cart
function getTotalCost() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'];
    }
    return $total;
}

?>


<?php

// Assume we have a database connection set up here...

// Retrieve products from database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

echo "<h2>Products:</h2>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<a href='cart.php?add=$row[id]'>$row[name]</a> - $" . number_format($row['price'], 2);
}

?>


<?php

// Include cart functionality file
include 'cart.php';

// Check if user has clicked "Add to Cart" button
if (isset($_GET['add'])) {
    $productID = $_GET['add'];
    // Retrieve product details from database
    $query = "SELECT * FROM products WHERE id='$productID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    addToCart($productID, $row['name'], $row['price']);
}

// Display cart contents
showCart();

?>


class Cart {
    private $cart;

    public function __construct() {
        $this->cart = array();
    }

    // Method to add an item to the cart
    public function addItem($product_id) {
        if (isset($this->cart[$product_id])) {
            $this->cart[$product_id]['quantity']++;
        } else {
            $this->cart[$product_id] = array('id' => $product_id, 'price' => 0.00);
            $product_data = get_product_info($product_id); // Function to retrieve product info from database
            $this->cart[$product_id]['price'] = $product_data['price'];
        }
    }

    // Method to remove an item from the cart
    public function removeItem($product_id) {
        if (isset($this->cart[$product_id])) {
            unset($this->cart[$product_id]);
        }
    }

    // Method to update quantity of an item in the cart
    public function updateQuantity($product_id, $quantity) {
        if (isset($this->cart[$product_id])) {
            $this->cart[$product_id]['quantity'] = $quantity;
        }
    }

    // Method to clear entire cart
    public function clearCart() {
        $this->cart = array();
    }

    // Method to calculate total cost of all items in the cart
    public function calculateTotal() {
        $total = 0.00;
        foreach ($this->cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Method to display contents of the cart
    public function displayCart() {
        echo '<table>';
        foreach ($this->cart as $item) {
            echo '<tr><td>' . get_product_name($item['id']) . '</td><td>Quantity: ' . $item['quantity'] . '</td><td>Price: ' . $item['price'] . '</td></tr>';
        }
        echo '</table>';
    }

    // Method to save cart items to database
    public function saveToDatabase() {
        foreach ($this->cart as $product_id => $item) {
            if (isset($item['id'])) {
                $data = array('product_id' => $item['id'], 'quantity' => $item['quantity']);
                insert_cart_item($product_id, $data); // Function to insert cart item into database
            }
        }
    }

    // Method to load cart items from database
    public function loadFromDatabase() {
        $cart_items = get_cart_items(); // Function to retrieve cart items from database
        foreach ($cart_items as $item) {
            if (isset($this->cart[$item['product_id']])) {
                $this->cart[$item['product_id']]['quantity'] += $item['quantity'];
            } else {
                $this->cart[$item['product_id']] = array('id' => $item['product_id'], 'price' => 0.00);
                $product_data = get_product_info($item['product_id']); // Function to retrieve product info from database
                $this->cart[$item['product_id']]['price'] = $product_data['price'];
            }
        }
    }
}


function get_product_info($id) {
    global $db;
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}

function insert_cart_item($cart_id, $data) {
    global $db;
    $query = "INSERT INTO cart (product_id, quantity) VALUES ('$data[product_id]', '$data[quantity]')";
    if (mysqli_query($db, $query)) {
        return true;
    } else {
        return false;
    }
}

function get_cart_items() {
    global $db;
    $query = "SELECT * FROM cart";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


$cart = new Cart();
$cart->loadFromDatabase(); // Load cart items from database

// Add items to cart
$cart->addItem(1);
$cart->addItem(2);

// Remove item from cart
$cart->removeItem(1);

// Update quantity of an item in the cart
$cart->updateQuantity(2, 3);

// Display contents of the cart
$cart->displayCart();

// Save cart items to database
$cart->saveToDatabase();


<?php
session_start();

// Check if the cart is already loaded in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    global $_SESSION;
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity if it's the same product
            $item['quantity'] += $quantity;
            return true;
        }
    }
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return true;
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
    return false;
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true;
        }
    }
    return false;
}

// Function to calculate total cost of cart
function calculateTotal() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>


<?php
require_once 'cart.php';

// Initialize cart with some sample products (you can replace this with your database or API)
$_SESSION['products'] = array(
    1 => array('name' => 'Product 1', 'price' => 9.99),
    2 => array('name' => 'Product 2', 'price' => 14.99),
    3 => array('name' => 'Product 3', 'price' => 19.99)
);

// Example usage: add item to cart
addItemToCart(1, 2);
addItemToCart(2, 1);
addItemToCart(1, 3); // quantity will be increased for product 1

// Get total cost of cart
$total = calculateTotal();
echo 'Total: $' . number_format($total, 2);

// Example usage: remove item from cart
removeItemFromCart(2);

// Example usage: update quantity of item in cart
updateQuantity(1, 5);
?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item($product_id, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Function to remove item from cart
function remove_item($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to calculate total cost of items in cart
function calculate_total() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

// Example product data
$products = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 9.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 14.99),
    array('id' => 3, 'name' => 'Product C', 'price' => 19.99)
);

?>


<?php include 'cart.php'; ?>

<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<form action="add_item.php" method="post">
    <select name="product_id">
        <?php foreach ($products as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?> - <?php echo $product['price']; ?></option>
        <?php } ?>
    </select>
    <input type="text" name="quantity" placeholder="Quantity">
    <button type="submit">Add to Cart</button>
</form>

<h2>Cart Contents:</h2>

<ul>
    <?php foreach ($_SESSION['cart'] as $product_id => $item) { ?>
        <li><?php echo $products[$product_id]['name']; ?> x <?php echo $item['quantity']; ?> = <?php echo $products[$product_id]['price'] * $item['quantity']; ?></li>
    <?php } ?>
</ul>

<h2>Total:</h2>
<p><?php echo calculate_total(); ?></p>

<form action="remove_item.php" method="post">
    <select name="product_id">
        <?php foreach ($products as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?> - <?php echo $product['price']; ?></option>
        <?php } ?>
    </select>
    <button type="submit">Remove from Cart</button>
</form>

<form action="update_quantity.php" method="post">
    <select name="product_id">
        <?php foreach ($products as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?> - <?php echo $product['price']; ?></option>
        <?php } ?>
    </select>
    <input type="text" name="new_quantity" placeholder="New Quantity">
    <button type="submit">Update Quantity</button>
</form>

</body>
</html>


<?php include 'cart.php'; ?>

<?php add_item($_POST['product_id'], $_POST['quantity']); ?>
<meta http-equiv="refresh" content="0; url=index.php">


<?php include 'cart.php'; ?>

<?php remove_item($_POST['product_id']); ?>
<meta http-equiv="refresh" content="0; url=index.php">


<?php include 'cart.php'; ?>

<?php update_quantity($_POST['product_id'], $_POST['new_quantity']); ?>
<meta http-equiv="refresh" content="0; url=index.php">


<?php
// Initialize session
session_start();

// Define cart array to store items
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // Update quantity if item is already in cart
            $item['quantity'] += $quantity;
            return true;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
    return true;
}

// Function to update quantity of item in cart
function update_item_quantity($item_id, $new_quantity) {
    global $_SESSION;
    
    // Find item in cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return true;
        }
    }
    return false;
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    // Find and remove item from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
    return false;
}

// Function to calculate total cost of items in cart
function calculate_total() {
    global $_SESSION;
    
    // Initialize total cost to 0
    $total = 0;
    
    // Calculate total cost by summing up quantities and prices of all items in cart
    foreach ($_SESSION['cart'] as &$item) {
        $total += $item['quantity'] * get_item_price($item['id']);
    }
    
    return $total;
}

// Function to retrieve item price (example: assume we have a function `get_item_price` that retrieves the price of an item from database)
function get_item_price($item_id) {
    // Example implementation:
    $prices = array(
        1 => 9.99,
        2 => 19.99,
        3 => 29.99
    );
    
    return isset($prices[$item_id]) ? $prices[$item_id] : 0;
}

// Function to display cart contents
function display_cart_contents() {
    global $_SESSION;
    
    // Display each item in cart with its quantity and price
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as &$item) {
        echo "Item ID: $item[id] - Quantity: $item[quantity] - Price: $" . get_item_price($item['id']) . "<br>";
    }
    
    // Display total cost
    echo "<p>Total Cost: $" . calculate_total() . "</p>";
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    add_item_to_cart($_POST['item_id'], $_POST['quantity']);
} elseif (isset($_POST['update_quantity'])) {
    update_item_quantity($_POST['item_id'], $_POST['new_quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    remove_item_from_cart($_POST['item_id']);
}

// Display cart contents
display_cart_contents();
?>


<?php
include 'cart.php';
?>

<form action="cart.php" method="post">
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity"><br><br>
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<form action="cart.php" method="post">
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <label for="new_quantity">New Quantity:</label>
    <input type="number" id="new_quantity" name="new_quantity"><br><br>
    <button type="submit" name="update_quantity">Update Quantity</button>
</form>

<form action="cart.php" method="post">
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <button type="submit" name="remove_from_cart">Remove from Cart</button>
</form>

<?php
// Display cart contents (same as in cart.php)
display_cart_contents();
?>


class Cart {
    private $items = array();
    private $totalPrice = 0;
    private $taxRate = 0.08; // default tax rate

    public function add($item, $quantity) {
        if (!isset($this->items[$item['id']])) {
            $this->items[$item['id']] = array(
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => 0,
                'totalPrice' => 0
            );
        }

        $this->items[$item['id']]['quantity'] += $quantity;
        $this->items[$item['id']]['totalPrice'] = $item['price'] * $quantity;

        $this->updateTotalPrice();
    }

    public function remove($itemId) {
        if (isset($this->items[$itemId])) {
            unset($this->items[$itemId]);
            $this->updateTotalPrice();
        }
    }

    private function updateTotalPrice() {
        $this->totalPrice = 0;
        foreach ($this->items as $item) {
            $this->totalPrice += $item['totalPrice'];
        }

        // apply tax
        $this->totalPrice *= (1 + $this->taxRate);
    }
}


session_start();


$_SESSION['cart'] = new Cart();
$_SESSION['cart']->totalPrice = 0;


function addProductToCart($productId, $quantity) {
    // get the product data from the database or wherever it is stored
    $product = getProductById($productId);

    // add the product to the cart
    $_SESSION['cart']->add(array(
        'id' => $productId,
        'name' => $product['name'],
        'price' => $product['price']
    ), $quantity);
}


function displayCart() {
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart']->items as $item) {
        echo "$" . number_format($item['price']) . " x " . $item['quantity'] . " = $" . number_format($item['totalPrice']) . "<br>";
    }

    echo "Total Price: $" . number_format($_SESSION['cart']->totalPrice);
}


// add 2 products to the cart
addProductToCart(1, 2);
addProductToCart(3, 1);

// display the cart contents
displayCart();

// remove one product from the cart
$_SESSION['cart']->remove(1);

// display the updated cart contents
displayCart();


<?php
// Initialize session variables
session_start();

// Function to add product to cart
function addToCart($productId) {
    // Get product details from database
    $product = getProduct($productId);

    // If product is not found, return false
    if (!$product) {
        return false;
    }

    // Check if product is already in cart
    $cartItems = getCartItems();
    foreach ($cartItems as &$item) {
        if ($item['id'] == $productId) {
            // Increment quantity of existing item
            $item['quantity']++;
            break;
        }
    }

    // If product is not in cart, add it with quantity 1
    if (!isset($cartItems[$productId])) {
        $cartItems[$productId] = array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1
        );
    }

    // Update session cart data
    $_SESSION['cart'] = $cartItems;

    return true;
}

// Function to remove product from cart
function removeFromCart($productId) {
    // Get current cart items
    $cartItems = getCartItems();

    // If product is in cart, delete it
    if (isset($cartItems[$productId])) {
        unset($cartItems[$productId]);
    }

    // Update session cart data
    $_SESSION['cart'] = $cartItems;

    return true;
}

// Function to update quantity of a product in the cart
function updateQuantity($productId, $quantity) {
    // Get current cart items
    $cartItems = getCartItems();

    // If product is in cart and quantity is valid, update it
    if (isset($cartItems[$productId]) && $quantity > 0) {
        $cartItems[$productId]['quantity'] = $quantity;
    }

    // Update session cart data
    $_SESSION['cart'] = $cartItems;

    return true;
}

// Function to calculate total cost of the cart
function calculateTotal() {
    // Get current cart items
    $cartItems = getCartItems();

    // Calculate total cost by summing up the product prices and quantities
    $total = 0;
    foreach ($cartItems as &$item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// Function to get all cart items
function getCartItems() {
    // Return session cart data if it exists, otherwise initialize an empty array
    return $_SESSION['cart'] ?? [];
}


<?php
include 'cart.php';

// Get products from database ( example )
$products = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 10.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 5.49),
    // ...
);

// Display cart
?>
<h1>Shopping Cart</h1>

<table>
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>
    <?php foreach (getCartItems() as $item): ?>
    <tr>
        <td><?= $item['name'] ?></td>
        <td><?= $item['price'] ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= $item['price'] * $item['quantity'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Total: <?= calculateTotal() ?></h2>

<?php if (isset($_SESSION['cart'])): ?>
<a href="checkout.php">Checkout</a>
<?php endif; ?>

<form action="" method="post">
    <select name="product_id" id="product_id">
        <?php foreach ($products as $product): ?>
            <option value="<?= $product['id'] ?>"><?= $product['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="quantity" id="quantity" value="1">

    <button type="submit">Add to Cart</button>
</form>


<?php
include 'cart.php';

// Process checkout form submission ( example )
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save cart data to database
    saveCartData();
}

// Display saved cart data
?>
<h1>Checkout Successful!</h1>

<p>Your order has been successfully processed.</p>


class Cart {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    // Add an item to the cart
    function addItem($product_id, $quantity = 1) {
        try {
            $stmt = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
            $stmt->bindParam(":cart_id", $_SESSION["cart_id"]);
            $stmt->bindParam(":product_id", $product_id);
            $stmt->bindParam(":quantity", $quantity);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error adding item to cart: " . $e->getMessage();
        }
    }

    // Remove an item from the cart
    function removeItem($product_id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM cart_items WHERE product_id = :product_id AND cart_id = :cart_id");
            $stmt->bindParam(":product_id", $product_id);
            $stmt->bindParam(":cart_id", $_SESSION["cart_id"]);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error removing item from cart: " . $e->getMessage();
        }
    }

    // Update the quantity of an item in the cart
    function updateQuantity($product_id, $new_quantity) {
        try {
            $stmt = $this->db->prepare("UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id AND cart_id = :cart_id");
            $stmt->bindParam(":quantity", $new_quantity);
            $stmt->bindParam(":product_id", $product_id);
            $stmt->bindParam(":cart_id", $_SESSION["cart_id"]);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error updating quantity: " . $e->getMessage();
        }
    }

    // Get the total cost of items in the cart
    function getTotalCost() {
        try {
            $stmt = $this->db->prepare("SELECT SUM(ci.quantity * p.price) AS total FROM cart_items ci INNER JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = :cart_id");
            $stmt->bindParam(":cart_id", $_SESSION["cart_id"]);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo "Error getting total cost: " . $e->getMessage();
        }
    }

    // Checkout the cart
    function checkout() {
        try {
            // Clear the cart items table for this user
            $this->db->query("DELETE FROM cart_items WHERE cart_id = :cart_id", array(":cart_id" => $_SESSION["cart_id"]));

            // Insert a new order into the database
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_cost) VALUES (:user_id, :total_cost)");
            $stmt->bindParam(":user_id", $_SESSION["user_id"]);
            $stmt->bindParam(":total_cost", $this->getTotalCost());
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error checking out cart: " . $e->getMessage();
        }
    }
}


// Initialize the Cart class with a database connection
$db = new PDO("mysql:host=localhost;dbname=example", "username", "password");
$cart = new Cart($db);

// Add items to the cart
$_SESSION["cart_id"] = 1;
$cart->addItem(1, 2);
$cart->addItem(3, 4);

// Remove an item from the cart
$cart->removeItem(3);

// Update quantity of an item in the cart
$cart->updateQuantity(1, 5);

// Get total cost of items in the cart
echo $cart->getTotalCost();

// Checkout the cart
$cart->checkout();


class Cart {
    private $items;

    public function __construct() {
        $this->items = array();
    }

    public function add_item($product_id, $quantity) {
        if (array_key_exists($product_id, $this->items)) {
            $this->items[$product_id]['quantity'] += $quantity;
        } else {
            $this->items[$product_id] = array('product' => get_product($product_id), 'quantity' => $quantity);
        }
    }

    public function remove_item($product_id) {
        if (array_key_exists($product_id, $this->items)) {
            unset($this->items[$product_id]);
        }
    }

    public function update_quantity($product_id, $new_quantity) {
        if (array_key_exists($product_id, $this->items)) {
            $this->items[$product_id]['quantity'] = $new_quantity;
        }
    }

    public function get_total() {
        $total = 0;
        foreach ($this->items as $item) {
            $price = $item['product']['price'];
            $total += $price * $item['quantity'];
        }
        return $total;
    }

    public function display_cart() {
        echo '<h2>Cart:</h2>';
        foreach ($this->items as $item) {
            echo $item['product']['name'] . ' x' . $item['quantity'] . ': $' . number_format($item['product']['price'] * $item['quantity'], 2) . '<br>';
        }
        echo 'Total: $' . number_format($this->get_total(), 2);
    }
}


function get_product($product_id) {
    // Connect to database and retrieve product data
    $db = new mysqli('localhost', 'username', 'password', 'database');
    if ($result = $db->query("SELECT * FROM products WHERE id = '$product_id'")) {
        return $result->fetch_assoc();
    }
}


$cart = new Cart();

// Add items to cart
$cart->add_item(1, 2);
$cart->add_item(3, 1);

// Display cart
$cart->display_cart();

// Update quantity of item in cart
$cart->update_quantity(1, 3);

// Remove item from cart
$cart->remove_item(3);

// Display updated cart
$cart->display_cart();


<?php
session_start();

// Initialize cart array if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id) {
    global $conn;

    // Get product details from database
    $query = "SELECT * FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product_name = $row['name'];
            $price = $row['price'];

            // Add to session cart
            array_push($_SESSION['cart'], array('id' => $product_id, 'name' => $product_name, 'price' => $price));
        }
    }

    return mysqli_affected_rows($conn);
}

function remove_from_cart($product_id) {
    global $conn;

    // Check if product exists in cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    return count($_SESSION['cart']);
}

function get_cart() {
    global $conn;

    // Get product details from database
    $cart = array();
    foreach ($_SESSION['cart'] as $item) {
        $query = "SELECT * FROM products WHERE id='$item[id]'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product_name = $row['name'];
                $price = $row['price'];

                array_push($cart, array('id' => $item['id'], 'name' => $product_name, 'price' => $price));
            }
        }
    }

    return $cart;
}

function calculate_total() {
    global $conn;

    // Calculate total cost
    $total = 0;
    foreach ($_SESSION['cart'] as $value) {
        $query = "SELECT * FROM products WHERE id='$value[id]'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $price = $row['price'];

                $total += $price;
            }
        }
    }

    return $total;
}

function clear_cart() {
    unset($_SESSION['cart']);
}
?>


<?php
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id']);
}

if (isset($_GET['remove'])) {
    remove_from_cart($_GET['remove']);
}

if (isset($_GET['clear'])) {
    clear_cart();
}
?>


<?php
$cart = get_cart();

echo "Your Cart Contents:
";
foreach ($cart as $value) {
    echo "ID: {$value['id']} | Name: {$value['name']} | Price: {$value['price']}
";
}

echo "
Total Cost: $" . calculate_total();
?>


<?php
// Initialize the cart session variable
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return true; // Product already in cart, increment quantity
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
    return false; // New product added to cart
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true; // Item removed from cart
        }
    }
    return false; // Item not found in cart
}

// Function to update quantity of item in cart
function update_quantity_in_cart($product_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated
        }
    }
    return false; // Item not found in cart
}

// Function to calculate total cost of items in cart
function calculate_total_cost() {
    global $_SESSION;
    
    $total_cost = 0;
    foreach ($_SESSION['cart'] as &$item) {
        $product_price = get_product_price($item['product_id']);
        $total_cost += $product_price * $item['quantity'];
    }
    return $total_cost;
}

// Function to display cart contents
function display_cart_contents() {
    global $_SESSION;
    
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Product</th><th>Quantity</th><th>Total Cost</th></tr>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr><td>' . get_product_name($item['product_id']) . '</td><td>' . $item['quantity'] . '</td><td>$' . (get_product_price($item['product_id']) * $item['quantity']) . '</td></tr>';
    }
    echo '</table>';
}

// Function to process checkout (simple example, may need to be modified for actual use case)
function process_checkout() {
    global $_SESSION;
    
    // For this example, we'll just clear the cart and redirect back to the home page
    unset($_SESSION['cart']);
    header('Location: index.php');
}

// Helper function to get product price (replace with your own database query or logic)
function get_product_price($product_id) {
    return 9.99; // Just a placeholder, replace with actual product prices
}

// Helper function to get product name (replace with your own database query or logic)
function get_product_name($product_id) {
    return 'Example Product'; // Just a placeholder, replace with actual product names
}


<?php
require_once('cart.php');

// Add item to cart
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);

// Display cart contents
display_cart_contents();

// Remove item from cart
remove_item_from_cart(2);

// Update quantity of item in cart
update_quantity_in_cart(1, 4);

// Calculate total cost of items in cart
echo '<p>Total Cost: $' . calculate_total_cost() . '</p>';

// Process checkout (simple example)
process_checkout();
?>


<?php

class Cart {
    private $items;

    function __construct() {
        $this->items = array();
    }

    // Add an item to the cart with a specific quantity
    function add_item($product_id, $quantity) {
        if (array_key_exists($product_id, $this->items)) {
            $this->items[$product_id]['quantity'] += $quantity;
        } else {
            $this->items[$product_id] = array(
                'product_name' => $_SESSION['products'][$product_id]['name'],
                'price' => $_SESSION['products'][$product_id]['price'],
                'quantity' => $quantity
            );
        }
    }

    // Remove an item from the cart
    function remove_item($product_id) {
        if (array_key_exists($product_id, $this->items)) {
            unset($this->items[$product_id]);
        }
    }

    // Update the quantity of an item in the cart
    function update_quantity($product_id, $quantity) {
        if (array_key_exists($product_id, $this->items)) {
            $this->items[$product_id]['quantity'] = max(1, min($quantity, 1000));
        }
    }

    // Calculate the total cost of items in the cart
    function calculate_total() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Display the contents of the cart
    function display_cart() {
        ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price per unit</th>
                <th>Total Price</th>
            </tr>
        <?php
        foreach ($this->items as $item) { ?>
            <tr>
                <td><?= $item['product_name'] ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php } ?>
            <tr>
                <th>Total</th>
                <th colspan="3"><?= number_format($this->calculate_total(), 2) ?></th>
            </tr>
        </table>
        <?php
    }
}

// Initialize the cart and display it
$cart = new Cart();
if (isset($_SESSION['products'])) {
    $cart->display_cart();
}
?>


<?php
require_once 'cart.php';

// Mock products data
$_SESSION['products'] = array(
    1 => array('name' => 'Product 1', 'price' => 9.99),
    2 => array('name' => 'Product 2', 'price' => 19.99)
);

// Handle form submission to add items to the cart
if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $cart->add_item($product_id, $quantity);
}

// Display the cart and forms to add or remove items
?>
<form action="" method="post">
    <select name="product_id" id="product-id">
        <?php foreach ($_SESSION['products'] as $id => $product) { ?>
            <option value="<?= $id ?>"><?= $product['name'] ?></option>
        <?php } ?>
    </select>
    <input type="number" name="quantity" min="1" max="1000">
    <button type="submit" name="add">Add to Cart</button>
</form>

<?= $cart->display_cart() ?>

<form action="" method="post">
    <select name="product_id" id="remove-product-id">
        <?php foreach ($cart->items as $id => $item) { ?>
            <option value="<?= $id ?>"><?= $item['product_name'] ?></option>
        <?php } ?>
    </select>
    <button type="submit" name="remove">Remove from Cart</button>
</form>

<form action="" method="post">
    <input type="hidden" name="update_product_id" value="">
    <input type="number" name="new_quantity" min="1" max="1000">
    <button type="submit" name="update">Update Quantity</button>
</form>


<?php

// Set session variables for cart and order
session_start();
$cart = array();
$order_id = uniqid();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $cart;
    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $cart;
    if (array_key_exists($product_id, $cart)) {
        unset($cart[$product_id]);
    }
}

// Function to update quantity of item in cart
function update_quantity_in_cart($product_id, $quantity) {
    global $cart;
    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id] = $quantity;
    } else {
        echo "Product not found in cart.";
    }
}

// Function to display total cost
function calculate_total_cost() {
    global $cart;
    $total_cost = 0;
    foreach ($cart as $product_id => $quantity) {
        // Retrieve product price from database or external API
        $price = get_product_price($product_id);
        $total_cost += $price * $quantity;
    }
    return $total_cost;
}

// Function to checkout
function checkout() {
    global $order_id, $cart;
    // Create new order in database
    create_new_order($order_id, $cart);
    // Clear cart and session variables
    clear_cart_and_session();
}

?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<!-- Display current cart contents -->
<table border="1">
    <tr>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total Cost</th>
        <th>Actions</th>
    </tr>
    <?php
        foreach ($cart as $product_id => $quantity) {
            // Retrieve product details from database or external API
            $price = get_product_price($product_id);
            $total_cost = $price * $quantity;
            ?>
            <tr>
                <td><?= $product_id ?></td>
                <td><?= $quantity ?></td>
                <td><?= $price ?></td>
                <td><?= $total_cost ?></td>
                <td>
                    <!-- Update quantity -->
                    <form action="" method="post">
                        <input type="hidden" name="update_quantity" value="<?= $product_id ?>">
                        <select name="new_quantity">
                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                <option value="<?= $i ?>" <?= ($quantity == $i) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit">Update Quantity</button>
                    </form>

                    <!-- Remove item from cart -->
                    <form action="" method="post">
                        <input type="hidden" name="remove_item" value="<?= $product_id ?>">
                        <button type="submit">Remove Item</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

<!-- Display total cost -->
<p>Total Cost: <?= calculate_total_cost() ?></p>

<!-- Add item to cart form -->
<form action="" method="post">
    <input type="hidden" name="add_item" value="1">
    <select name="product_id">
        <?php
            // Retrieve list of products from database or external API
            $products = get_products();
            foreach ($products as $product) {
                ?>
                <option value="<?= $product['id'] ?>"><?= $product['name'] ?></option>
                <?php
            }
        ?>
    </select>
    <input type="number" name="quantity" placeholder="Quantity">
    <button type="submit">Add to Cart</button>
</form>

<!-- Checkout button -->
<button onclick="checkout()">Checkout</button>

<script>
function checkout() {
    // Call PHP function to create new order
    <?php echo "alert('Order created successfully!');"; ?>
}
</script>


<?php

// Function to get product price from database or external API
function get_product_price($product_id) {
    // Retrieve price from database or external API
    $price = 10.99; // Replace with actual price logic
    return $price;
}

// Function to retrieve list of products from database or external API
function get_products() {
    // Retrieve products from database or external API
    $products = array(
        array('id' => 1, 'name' => 'Product A'),
        array('id' => 2, 'name' => 'Product B'),
        array('id' => 3, 'name' => 'Product C')
    );
    return $products;
}

// Function to create new order in database
function create_new_order($order_id, $cart) {
    // Create new order in database
    // Replace with actual database logic
    echo "Order created successfully!";
}

// Function to clear cart and session variables
function clear_cart_and_session() {
    global $cart;
    $_SESSION['cart'] = array();
    unset($_SESSION['order_id']);
}
?>


<?php

// Initialize session variables
session_start();

// Define products array
$products = [
    ['id' => 1, 'name' => 'Product A', 'price' => 9.99],
    ['id' => 2, 'name' => 'Product B', 'price' => 19.99],
    ['id' => 3, 'name' => 'Product C', 'price' => 29.99]
];

// Initialize cart array
$_SESSION['cart'] = [];

// Display cart contents
function display_cart() {
    global $products;
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Product</th><th>Price</th><th>Quantity</th></tr>';
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = array_filter($products, function ($p) use ($product_id) { return $p['id'] == $product_id; });
        echo '<tr><td>' . $product[0]['name'] . '</td><td>$' . number_format($product[0]['price'], 2) . '</td><td>' . $quantity . '</td></tr>';
    }
    echo '</table>';
}

// Add product to cart
function add_to_cart($product_id, $quantity = 1) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove product from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// Calculate total cost of cart contents
function calculate_total() {
    global $_SESSION;
    global $products;
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = array_filter($products, function ($p) use ($product_id) { return $p['id'] == $product_id; });
        $total += $product[0]['price'] * $quantity;
    }
    return number_format($total, 2);
}

// Display checkout form
function display_checkout_form() {
    global $_SESSION;
    echo '<h2>Checkout:</h2>';
    echo '<form action="checkout.php" method="post">';
    echo '<table border="1">';
    echo '<tr><th>Product</th><th>Price</th><th>Quantity</th></tr>';
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = array_filter($products, function ($p) use ($product_id) { return $p['id'] == $product_id; });
        echo '<tr><td>' . $product[0]['name'] . '</td><td>$' . number_format($product[0]['price'], 2) . '</td><td>' . $quantity . '</td></tr>';
    }
    echo '</table>';
    echo '<input type="submit" value="Proceed to Checkout">';
    echo '</form>';
}

// Display cart contents and checkout form
display_cart();
echo '<p>Total: $' . calculate_total() . '</p>';
display_checkout_form();

?>


<?php

// Initialize session variables
session_start();

// Process checkout form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get cart contents from session variable
    global $_SESSION;
    $cart = $_SESSION['cart'];

    // Calculate total cost of cart contents
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $product = array_filter($products, function ($p) use ($product_id) { return $p['id'] == $product_id; });
        $total += $product[0]['price'] * $quantity;
    }

    // Process payment (e.g. via PayPal or Stripe)
    echo 'Payment processed successfully! Thank you for your order.';
} else {
    header('Location: cart.php');
}

?>


// config.php (configuration file)
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// db.php (database connection file)
function connectToDB() {
    $conn = new PDO("mysql:host=$GLOBALS['host'];dbname=$GLOBALS['dbname']", $GLOBALS['username'], $GLOBALS['password']);
    return $conn;
}

// cart.php (cart functionality file)
require_once 'config.php';
require_once 'db.php';

class Cart {
    private $conn;

    function __construct() {
        $this->conn = connectToDB();
    }

    // Add item to cart
    public function addItem($product_id, $quantity) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :product_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            $product = $stmt->fetch();

            if ($product) {
                $cartStmt = $this->conn->prepare("SELECT * FROM carts WHERE user_id = :user_id AND total >= :total");
                $cartStmt->bindParam(':user_id', $_SESSION['user_id']);
                $cartStmt->bindParam(':total', $product['price'] * $quantity);
                $cartStmt->execute();
                $cart = $cartStmt->fetch();

                if (!$cart) {
                    // Create new cart
                    $newCartStmt = $this->conn->prepare("INSERT INTO carts (user_id, total) VALUES (:user_id, :total)");
                    $newCartStmt->bindParam(':user_id', $_SESSION['user_id']);
                    $newCartStmt->bindParam(':total', $product['price'] * $quantity);
                    $newCartStmt->execute();
                    $cartId = $this->conn->lastInsertId();

                    // Add item to cart
                    $addItemStmt = $this->conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
                    $addItemStmt->bindParam(':cart_id', $cartId);
                    $addItemStmt->bindParam(':product_id', $product['id']);
                    $addItemStmt->bindParam(':quantity', $quantity);
                    $addItemStmt->execute();
                } else {
                    // Add item to existing cart
                    $existingItemStmt = $this->conn->prepare("SELECT * FROM cart_items WHERE product_id = :product_id AND cart_id = :cart_id");
                    $existingItemStmt->bindParam(':product_id', $product['id']);
                    $existingItemStmt->bindParam(':cart_id', $_SESSION['cart_id']);
                    $existingItemStmt->execute();
                    $existingItem = $existingItemStmt->fetch();

                    if (!$existingItem) {
                        // Update quantity in cart
                        $updateQuantityStmt = $this->conn->prepare("UPDATE cart_items SET quantity = quantity + :quantity WHERE product_id = :product_id AND cart_id = :cart_id");
                        $updateQuantityStmt->bindParam(':product_id', $product['id']);
                        $updateQuantityStmt->bindParam(':quantity', $quantity);
                        $updateQuantityStmt->bindParam(':cart_id', $_SESSION['cart_id']);
                        $updateQuantityStmt->execute();
                    } else {
                        // Update quantity in existing item
                        $updateExistingItemStmt = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id AND cart_id = :cart_id");
                        $updateExistingItemStmt->bindParam(':product_id', $product['id']);
                        $updateExistingItemStmt->bindParam(':quantity', $quantity);
                        $updateExistingItemStmt->bindParam(':cart_id', $_SESSION['cart_id']);
                        $updateExistingItemStmt->execute();
                    }
                }

                // Update cart total
                $updateTotalStmt = $this->conn->prepare("UPDATE carts SET total = :total WHERE id = :id");
                $updateTotalStmt->bindParam(':id', $_SESSION['cart_id']);
                $updateTotalStmt->bindParam(':total', $this->getCartTotal());
                $updateTotalStmt->execute();

                // Update session cart ID
                $_SESSION['cart_id'] = $cartId;
            }
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
        }
    }

    // Get cart total
    public function getCartTotal() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM carts WHERE id = :cart_id");
            $stmt->bindParam(':cart_id', $_SESSION['cart_id']);
            $stmt->execute();
            $cart = $stmt->fetch();

            if ($cart) {
                return $cart['total'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
        }
    }

    // Remove item from cart
    public function removeItem($product_id, $quantity = null) {
        try {
            if ($quantity === null) {
                $quantity = 1;
            }

            $stmt = $this->conn->prepare("SELECT * FROM cart_items WHERE product_id = :product_id AND cart_id = :cart_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':cart_id', $_SESSION['cart_id']);
            $stmt->execute();
            $item = $stmt->fetch();

            if ($item) {
                // Update quantity in cart
                $updateQuantityStmt = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id AND cart_id = :cart_id");
                $updateQuantityStmt->bindParam(':product_id', $product_id);
                $updateQuantityStmt->bindParam(':quantity', 0);
                $updateQuantityStmt->bindParam(':cart_id', $_SESSION['cart_id']);
                $updateQuantityStmt->execute();

                // Update cart total
                $updateTotalStmt = $this->conn->prepare("UPDATE carts SET total = :total WHERE id = :id");
                $updateTotalStmt->bindParam(':id', $_SESSION['cart_id']);
                $updateTotalStmt->bindParam(':total', $this->getCartTotal());
                $updateTotalStmt->execute();
            }
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
        }
    }

    // Update quantity of item in cart
    public function updateQuantity($product_id, $quantity) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM cart_items WHERE product_id = :product_id AND cart_id = :cart_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':cart_id', $_SESSION['cart_id']);
            $stmt->execute();
            $item = $stmt->fetch();

            if ($item) {
                // Update quantity in cart
                $updateQuantityStmt = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id AND cart_id = :cart_id");
                $updateQuantityStmt->bindParam(':product_id', $product_id);
                $updateQuantityStmt->bindParam(':quantity', $quantity);
                $updateQuantityStmt->bindParam(':cart_id', $_SESSION['cart_id']);
                $updateQuantityStmt->execute();

                // Update cart total
                $updateTotalStmt = $this->conn->prepare("UPDATE carts SET total = :total WHERE id = :id");
                $updateTotalStmt->bindParam(':id', $_SESSION['cart_id']);
                $updateTotalStmt->bindParam(':total', $this->getCartTotal());
                $updateTotalStmt->execute();
            }
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
        }
    }

    // Get cart items
    public function getCartItems() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM cart_items WHERE cart_id = :cart_id");
            $stmt->bindParam(':cart_id', $_SESSION['cart_id']);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
        }
    }
}

// Example usage
$cart = new Cart();

// Add item to cart
$cart->addItem(1, 2);

// View cart items
print_r($cart->getCartItems());

// Update quantity of item in cart
$cart->updateQuantity(1, 3);

// Remove item from cart
$cart->removeItem(1);


<?php

// Initialize the cart array
$cart = array();

// Function to add item to cart
function addItem($item_id, $quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    // If item is already in cart, increment quantity
    $cart[$item_id] += $quantity;
  } else {
    // Add new item to cart with specified quantity
    $cart[$item_id] = $quantity;
  }
}

// Function to remove item from cart
function removeItem($item_id) {
  global $cart;
  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  }
}

// Function to update quantity of item in cart
function updateQuantity($item_id, $new_quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    $cart[$item_id] = $new_quantity;
  }
}

// Function to get total cost of items in cart
function getTotalCost() {
  global $cart;
  $total_cost = 0;
  foreach ($cart as $item_id => $quantity) {
    // Assume we have a function get_item_price($item_id) that returns the price of an item
    $price = get_item_price($item_id);
    $total_cost += $price * $quantity;
  }
  return $total_cost;
}

// Function to display cart contents
function displayCart() {
  global $cart;
  echo "<h2>Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item ID</th><th>Quantity</th><th>Price</th></tr>";
  foreach ($cart as $item_id => $quantity) {
    // Assume we have a function get_item_price($item_id) that returns the price of an item
    $price = get_item_price($item_id);
    echo "<tr><td>$item_id</td><td>$quantity</td><td>\$$price</td></tr>";
  }
  echo "</table>";
}

// Example usage:
if (isset($_POST['add'])) {
  addItem($_POST['item_id'], $_POST['quantity']);
} elseif (isset($_POST['remove'])) {
  removeItem($_POST['item_id']);
} elseif (isset($_POST['update'])) {
  updateQuantity($_POST['item_id'], $_POST['new_quantity']);
}

if (isset($_POST['display_cart'])) {
  displayCart();
}

// Display cart contents and form for adding/removing/updating items
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="item_id" value="<?php echo $cart_item; ?>">
  <input type="number" name="quantity" value="<?php echo $cart_quantity; ?>">
  <input type="submit" name="add" value="Add to Cart">
</form>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="item_id" value="<?php echo $cart_item; ?>">
  <input type="submit" name="remove" value="Remove from Cart">
</form>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="item_id" value="<?php echo $cart_item; ?>">
  <input type="number" name="new_quantity" value="<?php echo $new_quantity; ?>">
  <input type="submit" name="update" value="Update Quantity">
</form>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="submit" name="display_cart" value="Display Cart">
</form>

<?php
// Display total cost of items in cart
echo "Total Cost: \$$getTotalCost()";
?>

