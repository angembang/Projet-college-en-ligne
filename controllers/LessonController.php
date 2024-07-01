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
        // Instantiate teacher and teacher referent managers
        $teacherManager = new TeacherManager();
        $teacherReferentManager = new TeacherReferentManager();
        
        // Retrieve all teachers and teachers referent from the database
        $teachers = $teacherManager->findAll();
        $teachersreferent = $teacherReferentManager->findAll();
        
        // Render the lesson form view with necessary data passed as parameters
        $this->render("lessonForm.html.twig", [
            "teachers" => $teachers,
            "teachersReferent" => $teachersreferent
        ]);
    }
    
    
    /**
     * Adds a new lesson to the database.
     *
     * @param Lesson|null $lesson The lesson object to be added.
     * @return void
     */
    public function checkAddLesson(): void
    {
        try {
            // Check if the form is submitted via POST method
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Check if all required fields are present and not empty
                if((isset($_POST["name"])) && 
                (isset($_POST["classLevel"])) && 
                (isset($_POST["idTeacher"])) && 
                (isset($_POST["dayOfWeek"])) && 
                (isset($_POST["startTime"])) && 
                (isset($_POST["endTime"]))) {
                    // Retrieve and sanitize input data
                    $lessonName = htmlspecialchars($_POST["name"]);
                    $classLevel = htmlspecialchars($_POST["classLevel"]);
                    $idTeacher = htmlspecialchars($_POST["idTeacher"]);
                    $dayOfWeek = htmlspecialchars($_POST["dayOfWeek"]);
                    $startTime = htmlspecialchars($_POST["startTime"]);
                    $endTime = htmlspecialchars($_POST["endTime"]);
                    
                    // Instanciate necessary managers for handling database operations
                    $classeManager = new ClasseManager();
                    $timeTableManager = new TimeTableManager();
                    $lessonManager = new LessonManager();
                    
                    // Check and create class if necessary
                    $class = $classeManager->findClasseByLevel($classLevel);
                    if (!$class) {
                        $classData = new Classe(null, $classLevel);
                        $class = $classeManager->createClasse($classData);
                        if (!$class) {
                            $this->renderJson(["success" => false, "message" => "Échec lors de l'ajout de la classe"]);
                            return;
                        }
                    }
                    $idClass = $class->getId();

                    // Check and create timetable if necessary
                    $timeTable = $timeTableManager->findTimeTableByWeekDayStartTimeAndEndTime($dayOfWeek, $startTime, $endTime);
                    if (!$timeTable) {
                        $timeTableData = new TimeTable(null, $dayOfWeek, $startTime, $endTime);
                        $timeTable = $timeTableManager->createTimeTable($timeTableData);
                        if (!$timeTable) {
                            $this->renderJson(["success" => false, "message" => "Échec lors de l'ajout de l'horaire"]);
                            return;
                        }
                    }
                    $idTimeTable = $timeTable->getId();

                    // Create the lesson using identifiers of class, teacher, and timetable
                    $lessonData = new Lesson(null, $lessonName, $idClass, $idTeacher, $idTimeTable);
                    $lesson = $lessonManager->createLesson($lessonData);
                    if (!$lesson) {
                        $this->renderJson(["success" => false, "message" => "Échec lors de l'ajout du cours"]);
                        return;
                    }

                    // If all operations were successful
                    $this->renderJson(["success" => true, "message" => "Cours ajouté avec succès"]);
                } else {
                    // If not all fields are filled
                    $this->renderJson(["success" => false, "message" => "Veuillez renseigner tous les champs obligatoires"]);
                }
            } else {
                // If not submitted via POST
                $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la methode POST"]);
            }
        } catch (Exception $e) {
            // Catch any thrown exceptions and display the error message
            $this->renderJson(["success" => false, "message" => "An error occurred: " . $e->getMessage()]);
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

        // Retrieve the class ID of the logged-in collegian
        $loggedInCollegianId = $_SESSION["user"]; 
        $collegianManager = new CollegianManager();
        $collegian = $collegianManager->findCollegianById($loggedInCollegianId);
        $classId = $collegian->getIdClass();

        // Instantiate the ClasseManager to retrieve the class
        $classeManager = new ClasseManager();
        $classe = $classeManager->findClasseById($classId);

        // Check if class was found
        if ($classe !== null) {
            $classLevel = $classe->getLevel();

            // Instantiate the LessonManager to retrieve lessons for the current day by class level
            $lessonManager = new LessonManager();
            $lessonList = $lessonManager->findLessonsByClassLevelAndWeekDay($classLevel, $weekDay);

            $remainsTimeByLesson = []; // Initialize the array

            // Foreach lesson, retrieve the corresponding timetable
            foreach ($lessonList as $lesson) {
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
    
    
    /**
     * Display the add course form with necessary data
     * 
     * @return void
     */
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
     * Display the update course form with necessary data
     * 
     * @return void
     */
    public function updateCourse(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $courseId = (int) $_GET['id'];
            $courseManager = new CourseManager();
            $course = $courseManager->findCourseById($courseId);

            $lessonManager = new LessonManager();
            $lessons = $lessonManager->findAll();

            if ($course) {
                $this->render("editCourseForm.html.twig", [
                    "course" => $course,
                    "lessons" => $lessons
                ]);
            } else {
                echo "Cours non trouvé.";
            }
        } else {
            echo "Méthode de requête non valide.";
        }
    }
    
    
    /**
     * Process form submission to update a course.
     * Handles form validation, file uploads, and database update.
     * 
     */
    public function checkUpdateCourse(): void 
    {
        // Check if the request method is POST and the course ID is set
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $courseId = (int) $_POST['id'];
            $courseManager = new CourseManager();

            // Retrieve the current course to get the existing course data
            $currentCourse = $courseManager->findCourseById($courseId);
            if (!$currentCourse) {
                $this->renderJson(["success" => false, "message" => "Cours non trouvé."]);
                return;
            }

            // Retrieve form data
            $idLesson = $_POST["idLesson"];
            $unlockdate = $_POST["unlockdate"];
            $subject = htmlspecialchars($_POST["subject"], ENT_QUOTES, 'UTF-8');
            $summary = htmlspecialchars($_POST["summary"], ENT_QUOTES, 'UTF-8');
            $content = htmlspecialchars($_POST["content"], ENT_QUOTES, 'UTF-8');
            $videoUrl = $_POST['video'];
            $link = $_POST["link"];
            $image = $currentCourse->getImage(); // Use the existing image by default
            $audio = $currentCourse->getAudio(); // Use the existing audio by default
            $fichierpdf = $currentCourse->getFichierpdf(); // Use the existing PDF by default
            $createdAt = $currentCourse->getCreatedAt(); // Keep the existing creation date
            
             // Handle uploaded files
            $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $allowedAudioTypes = ['audio/mpeg', 'audio/wav'];
            $allowedPdfType = ['application/pdf'];

            $uploadDir = realpath(__DIR__ . '/../uploads');

            if ($uploadDir !== false) {
                $imageDir = $uploadDir . '/images/';
                $audioDir = $uploadDir . '/audios/';
                $pdfDir = $uploadDir . '/pdfs/';

                // Create directories if they do not exist
                if (!is_dir($imageDir)) {
                mkdir($imageDir, 0777, true);
                }
                if (!is_dir($audioDir)) {
                    mkdir($audioDir, 0777, true);
                }
                if (!is_dir($pdfDir)) {
                    mkdir($pdfDir, 0777, true);
                }

                // Process image upload
                if (!empty($_FILES['image']['name']) && in_array($_FILES['image']['type'], $allowedImageTypes)) {
                    $imagePath = $imageDir . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                    $image = '/collège/Projet-college-en-ligne/uploads/images/' . basename($_FILES['image']['name']);
                }

                // Process audio upload
                if (!empty($_FILES['audio']['name']) && in_array($_FILES['audio']['type'], $allowedAudioTypes)) {
                    $audioPath = $audioDir . basename($_FILES['audio']['name']);
                    move_uploaded_file($_FILES['audio']['tmp_name'], $audioPath);
                    $audio = '/collège/Projet-college-en-ligne/uploads/audios/' . basename($_FILES['audio']['name']);
                }

                // Process PDF upload
                if (!empty($_FILES['fichierpdf']['name']) && in_array($_FILES['fichierpdf']['type'], $allowedPdfType)) {
                    $pdfPath = $pdfDir . basename($_FILES['fichierpdf']['name']);
                    move_uploaded_file($_FILES['fichierpdf']['tmp_name'], $pdfPath);
                    $fichierpdf = '/collège/Projet-college-en-ligne/uploads/pdfs/' . basename($_FILES['fichierpdf']['name']);
                }
            }

            // Update the course
            $course = new Course(
                $courseId,
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
                $createdAt // Keep the existing creation date
            );
             // Save the updated course
            $updatedCourse = $courseManager->updateCourse($course);

            // Return JSON response based on the update result
            if ($updatedCourse) {
                $this->renderJson(["success" => true, "message" => "Cours mis à jour avec succès."]);
            } else {
                $this->renderJson(["success" => false, "message" => "Échec de la mise à jour du cours."]);
            }
        } else {
            // Return JSON response if the request method is invalid
            $this->renderJson(["success" => false, "message" => "Méthode de requête non valide."]);
        }
    }
    
    
    /**
     * Handles the course deletion process.
     * 
     */
    public function deleteCourse(): void 
    {
        // Check if the request method is GET and the course ID is set
        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
            $courseId = (int)$_GET["id"];
            $courseManager = new CourseManager();
        
            // Attempt to delete the course by its ID
            $deleted = $courseManager->deleteCourseById($courseId);
        
            // Return JSON response based on the deletion result
            if ($deleted) {
                $this->renderJson(["success" => true, "message" => "Cours supprimé avec succès."]);
            } else {
                $this->renderJson(["success" => false, "message" => "Échec de la suppression du cours."]);
            }
        } else {
            // Return JSON response if the request method is invalid
            $this->renderJson(["success" => false, "message" => "Méthode de requête non valide."]);
        }
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
    
    
    /**
     * Searches for courses by keyword and renders the corresponding view. 
     * 
     */
    public function searchCoursesByKeyword(): void
    {
        try {
            if (isset($_GET["keyword"])) {
                $keyword = htmlspecialchars($_GET["keyword"]);
            
                $courseManager = new CourseManager();
                $courses = $courseManager->searchCoursesByKeyword($keyword);
            
                if ($courses) {
                    $this->render('searchCourseByKeyword.html.twig', [
                        'courses' => $courses,
                        'searchKeyword' => $keyword
                    ]);
                } else {
                    $this->render("error.html.twig", ['keyword' => $keyword]);
                }
            } else {
                $this->render("error.html.twig", []);
            }
        } catch (Exception $e) {
            echo "Une erreur s'est produite lors de l'opération: " . $e->getMessage();
        }
    }
    
    
    /**
     * 
     * 
     */
    /*public function upload()
    {
        try {
            // Destination directory for uploads
            $uploadDir = realpath(__DIR__ . '/../uploads');
            $errorMessages = [];
            $uploadOk = 1;

            // Debug: Output the real path to the target directory
            error_log("Base upload directory: " . ($uploadDir !== false ? $uploadDir : "Not found"));

            if ($uploadDir === false) {
                $errorMessages[] = "Upload directory does not exist.";
                $uploadOk = 0;
            }

            $imageDir = $uploadDir . '/images/';

            // Create directory if it does not exist
            if (!is_dir($imageDir) && !mkdir($imageDir, 0777, true) && !is_dir($imageDir)) {
                $errorMessages[] = "Failed to create image directory.";
                $uploadOk = 0;
            }

            if ($uploadOk == 1) {
                $targetFile = $imageDir . basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if image file is an actual image or fake image
                $check = getimagesize($_FILES["file"]["tmp_name"]);
                if ($check === false) {
                    $errorMessages[] = 'File is not an image.';
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($targetFile)) {
                    $errorMessages[] = 'Sorry, file already exists.';
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["file"]["size"] > 5000000) { // 5MB limit
                    $errorMessages[] = 'Sorry, your file is too large.';
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                    $errorMessages[] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo json_encode(['error' => implode(' ', $errorMessages)]);
                } else {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                        echo json_encode(['location' => '/uploads/images/' . basename($_FILES["file"]["name"])]);
                    } else {
                        echo json_encode(['error' => 'Sorry, there was an error uploading your file.']);
                    }
                }
            } else {
                echo json_encode(['error' => implode(' ', $errorMessages)]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            error_log("An error occurred during the upload: " . $e->getMessage() . $e->getCode());
        }
    }*/
    
    
    /**
     * Retrieves and renders the courses associated with a specific lesson.
     *
     * This method retrieves the courses associated with a specific lesson by its ID.
     * It then renders the courses in a view if found.
     *
     * @param int $lessonId The ID of the lesson to retrieve courses for.
     * 
     * @return void 
     */
    public function showTeacherCoursesByLessonId(int $lessonId): void 
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
                $this->render("teacherCourses.html.twig", [
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