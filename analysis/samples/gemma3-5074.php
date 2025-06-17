  <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
