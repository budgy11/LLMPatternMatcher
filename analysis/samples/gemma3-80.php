

<!DOCTYPE html>
<html>
<head>
    <title>Simple Login Example</title>
</head>
<body>

    <h1>Simple Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php
//  This is a very simplified example and assumes you have the
//  bcrypt extension installed and configured correctly in your PHP environment.

//  In a real application, you would use a proper database.  This is
//  for demonstration only.

function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword(string $password, string $hashedPassword): bool {
    return password_verify($password, $hashedPassword);
}

// Example Usage (for demonstration)
$password = "mySecretPassword";
$hashed = hashPassword($password);
echo "Hashed Password: " . $hashed . "
";

if (verifyPassword("mySecretPassword", $hashed)) {
    echo "Password matches!
";
} else {
    echo "Password does not match.
";
}
?>
