======================================================================
JAWEB SOLUTIONS - PROJECT DOCUMENTATION & SETUP GUIDE
======================================================================

Project Name: JAWeb Portfolio & CMS
Version: 1.0.0
Author: Gemini Thought Partner
Date: December 2025

----------------------------------------------------------------------
1. PROJECT OVERVIEW
----------------------------------------------------------------------
A custom-built, lightweight Content Management System (CMS) designed 
for digital agencies and freelancers. Features include:
- Glassmorphism Admin Login
- Real-time Lead Tracking & Email Notifications
- Dynamic Portfolio Management (Upload/Edit/Delete)
- Global Site Configuration (Settings Manager)
- Lead Analytics Chart (Chart.js)

----------------------------------------------------------------------
2. INSTALLATION STEPS
----------------------------------------------------------------------
1. Database Setup:
   - Create a database in phpMyAdmin named `jasquare_app`.
   - Import the provided `setup.sql` file.

2. Configuration:
   - Open `db_config.php` in the root folder.
   - Update DB_USER (usually 'root') and DB_PASS (usually '') to 
     match your environment.

3. File Permissions:
   - Ensure the `/uploads/portfolio/` directory is writable (chmod 755).

4. Accessing the Panel:
   - Frontend: http://localhost/jaweb/
   - Admin Panel: http://localhost/jaweb/admin/

----------------------------------------------------------------------
3. DEFAULT CREDENTIALS
----------------------------------------------------------------------
Username: admin
Password: admin123
Access Level: Super Admin

*Security Note: Please change the password immediately via the 
 "Team" management page or phpMyAdmin.*

----------------------------------------------------------------------
4. FOLDER STRUCTURE
----------------------------------------------------------------------
/admin              - Protected administrative backend scripts
/uploads/portfolio  - Repository for project images
index.php           - Modern, responsive frontend homepage
db_config.php       - Global database and settings configuration
contact-process.php - Form logic with email automation

----------------------------------------------------------------------
5. TECHNOLOGY STACK
----------------------------------------------------------------------
- Language: PHP 7.4+ (MySQLi)
- Database: MySQL (MariaDB)
- Frontend: Bootstrap 5, FontAwesome 6, Animate.css
- Charts: Chart.js
- Design: Glassmorphism UI / Modern Grid

----------------------------------------------------------------------
6. TROUBLESHOOTING
----------------------------------------------------------------------
- If you see "Warning: Undefined array key": 
  Ensure you have logged in through index.php to set the session.
- If images don't upload: 
  Check that the 'uploads' folder has write permissions.
- If emails aren't arriving: 
  Ensure your server has SMTP/PHP Mail support enabled.

======================================================================
(c) 2025 JAWeb Solutions. Build with efficiency and security.
======================================================================