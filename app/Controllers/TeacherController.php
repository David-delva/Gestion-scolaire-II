<?php
//
// Fichier : app/Controllers/TeacherController.php
// Description : Contrôleur pour la gestion des fonctionnalités spécifiques aux enseignants.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Controllers;

use App\Models\TeacherModel;
use App\Models\GradeModel;
use App\Models\AbsenceModel;
use App\Models\AssignmentModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\ClassModel;

class TeacherController extends BaseController
{
    private $teacherModel;
    private $gradeModel;
    private $absenceModel;
    private $assignmentModel;
    private $studentModel;
    private $subjectModel;
    private $classModel;

    public function __construct()
    {
        // Assurez-vous que l'utilisateur est connecté et a le rôle d'Enseignant
        $this->requireRole('Enseignant');
        $this->teacherModel = new TeacherModel();
        $this->gradeModel = new GradeModel();
        $this->absenceModel = new AbsenceModel();
        $this->assignmentModel = new AssignmentModel();
        $this->studentModel = new StudentModel();
        $this->subjectModel = new SubjectModel();
        $this->classModel = new ClassModel();
    }

    /**
     * Affiche le tableau de bord de l'enseignant.
     */
    public function dashboard()
    {
        $teacherId = $this->getTeacherIdFromSession();
        $teacherInfo = $this->teacherModel->getTeacherById($teacherId);
        $teacherSubjects = $this->teacherModel->getSubjectsForTeacher($teacherId);
        $teacherClasses = $this->teacherModel->getClassesForTeacher($teacherId);

        // Ajout des statistiques directement dans le tableau de bord
        $totalStudents = $this->teacherModel->getStudentsForTeacher($teacherId);
        $averageGrade = $this->gradeModel->getAverageGradeByTeacher($teacherId);
        $totalAbsences = $this->absenceModel->getTotalAbsencesByTeacher($teacherId);

        $data = [
            'title' => 'Tableau de Bord Enseignant',
            'welcome_message' => 'Bienvenue, Enseignant !',
            'teacher_info' => $teacherInfo,
            'teacher_subjects' => $teacherSubjects,
            'teacher_classes' => $teacherClasses,
            'total_students' => count($totalStudents),
            'average_grade' => $averageGrade,
            'total_absences' => $totalAbsences,
        ];

        $this->render('teacher/dashboard', $data);
    }

    /**
     * Affiche et gère la gestion des notes.
     */
    public function grades()
    {
        $teacherId = $this->getTeacherIdFromSession();
        if (!$teacherId) {
            $this->setAlert('danger', 'Impossible de récupérer les informations de l\'enseignant.');
            $this->redirect('');
        }

        $teacherAssignments = $this->teacherModel->getTeacherAssignmentsByTeacherId($teacherId);
        $grades = $this->gradeModel->getGradesByTeacherId($teacherId);

        $data = [
            'title' => 'Gestion des Notes',
            'teacherAssignments' => $teacherAssignments,
            'grades' => $grades,
            'allStudents' => $this->teacherModel->getStudentsForTeacher($teacherId), // Uniquement les étudiants de l'enseignant
            'allSubjects' => $this->teacherModel->getSubjectsForTeacher($teacherId), // Uniquement les matières de l'enseignant
        ];

        $this->render('teacher/grades', $data);
    }

