# Material Admin — cPanel / shared hosting deployment

This guide covers deploying **Material Admin** (Laravel 10) on cPanel, WHM, or similar Apache shared hosting.

## Quick diagnosis

If the login page loads but looks unstyled (plain text, no orange branding):

1. Open `https://your-domain.com/css/admin.css` in the browser.
2. If you get **404**, static assets are not being served correctly (see [Document root](#1-document-root) below).

The app auto-adjusts asset URLs when the web root is the project folder instead of `public/`. After uploading files, still run `php artisan config:clear` if you previously cached config.

---

## 1. Document root

**Recommended:** point the subdomain or domain to the Laravel `public` folder.

| cPanel location | Example path |
|-----------------|----------------|
| Subdomain root | `/home/username/material.businessnavacharschool.com/public` |
| Addon domain | `/home/username/public_html/material/public` |

**Steps (cPanel → Domains → Domains):**

1. Click **Manage** next to your domain/subdomain.
2. Set **Document Root** to the `public` directory inside the project (not the project root).
3. Save and wait a minute for Apache to reload.

**Alternative:** keep the document root on the project folder (one level above `public`). The included root `.htaccess` and automatic asset URL detection support this layout, but pointing at `public/` is simpler and faster.

---

## 2. Upload project files

Upload the full repository to the server (Git, ZIP, or File Manager). Required layout:

```
material/                 ← project root (or parent of public/)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/               ← should be the document root (recommended)
│   ├── css/admin.css
│   ├── js/admin.js
│   └── index.php
├── resources/
├── routes/
├── storage/
├── vendor/               ← from composer install
├── .env
├── artisan
└── composer.json
```

Do **not** skip `public/css/` or `public/js/` — the UI depends on them.

---

## 3. Environment file (`.env`)

Copy `.env.example` to `.env` and set at least:

```env
APP_NAME="Material Admin"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://material.businessnavacharschool.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

Generate the application key (SSH or cPanel **Terminal**):

```bash
cd /home/username/path/to/material
php artisan key:generate
```

### Optional: force asset URL

Only needed if assets still 404 after setting the document root to `public/`:

```env
ASSET_URL=https://material.businessnavacharschool.com/public
```

Then clear config cache:

```bash
php artisan config:clear
```

---

## 4. Install PHP dependencies

From the project root (SSH or Terminal):

```bash
composer install --optimize-autoloader --no-dev
```

PHP **8.1+** is required. Enable extensions: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`.

---

## 5. Database

1. Create a MySQL database and user in cPanel → **MySQL® Databases**.
2. Grant the user **ALL PRIVILEGES** on that database.
3. Put credentials in `.env`.
4. Run migrations and seed default users:

```bash
php artisan migrate --force
php artisan db:seed --force
```

### Default login (change after first sign-in)

| Role  | Email                 | Password   |
|-------|-----------------------|------------|
| Admin | `admin@material.test` | `password` |
| Staff | `staff@material.test` | `password` |

---

## 6. Storage and permissions

```bash
php artisan storage:link
chmod -R ug+rwx storage bootstrap/cache
```

On some hosts you may need `775` instead of `777`. Directories must be writable by the web server user.

---

## 7. Optimize for production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

After any `.env` change, run `php artisan config:clear` (or `config:cache` again).

---

## 8. OpenAI (optional)

For AI material generation features, add to `.env`:

```env
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-5.6
OPENAI_USE_API=true
```

---

## 9. Post-deploy checklist

- [ ] `https://your-domain.com/login` shows styled orange/navy login page
- [ ] `https://your-domain.com/css/admin.css` returns **200** (not 404)
- [ ] Login works with seeded admin account
- [ ] `APP_DEBUG=false` in production
- [ ] Default passwords changed
- [ ] HTTPS enabled (cPanel → SSL/TLS)

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| Unstyled login page | Set document root to `public/`, or set `ASSET_URL` and run `config:clear` |
| 500 error | Check `storage/logs/laravel.log`; fix permissions on `storage/` and `bootstrap/cache/` |
| 419 / CSRF on login | Ensure `APP_URL` matches the URL in the browser (including `https://`) |
| Blank page | Set `APP_DEBUG=true` temporarily, check PHP version and `vendor/` installed |
| Database error | Verify `DB_HOST` (often `localhost` on cPanel), database name, user, password |

---

## Local development (XAMPP)

For local paths such as `http://localhost/mehulbhai/git/material/public`:

```env
APP_URL=http://localhost/mehulbhai/git/material/public
```

Use `php artisan serve` for a simpler local setup:

```bash
php artisan serve
# Visit http://127.0.0.1:8000
```
