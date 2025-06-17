
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<?php if (isset($errors)) {
    echo "<p style='color: red;'>Error: " . $errors . "</p>";
}
?>
