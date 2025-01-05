CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,  
    username VARCHAR(150) NOT NULL,          
    password VARCHAR(255) NOT NULL,          
    email VARCHAR(150) NOT NULL UNIQUE,      
    role ENUM('admin', 'member') NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
);

CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    task_title VARCHAR(255) NOT NULL,
    task_description TEXT NOT NULL,
    due_date DATE NOT NULL,
    assigned_to INT,
    status ENUM('To Do', 'Doing', 'Done') NOT NULL DEFAULT 'To Do',
    FOREIGN KEY (assigned_to) REFERENCES users(user_id)
);

CREATE TABLE tasks_users (
    task_id INT,
    user_id INT,
    PRIMARY KEY (task_id, user_id),
    FOREIGN KEY (task_id) REFERENCES tasks(task_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

