<?php 
  session_start();

  require "config/db.php";

  if(!isset($_SESSION['table_number']) || !isset($_SESSION['outlet_code'])){
    header("location: " . $BASE_URL . 'access-menu.php');
    exit;
  }

  $table_number = $_SESSION['table_number'];
  $outlet_code = $_SESSION['outlet_code'];

  $query_products = mysqli_query(
      $conn,
      "SELECT * FROM product 
      WHERE outlet_code = '$outlet_code'
      AND (stock_large > 0 OR stock_small > 0)"
  );
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu | BurgerGOOD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/styles.css" />
    <link rel="stylesheet" href="assets/bootstrap-icons/bootstrap-icons.css" />
    <link rel="icon" href=" public/logo.png" />
  </head>
  <body class="bg-cream">
    <div
      class="w-100 justify-content-between align-items-center px-4 big-font d-flex"
      style="height: 80px"
    >
      <a href="access-menu.php" class="text-dark">
        <i class="bi bi-arrow-left-short"></i>
      </a>
    </div>

    <!-- Hero Menu -->
    <section
      class="w-100 h-auto px-4 px-lg-5 pt-lg-4 my-1 d-flex flex-column justify-content-center align-items-start"
    >
      <div class="w-100 d-flex justify-content-between align-items-center">
        <div class="w-75">
          <h1 class="fredoka-font-medium fs-2">Welcome!</h1>
          <p class="fredoka-font fs-5">BurgerGOOD make your mood is good</p>
        </div>
        <span class="bg-orange-50 p-3 fredoka-font-medium fs-3 rounded-2 text-light">
          <?= $table_number; ?>
        </span>
      </div>

      <div class="w-100 bg-dark hero-menu rounded-2">
        <img
          src="public/banner-menu.png"
          alt="banner-menu"
          class="object-fit-lg-contain object-fit-cover w-100 h-100 rounded-2"
        />
      </div>
    </section>

    <!-- Menu -->
    <section
      class="w-100 px-4 px-lg-5 my-3 d-flex flex-column justify-content-center align-items-center gap-3"
    >
      <div class="w-100 h-auto d-flex gap-2 text-dark fredoka-font">
        <span class="px-4 py-2 btn-active">
          <p class="my-0">Burger</p>
        </span>
        <span class="px-4 py-2 btn-unactive">
          <p class="my-0">Drink</p>
        </span>
      </div>

      <div class="container-fluid" style="margin-bottom: 100px">
        <div
          id="menu-section"
          class="row row-cols-1 row-cols-lg-3 row-cols-md-2 g-3"
        >
          <!-- Menu card -->
          <?php while($product = mysqli_fetch_assoc($query_products)): ?>
            <div class="col menu-card" data-name="<?= $product['product_name']; ?>">
              <div class="h-auto bg-light shadow-lg rounded d-flex justify-content-start align-items-center gap-3"
                  style="padding: 20px 25px;">
                <img
                  src="data:image/jpeg;base64,<?= base64_encode($product['image']); ?>"
                  alt="<?= $product['product_name']; ?>"
                  class="object-fit-contain"
                  style="width: 100px"
                />
                <div class="w-100 d-flex flex-column justify-content-center align-items-start gap-1">
                  <p class="fredoka-font-bold my-0 productName"><?= $product['product_name']; ?></p>
                  <p class="fredoka-font my-0" style="font-size: 0.8rem">
                    <?= $product['description']; ?>
                  </p>
                  <div class="w-100 d-flex flex-column flex-md-row flex-lg-row justify-content-between align-items-lg-center align-items-end mt-3">
                    <p class="my-0 fredoka-font w-100">
                      Rp. <?= number_format($product['price']); ?>
                    </p>
                    <div
                        class="w-100 d-flex justify-content-end align-items-center gap-3 mt-3 mt-md-2 mt-lg-0 d-none btn-card"
                      >
                        <button
                          type="button"
                          class="btn rounded-5 btn-unactive"
                          style="padding: 6px 9px"
                          onclick="delCountCard('<?= $product['product_name']; ?>')"
                        >
                          <i class="bi bi-dash"></i>
                        </button>
                        <p class="my-0 quantity"></p>
                        <button
                          type="button"
                          class="btn rounded-5 btn-unactive"
                          style="padding: 6px 9px"
                          onclick="addCountCard('<?= $product['product_name']; ?>', <?= $product['stock_large']; ?>, <?= $product['stock_small']; ?>)"
                        >
                          <i class="bi bi-plus"></i>
                        </button>
                      </div>

                    <button
                      type="button"
                      class="btn rounded-5 btn-active btn-detail mt-2 mt-lg-0"
                      style="padding: 6px 10px"
                      data-bs-toggle="modal"
                      data-bs-target="#staticBackdrop"
                      onclick='showDetail(
                        "<?= htmlspecialchars($product["product_name"]) ?>",
                        "<?= htmlspecialchars($product["description"]) ?>",
                        "<?= base64_encode($product["image"]) ?>",
                        <?= $product["price"] ?>,
                        <?= $product["stock_small"] ?>,
                        <?= $product["stock_large"] ?>
                      )'
                      data-name="<?= $product['product_name']; ?>"
                      data-price=<?= $product['price']; ?>
                    >
                      <i class="bi bi-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </section>

    <!-- bar -->
    <div
      id="bar-checkout"
      class="w-100 d-flex justify-content-center align-items-center fixed-bottom my-3 px-4 d-none"
    >
      <a
        id="btn-checkout"
        href="checkout.php"
        class="btn bar-menu bg-dark py-3 px-4 rounded-5 text-light d-flex justify-content-between align-items-center"
      >
        <p id="bar-item" class="my-0 fredoka-font"></p>
        <p id="bar-price" class="my-0 fredoka-font d-flex gap-2"></p>
      </a>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="staticBackdrop"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div
              class="h-auto d-flex flex-column justify-content-start align-items-center gap-3 p-2"
            >
              <img
                id="detailImage"
                src=""
                alt=""
                class="object-fit-contain"
                style="width: 150px"
              />
              <div
                class="d-flex flex-column justify-content-center align-items-start gap-1"
              >
                <p id="detailName" class="fredoka-font-bold my-0 fs-3"></p>
                <p id="detailDescription" class="fredoka-font my-0"></p>
                <div
                  id="sizeOptions"
                  class="w-100 d-flex flex-column justify-content-start align-items-start my-5 gap-3"
                >
                <!-- radios -->
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer w-100">
            <div
              class="w-100 px-2 mb-2 d-flex justify-content-between align-items-center fredoka-font"
            >
              <p class="my-0">Quantity</p>
              <div
                class="d-flex justify-content-center align-items-center gap-3"
              >
                <button
                  type="button"
                  class="btn rounded-5 btn-unactive"
                  style="padding: 6px 10px"
                  onclick="delCount()"
                >
                  <i class="bi bi-dash"></i>
                </button>
                <p class="my-0 quantity">1</p>
                <button
                  type="button"
                  class="btn rounded-5 btn-unactive"
                  style="padding: 6px 10px"
                  onclick="addCount()"
                >
                  <i class="bi bi-plus"></i>
                </button>
              </div>
            </div>
            <button
              id="btn-add-cart"
              type="button"
              data-bs-dismiss="modal"
              class="w-100 btn bar-menu bg-dark py-3 px-4 rounded-5 text-light d-flex justify-content-between align-items-center"
            >
              <p class="my-0 fredoka-font">Total</p>
              <p id="detailPrice" class="my-0 fredoka-font d-flex gap-2"></p>
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/menu.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
