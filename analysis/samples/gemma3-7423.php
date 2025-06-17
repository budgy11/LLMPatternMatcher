
<!--  This would be your 'cart.php' file (or a similar page for display) -->
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <form method="post" action="">

    <h2>Available Products</h2>
    <ul>
      <?php foreach ($products as $id => $product): ?>
