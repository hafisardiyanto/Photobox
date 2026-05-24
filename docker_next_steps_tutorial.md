# Next Steps after Docker Deployment (Photobox Laravel)

## Bahasa Indonesia (Indonesian)

### 1. Pastikan file `.env` sudah tepat
- Pada VPS, buat atau salin file `.env` di root project (`/home/user/photobox`).
- Sesuaikan variabel database dengan service `db` di Docker Compose:
  ```
  DB_CONNECTION=mysql
  DB_HOST=db               # nama service Docker, bukan localhost
  DB_PORT=3306
  DB_DATABASE=photobox
  DB_USERNAME=photobox_user
  DB_PASSWORD=secret_user_password
  ```
- Jangan lupa **APP_KEY** yang sudah Anda generate pada langkah sebelumnya.

### 2. Install dependensi Composer di dalam container `app`
```bash
docker exec -it photobox_app composer install --no-interaction --prefer-dist
```
Jika Anda belum meng‑install Composer di dalam container, gunakan image yang sudah memiliki Composer atau tambahkan langkah berikut ke Dockerfile (optional).

### 3. Jalankan migrasi database
```bash
docker exec -it photobox_app php artisan migrate --force
```
Perintah `--force` diperlukan karena dijalankan di dalam environment non‑interactive.

### 4. (Opsional) Seed data contoh
```bash
docker exec -it photobox_app php artisan db:seed --force
```
Jika Anda memiliki seeder khusus, pastikan sudah terdaftar di `DatabaseSeeder`.

### 5. Bersihkan cache & konfigurasi
```bash
docker exec -it photobox_app php artisan config:cache
docker exec -it photobox_app php artisan route:cache
docker exec -it photobox_app php artisan view:cache
```

### 6. Akses aplikasi
- Buka browser dan kunjungi **http://<VPS‑IP>** (port 80). 
- phpMyAdmin tersedia di **http://<VPS‑IP>:8080**.

### 7. Tambahan: HTTPS dengan Let’s Encrypt (opsional)
1. Tambahkan layanan `nginx-proxy` atau gunakan **Certbot** di VPS.
2. Contoh cepat menggunakan Certbot:
```bash
sudo apt-get update && sudo apt-get install -y certbot
sudo certbot certonly --standalone -d yourdomain.com
```
3. Update `docker/nginx/default.conf` untuk menggunakan sertifikat:
```nginx
ssl_certificate     /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
```
4. Restart stack: `docker compose up -d`.

### 8. Monitoring & Log
- Tampilkan log seluruh stack:
```bash
docker compose logs -f
```
- Log service tertentu:
```bash
docker logs -f photobox_app
```

---

## English

### 1. Verify the `.env` file
- On the VPS, create or copy the `.env` file at the project root (`/home/user/photobox`).
- Adjust the database variables to match the Docker‑Compose `db` service:
  ```
  DB_CONNECTION=mysql
  DB_HOST=db               # Docker service name, not localhost
  DB_PORT=3306
  DB_DATABASE=photobox
  DB_USERNAME=photobox_user
  DB_PASSWORD=secret_user_password
  ```
- Ensure the **APP_KEY** you generated earlier is set.

### 2. Install Composer dependencies inside the `app` container
```bash
docker exec -it photobox_app composer install --no-interaction --prefer-dist
```
If Composer is not available in the image, you may need to extend the PHP‑FPM image with Composer installed.

### 3. Run database migrations
```bash
docker exec -it photobox_app php artisan migrate --force
```
The `--force` flag is required for non‑interactive environments.

### 4. (Optional) Seed example data
```bash
docker exec -it photobox_app php artisan db:seed --force
```
Make sure your custom seeders are registered in `DatabaseSeeder`.

### 5. Clear caches & config
```bash
docker exec -it photobox_app php artisan config:cache
docker exec -it photobox_app php artisan route:cache
docker exec -it photobox_app php artisan view:cache
```

### 6. Access the application
- Open a browser and go to **http://<VPS‑IP>** (port 80).
- phpMyAdmin is reachable at **http://<VPS‑IP>:8080**.

### 7. (Optional) HTTPS with Let’s Encrypt
1. Add a reverse‑proxy service (e.g., nginx‑proxy) or use **Certbot** directly on the VPS.
2. Quick Certbot example:
```bash
sudo apt-get update && sudo apt-get install -y certbot
sudo certbot certonly --standalone -d yourdomain.com
```
3. Update `docker/nginx/default.conf` to use the certificates:
```nginx
ssl_certificate     /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
```
4. Restart the stack: `docker compose up -d`.

### 8. Monitoring & Logs
- View live logs of the whole stack:
```bash
docker compose logs -f
```
- View a specific service log:
```bash
docker logs -f photobox_app
```

---

*These steps assume you already have Docker and Docker‑Compose installed on the VPS and that the `docker-compose.yml` file is already placed in the project root.*
