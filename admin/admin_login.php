<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/admin_login.css">

    <!-- Boxicons -->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="l-form">
        <div class="shape1"></div>
        <div class="shape2"></div>

        <div class="form">
            <img src="../assets/images/logos/authentication.svg" alt="" class="form__img">

            <form action="admin_login_process.php" method="POST" class="form__content">
                <h1 class="form__title">Welcome Admin</h1>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="form__div form__div-one">
                    <div class="form__icon"><i class='bx bx-user-circle'></i></div>
                    <div class="form__div-input">
                        <label for="username" class="form__label">Username</label>
                        <input type="text" name="username" id="username" class="form__input" required>
                    </div>
                </div>

                <div class="form__div">
                    <div class="form__icon"><i class='bx bx-lock'></i></div>
                    <div class="form__div-input">
                        <label for="password" class="form__label">Password</label>
                        <input type="password" name="password" id="password" class="form__input" required>
                    </div>
                </div>

                <input type="submit" name="login" class="form__button" value="Login">
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        const inputs = document.querySelectorAll(".form__input");
        function addfocus() {
            let parent = this.parentNode.parentNode;
            parent.classList.add("focus");
        }
        function remfocus() {
            let parent = this.parentNode.parentNode;
            if (this.value === "") {
                parent.classList.remove("focus");
            }
        }
        inputs.forEach(input => {
            input.addEventListener("focus", addfocus);
            input.addEventListener("blur", remfocus);
        });
    </script>
</body>
</html>
