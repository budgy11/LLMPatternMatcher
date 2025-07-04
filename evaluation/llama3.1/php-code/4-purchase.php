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
```