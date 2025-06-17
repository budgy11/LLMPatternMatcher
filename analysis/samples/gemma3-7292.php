
  <form method="GET" action="">
    <label for="productId">Product ID:</label>
    <select name="productId" id="productId">
      <?php
      foreach ($products as $id => $product) {
        echo "<option value='" . $id . "'>" . $id . "</option>";
      }
      ?>
