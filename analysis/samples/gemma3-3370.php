
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php if (isset($error_message)) {
    echo "<p style='color:red;'>Error: " . $error_message . "</p>";
}
?>
