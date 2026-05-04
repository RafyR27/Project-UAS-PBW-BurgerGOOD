<?php
    session_start();
    require "../config/db.php";

    if (!isset($_SESSION["id"])) {
        header("Location: " . $BASE_URL . "auth.php");
        exit;
    }

    if ($_SESSION["role"] === "kasir") {
        header("Location: " . $BASE_URL . "kasir/dashboard.php");
        exit;
    }

    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM product WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        header("Location: " . $BASE_URL . "admin/dashboard.php");
        exit;
    }

    $error = false;
    $err_message = "";

    $product_name = $data['product_name'];
    $description  = $data['description'];
    $stock_large  = $data['stock_large'];
    $stock_small  = $data['stock_small'];
    $category     = $data['category'];
    $price        = $data['price'];

    if (isset($_POST['updateProduct'])) {
        $product_name = htmlspecialchars($_POST['product_name']);
        $description  = htmlspecialchars($_POST['description']);
        $stock_large  = htmlspecialchars($_POST['stock_large']);
        $stock_small  = htmlspecialchars($_POST['stock_small']);
        $category     = htmlspecialchars($_POST['category']);
        $price        = htmlspecialchars($_POST['price']);
        
        if (empty($product_name) || empty($description) || empty($price)) {
            $error = true;
            $err_message = "Basic fields must be filled.";
        } else {
            if (!empty($_FILES['image']['name'])) {
                $fileTmp = $_FILES['image']['tmp_name'];
                $imageData = addslashes(file_get_contents($fileTmp));
                $sql = "UPDATE product SET 
                        product_name='$product_name', description='$description', 
                        stock_large='$stock_large', stock_small='$stock_small', 
                        category='$category', price='$price', image='$imageData' 
                        WHERE id='$id'";
            } else {
                $sql = "UPDATE product SET 
                        product_name='$product_name', description='$description', 
                        stock_large='$stock_large', stock_small='$stock_small', 
                        category='$category', price='$price' 
                        WHERE id='$id'";
            }

            if (mysqli_query($conn, $sql)) {
                header("Location: " . $BASE_URL . "admin/dashboard.php?status=updated");
                exit;
            } else {
                $error = true;
                $err_message = "Failed to update product.";
            }
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Product | Admin</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/styles.css" />
    <link rel="stylesheet" href="../assets/bootstrap-icons/bootstrap-icons.css" />
</head>
<body class="bg-cream">
    <div class="container py-5">
        <div class="mb-4 d-flex align-items-center gap-3">
            <a href="dashboard.php" class="btn btn-dark rounded-circle"><i class="bi bi-arrow-left"></i></a>
            <h2 class="fredoka-font-medium mb-0">Edit Product</h2>
        </div>

        <div class="bg-white shadow rounded-4 p-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="product_name" class="form-control" value="<?= $product_name ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control"><?= $description ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Large</label>
                        <input type="number" name="stock_large" class="form-control" value="<?= $stock_large ?>" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Small</label>
                        <input type="number" name="stock_small" class="form-control" value="<?= $stock_small ?>" min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="makanan" <?= $category == 'makanan' ? 'selected' : '' ?>>Makanan</option>
                        <option value="minuman" <?= $category == 'minuman' ? 'selected' : '' ?>>Minuman</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" value="<?= $price ?>" min="0">
                </div>
                <div class="mb-4">
                    <label class="form-label">Image (Leave blank if not changing)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="mt-2">
                        <small>Current Image:</small><br>
                        <img src="data:image/jpeg;base64,<?= base64_encode($data['image']) ?>" width="100" class="rounded border">
                    </div>
                </div>
                <button type="submit" name="updateProduct" class="btn btn-dark px-5">Update Product</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>