<?php

/**
 * Controller class for handling lesson-related actions.
 */
class LessonController extends AbstractController
{
    /**
     * Retrieves and renders the lessons for the current day.
     *
     * Retrieves the lessons for the current day by fetching the weekday,
     * converting it to lowercase to match the database format, then
     * instantiates the LessonManager class to retrieve the lessons for
     * the specified day. Finally, renders the lessons in a view using
     * the render method.
     *
     * @return void
     */
    public function lessonListOfTheDay()
    {
        // Retrieves the full name of the current weekday
        $weekday = date('l');
        
        // Converts the full weekday name to lowercase to match database format
        $weekDay = strtolower($weekday);
        
        // Instantiates the LessonManager class
        $lessonManager = new LessonManager();
        
        // Calls the findLessonsByWeekDay method of LessonManager to retrieve lessons
        $lessonList = $lessonManager->findLessonsByWeekDay($weekDay);
        
        // Renders the lessons in a view using the render method
        $this->render("lessonList.html.twig", [
            "lessons" => $lessonList
        ]);
    }
}
