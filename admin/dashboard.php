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

    $outlet_code = $_SESSION['outlet_code'];

    $category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

    $sql = "SELECT * FROM product WHERE outlet_code = '$outlet_code'";

    if ($category_filter !== 'all') {
        $sql .= " AND category = '$category_filter'";
    }

    if ($search !== '') {
      $sql .= " AND product_name LIKE '%$search%'";
    }

    $query_product = mysqli_query($conn, $sql);

    $query_outlet = mysqli_query($conn, "SELECT * FROM outlet WHERE outlet_code = '$outlet_code'");

    $outlet = mysqli_fetch_assoc($query_outlet);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/styles.css" />
    <link
      rel="stylesheet"
      href="../assets/bootstrap-icons/bootstrap-icons.css"
    />
    <link rel="icon" href="../public/logo.png" />
  </head>
  <body class="bg-cream">
    <div class="w-100 p-4">
      <button
        class="btn btn-dark rounded-5"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasExample"
        aria-controls="offcanvasExample"
      >
        <i class="bi bi-list fs-5"></i>
      </button>
    </div>

    <div
      class="offcanvas offcanvas-start fredoka-font"
      tabindex="-1"
      id="offcanvasExample"
      aria-labelledby="offcanvasExampleLabel"
    >
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Admin</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          aria-label="Close"
        ></button>
      </div>
      <div
        class="offcanvas-body px-4 d-flex flex-column justify-content-between"
      >
        <div class="d-flex flex-column gap-2 justify-content-start">
          <a
            href="dashboard.php"
            class="w-100 link-underline link-underline-opacity-0"
          >
            <button
              class="w-100 btn btn-warning text-start px-4 py-3 d-flex align-items-center gap-3"
            >
              <i class="bi bi-archive-fill"></i>
              Dashboard
            </button>
          </a>
          <a
            href="add-product.php"
            class="w-100 link-underline link-underline-opacity-0"
          >
            <button
              class="w-100 btn btn-light text-start px-4 py-3 d-flex align-items-center gap-3"
            >
              <i class="bi bi-plus"></i>
              Add Product
            </button>
          </a>
        </div>
        <a href="../controller/logout.php" class="link-underline link-underline-opacity-0">
          <button
            class="w-100 btn btn-outline-danger text-start px-4 py-3 d-flex align-items-center gap-3"
          >
            <i class="bi bi-box-arrow-left"></i>
            Logout
          </button>
        </a>
      </div>
    </div>

    <div class="container pb-5 px-4">
      <div class="mb-4">
        <h2 class="fredoka-font-medium">Selamat datang, Admin</h2>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="bg-white rounded-4 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-3">
                <div class="bg-warning rounded-circle d-flex justify-content-center align-items-center" style="width: 55px; height: 55px">
                  <i class="bi bi-fork-knife fs-4"></i>
                </div>
                <div>
                  <p class="mb-1 text-muted">Total Seats</p>
                  <h4 class="mb-0 fredoka-font-medium"><?= $outlet['total_tables'] ?></h4>
                </div>
              </div>

              <a href="edit-outlet.php" class="btn btn-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px">
                <i class="bi bi-pencil-square"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="bg-white rounded-4 shadow-sm p-4">
            <div class="d-flex align-items-center gap-3">
              <div
                class="bg-warning rounded-circle d-flex justify-content-center align-items-center"
                style="width: 55px; height: 55px"
              >
                <i class="bi bi-shop fs-4"></i>
              </div>
              <div>
                <p class="mb-1 text-muted">Outlet Code</p>
                <h4 class="mb-0 fredoka-font-medium"><?= $outlet['outlet_code'] ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
          <form action="dashboard.php" method="GET" class="d-flex gap-2">
            <input type="hidden" name="category" value="<?= $category_filter ?>">
            
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input 
                type="text" 
                name="search" 
                class="form-control border-start-0 rounded-end-pill py-2" 
                placeholder="Search products..." 
                value="<?= htmlspecialchars($search) ?>"
              >
            </div>
            
            <?php if($search !== ''): ?>
              <a href="dashboard.php?category=<?= $category_filter ?>" class="btn btn-light rounded-circle shadow-sm">
                <i class="bi bi-x-lg"></i>
              </a>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <div class="mb-4 d-flex gap-2">
        <a href="dashboard.php?category=all&search=<?= urlencode($search) ?>" 
          class="btn btn-sm <?= $category_filter == 'all' ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-3">
          All
        </a>
        <a href="dashboard.php?category=makanan&search=<?= urlencode($search) ?>" 
          class="btn btn-sm <?= $category_filter == 'makanan' ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-3">
          Food
        </a>
        <a href="dashboard.php?category=minuman&search=<?= urlencode($search) ?>" 
          class="btn btn-sm <?= $category_filter == 'minuman' ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-3">
          Drink
        </a>
      </div>
      
      <div class="row g-4">
        <!-- Product Card -->
        <?php if(mysqli_num_rows($query_product) > 0): ?>
          <?php while($product = mysqli_fetch_assoc($query_product)):?>
            <div class="col-lg-4 col-md-6">
              <div class="bg-light rounded-4 shadow-sm p-4 h-100">
                <div class="d-flex gap-3 align-items-start">
                  <img
                    src="data:image/jpeg;base64,<?= base64_encode($product['image']); ?>"
                    alt="<?= $product['product_name']; ?>"
                    style="width: 110px"
                    class="object-fit-contain"
                  />

                  <div class="w-100">
                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                      <?= $product['category']; ?>
                    </small>
                    <h5 class="fredoka-font-medium mb-2"><?= $product['product_name']; ?></h5>
                    <p class="mb-2 text-muted">
                      <?= $product['description']; ?>
                    </p>
                    <p class="mb-0 fredoka-font">
                      Stock large: <?= $product['stock_large']; ?>
                    </p>
                    <p class="mb-4 fredoka-font">
                      Stock small: <?= $product['stock_small']; ?>
                    </p>

                    <div class="d-flex justify-content-between align-items-center">
                      <h4 class="mb-0 fredoka-font-medium">Rp. <?= number_format($product['price']); ?></h4>

                      <div class="d-flex gap-2">
                          <a href="edit-product.php?id=<?= $product['id']; ?>" 
                            class="btn btn-warning rounded-circle d-flex align-items-center justify-content-center" 
                            style="width: 40px; height: 40px">
                              <i class="bi bi-pencil-square fs-6"></i>
                          </a>
                          
                          <button 
                              type="button"
                              data-id="<?= $product['id']; ?>"
                              data-name="<?= htmlspecialchars($product['product_name']); ?>"
                              class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center btn-delete" 
                              style="width: 40px; height: 40px">
                              <i class="bi bi-trash fs-6"></i>
                          </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <i class="bi bi-search fs-1 text-muted"></i>
            <h4 class="mt-3 fredoka-font text-muted">
              <?= ($search !== '') ? "No products match '$search'" : "No products found in this category." ?>
            </h4>
            <a href="dashboard.php" class="btn btn-link text-dark">Clear all filters</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/script.js"></script>
  </body>
</html>
