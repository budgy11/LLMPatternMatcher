<?php
// Include database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// Get all items in cart for current user
$cart_items = array();
$sql = "SELECT * FROM cart WHERE user_id = $_SESSION[user_id]";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
  $product_id = $row['product_id'];
  $item = get_product_info($product_id);
  $cart_items[] = array(
    'id' => $product_id,
    'name' => $item['name'],
    'description' => $item['description'],
    'price' => $item['price'],
    'quantity' => $row['quantity']
  );
}

// Get total cost of all items in cart
$total = 0;
foreach ($cart_items as $item) {
  $total += $item['price'] * $item['quantity'];
}


?>
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Shopping Cart</h1>
  <?php foreach ($cart_items as $item) { ?>
    <div class="cart-item">
      <img src="images/<?= $item['id'] ?>.jpg" alt="<?= $item['name'] ?>">
      <p><?= $item['name'] ?></p>
      <p>Quantity: <?= $item['quantity'] ?></p>
      <p>Price: <?= $item['price'] ?></p>
      <button class="remove-btn">Remove</button>
    </div>
  <?php } ?>
  <h2>Total: <?= number_format($total, 2) ?></h2>
  <form action="cart_update.php" method="post">
    <input type="hidden" name="cart_items[]" value="">
    <input type="submit" value="Update Cart">
  </form>

  <!-- JavaScript files for dynamic functionality -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/cart_functions.js"></script>
</body>
</html>

<?php
// Include database connection file
require_once 'db.php';

// Get product ID from URL
$product_id = $_GET['product_id'];

// Add item to cart for current user
$quantity = 1;
$sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$_SESSION[user_id]', '$product_id', '$quantity')";
mysqli_query($conn, $sql);

// Redirect back to cart page
header('Location: cart.php');
exit;
?>

<?php
// Include database connection file
require_once 'db.php';

// Get item ID from URL
$item_id = $_GET['item_id'];

// Remove item from cart for current user
$sql = "DELETE FROM cart WHERE user_id = $_SESSION[user_id] AND id = '$item_id'";
mysqli_query($conn, $sql);

// Redirect back to cart page
header('Location: cart.php');
exit;
?>

<?php
// Include database connection file
require_once 'db.php';

// Get all items in cart for current user
$cart_items = array();
$sql = "SELECT * FROM cart WHERE user_id = $_SESSION[user_id]";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
  $product_id = $row['product_id'];
  $item = get_product_info($product_id);
  $cart_items[] = array(
    'id' => $product_id,
    'name' => $item['name'],
    'description' => $item['description'],
    'price' => $item['price'],
    'quantity' => $row['quantity']
  );
}

// Update cart with new quantities
foreach ($_POST['cart_items'] as $item) {
  $product_id = $item['id'];
  $new_quantity = $item['quantity'];
  $sql = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = $_SESSION[user_id] AND product_id = '$product_id'";
  mysqli_query($conn, $sql);
}

// Redirect back to cart page
header('Location: cart.php');
exit;
?>
