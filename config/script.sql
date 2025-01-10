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


---------------------------------------- < exemple of database a inseret > -------------------------------------------------------


-- -- Insérer des utilisateurs (managers et membres)
-- INSERT INTO users (name, email, password, role) VALUES
-- ('Alice Dupont', 'alice@exemple.com', 'password123', 'manager'),
-- ('Bob Martin', 'bob@exemple.com', 'password123', 'member'),
-- ('Charlie Lemoine', 'charlie@exemple.com', 'password123', 'member'),
-- ('David Girard', 'david@exemple.com', 'password123', 'member');

-- -- Insérer des catégories
-- INSERT INTO category (name) VALUES
-- ('Développement'),
-- ('Design'),
-- ('Marketing');

-- -- Insérer des tags
-- INSERT INTO tag (name) VALUES
-- ('Urgent'),
-- ('Important'),
-- ('Low Priority'),
-- ('High Priority');

-- -- Insérer des projets
-- INSERT INTO project (name, description, manager_id) VALUES
-- ('Projet A', 'Un projet de développement d\'une application web.', 1),
-- ('Projet B', 'Création d une nouvelle plateforme marketing.', 1),
-- ('Projet C', 'Refonte du site internet de l\'entreprise.', 2);

-- -- Insérer des tâches
-- INSERT INTO task (title, description, createDate, status, tag_id, category_id, project_id) VALUES
-- ('Développement de la base de données', 'Créer la base de données pour le projet A.', '2025-01-01', 'TODO', 1, 1, 1),
-- ('Création des maquettes', 'Concevoir les maquettes pour le projet B.', '2025-01-02', 'DOING', 2, 2, 2),
-- ('Campagne marketing', 'Lancer la campagne marketing pour le projet C.', '2025-01-03', 'TODO', 3, 3, 3),
-- ('Test des fonctionnalités', 'Tester les fonctionnalités développées pour le projet A.', '2025-01-04', 'DONE', 4, 1, 1);

-- -- Assigner des tâches aux utilisateurs
-- INSERT INTO task_assignment (task_id, users_id) VALUES
-- (1, 2),  -- Bob Martin assigne à la tâche "Développement de la base de données"
-- (2, 3),  -- Charlie Lemoine assigne à la tâche "Création des maquettes"
-- (3, 4),  -- David Girard assigne à la tâche "Campagne marketing"
-- (4, 2);  -- Bob Martin assigne à la tâche "Test des fonctionnalités"
