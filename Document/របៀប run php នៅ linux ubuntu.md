នេះជាការពន្យល់ “របៀប run PHP នៅលើ Ubuntu” ជាភាសាខ្មែរ ឲ្យងាយយល់ 👇

---

## 🔹 1. ដំឡើង PHP

បើក Terminal ហើយវាយ:

```bash
sudo apt update
sudo apt install php
```

ពិនិត្យថា PHP ដំណើរការឬអត់:

```bash
php -v
```

បើបង្ហាញ version (ឧ. PHP 8.x) មានន័យថា OK ✅

---

## 🔹 2. Run PHP file (សាកល្បង)

បង្កើត file មួយ:

```bash
nano test.php
```

បញ្ចូល code:

```php
<?php
echo "Hello PHP!";
?>
```

រក្សាទុក:

* ចុច `Ctrl + X`
* ចុច `Y`
* ចុច Enter

Run:

```bash
php test.php
```

👉 បើបង្ហាញ “Hello PHP!” មានន័យថាដំណើរការហើយ

---

## 🔹 3. Run ជា Web (មើលក្នុង Browser)

ប្រើ PHP built-in server:

```bash
php -S localhost:8000
```

បន្ទាប់មកបើក browser:

```
http://localhost:8000/test.php
```

---

## 🔹 4. ប្រើ Apache (Server ពិត)

ដំឡើង Apache + PHP:

```bash
sudo apt install apache2 libapache2-mod-php
```

បើក Apache:

```bash
sudo systemctl start apache2
```

ដាក់ file នៅទីនេះ:

```
/var/www/html/
```

ឧទាហរណ៍:

```bash
sudo nano /var/www/html/index.php
```

ដាក់ code:

```php
<?php
phpinfo();
?>
```

បើក browser:

```
http://localhost
```

---

## 🔹 5. កំណត់ Permission (សំខាន់ណាស់)

បើអ្នកធ្វើ project:

```bash
sudo chown -R $USER:$USER /var/www/html
sudo chmod -R 755 /var/www/html
```

---

## 🔹 6. ដំឡើង Extension (សម្រាប់ Laravel)

```bash
sudo apt install php-mysql php-curl php-mbstring php-xml php-zip
```

---

## 🔹 7. ដំឡើង Composer (Laravel)

```bash
sudo apt install composer
```

---

## 📌 សង្ខេប

* `php file.php` 👉 run ក្នុង Terminal
* `php -S localhost:8000` 👉 run web ងាយៗ
* Apache 👉 សម្រាប់ project ពិត

---

បើអ្នកចង់ 👉 ខ្ញុំអាចបង្រៀនបន្តពី:

* របៀបដំឡើង Laravel លើ Ubuntu
* ឬ setup project នៅ `/var/www` ឲ្យដំណើរការល្អ

ប្រាប់ខ្ញុំបាន 👍
