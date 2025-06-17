    Username: <input type="text" name="username" placeholder="Enter your username" value="<?php echo isset($username) ? $username : ''; ?>">
    Password: <input type="password" name="password" placeholder="Enter your password">
    <button type="submit">Login</button>
</form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION["username"]; ?>!</h1>
