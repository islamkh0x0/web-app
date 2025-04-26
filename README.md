# Web Application for Learning Security Vulnerabilities

### Overview

This is a PHP-based web application designed as a learning platform for studying and practicing web security vulnerabilities. The application simulates a social media platform with features like user registration, login, posting, following users, and an admin panel. 
 
### Purpose

The primary goal of this application is to serve as a hands-on tool for learning web penetration testing. 

---

### Features 

- User Authentication: Register, login, logout, and password reset functionality. 

- User Profiles: View and edit user profiles, including posts and followers. 

- Blog System: Create, edit, and delete posts (vulnerable to various attacks). 

- Followers System: Follow/unfollow users with a vulnerable implementation. 

- Admin Panel: Manage users and toggle admin privileges (with access control issues). 

---

## Setup Instructions

To run the application locally, follow these steps:

### Prerequisites 

- XAMPP (or any PHP-compatible web server with MySQL). 

- PHP 7.4 or higher. 

- MySQL/MariaDB. 

- A web browser 

---

## Installation 

1. Clone the Repository: 

```bash 
git clone https://github.com/islamkh0x0/web-app.git 
cd web-app 
``` 

2. Set Up XAMPP: 
- Install XAMPP and start Apache and MySQL. 

- Copy the project folder to C:\xampp\htdocs\

3. Create the Database: 
```sql 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT DEFAULT 0
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
    user_id INT,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
); 
```

