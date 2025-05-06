<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="stylesheet" href="admin.css"> <!-- Link to your existing CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
        }

        body.dark-mode {
            background-color: #1e1e1e;
            color: #f1f1f1;
        }

        .settings-container {
            margin-left: 220px;
            padding: 20px;
        }

        .toggle-switch {
            margin-bottom: 20px;
        }

        a, .btn {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <!-- Your main content goes here -->
        <div class="settings-container">
        <h2>Settings</h2>

        <div class="toggle-switch">
            <label for="themeToggle">Dark Mode</label>
            <input type="checkbox" id="themeToggle">
        </div>

        <div>
            <label for="colorScheme">Theme Color</label>
            <input type="color" id="colorScheme">
        </div>
    </div>
    </div>
</div>



    <script>
        // Load theme from localStorage
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
            document.getElementById("themeToggle").checked = true;
        }

        if (localStorage.getItem("primaryColor")) {
            document.documentElement.style.setProperty('--primary-color', localStorage.getItem("primaryColor"));
            document.getElementById("colorScheme").value = localStorage.getItem("primaryColor");
        }

        document.getElementById("themeToggle").addEventListener("change", function () {
            if (this.checked) {
                document.body.classList.add("dark-mode");
                localStorage.setItem("theme", "dark");
            } else {
                document.body.classList.remove("dark-mode");
                localStorage.setItem("theme", "light");
            }
        });

        document.getElementById("colorScheme").addEventListener("input", function () {
            const color = this.value;
            document.documentElement.style.setProperty('--primary-color', color);
            localStorage.setItem("primaryColor", color);
        });
    </script>
</body>
</html>
