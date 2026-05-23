# Docker Compose & Deployment Guide for Photobox Laravel Project

## Bahasa Indonesia (Indonesian)

### 1. Membuat file `docker-compose.yml`
1. Pada folder project root (`c:\\laragon\\www\\photobox`) buat file bernama `docker-compose.yml`.
2. Salin isi berikut ke dalam file tersebut:
```yaml
version: '3.8'

services:
  # Layanan aplikasi PHP (Laravel)
  app:
    image: php:8.2-fpm
    container_name: photobox_app
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
    environment:
      APP_ENV: local
      APP_DEBUG: 'true'
      APP_KEY: base64:YOUR_APP_KEY_HERE   # ganti dengan kunci aplikasi Laravel yang valid
    depends_on:
      - db

  # Web server Nginx
  web:
    image: nginx:alpine
    container_name: photobox_web
    ports:
      - "80:80"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - app

  # Database MySQL
  db:
    image: mysql:8.0
    container_name: photobox_db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: secret_root_password
      MYSQL_DATABASE: photobox
      MYSQL_USER: photobox_user
      MYSQL_PASSWORD: secret_user_password
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql

  # Opsional: phpMyAdmin untuk manajemen database
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: photobox_phpmyadmin
    restart: unless-stopped
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: secret_root_password
    ports:
      - "8080:80"
    depends_on:
      - db

volumes:
  dbdata:
```

### 2. Menghasilkan kunci aplikasi Laravel
Jalankan perintah berikut di terminal Laravel Anda (pastikan PHP dan Composer sudah terinstall):
```bash
php artisan key:generate --show
```
Copy nilai yang dihasilkan, lalu ganti `YOUR_APP_KEY_HERE` pada file `docker-compose.yml` dengan nilai tersebut.

### 3. Membuat konfigurasi Nginx (opsional)
Buat folder `docker/nginx` di dalam project, lalu buat file `default.conf` dengan konfigurasi dasar berikut:
```nginx
server {
    listen 80;
    index index.php index.html;
    root /var/www/html/public;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \\.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 4. Menjalankan secara lokal
```powershell
# Pastikan Docker Desktop sudah berjalan
docker compose up -d
```
Akses aplikasi di `http://localhost` dan phpMyAdmin di `http://localhost:8080`.

### 5. Deploy ke VPS
1. **Install Docker & Docker‑Compose** pada server (ikuti panduan resmi Docker untuk Ubuntu/Debian).
2. **Transfer project** ke VPS, misalnya dengan SCP:
```bash
scp -r c:/laragon/www/photobox user@your-vps-ip:/home/user/photobox
```
3. **Masuk ke VPS**, sesuaikan `APP_KEY` bila perlu, lalu jalankan:
```bash
cd /home/user/photobox
docker compose up -d
```
Aplikasi akan dapat diakses melalui IP publik VPS pada port 80.

## English

### 1. Create the `docker-compose.yml`
1. In the project root (`c:\\laragon\\www\\photobox`) create a file named `docker-compose.yml`.
2. Paste the following content:
```yaml
version: '3.8'

services:
  # PHP application (Laravel) service
  app:
    image: php:8.2-fpm
    container_name: photobox_app
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
    environment:
      APP_ENV: local
      APP_DEBUG: 'true'
      APP_KEY: base64:YOUR_APP_KEY_HERE   # replace with a real Laravel app key
    depends_on:
      - db

  # Nginx web server
  web:
    image: nginx:alpine
    container_name: photobox_web
    ports:
      - "80:80"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - app

  # MySQL database
  db:
    image: mysql:8.0
    container_name: photobox_db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: secret_root_password
      MYSQL_DATABASE: photobox
      MYSQL_USER: photobox_user
      MYSQL_PASSWORD: secret_user_password
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql

  # Optional: phpMyAdmin for DB management
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: photobox_phpmyadmin
    restart: unless-stopped
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: secret_root_password
    ports:
      - "8080:80"
    depends_on:
      - db

volumes:
  dbdata:
```

### 2. Generate Laravel APP_KEY
Run the following command in your Laravel project folder:
```bash
php artisan key:generate --show
```
Copy the generated key and replace `YOUR_APP_KEY_HERE` in `docker-compose.yml`.

### 3. Create a minimal Nginx config (optional)
Create the directory `docker/nginx` and add `default.conf` with:
```nginx
server {
    listen 80;
    index index.php index.html;
    root /var/www/html/public;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \\.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 4. Run locally
```powershell
# Ensure Docker Desktop is running
docker compose up -d
```
Open http://localhost to see the Laravel app and http://localhost:8080 for phpMyAdmin.

### 5. Deploy to a VPS
1. **Install Docker & Docker‑Compose** on the server (follow Docker’s official installation guide for Ubuntu/Debian).
2. **Transfer the project** to the VPS, e.g., using SCP:
```bash
scp -r c:/laragon/www/photobox user@your-vps-ip:/home/user/photobox
```
3. **SSH into the VPS**, adjust the `APP_KEY` if needed, then start the containers:
```bash
cd /home/user/photobox
docker compose up -d
```
The application will be reachable via the VPS public IP on port 80.