    /**
     * Gère l'ajout d'une note.
     */
    public function addGrade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('teacher/grades');
            }

            $teacherId = $this->getTeacherIdFromSession();
            if (!$teacherId) {
                $this->setAlert('danger', 'Impossible de récupérer les informations de l\'enseignant.');
                $this->redirect('teacher/grades');
            }

            $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
            $subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
            $gradeValue = filter_input(INPUT_POST, 'grade_value', FILTER_VALIDATE_FLOAT);
            $gradeDate = filter_input(INPUT_POST, 'grade_date', FILTER_SANITIZE_STRING);
            $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

            if (empty($studentId) || empty($subjectId) || $gradeValue === false || empty($gradeDate)) {
                $this->setAlert('danger', 'Tous les champs obligatoires doivent être remplis correctement.');
            } elseif ($gradeValue < 0 || $gradeValue > 20) {
                $this->setAlert('danger', 'La note doit être comprise entre 0 et 20.');
            } elseif (!$this->teacherModel->isAllowedToAddGrade($teacherId, $studentId, $subjectId)) {
                $this->setAlert('danger', 'Vous n\'êtes pas autorisé à ajouter une note pour cet étudiant ou cette matière.');
            } else {
                if ($this->gradeModel->addGrade($studentId, $subjectId, $teacherId, $gradeValue, $gradeDate, $comment, $category)) {
                    $this->setAlert('success', 'Note ajoutée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de la note.');
                }
            }
        }
        $this->redirect('teacher/grades');
    }

    /**
     * Gère la modification d'une note.
     */
    public function editGrade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('teacher/grades');
            }

            $gradeId = filter_input(INPUT_POST, 'grade_id', FILTER_VALIDATE_INT);
            $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
            $subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
            $gradeValue = filter_input(INPUT_POST, 'grade_value', FILTER_VALIDATE_FLOAT);
            $gradeDate = filter_input(INPUT_POST, 'grade_date', FILTER_SANITIZE_STRING);
            $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

            if (empty($gradeId) || empty($studentId) || empty($subjectId) || $gradeValue === false || empty($gradeDate)) {
                $this->setAlert('danger', 'Tous les champs obligatoires doivent être remplis correctement.');
            } elseif ($gradeValue < 0 || $gradeValue > 20) {
                $this->setAlert('danger', 'La note doit être comprise entre 0 et 20.');
            } else {
                if ($this->gradeModel->updateGrade($gradeId, $studentId, $subjectId, $gradeValue, $gradeDate, $comment, $category)) {
                    $this->setAlert('success', 'Note mise à jour avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la mise à jour de la note.');
                }
            }
        }
        $this->redirect('teacher/grades');
    }

    /**
     * Gère la suppression d'une note.
     */
    public function deleteGrade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('teacher/grades');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID de note manquant.');
            }
            else {
                if ($this->gradeModel->deleteGrade($id)) {
                    $this->setAlert('success', 'Note supprimée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de la note.');
                }
            }
        }
        $this->redirect('teacher/grades');
    }

    /**
     * Affiche et gère la gestion des absences.
     */
    public function absences()
    {
        $teacherId = $this->getTeacherIdFromSession();
        if (!$teacherId) {
            $this->setAlert('danger', 'Impossible de récupérer les informations de l\'enseignant.');
            $this->redirect('');
        }

        $teacherAssignments = $this->teacherModel->getTeacherAssignmentsByTeacherId($teacherId);
        $absences = $this->absenceModel->getAbsencesByTeacherId($teacherId);

        $data = [
            'title' => 'Gestion des Absences',
            'teacherAssignments' => $teacherAssignments,
            'absences' => $absences,
            'allStudents' => $this->teacherModel->getStudentsForTeacher($teacherId), // Uniquement les étudiants de l'enseignant
            'allSubjects' => $this->teacherModel->getSubjectsForTeacher($teacherId), // Uniquement les matières de l'enseignant
            'allClasses' => $this->teacherModel->getClassesForTeacher($teacherId),   // Uniquement les classes de l'enseignant
        ];

        $this->render('teacher/absences', $data);
    }

    /**
     * Gère l'ajout d'une absence.
     */
    public function addAbsence()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (empty($csrfToken) || !verifyCsrfToken($csrfToken)) {
                $this->setAlert('danger', 'Jeton CSRF invalide ou manquant.');
                $this->redirect('teacher/absences');
                return;
            }

            $teacherId = $this->getTeacherIdFromSession();
            if (!$teacherId) {
                $this->setAlert('danger', 'Impossible de récupérer les informations de l\'enseignant.');
                $this->redirect('teacher/absences');
                return;
            }

            $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
            $subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
            $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT);
            $absenceDate = filter_input(INPUT_POST, 'absence_date', FILTER_SANITIZE_STRING);
            $justified = isset($_POST['justified']) ? 1 : 0;
            $justificationDetails = filter_input(INPUT_POST, 'justification_details', FILTER_SANITIZE_STRING);

            if (empty($studentId) || empty($absenceDate) || (empty($subjectId) && empty($classId))) {
                $this->setAlert('danger', 'Tous les champs obligatoires doivent être remplis correctement (étudiant, date, et matière ou classe).');
            } elseif (!$this->teacherModel->isAllowedToAddAbsence($teacherId, $studentId)) {
                $this->setAlert('danger', 'Vous n\'êtes pas autorisé à déclarer une absence pour cet étudiant.');
            } else {
                if ($this->absenceModel->addAbsence($studentId, $subjectId, $classId, $absenceDate, $justified, $justificationDetails)) {
                    $this->setAlert('success', 'Absence ajoutée avec succès.');
                } else {
                    $this->setAlert('danger', 'Erreur lors de l\'ajout de l\'absence.');
                }
            }
        }
        $this->redirect('teacher/absences');
    }

    /**
     * Gère la modification d'une absence.
     */
    public function editAbsence()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('teacher/absences');
            }

            $absenceId = filter_input(INPUT_POST, 'absence_id', FILTER_VALIDATE_INT);
            $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
            $subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
            $classId = filter_input(INPUT_POST, 'class_id', FILTER_VALIDATE_INT);
            $absenceDate = filter_input(INPUT_POST, 'absence_date', FILTER_SANITIZE_STRING);
            $justified = isset($_POST['justified']) ? 1 : 0;
            $justificationDetails = filter_input(INPUT_POST, 'justification_details', FILTER_SANITIZE_STRING);

            if (empty($absenceId) || empty($studentId) || empty($absenceDate) || (empty($subjectId) && empty($classId))) {
                $this->setAlert('danger', 'Tous les champs obligatoires doivent être remplis correctement (étudiant, date, et matière ou classe).');
            } else {
                if ($this->absenceModel->updateAbsence($absenceId, $studentId, $subjectId, $classId, $absenceDate, $justified, $justificationDetails)) {
                    $this->setAlert('success', 'Absence mise à jour avec succès.');
                }
                else {
                    $this->setAlert('danger', 'Erreur lors de la mise à jour de l\'absence.');
                }
            }
        }
        $this->redirect('teacher/absences');
    }

    /**
     * Gère la suppression d'une absence.
     */
    public function deleteAbsence()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->setAlert('danger', 'Jeton CSRF invalide.');
                $this->redirect('teacher/absences');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($id)) {
                $this->setAlert('danger', 'ID d\'absence manquant.');
            } else {
                if ($this->absenceModel->deleteAbsence($id)) {
                    $this->setAlert('success', 'Absence supprimée avec succès.');
                }
                else {
                    $this->setAlert('danger', 'Erreur lors de la suppression de l\'absence.');
                }
            }
        }
        $this->redirect('teacher/absences');
    }

    /**
     * Affiche les classes et matières affectées à l'enseignant.
     */
    public function classes()
    {
        $teacherId = $this->getTeacherIdFromSession();
        if (!$teacherId) {
            $this->setAlert('danger', 'Impossible de récupérer les informations de l\'enseignant.');
            $this->redirect('');
        }

        $teacherAssignments = $this->teacherModel->getTeacherAssignmentsByTeacherId($teacherId);

        $data = [
            'title' => 'Mes Classes et Matières',
            'teacherAssignments' => $teacherAssignments,
        ];

        $this->render('teacher/classes', $data);
    }

    /**
     * Affiche les statistiques personnelles de l'enseignant.
     */
    public function stats()
    {
        $teacherId = $this->getTeacherIdFromSession();
        if (!$teacherId) {
            $this->setAlert('danger', 'Impossible de récupérer les informations de l\'enseignant.');
            $this->redirect('');
        }

        $teacherClasses = $this->teacherModel->getClassesForTeacher($teacherId);

        $data = [
            'title' => 'Mes Statistiques',
            'totalGrades' => $this->gradeModel->getTotalGradesByTeacher($teacherId),
            'averageGrade' => $this->gradeModel->getAverageGradeByTeacher($teacherId),
            'totalAbsences' => $this->absenceModel->getTotalAbsencesByTeacher($teacherId),
            'totalJustifiedAbsences' => $this->absenceModel->getTotalJustifiedAbsencesByTeacher($teacherId),
            'totalUnjustifiedAbsences' => $this->absenceModel->getTotalUnjustifiedAbsencesByTeacher($teacherId),
            'topStudentsByGrade' => $this->gradeModel->getTopStudentsByGradeForTeacher($teacherId),
            'mostAbsentStudents' => $this->absenceModel->getMostAbsentStudentsForTeacher($teacherId),
            'teacherClasses' => $teacherClasses,
            'teacherSubjects' => $this->teacherModel->getSubjectsForTeacher($teacherId),
            'allStudents' => $this->teacherModel->getStudentsForTeacherWithDetails($teacherId),
            'myGrades' => $this->gradeModel->getGradesByTeacherId($teacherId),
            'myAbsences' => $this->absenceModel->getAbsencesByTeacherId($teacherId),
        ];

        $this->render('teacher/stats', $data);
    }

    /**
     * Récupère l'ID de l'enseignant à partir de l'ID utilisateur en session.
     *
     * @return int|false L'ID de l'enseignant ou false si non trouvé.
     */
    private function getTeacherIdFromSession()
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        $teacher = $this->teacherModel->getTeacherByUserId($_SESSION['user_id']);
        return $teacher ? $teacher['id'] : false;
    }
}