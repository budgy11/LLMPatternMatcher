  <?php } ?>

  <form method="post" action="cart.php">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <?php
      // Populate the dropdown with product IDs (replace with your actual product data)
      foreach ($product_ids as $id) {
        echo "<option value=\"$id\">$id</option>";
      }
      ?>
