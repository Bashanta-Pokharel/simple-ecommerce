<?php
include "db.php";

$product_sql = "SELECT * FROM products ORDER BY id DESC";
$product_result = mysqli_query($con, $product_sql);

$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = '';
$product_name = $description = $category = $price = $stock = '';

if (isset($_POST['addproduct'])) {
    $product_name = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    if ($product_name === '') {
        $error1 = '* Product name is required';
    }

    if ($description === '') {
        $error2 = '* Description is required';
    }

    if ($category === '') {
        $error3 = '* Category is required';
    }

    if ($price === '') {
        $error4 = '* Price is required';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error4 = '* Enter valid price';
    }

    if ($stock === '') {
        $error5 = '* Stock is required';
    } elseif (!is_numeric($stock) || $stock < 0) {
        $error5 = '* Enter valid stock quantity';
    }

    $image = upload_product_image('image', $error6);

    if ($error1 === '' && $error2 === '' && $error3 === '' && $error4 === '' && $error5 === '' && $error6 === '') {
        $stmt = mysqli_prepare($con, "INSERT INTO products (name, product_name, description, category, price, stock, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
        mysqli_stmt_bind_param($stmt, 'ssssdis', $product_name, $product_name, $description, $category, $price, $stock, $image);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Product Added Successfully'); window.location='addproduct.php';</script>";
            exit;
        }

        echo "<script>alert('Error Adding Product');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="headerlogin">
    <h1>E-Commerce</h1>
    <h2>Admin Panel</h2>
</div>

<div class="navbar">
    <div class="nav-container">
        <a href="index.php"><div class="nav-item">Home</div></a>
        <a href="products.php"><div class="nav-item">Products</div></a>
        <a href="cart.php"><div class="nav-item">Cart</div></a>
        <a href="wishlist.php"><div class="nav-item">Wishlist</div></a>
        <a href="orders.php"><div class="nav-item">Orders</div></a>
        <a href="admin.php"><div class="nav-item" style="background:#e8f0fe;color:#1a73e8;">Admin</div></a>
    </div>
</div>

<div class="container">
    <h2 style="text-align:center;">Add New Product</h2>

    <form method="post" enctype="multipart/form-data" class="login-form">
        <label class="form-label">Product Name</label>
        <input type="text" name="product_name" class="form-input" value="<?php echo e($product_name); ?>">
        <div class="error"><?php echo e($error1); ?></div>

        <label class="form-label">Description</label>
        <textarea name="description" class="form-input" rows="4"><?php echo e($description); ?></textarea>
        <div class="error"><?php echo e($error2); ?></div>

        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-input" value="<?php echo e($category); ?>">
        <div class="error"><?php echo e($error3); ?></div>

        <label class="form-label">Price (Rs.)</label>
        <input type="number" step="0.01" name="price" class="form-input" value="<?php echo e($price); ?>">
        <div class="error"><?php echo e($error4); ?></div>

        <label class="form-label">Stock Quantity</label>
        <input type="number" name="stock" class="form-input" value="<?php echo e($stock); ?>">
        <div class="error"><?php echo e($error5); ?></div>

        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-input" accept="image/*">
        <div class="error"><?php echo e($error6); ?></div>

        <button type="submit" name="addproduct" class="btn">Add Product</button>
    </form>

    <hr style="margin:40px 0;">
    <h2 style="text-align:center;">Available Products</h2>

    <div class="products">
        <?php while ($row = mysqli_fetch_assoc($product_result)): ?>
            <div class="product-card">
                <img src="<?php echo e(product_image_src($row['image'])); ?>" alt="<?php echo e(product_name($row)); ?>" style="width:100%;height:220px;object-fit:cover;">
                <h3><?php echo e(product_name($row)); ?></h3>
                <p><strong>Description:</strong><br><?php echo nl2br(e($row['description'])); ?></p>
                <p><strong>Category:</strong> <?php echo e($row['category']); ?></p>
                <p><strong>Price:</strong> Rs. <?php echo e($row['price']); ?></p>
                <p><strong>Stock:</strong> <?php echo e($row['stock']); ?></p>
                <p><strong>Added On:</strong> <?php echo e(date('d-m-Y', strtotime($row['created_at']))); ?></p>
                <div class="action-row">
                    <a href="product_view.php?id=<?php echo e($row['id']); ?>" class="btn">View</a>
                    <a href="editproduct.php?id=<?php echo e($row['id']); ?>" class="btn">Edit</a>
                    <a href="deleteproduct.php?id=<?php echo e($row['id']); ?>" class="btn" onclick="return confirm('Delete this product?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="footer">
    <p style="text-align:center;">&copy; 2025 E-Commerce System. All Rights Reserved.</p>
</div>
</body>
</html>
