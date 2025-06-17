<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
