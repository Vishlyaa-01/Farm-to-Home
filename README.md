# Farm-to-Home
Online crop selling platform for farmer

# 🗄️ Database Setup (XAMPP / phpMyAdmin)

Follow these steps to connect the project to the database:

## 1. Start XAMPP

* Open **XAMPP Control Panel**
* Start:

  * Apache
  * MySQL

---

## 2. Open phpMyAdmin

Go to:
http://localhost/phpmyadmin/

---

## 3. Create Database

* Click on **New**
* Enter database name:

```bash
farm2home
```

* Click **Create**

---

## 4. Import SQL File

* Select the database (`farm2home`)
* Go to **Import** tab
* Click **Choose File**
* Select file from project:

```
Database/farm2home (1).sql
```

* Click **Go**

---

## 5. Configure Database Connection

Open file:

```
db.php
```

Update credentials if needed:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farm2home";
```

---

## 6. Run the Project

Open in browser:

```
http://localhost/farm2home
```

---

## ⚠️ Notes

* Default XAMPP MySQL username is `root`
* Password is empty (`""`) by default
* Make sure Apache & MySQL are running

---

## ✅ Done!

Your project should now be connected to the database successfully 🎉
