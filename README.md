# Aleksandra Marchewka: Portfolio

Personal portfolio website built with PHP, JavaScript, and MySQL. Features a bilingual (English/Polish) interface, project showcase with filtering and modal views, contact form, and a private admin panel for content management.

## Features

- English / Polish language toggle
- Dark / light mode (respects system preference)
- Project gallery with tag filters, search, and detail modals
- CV/Resume viewer with language-switching PDF
- Contact form with server-side validation and rate limiting
- Password-protected admin panel
- Admin dashboard: manage projects and view inquiries
- Leaflet map on contact page

## Tech Stack

- **Backend:** PHP 8, PDO, MySQL
- **Frontend:** Vanilla JavaScript, CSS custom properties
- **Libraries:** Font Awesome, Leaflet.js
- **Server:** Apache via XAMPP (local) / any PHP host

## Project Structure

```
portfolio/
├── css/
│   └── styles.css
├── js/
│   ├── translations.js
│   ├── script.js
│   ├── Carousel.js
│   ├── home.js
│   ├── contact.js
│   ├── projects.js
│   └── resume.js
├── php/
│   ├── nav.php
│   └── footer.php
├── admin/
│   ├── index.php
│   ├── dashboard.php
│   ├── project_form.php
│   ├── submit_inquiry.php
│   ├── db.php           ← see setup below
│   └── admin.css
├── uploads/             ← images and PDFs (not tracked in git)
├── Home.php
├── Contact.php
├── Projects.php
└── Resume.php
```

## Setup

### 1. Clone the repo

```bash
git clone https://github.com/AleksandraMarchewka03/portfolio-public.git
```

### 2. Configure the database

Copy the example config and fill in your credentials:

```bash
cp admin/db.example.php admin/db.php
```

Edit `admin/db.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_mysql_user');
define('DB_PASS', 'your_mysql_password');
define('DB_NAME', 'your_db_name');
define('DB_PORT', '3306');
```

### 3. Create the database tables

```sql
CREATE TABLE projects (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  slug        VARCHAR(120) UNIQUE NOT NULL,
  title_en    VARCHAR(255) NOT NULL,
  title_pl    VARCHAR(255),
  desc_en     TEXT,
  desc_pl     TEXT,
  detail_en   TEXT,
  detail_pl   TEXT,
  icon        VARCHAR(100) DEFAULT 'fas fa-code',
  tags        JSON,
  images      JSON,
  github_url  VARCHAR(500),
  live_url    VARCHAR(500),
  sort_order  INT DEFAULT 0,
  published   TINYINT(1) DEFAULT 1
);

CREATE TABLE inquiries (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  sender_name   VARCHAR(255),
  sender_email  VARCHAR(255),
  best_contact  VARCHAR(255),
  message       TEXT,
  ip_address    VARCHAR(45),
  status        ENUM('new','read','replied','archived') DEFAULT 'new',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Add your uploads

Place the following in the `uploads/` folder:

```
uploads/
├── icon.png
├── headshot.jpg
├── placeholder.jpg
├── AJM_CV_EN.pdf
├── AJM_CV_PL.pdf
└── projects/
```

### 5. Admin access

Navigate to `/admin/` and log in with your password. To set a new password hash:

```php
echo password_hash('your_chosen_password', PASSWORD_DEFAULT);
```

Paste the resulting hash into `admin/index.php`.

## Notes

- Real `admin/db.php` is excluded from this repo: never commit real credentials
- `uploads/` is excluded from this repo: add your own assets locally
- Tested on XAMPP (PHP 8.2, MySQL 8)

## Author

**Aleksandra Marchewka**
[LinkedIn](https://www.linkedin.com/in/aleksandrajowitamarchewka/) · [GitHub](https://github.com/AleksandraMarchewka03)
