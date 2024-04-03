<?php

/**
 * Class ShowController
 *
 * Controller class for displaying teachers.
 */
class ShowController extends AbstractController
{
    /**
     * Displays all teachers.
     *
     * Retrieves all teachers and teachers referents from the database,
     * then renders the teacher view with the retrieved data.
     *
     * @return void
     */
    public function showAllTeachers(): void
    {
        $teacherManager = new TeacherManager();
        $teacherReferentManager = new TeacherReferentManager();
        
        $teachers = $teacherManager->findAll();
        $teachersReferents = $teacherReferentManager->findAll();
        
        $this->render("teacher.html.twig", [
            "teachers" => $teachers,
            "teachersReferents" => $teachersReferents
            ]);
    }
}