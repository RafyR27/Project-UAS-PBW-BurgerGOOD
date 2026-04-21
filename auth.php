<?php
  session_start();

  require "config/db.php";

  if(isset($_SESSION['id'])){
    if ($_SESSION['role'] === "admin") {
      header("location: " . $BASE_URL . 'admin/dashboard.php');
    } else if ($_SESSION['role'] === "kasir") {
      header("location: " . $BASE_URL . 'kasir/dashboard.php');
    }
    exit;
  }

  $error = false; 

  if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM user WHERE username = ? AND password = ?"
    );

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($query) == 1){
      $row = mysqli_fetch_assoc($query);

      $_SESSION['id'] = $row['id'];
      $_SESSION['role'] = $row['role'];
      $_SESSION['outlet_code'] = $row['outlet_code'];

      if ($row['role'] === "admin") {
        header("location: " . $BASE_URL . 'admin/dashboard.php');
      } else if ($row['role'] === "kasir") {
        header("location: " . $BASE_URL . 'kasir/dashboard.php');     
      }

      exit;
    } else {
      $error = true; 
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | BurgerGOOD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/styles.css" />
    <link rel="stylesheet" href="assets/bootstrap-icons/bootstrap-icons.css" />
    <link rel="icon" href="public/logo.png" />
  </head>
  <body class="bg-cream">
    <div class="w-100 d-flex justify-content-center align-items-center">
      <div
        class="w-50 logo-section bg-dark d-none d-lg-flex justify-content-center align-items-center"
      >
        <img
          src="public/logo.png"
          alt="BurgerGOOD"
          class="w-50 object-fit-cover"
        />
      </div>
      <form
        class="form-input login-section fredoka-font d-flex flex-column justify-content-center align-items-center px-5 position-relative"
        method="POST"
        action=""
      >
        <div
          class="d-flex align-items-center position-absolute top-0 start-0 p-3 gap-2"
        >
          <img
            src="public/logo.png"
            alt="burgerGOOD"
            class="object-fit-cover"
            style="width: 50px"
          />
          <p class="my-0 fredoka-font-bold fs-5 text-danger">BurgerGOOD</p>
        </div>
        <h1 class="fredoka-font-medium">Login</h1>
        <?php if($error): ?>
          <p class="my-0 text-danger">Login failed!</p>
        <?php endif; ?>
        <div class="mb-3 form-input">
          <label for="exampleInputEmail1" class="form-label">Username</label>
          <input type="text" class="form-control" id="exampleInputEmail1" name="username" />
        </div>
        <div class="mb-3 form-input">
          <label for="exampleInputPassword1" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="exampleInputPassword1"
            name="password"
          />
        </div>
        <button type="submit" class="btn btn-dark form-input" name="login">Login</button>
        <a
          href="index.html"
          class="my-4 text-dark link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover"
          >Back to home</a
        >
      </form>
    </div>

    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
