    <br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" value="<?php echo isset($orderData['customer_name']) ? htmlspecialchars($orderData['customer_name']) : ''; ?>">
