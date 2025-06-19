

# Web Application for Learning Security Vulnerabilities

### Overview
This is a **PHP-based** web application designed as a learning platform for studying and practicing **web security vulnerabilities**. The application simulates a **social media platform** with features like user registration, login (both session-based and JWT-based), posting, following users, password reset via email, and an admin panel. It is intentionally built with vulnerabilities to help users practice **penetration testing** in a safe environment.

### Purpose
The primary goal is to provide a hands-on tool for learning **web penetration testing**. It includes a lot of common vulnerabilities.

---

### Features

1. **User Authentication (Session-Based)**:
   - **Registration**: Create a new account with `username`, `email`, and `password`.
   - **Login**: Sign in using `email` and `password` with `password_verify`.
   - **Logout**: End session and redirect to login page.
   - **Password Reset via Email**:
     - Request a password reset link via email (`forgot_password_process_mail.php`).
     - Receive a reset link with a token sent using **PHPMailer** (SMTP via Gmail).
     - Reset password using a secure form (`reset_password_mail.php`).

2. **User Authentication (JWT-Based)**:
   - **JWT Login**: Separate login system using **JSON Web Tokens** (`jwt_login.php`).
   - **JWT Storage**: Tokens stored in **HttpOnly Cookies** 
   - **JWT Verification**: Middleware checks tokens for protected routes (`jwt_middleware.php`).


3. **User Profiles**:
   - **View Profile**: Display user details, posts, and follower counts (`profile.php`).
   - **Edit Profile**: Upload profile pictures (`upload_process.php`).
   - **Followers/Following**: Show number of followers and following users.

4. **Blog System**:
   - **Create Post**: Add new posts (`post_process.php`).
   - **Edit Post**: Modify existing posts (`edit_post.php`).
   - **Delete Post**: Remove posts (`delete_post.php`).
   - **View Posts**: Display user posts in profile.

5. **Followers System**:
   - **Follow/Unfollow**: Follow or unfollow other users (`follow_process.php`).
   - **View Followers**: List followers for a user (`followers.php`).
   - **Self-Follow Protection**: Prevents users from following themselves.

6. **Admin Panel**:
   - **Admin Access**: Admins (`is_admin = 1`) can access the admin panel (`admin_panel.php`).
   - **User Management**: Admins can manage user accounts .

7. **Blog Page**:
   - A blog page (`blog.php`) linked from profiles (details TBD).

---

## Setup Instructions

### Prerequisites
- **XAMPP** (or any PHP-compatible web server with MySQL).
- **PHP** 7.4 or higher.
- **MySQL/MariaDB**.
- **Composer** (for installing dependencies).
- A web browser.

### Installation

1. **Clone the Repository**:
```bash
   git clone https://github.com/islamkh0x0/web-app.git
   cd web-app 

``` 
 


2. **Install Dependencies**:
    
```bash
composer require firebase/php-jwt
composer require phpmailer/phpmailer 

``` 
 

    
3. **Set Up XAMPP**:
    
    - Install XAMPP and start **Apache** and **MySQL**.
    - Copy the project folder to `C:\xampp\htdocs\web_app`.
4. **Create the Database**:
    
    - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    - Create a database named `web_app`.
    - Run the following SQL to create tables:
        
```sql
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	is_admin TINYINT DEFAULT 0,
	profile_picture VARCHAR(255)
        );
        
CREATE TABLE posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        content TEXT,
        created_at DATETIME,
        FOREIGN KEY (user_id) REFERENCES users(id)
        );
        
CREATE TABLE followers (
       follower_id INT,
       followed_id INT,
       FOREIGN KEY (follower_id) REFERENCES users(id),
       FOREIGN KEY (followed_id) REFERENCES users(id)
       );
        
CREATE TABLE password_resets (
       id INT AUTO_INCREMENT PRIMARY KEY,
       email VARCHAR(255) NOT NULL,
       token VARCHAR(255) NOT NULL,
       created_at DATETIME NOT NULL
       ); 
``` 

        
5. **Configure Email for Password Reset**:
    
    - Update `forgot_password_process_mail.php` with your Gmail SMTP credentials:
        - Replace `mail@gmail.com` with your Gmail address.
        - Replace `app_password` with a Gmail **App Password** (generate one from Google Account → Security → 2-Step Verification → App Passwords).
    - Ensure **Less Secure App Access** is disabled in your Google Account.
6. **Run the Application**:
    
    - Open `http://localhost/web_app` in your browser.
    - Test session-based login at `login.php` or JWT-based login at `jwt_login.php`.


---

---

## Contributing

Feel free to fork this repository and submit pull requests. For bug reports or feature requests, open an issue on GitHub.

---

## License

This project is licensed under the MIT License.

---

## Contact

For questions or feedback, contact [islamkh0x0](https://github.com/islamkh0x0).  
