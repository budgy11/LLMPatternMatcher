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
