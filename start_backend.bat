@echo off
cd /d C:\projects\ERB

php artisan licenses:send-renewal-reminders
php artisan serve --host=0.0.0.0 --port=8000