    <p>Please enter your name to create a purchase order.</p>
    <form method="post" action="purchase.php">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>">
