<?php

/**
 * Controller class for handling lesson related actions.
 */
class LessonController extends AbstractController
{   
    /**
     * Renders the lesson form for adding a new lesson.
     *
     * This method is responsible for rendering the lesson form view,
     * allowing super-admin to input details for adding a new lesson.
     *
     * @return void
     */
    public function addLesson(): void
    {
        // Retrieve all teachers from the database
        $teacherManager = new TeacherManager();
        $teachers = $teacherManager->findAll();
        
         // Retrieve all classes from the database
        $classeManager = new ClasseManager();
        $classes = $classeManager->findAll();
        
        // Retrieve all timetables from the database
        $timeTable = new TimeTableManager();
        $timesTables = $timeTable->findAll();
        
        // Render the lesson form view with necessary data passed as parameters
        $this->render("lessonForm.html.twig", [
            "teachers" => $teachers,
            "classes" => $classes,
            "timesTables" => $timesTables
            ]);
    }
    
    
    /**
     * Adds a new lesson to the database.
     *
     * @param Lesson|null $lesson The lesson object to be added.
     * @return Lesson|null The added lesson object if successful, otherwise null.
     */
    public function checkAddLesson(Lesson $lesson): ?Lesson
    {
        // Check if the lesson passed as parameter is valid
        if($lesson !== null && 
        $lesson->getName() !== null && 
        $lesson->getIdClass() !== null && 
        $lesson->getIdTeacher() !== null && 
        $lesson->getIdTimeTable() !== null) 
        {
            // Instantiate the LessonManager to add the lesson to the database
            $lessonManager = new LessonManager();
    
            // Add the lesson to the database using the createLesson method of the lesson manager
            $lessonManager->createLesson($lesson);
        
            return $lesson;
        } else {
            // If the lesson passed as parameter is not valid, return null or handle the error as needed
            return null;
        }
    }
    
    
    /**
     * Retrieves and renders the lessons for the current day.
     *
     * Retrieves the lessons for the current day by fetching the weekday,
     * converting it to lowercase to match the database format, then
     * instantiates the ClasseManager class to retrieve all classes.
     * For each class found, it instantiates the LessonManager class
     * to retrieve the lessons for the specified day and class level.
     * Finally, renders the lessons in a view using the render method.
     *
     * @return void
     */
    public function lessonsListOfTheDay(): ?array
    {
        // Retrieve the actual day of the week
        $weekDay = /*$this->getCurrentWeekDay();*/ "Lundi";

        // Instantiate the ClasseManager to retrieve all classes
        $classeManager = new ClasseManager();
    
        // Retrieve all classes
        $classes = $classeManager->findAll();
    
        // Check if classes were found
        if ($classes !== null) {
        
            $lessonsByClass = [];
        
            foreach ($classes as $classe) {
                // Get class level for each class
                $classLevel = /*$classe->getLevel()*/ "5ème";
            
                // Instantiate the LessonManager class to retrieve lessons for the current day by class level
                $lessonManager = new LessonManager();
            
                // Retrieve lessons for the current day by class level
                $lessonList = $lessonManager->findLessonsByClassLevelAndWeekDay($classLevel, $weekDay);
            
                // Pour chaque leçon, récupérer l'horaire correspondant
                foreach($lessonList as $lesson) {
                    $lessonIdTimeTable = $lesson->getIdTimeTable();
                    $timeTableManager = new TimeTableManager();
                    $timeTable = $timeTableManager->findTimeTableById($lessonIdTimeTable);
                     
                    if ($timeTable) {
                        $startTime = strtotime($timeTable->getStartTime());
                        $currentTime = time();
                        $remainsSecondTime = $startTime - $currentTime; // Difference in seconds
                        $hours = floor($remainsSecondTime / 3600); // Calculate remaining hours
                        $minutes = floor(($remainsSecondTime % 3600) / 60); // Calculate remaining minutes
                        $seconds = $remainsSecondTime % 60; // Calculate remaining seconds

                        // Format the remaining time
                        $remainsTimeFormatted = sprintf('%dh %02dm %02ds', $hours, $minutes, $seconds);

                        $remainsTimeByLesson[$lesson->getId()] = $remainsTimeFormatted;
                        
                        
                    }
                     
                }
                // Store lessons in an array indexed by class level
                $lessonsByClass[$classLevel] = $lessonList;
            }
            
            
        
            // Renders the lessons in a view using the render method
            $this->render("lesson.html.twig", [
                "lessonsByClass" => $lessonsByClass,
                "remainsTimeByLesson" => $remainsTimeByLesson
                ]);
                // Return the lessons array
                return $lessonsByClass;

            } else {
            // Return null if no classes were found
            return null;
        }
    
        
    }
    
    
    
    /**
     * Get the current weekday in lowercase.
     *
     * @return string The lowercase representation of the current weekday in French.
     */
    private function getCurrentWeekDay(): string
    {
        // French names of the week days 
        $frenchDaysOfWeek = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche',
        ];
    
    // Get the numeric representation of the current day (1 for Monday, 2 for Tuesday, etc.)
    $numericDayOfWeek = date('N');
    
    // Retrieve the French name of the current day using the numeric representation
    $frenchWeekDay = $frenchDaysOfWeek[$numericDayOfWeek];
    
    // Make the first letter uppercase
    $frenchWeekDay = ucfirst($frenchWeekDay);
    
    return $frenchWeekDay;

        
    }
    
    
    public function addCourse(): void
    {
    
        $lessonManager = new LessonManager();
        $lessons = $lessonManager->findAll();
    
        // Rendre la vue du formulaire de cours avec les leçons filtrées
        $this->render("courseForm.html.twig", [
            "lessons" => $lessons
            ]);
    }
    
    
    
    
    /**
     * Retrieves and renders the courses associated with a specific lesson.
     *
     * This method retrieves the courses associated with a specific lesson by its ID.
     * It then renders the courses in a view if found.
     *
     * @param int $lessonId The ID of the lesson to retrieve courses for.
     * @return void
     */    
    public function showCoursesByIdLesson(int $lessonId): void
    {
        // Instantiate the LessonManager to retrieve the lesson by its unique identifier
        $lessonManager = new LessonManager();
        $currentLesson = $lessonManager->findLessonById($lessonId);

        // Check if the lesson is found
        if ($currentLesson) {
            // Retrieve the ID and name of the lesson
            $idLesson = $currentLesson->getId();
            $lessonName = $currentLesson->getName(); 
        
            // Instantiate the CourseManager to retrieve courses by lesson unique identifier
            $courseManager = new CourseManager();
            $courses = $courseManager->findCoursesByLessonId($idLesson);
        
            // Check if courses were found
            if ($courses) {
                // Render the courses in a view
                $this->render("cours.html.twig", [
                    "courses" => $courses,
                    "lessonName" => $lessonName 
                ]);
            } else {
                //No courses were found for the lesson
                echo "error";
            }
        } else {
            // Handle case where the lesson was not found
            echo "error";
        }
    }
    

}