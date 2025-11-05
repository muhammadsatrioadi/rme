@echo off
echo ============================================
echo Setup Database RME System
echo ============================================
echo.

echo [1/3] Creating database rme_system...
"D:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS rme_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %errorlevel% neq 0 (
    echo ERROR: Failed to create database!
    pause
    exit /b 1
)
echo SUCCESS: Database created!

echo.
echo [2/3] Importing database schema...
"D:\xampp\mysql\bin\mysql.exe" -u root rme_system < database_schema.sql

if %errorlevel% neq 0 (
    echo ERROR: Failed to import database schema!
    pause
    exit /b 1
)
echo SUCCESS: Schema imported!

echo.
echo [3/3] Importing default data (admin user)...
"D:\xampp\mysql\bin\mysql.exe" -u root rme_system < setup_database.sql

if %errorlevel% neq 0 (
    echo ERROR: Failed to import default data!
    pause
    exit /b 1
)
echo SUCCESS: Default data imported!

echo.
echo ============================================
echo Setup completed successfully!
echo ============================================
echo.
echo Login credentials:
echo   Username: admin
echo   Password: admin123
echo.
echo Access: http://localhost:8000/
echo.
pause

