FROM php:8.2-apache

# ติดตั้งตัวเชื่อมต่อฐานข้อมูล (mysqli และ pdo)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# เปิดใช้งานการเขียน URL ใหม่ (เผื่อต้องใช้ .htaccess)
RUN a2enmod rewrite