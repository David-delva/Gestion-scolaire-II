<?php
//
// Fichier : app/Controllers/StudentController.php
// Description : Contrôleur pour la gestion des fonctionnalités spécifiques aux étudiants.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\GradeModel;
use App\Models\AbsenceModel;
use App\Models\AssignmentModel;

class StudentController extends BaseController
{
    private $studentModel;
    private $gradeModel;
    private $absenceModel;
    private $assignmentModel;

    public function __construct()
    {
        // Assurez-vous que l'utilisateur est connecté et a le rôle d'Étudiant
        $this->requireRole('Étudiant');
        $this->studentModel = new StudentModel();
        $this->gradeModel = new GradeModel();
        $this->absenceModel = new AbsenceModel();
        $this->assignmentModel = new AssignmentModel();
    }

    /**
     * Affiche le tableau de bord de l'étudiant.
     */
    public function dashboard()
    {
        $studentId = $this->getStudentIdFromSession();
        $studentInfo = $this->studentModel->getStudentById($studentId);
        $studentClasses = $this->assignmentModel->getStudentClassesByStudentId($studentId);

        $data = [
            'title' => 'Tableau de Bord Étudiant',
            'welcome_message' => 'Bienvenue, Étudiant !',
            'student_info' => $studentInfo,
            'student_classes' => $studentClasses
        ];

        $this->render('student/dashboard', $data);
    }

    /**
     * Affiche les notes de l'étudiant connecté.
     */
    public function grades()
    {
        $studentId = $this->getStudentIdFromSession();
        if (!$studentId) {
            $this->setAlert('danger', 'Impossible de récupérer les informations de l\'étudiant.');
            $this->redirect('');
        }

        $grades = $this->gradeModel->getGradesByStudentId($studentId);

        $data = [
            'title' => 'Mes Notes',
            'grades' => $grades,
        ];

        $this->render('student/grades', $data);
    }

    /**
     * Affiche les absences de l'étudiant connecté.
     */
    public function absences()
    {
        $studentId = $this->getStudentIdFromSession();
        if (!$studentId) {
            $this->setAlert('danger', 'Impossible de récupérer les informations de l\'étudiant.');
            $this->redirect('');
        }

        $absences = $this->absenceModel->getAbsencesByStudentId($studentId);
        $totalAbsences = $this->absenceModel->getTotalAbsencesByStudentId($studentId);
        $totalJustifiedAbsences = $this->absenceModel->getTotalJustifiedAbsencesByStudentId($studentId);
        $totalUnjustifiedAbsences = $this->absenceModel->getTotalUnjustifiedAbsencesByStudentId($studentId);

        $data = [
            'title' => 'Mes Absences',
            'absences' => $absences,
            'totalAbsences' => $totalAbsences,
            'totalJustifiedAbsences' => $totalJustifiedAbsences,
            'totalUnjustifiedAbsences' => $totalUnjustifiedAbsences,
        ];

        $this->render('student/absences', $data);
    }

    /**
     * Affiche les matières et enseignants de l'étudiant connecté.
     */
    public function schedule()
    {
        $studentId = $this->getStudentIdFromSession();
        if (!$studentId) {
            $this->setAlert('danger', 'Impossible de récupérer les informations de l\'étudiant.');
            $this->redirect('');
        }

        $studentClasses = $this->assignmentModel->getStudentClassesByStudentId($studentId);
        $studentSubjectsTeachers = $this->assignmentModel->getStudentSubjectsAndTeachers($studentId);

        $data = [
            'title' => 'Info sur ma classe',
            'studentClasses' => $studentClasses,
            'studentSubjectsTeachers' => $studentSubjectsTeachers,
        ];

        $this->render('student/schedule', $data);
    }

    /**
     * Récupère l'ID de l'étudiant à partir de l'ID utilisateur en session.
     *
     * @return int|false L'ID de l'étudiant ou false si non trouvé.
     */
    private function getStudentIdFromSession()
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        $student = $this->studentModel->getStudentByUserId($_SESSION['user_id']);
        return $student ? $student['id'] : false;
    }
}
