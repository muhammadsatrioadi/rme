# Panduan Instalasi Sistem RME

## Persyaratan Sistem

### Server Requirements
- **PHP**: 7.4 atau lebih baru
- **MySQL**: 5.7 atau lebih baru
- **Apache**: 2.4 atau Nginx
- **RAM**: Minimal 2GB
- **Storage**: Minimal 10GB

### PHP Extensions
- OpenSSL
- PDO
- PDO_MySQL
- MBstring
- GD
- cURL
- JSON
- XML
- ZIP

### Browser Support
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## Langkah Instalasi

### 1. Persiapan Server

#### Ubuntu/Debian
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache
sudo apt install apache2 -y

# Install MySQL
sudo apt install mysql-server -y

# Install PHP dan extensions
sudo apt install php7.4 php7.4-mysql php7.4-curl php7.4-json php7.4-mbstring php7.4-xml php7.4-zip php7.4-gd php7.4-cli php7.4-common -y

# Enable Apache modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo systemctl restart apache2
```

#### CentOS/RHEL
```bash
# Update system
sudo yum update -y

# Install Apache
sudo yum install httpd -y

# Install MySQL
sudo yum install mysql-server -y

# Install PHP dan extensions
sudo yum install php php-mysql php-curl php-json php-mbstring php-xml php-zip php-gd php-cli -y

# Enable Apache modules
sudo systemctl enable httpd
sudo systemctl start httpd
```

### 2. Konfigurasi MySQL

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Login ke MySQL
sudo mysql -u root -p

# Buat database dan user
CREATE DATABASE rme_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'rme_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON rme_system.* TO 'rme_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Download dan Setup Aplikasi

```bash
# Clone repository
git clone https://github.com/your-repo/sistem-rme.git
cd sistem-rme

# Atau download dan extract
wget https://github.com/your-repo/sistem-rme/archive/main.zip
unzip main.zip
mv sistem-rme-main sistem-rme
cd sistem-rme
```

### 4. Konfigurasi Database

```bash
# Import database schema
mysql -u rme_user -p rme_system < database_schema.sql

# Verifikasi import
mysql -u rme_user -p rme_system -e "SHOW TABLES;"
```

### 5. Konfigurasi Aplikasi

#### Database Configuration
Edit file `application/config/database.php`:

```php
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'rme_user',
    'password' => 'strong_password_here',
    'database' => 'rme_system',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

#### Application Configuration
Edit file `application/config/config.php`:

```php
$config['base_url'] = 'https://yourdomain.com/';
$config['index_page'] = '';
$config['encryption_key'] = 'your_32_character_encryption_key_here';
$config['sess_driver'] = 'database';
$config['sess_save_path'] = 'ci_sessions';
$config['csrf_protection'] = TRUE;
```

#### Security Configuration
Edit file `application/config/security.php`:

```php
$config['encryption_key'] = 'your_32_character_encryption_key_here';
$config['force_https'] = TRUE;
$config['max_login_attempts'] = 5;
$config['login_attempt_timeout'] = 900;
```

### 6. Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/html/sistem-rme

# Set permissions
sudo chmod -R 755 /var/www/html/sistem-rme
sudo chmod -R 777 /var/www/html/sistem-rme/application/logs
sudo chmod -R 777 /var/www/html/sistem-rme/uploads
sudo chmod -R 777 /var/www/html/sistem-rme/assets/uploads
```

### 7. Konfigurasi Web Server

#### Apache Virtual Host
Buat file `/etc/apache2/sites-available/rme.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/sistem-rme
    
    <Directory /var/www/html/sistem-rme>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/rme_error.log
    CustomLog ${APACHE_LOG_DIR}/rme_access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/sistem-rme
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/html/sistem-rme>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/rme_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/rme_ssl_access.log combined
</VirtualHost>
```

Enable site:
```bash
sudo a2ensite rme.conf
sudo systemctl reload apache2
```

#### Nginx Configuration
Buat file `/etc/nginx/sites-available/rme`:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/html/sistem-rme;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/html/sistem-rme;
    index index.php index.html;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/rme /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 8. SSL Certificate

#### Let's Encrypt (Recommended)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get certificate
sudo certbot --apache -d yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

#### Self-Signed Certificate (Development)
```bash
# Generate private key
sudo openssl genrsa -out /etc/ssl/private/rme.key 2048

# Generate certificate
sudo openssl req -new -x509 -key /etc/ssl/private/rme.key -out /etc/ssl/certs/rme.crt -days 365

# Set permissions
sudo chmod 600 /etc/ssl/private/rme.key
sudo chmod 644 /etc/ssl/certs/rme.crt
```

### 9. Firewall Configuration

#### UFW (Ubuntu)
```bash
# Enable UFW
sudo ufw enable

# Allow SSH
sudo ufw allow ssh

# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Check status
sudo ufw status
```

#### Firewalld (CentOS)
```bash
# Start firewalld
sudo systemctl start firewalld
sudo systemctl enable firewalld

# Allow services
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --permanent --add-service=ssh

# Reload
sudo firewall-cmd --reload
```

### 10. Cron Jobs

```bash
# Edit crontab
sudo crontab -e

# Add jobs
# Cleanup logs every day at 2 AM
0 2 * * * /usr/bin/php /var/www/html/sistem-rme/index.php cron cleanup_logs

# Backup database every day at 3 AM
0 3 * * * /usr/bin/mysqldump -u rme_user -p'password' rme_system > /backup/rme_$(date +\%Y\%m\%d).sql

