CREATE DATABASE IF NOT EXISTS gestion_projet;
USE gestion_projet;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('manager', 'member') DEFAULT 'member'
);

CREATE TABLE IF NOT EXISTS category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS tag (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS project (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    isPublic BOOLEAN DEFAULT FALSE,
    manager_id INT NOT NULL,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS task (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    createDate DATE DEFAULT (CURRENT_DATE),
    status ENUM('TODO', 'DOING', 'DONE') DEFAULT 'TODO',
    tag_id INT DEFAULT NULL,
    category_id INT NOT NULL,
    project_id INT NOT NULL,
    FOREIGN KEY (tag_id) REFERENCES tag(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES project(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS task_assignment (
    task_id INT NOT NULL,
    users_id INT NOT NULL,
    PRIMARY KEY (task_id, users_id),
    FOREIGN KEY (task_id) REFERENCES task(id) ON DELETE CASCADE,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS project_assignment (
    project_id INT NOT NULL,
    users_id INT NOT NULL,
    PRIMARY KEY (project_id, users_id),
    FOREIGN KEY (project_id) REFERENCES project(id) ON DELETE CASCADE,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role, isLogged) VALUES
('Ibrahim Nidam', 'a@e.com', '$2a$12$CaK1h7hZOPH8lHlrzfWcG.9GKNeQvFzBWkM5oELpFgWJqa0ZyCTOa', 'manager', FALSE),
('Ali Rachid', 'b@e.com', '$2a$12$22jwn/Pb.vCcrFbOe/qgmOtJAGLTE1fcL66VbkFnjn.gffgrB2kX6', 'member', FALSE),
('Sara Elham', 'c@e.com', '$2a$12$22jwn/Pb.vCcrFbOe/qgmOtJAGLTE1fcL66VbkFnjn.gffgrB2kX6', 'member', FALSE),
('Omar Haddad', 'd@e.com', '$2a$12$22jwn/Pb.vCcrFbOe/qgmOtJAGLTE1fcL66VbkFnjn.gffgrB2kX6', 'member', FALSE),
('Lina Amina', 'e@e.com', '$2a$12$22jwn/Pb.vCcrFbOe/qgmOtJAGLTE1fcL66VbkFnjn.gffgrB2kX6', 'member', FALSE);

INSERT INTO category (name) VALUES
('Development'),
('Design'),
('Testing'),
('Documentation'),
('Deployment');

INSERT INTO tag (name) VALUES
('Urgent'),
('Bug'),
('Feature'),
('Improvement'),
('Research');

INSERT INTO project (name, description, isPublic, manager_id, completion_percentage) VALUES
('Kanban Board Project', 'A board to manage tasks in columns', TRUE, 1, 0),
('E-commerce App', 'An application to handle online sales and checkouts', FALSE, 1, 0),
('Mobile Game', 'A fun casual game for iOS and Android platforms', TRUE, 1, 0),
('Chat Application', 'A real-time chat app with notifications', FALSE, 1, 0);

INSERT INTO task (title, description, status, tag_id, category_id, project_id) VALUES
('Set Up Repository', 'Initialize Git repository and set up project structure', '2025-01-02', '2025-01-05', 'TODO', 3, 1, 1),
('Define Workflow', 'Establish Kanban workflow stages and policies', '2025-01-06', '2025-01-10', 'TODO', 4, 2, 1),
('Design UI Mockups', 'Create initial UI mockups for the Kanban board', '2025-01-11', '2025-01-15', 'TODO', 3, 2, 1),
('Implement Authentication', 'Develop user authentication system', '2025-01-16', '2025-01-20', 'TODO', 1, 1, 1),
('Deploy Initial Version', 'Deploy the initial version of the Kanban board application', '2025-01-21', '2025-01-25', 'TODO', 5, 5, 1);

INSERT INTO task (title, description, status, tag_id, category_id, project_id) VALUES
('DB Schema Design', 'Design database schema for products and users', '2025-02-01', '2025-02-05', 'TODO', 3, 1, 2),
('Payment System', 'Integrate payment gateway system', '2025-02-06', '2025-02-10', 'TODO', 1, 1, 2),
('Product Catalog', 'Create product listing interface', '2025-02-11', '2025-02-15', 'TODO', 3, 2, 2),
('User Auth', 'Implement user authentication', '2025-02-16', '2025-02-20', 'TODO', 1, 1, 2),
('Test Payment', 'Test payment system integration', '2025-02-21', '2025-02-25', 'TODO', 2, 3, 2);

INSERT INTO ask (title, description, startDate, endDate, status, tag_id, category_id, project_id) VALUES
('Game Design Doc', 'Create game design document', '2025-03-01', '2025-03-05', 'TODO', 5, 4, 3),
('Character Art', 'Design main character sprites', '2025-03-06', '2025-03-10', 'TODO', 3, 2, 3),
('Game Mechanics', 'Implement core game mechanics', '2025-03-11', '2025-03-15', 'TODO', 3, 1, 3),
('Sound Effects', 'Create and integrate SFX', '2025-03-16', '2025-03-20', 'TODO', 4, 2, 3),
('Beta Testing', 'Conduct beta testing phase', '2025-03-21', '2025-03-25', 'TODO', 2, 3, 3);

INSERT INTO task (title, description, startDate, endDate, status, tag_id, category_id, project_id) VALUES
('WebSocket Setup', 'Set up WebSocket server', '2025-04-01', '2025-04-05', 'TODO', 3, 1, 4),
('Chat UI Design', 'Design chat interface', '2025-04-06', '2025-04-10', 'TODO', 4, 2, 4),
('Message System', 'Implement messaging system', '2025-04-11', '2025-04-15', 'TODO', 3, 1, 4),
('Security Check', 'Perform security audit', '2025-04-16', '2025-04-20', 'TODO', 1, 3, 4),
('API Documentation', 'Document chat API endpoints', '2025-04-21', '2025-04-25', 'TODO', 5, 4, 4);

INSERT INTO task_assignment (task_id, users_id) VALUES
(1, 2), (2, 3), (3, 4), (4, 5), (5, 2),
(6, 2), (7, 3), (8, 4), (9, 5), (10, 2),
(11, 3), (12, 4), (13, 5), (14, 2), (15, 3),
(16, 4), (17, 5), (18, 2), (19, 3), (20, 4);