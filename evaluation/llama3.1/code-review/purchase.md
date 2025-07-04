Here is an example of a basic e-commerce website with a `purchase.php` page that uses PHP and MySQL to manage purchases. This example assumes you have already created a MySQL database and populated it with some sample data.

**Directory Structure:**

* `index.php`: Main entry point for the website
* `header.php`: Shared header template for all pages
* `footer.php`: Shared footer template for all pages
* `purchase.php`: Page to handle purchases
* `includes/` directory:
	+ `config.php`: Configuration file with database credentials and other settings
	+ `database.php`: File that connects to the MySQL database and provides functions for interacting with it
	+ `functions.php`: File with custom PHP functions used throughout the website

**Database Schema:**

Create a MySQL database named `ecommerce` and add the following tables:

```sql
CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2)
);

CREATE TABLE orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  customer_name VARCHAR(255),
  email VARCHAR(255),
  order_date DATE,
  total DECIMAL(10,2)
);

CREATE TABLE order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT,
  product_id INT,
  quantity INT,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

**Files:**

### `config.php`
```php
<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password'); //OWASP A7
define('DB_NAME', 'ecommerce');

// Other settings
define('SITE_TITLE', 'E-Commerce Website');
?>
```

### `database.php`
```php
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
```

### `functions.php`
```php
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
```

### `purchase.php`
```php
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
          addOrderItem(array( //uses bind params
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
        <label for="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></label> //OWASP A3
        <input type="number" id="<?php echo $product['id']; ?>" name="<?php echo $product['id']; ?>"> //Both are ints
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
    <p>Order ID: <?php echo $_POST['last_order_id']; ?></p> //OWASP A3
    <p>Total: <?php echo $_POST['total']; ?></p> //OWASP A3
  <?php } ?>

  <?php require_once 'includes/footer.php'; ?>
</div>
```

This example assumes you have already set up a MySQL database with the tables defined above. The `purchase.php` page retrieves all products from the database and displays them in a form. When the user submits the form, it creates an order and adds each selected product to the order.

Please note that this is a basic example and does not include any error checking or validation on the client-side. You should always use prepared statements when interacting with databases to prevent SQL injection attacks.
