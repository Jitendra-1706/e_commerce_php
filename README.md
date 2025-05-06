# 🛍️ E-Commerce Web Application

A full-stack PHP-MySQL-based E-Commerce platform featuring user and admin panels, cart management, product browsing, wishlist, and more. Built with modern UI practices and modular structure for scalability.

---

## 🚀 Features

### 👤 User Panel
- 🔐 Register/Login with session handling
- 🛒 Cart and Wishlist functionality
- 📦 View orders and order history
- 👤 Profile management
- 🔎 Product search and browse

### 🛠️ Admin Panel
- 🛍️ Add/Edit/Delete products
- 🗂️ Manage categories
- 📦 View and manage orders
- 👥 View users and their activities
- ⚙️ Settings panel (e.g., theme preferences)

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, Bootstrap
- **Backend:** PHP
- **Database:** MySQL
- **Other Tools:** JavaScript, Chart.js (for analytics), Font Awesome, Boxicons

---

## 📁 Folder Structure

```
📁 E-Commerce
├── 📁 admin
│   ├── add_product.php
│   ├── categories.php
│   ├── dashboard.php
│   ├── ...
├── 📁 assets
│   ├── css/
│   ├── js/
│   └── images/
├── 📁 database_file
│   └── db_connect.php
├── 📁 includes
│   ├── header.php
│   ├── footer.php
│   └── ...
├── 📁 users
│   ├── login.php
│   ├── register.php
│   ├── profile.php
│   └── ...
├── index.php
├── about.php
├── contact.php
├── products.php
├── cart.php
├── wishlist.php
└── README.md
```

---

## ⚙️ Setup Instructions

1. Clone this repository or download the ZIP.
2. Import the MySQL database from `ecommerce.sql`.
3. Configure your DB credentials in `database_file/db_connect.php`.
4. Run the app using [XAMPP](https://www.apachefriends.org/) or [WAMP](http://www.wampserver.com/).

---

## 🔐 Authentication

- Passwords are securely hashed using `password_hash()`.
- Session-based login system.

---

## 👨‍💻 Author

Built with ❤️ by **Jitendra C**  
🔗 [GitHub](https://github.com/Jitendra-1706)

---

## 📄 License

Licensed under the MIT License – feel free to use and adapt this project.