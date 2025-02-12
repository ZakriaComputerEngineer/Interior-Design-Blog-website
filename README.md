# Interior-Design-Blog-website

A PHP-based social networking website for interior designers and architects, built with XAMPP and phpMyAdmin. The platform allows users to register, upload designs, like, comment, and share posts. The project focuses on database engineering, ensuring secure authentication and scalable data storage for user interactions.

![image](https://github.com/user-attachments/assets/80729eed-61ea-4bc8-8317-6d218d3af7a5)

---

## **Key Features**  
- **User Authentication:** Secure registration and login system.
![image](https://github.com/user-attachments/assets/8f32fbec-f769-4fc4-b3cb-3ef1075edca9)
![image](https://github.com/user-attachments/assets/c18be43d-fb5f-4bdb-ab85-df6d0307eb2a)

- **Post Uploading:** Users can upload design images and descriptions.
![image](https://github.com/user-attachments/assets/9b3671b5-a39d-4e21-93fa-a382b5981a67)
 
- **Likes & Comments:** Users can interact with posts via likes and comments.
![image](https://github.com/user-attachments/assets/5a6438c3-25a1-46c6-9500-fdc9b9459eb3)
 
- **Post Sharing:** Users can share interesting designs with their network.
![image](https://github.com/user-attachments/assets/0042b9db-b105-4b7b-a9b0-753a0998c5a6)
 
- **Database-Driven Content:** All user interactions are stored in a MySQL database for persistent storage.  

---

## **Installation and Setup**  

### **1. Install XAMPP**  
Download and install [XAMPP](https://www.apachefriends.org/index.html) to run the local server.  

### **2. Clone the Repository**  
```bash
git clone https://github.com/yourusername/Interior-Design-Network.git
cd Interior-Design-Network
```

### **3. Start Apache & MySQL**  
Run XAMPP and start the **Apache** and **MySQL** services.  

### **4. Import the Database**  
- Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
- Create a new database called `DBPROJECT`.
- Import the `SQL Scripts.txt` file.

### **5. Configure PHP Files**  
Ensure the database connection is properly set up in your PHP files:  
```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "DBPROJECT";
$conn = new mysqli($servername, $username, $password, $database);
```

### **6. Run the Website**  
- Place the project files in `htdocs` inside the XAMPP installation directory.  
- Open your browser and go to:  
  ```
  http://localhost/Interior-Design-Network/
  ```

---

## **Database Schema**  

### **Users Table (Authentication System)**
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    role VARCHAR(50) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    age INT NOT NULL,
    qualification VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### **Blogs Table (Post Management)**
```sql
CREATE TABLE BLOGS (
    blog_id INT AUTO_INCREMENT PRIMARY KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    images JSON,
    upvotes INT DEFAULT 0,
    downvotes INT DEFAULT 0,
    comments TEXT, 
    FOREIGN KEY (user_id) REFERENCES USERS(id)
);
```

### **User Votes Table (Likes System)**
```sql
CREATE TABLE USER_VOTES (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT,
    user_id INT,
    vote_type ENUM('upvote', 'downvote'),
    FOREIGN KEY (blog_id) REFERENCES BLOGS(blog_id),
    FOREIGN KEY (user_id) REFERENCES USERS(id)
);
```

### **User Comments Table (Comment System)**
```sql
CREATE TABLE USER_COMMENTS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT,
    user_id INT,
    comment TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES BLOGS(blog_id),
    FOREIGN KEY (user_id) REFERENCES USERS(id)
);
```

### **Favorites Table (Saved Posts)**
```sql
CREATE TABLE favourites (
    favourite_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    blog_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id)
);
```

---

## **Project Motivation**  
This project was developed as a **Database Engineering semester project** with a focus on integrating a scalable and structured MySQL database into a social networking platform. The future goal of this project is to create a competitive space where designers can participate in contests and showcase the best interior designs for public view.
![image](https://github.com/user-attachments/assets/9ff14b88-76b4-4dbc-8dec-1006c048e4e3)
![image](https://github.com/user-attachments/assets/0f69953f-8c8e-4d2c-8338-861a3071e086)


---
