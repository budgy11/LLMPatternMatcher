    <table class="cart">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Quantity</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($_SESSION["cart"] as $item) {
          $product_id = $item["product_id"];
          $quantity = $item["quantity"];
          $total += $quantity; // Assuming product price is stored elsewhere
        }
        ?>
