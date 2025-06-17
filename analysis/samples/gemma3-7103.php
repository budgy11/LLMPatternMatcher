    <form method="post" action="">
      <?php
      $products = [];
      foreach($cart_contents as $item) {
        $products[$item['id']] = $item;
      }

      foreach ($products as $product_id => $product_data) {
          echo "<label for=\"product_" . $product_id . "\">" . $product_data["name"] . ":</label>";
          echo "<input type=\"number\" id=\"product_" . $product_id . "\" name=\"quantity\" min=\"1\" value=\"" . $product_data["quantity"] . "\"><br>";
          }

        ?>
