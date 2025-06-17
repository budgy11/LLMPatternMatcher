
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h1>User Registration</h1>

    <?php if (isset($error)) {
        echo "<p style='color: red;'>Error: " . $error . "</p>";
    }
    ?>
