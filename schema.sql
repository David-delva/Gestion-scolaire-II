--
-- Fichier : schema.sql
-- Description : Schéma de la base de données pour le projet de Gestion des Étudiants.
-- Version : 1.0
-- Date : Octobre 2025
--

-- Désactiver les vérifications de clés étrangères temporairement pour permettre la suppression des tables
SET FOREIGN_KEY_CHECKS = 0;

-- Suppression des tables existantes si elles existent
DROP TABLE IF EXISTS `absences`;
DROP TABLE IF EXISTS `grades`;
DROP TABLE IF EXISTS `teacher_subject_class`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `teachers`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `classes`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

--
-- Table `roles`
-- Description : Définit les rôles des utilisateurs dans l'application.
--
-- ==============================================
-- Base de données : gestion_scolaire
-- ==============================================

-- ==============================================
-- Base de données : gestion_scolaire
-- ==============================================
CREATE DATABASE IF NOT EXISTS gestion_scolaire 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE gestion_scolaire;

-- ==============================================
-- TABLE : roles
-- ==============================================
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nom du rôle (Administrateur, Enseignant, Étudiant)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE : users
-- ==============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL COMMENT 'Clé étrangère vers roles',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Adresse email de connexion',
    password VARCHAR(255) NOT NULL COMMENT 'Mot de passe haché',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Statut actif de l’utilisateur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE : classes
-- ==============================================
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE COMMENT 'Nom de la classe (ex: Terminale S, Première L)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE : subjects
-- ==============================================
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE COMMENT 'Nom de la matière'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE : teachers
-- ==============================================
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE COMMENT 'Référence vers users',
    phone VARCHAR(20),
    hire_date DATE NOT NULL,
    CONSTRAINT fk_teacher_user FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE : students
