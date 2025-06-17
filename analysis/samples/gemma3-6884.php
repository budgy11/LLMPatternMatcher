    <br><br>

    <label for="total_amount">Total Amount:</label>
    <input type="number" id="total_amount" name="total_amount" placeholder="Enter total amount" min="0" step="0.01" value="<?php echo isset($total_amount) ? floatval($total_amount) : ''; ?>">