# SSL certificate renewal
0 12 * * * /usr/bin/certbot renew --quiet
```

### 11. Monitoring dan Logging

#### Log Rotation
Buat file `/etc/logrotate.d/rme`:

```
/var/www/html/sistem-rme/application/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

#### System Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Monitor disk usage
df -h

# Monitor memory usage
free -h

# Monitor processes
htop
```

### 12. Backup Strategy

#### Database Backup
```bash
# Create backup script
sudo nano /usr/local/bin/backup_rme.sh

#!/bin/bash
BACKUP_DIR="/backup/rme"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="rme_system"
DB_USER="rme_user"
DB_PASS="password"

mkdir -p $BACKUP_DIR
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/rme_$DATE.sql
gzip $BACKUP_DIR/rme_$DATE.sql

# Keep only last 30 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete

# Make executable
sudo chmod +x /usr/local/bin/backup_rme.sh
```

#### File Backup
```bash
# Create file backup script
sudo nano /usr/local/bin/backup_files.sh

#!/bin/bash
BACKUP_DIR="/backup/rme"
SOURCE_DIR="/var/www/html/sistem-rme"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $SOURCE_DIR .

# Keep only last 7 days
find $BACKUP_DIR -name "files_*.tar.gz" -mtime +7 -delete

# Make executable
sudo chmod +x /usr/local/bin/backup_files.sh
```

### 13. Testing Instalasi

#### 1. Test Database Connection
```bash
# Test MySQL connection
mysql -u rme_user -p rme_system -e "SELECT COUNT(*) FROM mst_pasien;"
```

#### 2. Test Web Server
```bash
# Test HTTP
curl -I http://yourdomain.com

# Test HTTPS
curl -I https://yourdomain.com
```

#### 3. Test Application
1. Buka browser dan akses `https://yourdomain.com`
2. Login dengan user default (jika ada)
3. Test fitur-fitur utama
4. Periksa log error jika ada masalah

### 14. Troubleshooting

#### Common Issues

**1. Error 500 Internal Server Error**
```bash
# Check Apache error log
sudo tail -f /var/log/apache2/error.log

# Check PHP error log
sudo tail -f /var/log/php7.4-fpm.log

# Check application log
tail -f /var/www/html/sistem-rme/application/logs/log-$(date +%Y-%m-%d).php
```

**2. Database Connection Error**
```bash
# Test database connection
mysql -u rme_user -p rme_system

# Check MySQL status
sudo systemctl status mysql

# Check MySQL error log
sudo tail -f /var/log/mysql/error.log
```

**3. Permission Issues**
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/html/sistem-rme

# Fix permissions
sudo chmod -R 755 /var/www/html/sistem-rme
sudo chmod -R 777 /var/www/html/sistem-rme/application/logs
sudo chmod -R 777 /var/www/html/sistem-rme/uploads
```

**4. SSL Certificate Issues**
```bash
# Check certificate
openssl x509 -in /etc/ssl/certs/rme.crt -text -noout

# Test SSL
openssl s_client -connect yourdomain.com:443

# Renew Let's Encrypt
sudo certbot renew
```

### 15. Security Hardening

#### 1. Disable Unnecessary Services
```bash
# Disable Apache modules
sudo a2dismod status
sudo a2dismod info

# Disable PHP functions
sudo nano /etc/php/7.4/apache2/php.ini
# Add to disable_functions: exec,passthru,shell_exec,system,proc_open,popen
```

#### 2. Configure Fail2Ban
```bash
# Install Fail2Ban
sudo apt install fail2ban -y

# Configure
sudo nano /etc/fail2ban/jail.local

[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[apache-auth]
enabled = true

[apache-badbots]
enabled = true

[apache-noscript]
enabled = true

[apache-overflows]
enabled = true

# Start service
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

#### 3. Configure ModSecurity
```bash
# Install ModSecurity
sudo apt install libapache2-mod-security2 -y

# Configure
sudo a2enmod security2
sudo systemctl restart apache2
```

### 16. Performance Optimization

#### 1. PHP Optimization
```bash
# Edit PHP configuration
sudo nano /etc/php/7.4/apache2/php.ini

# Optimize settings
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
max_input_vars = 3000

# Restart Apache
sudo systemctl restart apache2
```

#### 2. MySQL Optimization
```bash
# Edit MySQL configuration
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Add optimizations
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_size = 64M
query_cache_type = 1

# Restart MySQL
sudo systemctl restart mysql
```

#### 3. Apache Optimization
```bash
# Edit Apache configuration
sudo nano /etc/apache2/apache2.conf

# Add optimizations
ServerTokens Prod
ServerSignature Off
KeepAlive On
MaxKeepAliveRequests 100
KeepAliveTimeout 5

# Enable modules
sudo a2enmod deflate
sudo a2enmod expires
sudo a2enmod headers

# Restart Apache
sudo systemctl restart apache2
```

## Maintenance

### Daily Tasks
- Monitor disk space
- Check application logs
- Verify backup completion
- Monitor system performance

### Weekly Tasks
- Review security logs
- Update system packages
- Clean temporary files
- Test backup restoration

### Monthly Tasks
- Security audit
- Performance review
- Update documentation
- Plan capacity upgrades

## Support

Jika mengalami masalah selama instalasi, silakan:
1. Periksa log error
2. Konsultasi dokumentasi
3. Hubungi tim support
4. Buat issue di repository

## Changelog

### Version 1.0.0
- Initial installation guide
- Basic security configuration
- Performance optimization
- Backup strategy

