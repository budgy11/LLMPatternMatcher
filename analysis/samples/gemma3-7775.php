
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase a Product</h1>

<?php if (isset($error_message)) {
    echo "<p style='color: red;'>Error: " . $error_message . "</p>";
}
?>
