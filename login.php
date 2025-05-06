<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login and Registration Form</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper <?php echo (isset($_SESSION['error'])) ? 'active' : ''; ?>">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>

        <?php if (isset($_SESSION['error'])): ?>
            <div style="color:red; text-align:center; margin-bottom: 10px;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="color:green; text-align:center; margin-bottom: 10px;">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div class="form-box login">
            <h2 class="animation" style="--i:0;--j:20">Login</h2>
            <form action="register_login.php" method="POST">
                <div class="input-box animation" style="--i:1;--j:21">
                    <input type="email" name="login_email" required>
                    <label>Email</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:2;--j:22">
                    <input type="password" name="login_password" required>
                    <label>Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" name="login" class="btn animation" style="--i:3;--j:23">Login</button>
                <div class="logreg-link animation" style="--i:4;--j:24">
                    <p>Don't have an account? <a href="#" class="register-link">Sign Up</a></p>
                </div>
            </form>
        </div>

        <div class="info-text login">
            <h2 class="animation" style="--i:0;--j:20">Welcome Back</h2>
            <p class="animation" style="--i:1;--j:21">Login to continue shopping</p>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <h2 class="animation" style="--i:17; --j:0">Register</h2>
            <form action="register_login.php" method="POST">
                <div class="input-box animation" style="--i:18; --j:1">
                    <input type="text" name="reg_name" required>
                    <label>Name</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:19; --j:2">
                    <input type="email" name="reg_email" required>
                    <label>Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box animation" style="--i:20; --j:3">
                    <input type="password" name="reg_password" required>
                    <label>Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" name="register" class="btn animation" style="--i:21; --j:4">Sign Up</button>
                <div class="logreg-link animation" style="--i:22; --j:5">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>

        <div class="info-text register">
            <h2 class="animation" style="--i:17; --j:0">Welcome!</h2>
            <p class="animation" style="--i:18; --j:1">Join us to shop your favorite products</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const wrapper = document.querySelector('.wrapper');
        const registerLink = document.querySelector('.register-link');
        const loginLink = document.querySelector('.login-link');

        registerLink.onclick = () => wrapper.classList.add('active');
        loginLink.onclick = () => wrapper.classList.remove('active');

        <?php if (isset($_SESSION['switch_to_login'])): ?>
            wrapper.classList.remove('active');
        <?php unset($_SESSION['switch_to_login']); endif; ?>
    </script>
</body>
</html>
