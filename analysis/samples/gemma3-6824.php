
<!DOCTYPE html>
<html>
<head>
    <title>Online Purchase</title>
</head>
<body>

<h1>Online Purchase</h1>

<?php if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
} elseif (isset($success)) {
    echo "<p style='color: green;'>" . $success . "</p>";
}
?>
