<?php
include 'db.php';
$total = 0;
$cart = mysqli_query($conn, 'SELECT * FROM cart');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Shopping Cart</h1>
<nav><a href="index.php">Home</a> <a href="products.php">Products</a> <a href="emptycart.php">Empty Cart</a> <a href="checkout.php">Checkout</a></nav>
<div class="grid">
<?php while ($row = mysqli_fetch_assoc($cart)) { $total += $row['price']; ?>
    <div class="card">
        <img src="<?php echo e(product_image_src($row['image'])); ?>" alt="<?php echo e($row['name']); ?>">
        <h3><?php echo e($row['name']); ?></h3>
        <p>Rs. <?php echo e($row['price']); ?></p>
        <a class="btn" href="product_view.php?id=<?php echo e($row['product_id']); ?>">View Product</a>
    </div>
<?php } ?>
</div>
<h2>Total Amount: Rs. <?php echo e($total); ?></h2>
</body>
</html>
