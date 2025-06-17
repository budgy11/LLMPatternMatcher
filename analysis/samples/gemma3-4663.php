
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>

<h1>Registration</h1>

<?php if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
} else if (isset($success)) {
    echo "<p style='color: green;'>Success: " . $success . "</p>";
    // Redirect to a login page or display a welcome message.  This is a basic example.
    header("Location: login.php"); // Replace login.php with your login page
    exit();
}
?>
