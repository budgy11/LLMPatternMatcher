
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
