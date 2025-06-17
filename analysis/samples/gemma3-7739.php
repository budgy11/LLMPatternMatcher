

<?php

// This is a simplified example.  In a real application, you'd use a database
// to store products, user information, and order details.

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
        if (isset($this->items[$product->id]) ) {
            $this->items[$product->id]['quantity'] += $quantity;
        } else {
            $this->items[$product->id] = ['quantity' => $quantity];
        }
    }

    public function removeItem($product) {
        unset($this->items[$product->id]);
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $totalPrice = $item->price * $item->quantity;
            $totalPrice += $totalPrice;
        }
        return $totalPrice;
    }
}


// Sample Products
$product1 = new Product(1, "Laptop", 1200);
$product2 = new Product(2, "Mouse", 25);
$product3 = new Product(3, "Keyboard", 75);

// Create a Shopping Cart
$cart = new ShoppingCart();

// Add items to the cart
$cart->addItem($product1, 1);
$cart->addItem($product2, 2);
$cart->addItem($product3, 1);

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
echo "<ul>";
foreach ($cart->items as $product) {
    echo "<li>" . $product->name . " - $" . $product->price . " x " . $product->quantity . " = $" . ($product->price * $product->quantity) . "</li>";
}
echo "</ul>";

echo "<p><b>Total: $" . $cart->getTotal() . "</b></p>";

// Example of removing an item
// $cart->removeItem($product2);
// echo "<p>Total after removing Mouse: $" . $cart->getTotal() . "</p>";


?>
