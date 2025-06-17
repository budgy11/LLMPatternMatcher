    <br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" placeholder="Enter price" step="0.01" value="<?php echo isset($price) ? floatval($price) : ''; ?>">
