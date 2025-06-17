
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <?php
    if (isset($error)) {
        echo "<div style='color: red;'>";
        foreach ($error as $err) {
            echo $err . "<br>";
        }
        echo "</div>";
    }

    if (isset($success)) {
        echo "<div style='color: green;'>" . $message . "</div>";
    }
    ?>
