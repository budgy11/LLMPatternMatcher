
<!DOCTYPE html>
<html>
<head>
    <title>Online Store - Purchase</title>
</head>
<body>

    <h1>Online Store - Purchase</h1>

    <form method="post">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo isset($customer_name) ? htmlspecialchars($customer_name) : ''; ?>">
