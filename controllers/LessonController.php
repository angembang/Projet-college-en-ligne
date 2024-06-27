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
            
                // Foreach lesson, retrieve the correspondant time tabe
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
     * Convert YouTube video URL to embeddable format
     * @param string $url
     * @return string
     */
    private function convertYouTubeUrlToEmbed(string $url): string
    {
        $regex = '/https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/';
        $replace = 'https://www.youtube.com/embed/$1';
        return preg_replace($regex, $replace, $url);
    }

    
    
    /*
     * Process the form submission to add a new course.
     * Handles form validation, file uploads, and database insertion.
     */
    public function checkAddCourse(): void 
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $errors = [];
                $debug = [];

                if (!isset($_POST["idLesson"]) || 
                    !isset($_POST["unlockdate"]) ||
                    !isset($_POST["subject"]) ||
                    !isset($_POST["summary"]) ||
                    !isset($_POST["content"])) {
                    $errors[] = "Veuillez remplir tous les champs obligatoires";
                }

                $idLesson = $_POST["idLesson"];
                $unlockdate = $_POST["unlockdate"];
                $subject = htmlspecialchars($_POST["subject"], ENT_QUOTES, 'UTF-8');
                $summary = htmlspecialchars($_POST["summary"], ENT_QUOTES, 'UTF-8');
                $content = htmlspecialchars($_POST["content"], ENT_QUOTES, 'UTF-8');
                $videoUrl = $_POST['video'];
                $link = $_POST["link"];
                $image = $audio = $fichierpdf = null;

                // Validate YouTube URL and convert to embed format
                if (!empty($videoUrl)) {
                    if (!preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=[\w-]+$/', $videoUrl)) {
                        $errors[] = "Le lien YouTube n'est pas valide.";
                    } else {
                        $videoUrl = $this->convertYouTubeUrlToEmbed($videoUrl);
                    }
                }
                
                // Validate URL format for link
                if (!empty($link)) {
                    if (!filter_var($link, FILTER_VALIDATE_URL)) {
                        $errors[] = "Le lien fourni n'est pas valide.";
                        $link = null;
                    }
                }

                // Validate file uploads
                $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $allowedAudioTypes = ['audio/mpeg', 'audio/wav'];
                $allowedPdfType = ['application/pdf'];

                 // Define the base upload directory
                $uploadDir = realpath(__DIR__ . '/../uploads');
                $debug[] = "Base upload directory: " . ($uploadDir !== false ? $uploadDir : "Non trouvé");

                if ($uploadDir === false) {
                    $errors[] = "Le répertoire de téléchargement n'existe pas.";
                } else {
                    $imageDir = $uploadDir . '/images/';
                    $audioDir = $uploadDir . '/audios/';
                    $pdfDir = $uploadDir . '/pdfs/';

                    // Create directories if they do not exist
                    if (!is_dir($imageDir) && !mkdir($imageDir, 0777, true) && !is_dir($imageDir)) {
                        $errors[] = "Échec de la création du répertoire des images.";
                    }
                    if (!is_dir($audioDir) && !mkdir($audioDir, 0777, true) && !is_dir($audioDir)) {
                        $errors[] = "Échec de la création du répertoire des audios.";
                    }
                    if (!is_dir($pdfDir) && !mkdir($pdfDir, 0777, true) && !is_dir($pdfDir)) {
                        $errors[] = "Échec de la création du répertoire des PDF.";
                    }

                    if (empty($errors)) {
                        if (!empty($_FILES['image']['name'])) {
                            if (!in_array($_FILES['image']['type'], $allowedImageTypes)) {
                                $errors[] = "Le format de l'image n'est pas valide. Seuls les formats JPEG, PNG et GIF sont acceptés.";
                            } else {
                                $imagePath = $imageDir . basename($_FILES['image']['name']);
                                $debug[] = "Chemin de l'image : " . $imagePath;
                                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                                    $errors[] = "Erreur lors du téléchargement de l'image.";
                                } else {
                                    $image = '/collège/Projet-college-en-ligne/uploads/images/' . basename($_FILES['image']['name']);
                                }
                            }
                        }

                        if (!empty($_FILES['audio']['name'])) {
                            if (!in_array($_FILES['audio']['type'], $allowedAudioTypes)) {
                                $errors[] = "Le format de l'audio n'est pas valide. Seuls les formats MP3 et WAV sont acceptés.";
                            } else {
                                $audioPath = $audioDir . basename($_FILES['audio']['name']);
                                $debug[] = "Chemin de l'audio : " . $audioPath;
                                if (!move_uploaded_file($_FILES['audio']['tmp_name'], $audioPath)) {
                                    $errors[] = "Erreur lors du téléchargement de l'audio.";
                                } else {
                                    $audio = '/collège/Projet-college-en-ligne/uploads/audios/' . basename($_FILES['audio']['name']);
                                }
                            }
                        }

                        if (!empty($_FILES['fichierpdf']['name'])) {
                            if (!in_array($_FILES['fichierpdf']['type'], $allowedPdfType)) {
                                $errors[] = "Le format du fichier PDF n'est pas valide. Seuls les fichiers PDF sont acceptés.";
                            } else {
                                $pdfPath = $pdfDir . basename($_FILES['fichierpdf']['name']);
                                $debug[] = "Chemin du fichier PDF : " . $pdfPath;
                                if (!move_uploaded_file($_FILES['fichierpdf']['tmp_name'], $pdfPath)) {
                                    $errors[] = "Erreur lors du téléchargement du fichier PDF.";
                                } else {
                                    $fichierpdf = '/collège/Projet-college-en-ligne/uploads/pdfs/' . basename($_FILES['fichierpdf']['name']);
                                }
                            }
                        }
                    }
                    
                }
                

                if (empty($errors)) {
                    $courseManager = new CourseManager();
                    $createdAt = date('Y-m-d H:i:s');
                    $courseModel = new Course(
                        null,
                        $idLesson,
                        $unlockdate,
                        $subject,
                        $summary,
                        $content,
                        $image,
                        $audio,
                        $videoUrl,
                        $fichierpdf,
                        $link,
                        $createdAt
                    );
                    $course = $courseManager->createCourse($courseModel);

                    if ($course) {
                        echo "Cours ajouté avec succès";
                    } else {
                        echo "Échec lors de l'ajout de cours";
                    }
                } else {
                    foreach ($errors as $error) {
                        echo $error . "<br>";
                    }
                }

                foreach ($debug as $message) {
                    echo "DEBUG: " . $message . "<br>";
                }
            } else {
                echo "Le formulaire n'a pas été soumis par la méthode POST";
            }
        } catch (Exception $e) {
            echo "Une erreur est survenue : " . $e->getMessage();
            error_log("An error occurred during the operation: " . $e->getMessage() . $e->getCode());
        }
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
                // Render courses in a view
                $this->render("lessonCourses.html.twig", [
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
    
    
    /**
     * Retrieves all lessons associated with the logged-in collegian's class and returns them as JSON.
     *
     * @return string|null A JSON string representing an array of lessons or null if none are found.
     */
    public function getAllLessonsByLoggedInCollegianClassId(): ?string
    {
        // Check if the user is logged in and is a collegian
        if (isset($_SESSION['user']) && $_SESSION['role'] === 'Collégien') {
            // Retrieve the class identifier from the session
            // Using the 'classId' key retrieved during login
            $collegianClassId = $_SESSION['classId']; 
    
            // Call the method to retrieve all lessons for this class
            $lessonManager = new LessonManager();
            $lessons = $lessonManager->findLessonByIdClass($collegianClassId);

            // Encode lessons array to JSON
            return json_encode($lessons);
        }

        // If the user is not logged in as a collegian or if the class identifier is not found, return null
        return null;
    }
    
    
    /**
     * Searches for courses by lesson name and renders the corresponding view.
     */
    public function searchCoursesByLessonName(): void
    {
        try {
            if((isset($_GET["lesson_name"])) && (isset($_GET["route"]))) {
            $lessonName = htmlspecialchars($_GET["lesson_name"]);
            $route = $_GET["route"];
            
            $lessonManager = new LessonManager();
            $lessonId = $lessonManager->searchLessonByName($lessonName);
            
            if($lessonId) {
                $courseManager = new CourseManager();
                $courses = $courseManager->findCoursesByLessonId($lessonId);
                
                if ($courses) {
                    $this->render('searchCourse.html.twig', [
                        'courses' => $courses
                    ]);
                } else {
                    $this->render("error.html.twig", []);
                }
            } else {
                $this->render("error.html.twig", []);
            }
        } else {
            $this->render("error.html.twig", []);
        }
    } catch(Exception $e) {
        echo "Une erreur s'est produite lors de l'opération: " . $e->getMessage();
    }
        
    }

}