-- ==============================================
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE COMMENT 'Référence vers users',
    student_id_number VARCHAR(50) NOT NULL UNIQUE COMMENT 'Matricule étudiant',
    class_id INT NULL COMMENT 'Classe de l’étudiant (une seule classe)',
    date_of_birth DATE NOT NULL,
    address VARCHAR(255),
    phone VARCHAR(20),
    CONSTRAINT fk_student_user FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_student_class FOREIGN KEY (class_id) REFERENCES classes(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE DE LIAISON : teacher_subject_class
-- ==============================================
CREATE TABLE teacher_subject_class (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    class_id INT NOT NULL,
    CONSTRAINT fk_tsc_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_tsc_subject FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_tsc_class FOREIGN KEY (class_id) REFERENCES classes(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (teacher_id, subject_id, class_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE : grades
-- ==============================================
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    grade_value DECIMAL(4,2) NOT NULL CHECK (grade_value >= 0 AND grade_value <= 20),
    grade_date DATE DEFAULT CURRENT_DATE,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_grade_student FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_grade_subject FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_grade_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABLE : absences
-- ==============================================
CREATE TABLE absences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NULL,
    class_id INT NULL,
    absence_date DATE NOT NULL,
    justified BOOLEAN DEFAULT FALSE,
    justification_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_absence_student FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_absence_subject FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_absence_class FOREIGN KEY (class_id) REFERENCES classes(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_absence_context CHECK (subject_id IS NOT NULL OR class_id IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- DONNÉES INITIALES
-- ==============================================

-- Rôles
INSERT INTO roles (name) VALUES 
('Administrateur'), 
('Enseignant'), 
('Étudiant');

-- Admin par défaut (mot de passe = "password")
INSERT INTO users (role_id, email, password, first_name, last_name)
VALUES (1, 'admin@example.com', '$2y$10$VhUosWw9lAcmkEXyShSnuu5HzG8oFzS8pNfSg0.y0fW/Z6l8ME0UO', 'Admin', 'User');

-- Classes
INSERT INTO classes (name) VALUES
('Terminale S'),
('Première L'),
('Seconde Générale');

-- Matières
INSERT INTO subjects (name) VALUES
('Mathématiques'),
('Français'),
('Histoire-Géographie'),
('Physique-Chimie'),
('Anglais');

-- Enseignants
INSERT INTO users (role_id, email, password, first_name, last_name) VALUES
(2, 'prof.math@example.com', '$2y$10$VhUosWw9lAcmkEXyShSnuu5HzG8oFzS8pNfSg0.y0fW/Z6l8ME0UO', 'Jean', 'Dupont'),
(2, 'prof.fr@example.com', '$2y$10$VhUosWw9lAcmkEXyShSnuu5HzG8oFzS8pNfSg0.y0fW/Z6l8ME0UO', 'Marie', 'Curie');

INSERT INTO teachers (user_id, phone, hire_date) VALUES
((SELECT id FROM users WHERE email = 'prof.math@example.com'), '0612345678', '2020-09-01'),
((SELECT id FROM users WHERE email = 'prof.fr@example.com'), '0698765432', '2018-09-01');

-- Étudiants
INSERT INTO users (role_id, email, password, first_name, last_name) VALUES
(3, 'etudiant1@example.com', '$2y$10$VhUosWw9lAcmkEXyShSnuu5HzG8oFzS8pNfSg0.y0fW/Z6l8ME0UO', 'Alice', 'Martin'),
(3, 'etudiant2@example.com', '$2y$10$VhUosWw9lAcmkEXyShSnuu5HzG8oFzS8pNfSg0.y0fW/Z6l8ME0UO', 'Bob', 'Lefevre');

INSERT INTO students (user_id, student_id_number, class_id, date_of_birth, address, phone) VALUES
((SELECT id FROM users WHERE email = 'etudiant1@example.com'), 'STU001', (SELECT id FROM classes WHERE name = 'Terminale S'), '2005-03-15', '10 Rue de la Paix, Paris', '0711223344'),
((SELECT id FROM users WHERE email = 'etudiant2@example.com'), 'STU002', (SELECT id FROM classes WHERE name = 'Première L'), '2004-11-20', '25 Avenue des Champs, Lyon', '0755667788');

-- Affectations Enseignants ↔ Matières ↔ Classes
INSERT INTO teacher_subject_class (teacher_id, subject_id, class_id) VALUES
((SELECT id FROM teachers WHERE user_id = (SELECT id FROM users WHERE email = 'prof.math@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Mathématiques'), 
 (SELECT id FROM classes WHERE name = 'Terminale S')),
((SELECT id FROM teachers WHERE user_id = (SELECT id FROM users WHERE email = 'prof.fr@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Français'), 
 (SELECT id FROM classes WHERE name = 'Première L'));

-- Notes de test
INSERT INTO grades (student_id, subject_id, teacher_id, grade_value, grade_date, comment) VALUES
((SELECT id FROM students WHERE user_id = (SELECT id FROM users WHERE email = 'etudiant1@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Mathématiques'), 
 (SELECT id FROM teachers WHERE user_id = (SELECT id FROM users WHERE email = 'prof.math@example.com')), 
 14.50, '2025-10-01', 'Bon travail'),
((SELECT id FROM students WHERE user_id = (SELECT id FROM users WHERE email = 'etudiant2@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Français'), 
 (SELECT id FROM teachers WHERE user_id = (SELECT id FROM users WHERE email = 'prof.fr@example.com')), 
 16.00, '2025-09-28', 'Très bonne analyse');

-- Absences de test
INSERT INTO absences (student_id, subject_id, absence_date, justified, justification_details) VALUES
((SELECT id FROM students WHERE user_id = (SELECT id FROM users WHERE email = 'etudiant1@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Mathématiques'), '2025-09-25', FALSE, NULL),
((SELECT id FROM students WHERE user_id = (SELECT id FROM users WHERE email = 'etudiant1@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Mathématiques'), '2025-10-10', TRUE, 'Rendez-vous médical'),
((SELECT id FROM students WHERE user_id = (SELECT id FROM users WHERE email = 'etudiant2@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Français'), '2025-10-02', TRUE, 'Certificat médical'),
((SELECT id FROM students WHERE user_id = (SELECT id FROM users WHERE email = 'etudiant2@example.com')), 
 (SELECT id FROM subjects WHERE name = 'Français'), '2025-10-15', FALSE, NULL);

-- Ajout de la colonne category à la table grades (SQL Server)
ALTER TABLE grades
ADD category VARCHAR(20) NOT NULL CONSTRAINT DF_grades_category DEFAULT ('Devoir') WITH VALUES;

-- Ajout d'une contrainte pour limiter les valeurs possibles
ALTER TABLE grades
ADD CONSTRAINT CK_grades_category CHECK (category IN ('Devoir', 'Contrôle', 'Examen', 'Oral', 'Projet', 'TP', 'Autre'));

-- Mise à jour des notes existantes avec une catégorie par défaut (au cas où)
UPDATE grades SET category = 'Devoir' WHERE category IS NULL;

-- Ajout de la colonne category à la table grades (SQL Server)
ALTER TABLE grades
ADD category VARCHAR(20) NOT NULL CONSTRAINT DF_grades_category DEFAULT ('Devoir') WITH VALUES;

-- Ajout d'une contrainte pour limiter les valeurs possibles
ALTER TABLE grades
ADD CONSTRAINT CK_grades_category CHECK (category IN ('Devoir', 'Contrôle', 'Examen', 'Oral', 'Projet', 'TP', 'Autre'));

-- Mise à jour des notes existantes avec une catégorie par défaut (au cas où)
UPDATE grades SET category = 'Devoir' WHERE category IS NULL;
