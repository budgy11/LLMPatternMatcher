    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" min="0.01" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" placeholder="Enter customer name" required><br><br>

    <input type="submit" value="Add to Purchase">
  </form>

</body>
</html>


<?php

// This is a simplified example. For a production environment, 
// you would likely use a database for storing product information,
// user information, and order details.

class Product {
  public $id;
  public $name;
  public $price;

  public function __construct($id, $name, $price) {
    $this->id = $id;
    $this->name = $name;
    $this->price = $price;
  }
}

class ShoppingCart {
  public $items = [];

  public function addItem($product, $quantity = 1) {
    $this->items[] = ['product' => $product, 'quantity' => $quantity];
  }

  public function removeItem($productId) {
    $newItems = [];
    foreach ($this->items as $item) {
      if ($item['product']->id != $productId) {
        $newItems[] = $item;
      }
    }
    $this->items = $newItems;
  }

  public function getTotal() {
    $total = 0;
    foreach ($this->items as $item) {
      $product = $item['product'];
      $total += $product->price * $item['quantity'];
    }
    return $total;
  }
}

class Purchase {
  public $cart;
  public $customerName;
  public $customerEmail;

  public function __construct($cart, $customerName, $customerEmail) {
    $this->cart = $cart;
    $this->customerName = $customerName;
    $this->customerEmail = $customerEmail;
  }

  public function processPurchase() {
    echo "<h2>Purchase Summary</h2>";
    echo "<p>Customer Name: " . htmlspecialchars($this->customerName) . "</p>";
    echo "<p>Customer Email: " . htmlspecialchars($this->customerEmail) . "</p>";

    echo "<h3>Items Purchased:</h3>";
    echo "<ul>";
    foreach ($this->cart->items as $item) {
      echo "<li>" . $item['product']->name . " - Quantity: " . $item['quantity'] . " - Price: $" . number_format($item['product']->price, 2) . "</li>";
    }
    echo "</ul>";

    echo "<p>Total Amount: $" . number_format($this->cart->getTotal(), 2) . "</p>";

    // In a real application, you would:
    // 1. Save the order details to a database.
    // 2. Send an email confirmation to the customer.
    // 3. Update inventory levels.

    echo "<p>Order placed successfully (simulated). Thank you!</p>";
  }
}

// Example Usage:
// Create some products
$product1 = new Product(1, "T-Shirt", 20);
$product2 = new Product(2, "Jeans", 50);
$product3 = new Product(3, "Hat", 15);

// Create a shopping cart
$cart = new ShoppingCart();
$cart->addItem($product1, 2);
$cart->addItem($product2, 1);
$cart->addItem($product3, 3);

// Create a purchase object
$purchase = new Purchase($cart, "John Doe", "john.doe@example.com");

// Process the purchase
$purchase->processPurchase();

?>
