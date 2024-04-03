<?php

class ShowController extends AbstractController
{
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