<?php
//
// Fichier : app/Controllers/AdminController.php
// Description : Contrôleur pour la gestion des fonctionnalités d'administration.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Controllers;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\TeacherModel;
use App\Models\StudentModel;
use App\Models\AssignmentModel;
use App\Models\StatsModel;

class AdminController extends BaseController
{
    private $userModel;
    private $classModel;
    private $subjectModel;
    private $teacherModel;
    private $studentModel;
    private $assignmentModel;
    private $statsModel;

    public function __construct()
    {
        // Assurez-vous que l'utilisateur est connecté et a le rôle d'Administrateur
        $this->requireRole('Administrateur');
        $this->userModel = new User();
        $this->classModel = new ClassModel();
        $this->subjectModel = new SubjectModel();
        $this->teacherModel = new TeacherModel();
        $this->studentModel = new StudentModel();
        $this->assignmentModel = new AssignmentModel();
        $this->statsModel = new StatsModel();
    }

    /**
     * Affiche le tableau de bord de l'administrateur.
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Tableau de Bord Administrateur',
            'welcome_message' => 'Bienvenue, Administrateur !'
        ];

        $this->render('admin/dashboard', $data);
    }

    // --- Méthodes CRUD pour les utilisateurs ---
    public function users()
    {
        $users = $this->userModel->getAllUsers();
        $roles = $this->userModel->getAllRoles();

        $data = [
            'title' => 'Gestion des Utilisateurs',
            'users' => $users,
            'roles' => $roles,
        ];
        $this->render('admin/users', $data);
    }

    /**
     * Gère l'ajout d'un nouvel utilisateur.
     */
    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/users');
            }

            $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
            $roleId = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);

            if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($roleId)) {
                $this->setAlert('danger', 'Tous les champs sont obligatoires.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->setAlert('danger', 'Adresse email invalide.');
            } elseif (strlen($password) < 8) {
                $this->setAlert('danger', 'Le mot de passe doit contenir au moins 8 caractères.');
            } elseif ($this->userModel->getUserByEmail($email)) {
                $this->setAlert('danger', 'Cette adresse email est déjà utilisée.');
            } else {
                $userId = $this->userModel->createUser($email, $password, $firstName, $lastName, $roleId);
                if ($userId) {
                    // Si c'est un enseignant (role_id = 2), créer l'entrée dans teachers
                    if ($roleId == 2) {
                        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
                        $hireDate = filter_input(INPUT_POST, 'hire_date', FILTER_SANITIZE_STRING) ?: date('Y-m-d');
                        $this->teacherModel->createTeacherFromUser($userId, $phone, $hireDate);
                    }
                    // Si c'est un étudiant (role_id = 3), créer l'entrée dans students
                    elseif ($roleId == 3) {
                        $studentIdNumber = filter_input(INPUT_POST, 'student_id_number', FILTER_SANITIZE_STRING) ?: 'STU' . str_pad($userId, 6, '0', STR_PAD_LEFT);
                        $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT) ?: null;
                        $dateOfBirth = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_STRING) ?: '2000-01-01';
                        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
                        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
                        $this->studentModel->createStudentFromUser($userId, $studentIdNumber, $classId, $dateOfBirth, $address, $phone);
                    }
                    $this->setAlert('success', 'Utilisateur ajouté avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de l\'utilisateur.');
                }
            }
        }
        $this->redirect('admin/users');
    }

    /**
     * Gère la modification d'un utilisateur existant.
     */
    public function editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/users');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $roleId = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);
            $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

            if (empty($id) || empty($firstName) || empty($lastName) || empty($email) || empty($roleId)) {
                $this->setAlert('danger', 'Tous les champs obligatoires sont requis.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->setAlert('danger', 'Adresse email invalide.');
            } else {
                $existingUser = $this->userModel->getUserByEmail($email);
                if ($existingUser && $existingUser['id'] !== $id) {
                    $this->setAlert('danger', 'Cette adresse email est déjà utilisée par un autre utilisateur.');
                    $this->redirect('admin/users');
                }

                $success = $this->userModel->updateUser($id, $email, $firstName, $lastName, $roleId);

                if (!empty($password)) {
                    if (strlen($password) < 8) {
                        $this->setAlert('danger', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
                        $this->redirect('admin/users');
                    }
                    $success = $success && $this->userModel->updatePassword($id, $password);
                }

                if ($success) {
                    $this->setAlert('success', 'Utilisateur mis à jour avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la mise à jour de l\'utilisateur.');
                }
            }
        }
        $this->redirect('admin/users');
    }

    /**
     * Gère la suppression d'un utilisateur.
     */
    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/users');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID utilisateur manquant.');
            } else {
                // Empêcher un admin de se supprimer lui-même
                if ($id == $_SESSION['user_id']) {
                    $this->setAlert('danger', 'Vous ne pouvez pas supprimer votre propre compte.');
                    $this->redirect('admin/users');
                }

                if ($this->userModel->deleteUser($id)) {
                    $this->setAlert('success', 'Utilisateur supprimé avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de l\'utilisateur.');
                }
            }
        }
        $this->redirect('admin/users');
    }

    /**
     * Active ou désactive un utilisateur.
     */
    public function toggleUserStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/users');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $isActive = filter_input(INPUT_POST, 'is_active', FILTER_VALIDATE_INT);

            if ($id === false || $isActive === false || $isActive === null) {
                $this->setAlert('danger', 'Données manquantes.');
            } elseif ($id == $_SESSION['user_id']) {
                $this->setAlert('danger', 'Vous ne pouvez pas désactiver votre propre compte.');
            } else {
                if ($this->userModel->toggleUserStatus($id, $isActive == 1)) {
                    $this->setAlert('success', $isActive == 1 ? 'Utilisateur activé.' : 'Utilisateur désactivé.');
                } else {
                    $this->setAlert('danger', 'Erreur lors du changement de statut.');
                }
            }
        }
        $this->redirect('admin/users');
    }

    // --- Méthodes CRUD pour les classes ---
    public function classes()
    {
        $classes = $this->classModel->getAllClasses();

        $data = [
            'title' => 'Gestion des Classes',
            'classes' => $classes,
        ];
        $this->render('admin/classes', $data);
    }

    /**
     * Gère l'ajout d'une nouvelle classe.
     */
    public function addClass()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

            if (empty($name)) {
                $this->setAlert('danger', 'Le nom de la classe est obligatoire.');
            } elseif ($this->classModel->getClassByName($name)) {
                $this->setAlert('danger', 'Une classe avec ce nom existe déjà.');
            } else {
                if ($this->classModel->addClass($name)) {
                    $this->setAlert('success', 'Classe ajoutée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de la classe.');
                }
            }
        }
        $this->redirect('admin/classes');
    }

    /**
     * Gère la modification d'une classe existante.
     */
    public function editClass()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

            if (empty($id) || empty($name)) {
                $this->setAlert('danger', 'Tous les champs sont obligatoires.');
            } elseif ($this->classModel->getClassByName($name, $id)) {
                $this->setAlert('danger', 'Une autre classe avec ce nom existe déjà.');
            } else {
                if ($this->classModel->updateClass($id, $name)) {
                    $this->setAlert('success', 'Classe mise à jour avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la mise à jour de la classe.');
                }
            }
        }
        $this->redirect('admin/classes');
    }

    /**
     * Gère la suppression d'une classe.
     */
    public function deleteClass()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/classes');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID de classe manquant.');
            } else {
                // TODO: Ajouter une vérification si la classe est utilisée par des étudiants ou des affectations
                if ($this->classModel->deleteClass($id)) {
                    $this->setAlert('success', 'Classe supprimée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de la classe. Elle est peut-être liée à des étudiants ou des affectations.');
                }
            }
        }
        $this->redirect('admin/classes');
    }

    // --- Méthodes CRUD pour les matières ---
    public function subjects()
    {
        $subjects = $this->subjectModel->getAllSubjects();

        $data = [
            'title' => 'Gestion des Matières',
            'subjects' => $subjects,
        ];
        $this->render('admin/subjects', $data);
    }

    /**
     * Gère l'ajout d'une nouvelle matière.
     */
    public function addSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/subjects');
            }

            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $coefficient = filter_input(INPUT_POST, 'coefficient', FILTER_VALIDATE_FLOAT) ?: 1.00;

            if (empty($name)) {
                $this->setAlert('danger', 'Le nom de la matière est obligatoire.');
            } elseif ($this->subjectModel->getSubjectByName($name)) {
                $this->setAlert('danger', 'Une matière avec ce nom existe déjà.');
            } else {
                if ($this->subjectModel->addSubject($name, $coefficient)) {
                    $this->setAlert('success', 'Matière ajoutée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de la matière.');
                }
            }
        }
        $this->redirect('admin/subjects');
    }

    /**
     * Gère la modification d'une matière existante.
     */
    public function editSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/subjects');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $coefficient = filter_input(INPUT_POST, 'coefficient', FILTER_VALIDATE_FLOAT) ?: 1.00;

            if (empty($id) || empty($name)) {
                $this->setAlert('danger', 'Tous les champs sont obligatoires.');
            } elseif ($this->subjectModel->getSubjectByName($name, $id)) {
                $this->setAlert('danger', 'Une autre matière avec ce nom existe déjà.');
            }
            else {
                if ($this->subjectModel->updateSubject($id, $name, $coefficient)) {
                    $this->setAlert('success', 'Matière mise à jour avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la mise à jour de la matière.');
                }
            }
        }
        $this->redirect('admin/subjects');
    }

    /**
     * Gère la suppression d'une matière.
     */
    public function deleteSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/subjects');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID de matière manquant.');
            } else {
                // TODO: Ajouter une vérification si la matière est utilisée dans des notes, absences ou affectations
                if ($this->subjectModel->deleteSubject($id)) {
                    $this->setAlert('success', 'Matière supprimée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de la matière. Elle est peut-être liée à d\'autres enregistrements.');
                }
            }
        }
        $this->redirect('admin/subjects');
    }

    // --- Méthodes CRUD pour les enseignants ---
    public function teachers()
    {
        $teachers = $this->teacherModel->getAllTeachers();

        $data = [
            'title' => 'Gestion des Enseignants',
            'teachers' => $teachers,
        ];
        $this->render('admin/teachers', $data);
    }

    /**
     * Gère l'ajout d'un nouvel enseignant.
     */
    public function addTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/teachers');
            }

            $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
            $hireDate = filter_input(INPUT_POST, 'hire_date', FILTER_SANITIZE_STRING);

            if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($hireDate)) {
                $this->setAlert('danger', 'Tous les champs obligatoires sont requis.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->setAlert('danger', 'Adresse email invalide.');
            } elseif (strlen($password) < 8) {
                $this->setAlert('danger', 'Le mot de passe doit contenir au moins 8 caractères.');
            } elseif ($this->userModel->getUserByEmail($email)) {
                $this->setAlert('danger', 'Cette adresse email est déjà utilisée.');
            } else {
                if ($this->teacherModel->addTeacher($email, $password, $firstName, $lastName, $phone, $hireDate)) {
                    $this->setAlert('success', 'Enseignant ajouté avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de l\'enseignant.');
                }
            }
        }
        $this->redirect('admin/teachers');
    }

    /**
     * Gère la modification d'un enseignant existant.
     */
    public function editTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/teachers');
            }

            $teacherId = filter_input(INPUT_POST, 'teacher_id', FILTER_VALIDATE_INT);
            $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
            $hireDate = filter_input(INPUT_POST, 'hire_date', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

            if (empty($teacherId) || empty($userId) || empty($firstName) || empty($lastName) || empty($email) || empty($hireDate)) {
                $this->setAlert('danger', 'Tous les champs obligatoires sont requis.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->setAlert('danger', 'Adresse email invalide.');
            } else {
                $existingUser = $this->userModel->getUserByEmail($email);
                if ($existingUser && $existingUser['id'] !== $userId) {
                    $this->setAlert('danger', 'Cette adresse email est déjà utilisée par un autre utilisateur.');
                    $this->redirect('admin/teachers');
                }

                if ($this->teacherModel->updateTeacher($teacherId, $userId, $email, $firstName, $lastName, $phone, $hireDate, $password)) {
                    $this->setAlert('success', 'Enseignant mis à jour avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la mise à jour de l\'enseignant.');
                }
            }
        }
        $this->redirect('admin/teachers');
    }

    /**
     * Gère la suppression d'un enseignant.
     */
    public function deleteTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/teachers');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID enseignant manquant.');
            } else {
                if ($this->teacherModel->deleteTeacher($id)) {
                    $this->setAlert('success', 'Enseignant supprimé avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de l\'enseignant. Il est peut-être lié à des affectations, notes ou absences.');
                }
            }
        }
        $this->redirect('admin/teachers');
    }

    // --- Méthodes CRUD pour les étudiants ---
    public function students()
    {
        $students = $this->studentModel->getAllStudents();
        $classes = $this->classModel->getAllClasses();

        $data = [
            'title' => 'Gestion des Étudiants',
            'students' => $students,
            'classes' => $classes,
        ];
        $this->render('admin/students', $data);
    }

    /**
     * Gère l'ajout d'un nouvel étudiant.
     */
    public function addStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/students');
            }

            $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
            $studentIdNumber = filter_input(INPUT_POST, 'student_id_number', FILTER_SANITIZE_STRING);
            $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT) ?: null;
            $dateOfBirth = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_STRING);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

            if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($studentIdNumber) || empty($dateOfBirth)) {
                $this->setAlert('danger', 'Tous les champs obligatoires sont requis.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->setAlert('danger', 'Adresse email invalide.');
            } elseif (strlen($password) < 8) {
                $this->setAlert('danger', 'Le mot de passe doit contenir au moins 8 caractères.');
            } elseif ($this->userModel->getUserByEmail($email)) {
                $this->setAlert('danger', 'Cette adresse email est déjà utilisée.');
            } elseif ($this->studentModel->getStudentByStudentIdNumber($studentIdNumber)) {
                $this->setAlert('danger', 'Ce numéro d\'étudiant est déjà utilisé.');
            } else {
                if ($this->studentModel->addStudent($email, $password, $firstName, $lastName, $studentIdNumber, $classId, $dateOfBirth, $address, $phone)) {
                    $this->setAlert('success', 'Étudiant ajouté avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de l\'étudiant.');
                }
            }
        }
        $this->redirect('admin/students');
    }

    /**
     * Gère la modification d'un étudiant existant.
     */
    public function editStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/students');
            }

            $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
            $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $studentIdNumber = filter_input(INPUT_POST, 'student_id_number', FILTER_SANITIZE_STRING);
            $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT) ?: null;
            $dateOfBirth = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_STRING);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

            if (empty($studentId) || empty($userId) || empty($firstName) || empty($lastName) || empty($email) || empty($studentIdNumber) || empty($dateOfBirth)) {
                $this->setAlert('danger', 'Tous les champs obligatoires sont requis.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->setAlert('danger', 'Adresse email invalide.');
            } else {
                $existingUser = $this->userModel->getUserByEmail($email);
                if ($existingUser && $existingUser['id'] !== $userId) {
                    $this->setAlert('danger', 'Cette adresse email est déjà utilisée par un autre utilisateur.');
                    $this->redirect('admin/students');
                }
                $existingStudentIdNumber = $this->studentModel->getStudentByStudentIdNumber($studentIdNumber, $studentId);
                if ($existingStudentIdNumber) {
                    $this->setAlert('danger', 'Ce numéro d\'étudiant est déjà utilisé par un autre étudiant.');
                    $this->redirect('admin/students');
                }

                if ($this->studentModel->updateStudent($studentId, $userId, $email, $firstName, $lastName, $studentIdNumber, $classId, $dateOfBirth, $address, $phone, $password)) {
                    $this->setAlert('success', 'Étudiant mis à jour avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la mise à jour de l\'étudiant.');
                }
            }
        }
        $this->redirect('admin/students');
    }

    /**
     * Gère la suppression d'un étudiant.
     */
    public function deleteStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/students');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID étudiant manquant.');
            } else {
                if ($this->studentModel->deleteStudent($id)) {
                    $this->setAlert('success', 'Étudiant supprimé avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de l\'étudiant. Il est peut-être lié à des notes, absences ou affectations.');
                }
            }
        }
        $this->redirect('admin/students');
    }

    // --- Méthodes CRUD pour les affectations ---
    public function assignments()
    {
        $teacherAssignments = $this->assignmentModel->getAllTeacherAssignments();

        $allTeachers = $this->teacherModel->getAllTeachers();
        $allSubjects = $this->subjectModel->getAllSubjects();
        $allClasses = $this->classModel->getAllClasses();

        $data = [
            'title' => 'Gestion des Affectations',
            'teacherAssignments' => $teacherAssignments,
            'allTeachers' => $allTeachers,
            'allSubjects' => $allSubjects,
            'allClasses' => $allClasses,
        ];
        $this->render('admin/assignments', $data);
    }

    /**
     * Gère l'ajout d'une affectation enseignant-matière-classe.
     */
    public function addTeacherAssignment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/assignments');
            }

            $teacherId = filter_input(INPUT_POST, 'teacher_id', FILTER_VALIDATE_INT);
            $subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
            $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT);

            if (empty($teacherId) || empty($subjectId) || empty($classId)) {
                $this->setAlert('danger', 'Tous les champs sont obligatoires.');
            } elseif ($this->assignmentModel->getTeacherAssignment($teacherId, $subjectId, $classId)) {
                $this->setAlert('danger', 'Cette affectation existe déjà.');
            } else {
                if ($this->assignmentModel->addTeacherAssignment($teacherId, $subjectId, $classId)) {
                    $this->setAlert('success', 'Affectation enseignant-matière-classe ajoutée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de l\'affectation.');
                }
            }
        }
        $this->redirect('admin/assignments');
    }

    /**
     * Gère la suppression d'une affectation enseignant-matière-classe.
     */
    public function deleteTeacherAssignment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('admin/assignments');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID d\'affectation manquant.');
            }
            else {
                if ($this->assignmentModel->deleteTeacherAssignment($id)) {
                    $this->setAlert('success', 'Affectation enseignant-matière-classe supprimée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de l\'affectation.');
                }
            }
        }
        $this->redirect('admin/assignments');
    }



    // --- Statistiques globales ---
    public function stats()
    {
        $data = [
            'title' => 'Statistiques Globales',
            'totalUsers' => $this->statsModel->getTotalUsers(),
            'totalStudents' => $this->statsModel->getTotalStudents(),
            'totalTeachers' => $this->statsModel->getTotalTeachers(),
            'totalClasses' => $this->statsModel->getTotalClasses(),
            'totalSubjects' => $this->statsModel->getTotalSubjects(),
            'overallAverageGrade' => $this->statsModel->getOverallAverageGrade(),
            'totalAbsences' => $this->statsModel->getTotalAbsences(),
            'totalUnjustifiedAbsences' => $this->statsModel->getTotalUnjustifiedAbsences(),
            'allClasses' => $this->classModel->getAllClasses(),
            'allSubjects' => $this->subjectModel->getAllSubjects(),
            'teacherAssignments' => $this->statsModel->getAllTeacherAssignments(),
            'allGrades' => $this->statsModel->getAllGrades(),
            'allAbsences' => $this->statsModel->getAllAbsences(),
        ];
        $this->render('admin/stats', $data);
    }
}