
<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'ecommerce');

// Other settings
define('SITE_TITLE', 'E-Commerce Website');
?>

<?php
class Database {
  private $conn;

  public function connect() {
    $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  public function query($sql) {
    return $this->conn->query($sql);
  }

  public function getResults() {
    $results = array();
    while ($row = $this->conn->fetch_assoc()) {
      $results[] = $row;
    }
    return $results;
  }

  public function closeConnection() {
    $this->conn->close();
  }
}
?>

<?php
function getProducts() {
  $db = new Database();
  $db->connect();
  $query = "SELECT * FROM products";
  $result = $db->query($query);
  $products = array();
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
  return $products;
}

function createOrder() {
  global $_POST;
  $db = new Database();
  $db->connect();
  $sql = "INSERT INTO orders (customer_name, email, order_date, total) VALUES (?, ?, NOW(), ?)";
  $stmt = $db->conn->prepare($sql);
  $stmt->bind_param("sss", $_POST['customer_name'], $_POST['email'], $_POST['total']);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

function addOrderItem() {
  global $_POST;
  $db = new Database();
  $db->connect();
  $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
  $stmt = $db->conn->prepare($sql);
  $stmt->bind_param("iis", $_POST['order_id'], $_POST['product_id'], $_POST['quantity']);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}
?>

<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

if (isset($_POST['submit'])) {
  $products = getProducts();
  if ($_POST['customer_name'] && $_POST['email']) {
    // Create order
    if (createOrder()) {
      foreach ($products as $product) {
        if (isset($_POST[$product['id']])) {
          addOrderItem(array(
            'order_id' => $_POST['last_order_id'],
            'product_id' => $product['id'],
            'quantity' => $_POST[$product['id']]
          ));
        }
      }
    } else {
      echo "Error creating order";
    }
  }
}

$products = getProducts();
?>
<div class="container">
  <h1>Purchase</h1>
  <form action="" method="post">
    <?php foreach ($products as $product) { ?>
      <div class="form-group">
        <label for="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></label>
        <input type="number" id="<?php echo $product['id']; ?>" name="<?php echo $product['id']; ?>">
      </div>
    <?php } ?>
    <div class="form-group">
      <label for="customer_name">Customer Name:</label>
      <input type="text" id="customer_name" name="customer_name">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email">
    </div>
    <button type="submit" name="submit">Place Order</button>
  </form>

  <?php if (isset($_POST['last_order_id'])) { ?>
    <p>Order ID: <?php echo $_POST['last_order_id']; ?></p>
    <p>Total: <?php echo $_POST['total']; ?></p>
  <?php } ?>

  <?php require_once 'includes/footer.php'; ?>
</div>
