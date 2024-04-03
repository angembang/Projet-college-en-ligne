<?php

/**
 * Manages the retrieval and persistence of TeacherReferent object in the platform.
 */
class TeacherReferentManager extends AbstractManager
{
    /**
     * Creates a teacher referent and persists him in the database.
     *
     * @param TeacherReferent $teacherReferent The teacherReferent object to be created.
     *
     * @return teacherReferent The created teacherReferent object with the assigned identifier.
     */
    public function createTeacherReferent(TeacherReferent $teacherReferent): TeacherReferent
    {
        
        // Prepare the SQL query to insert a new teacher into the database
        $query = $this->db->prepare("INSERT INTO teachersReferents 
        (firstName, lastName, email, password, idClass, idRole) 
        VALUES (:firstName, :lastName, :email, :password, :idClass, :idRole)");
        
        // Bind the parameters
        $parameters = [
            ":firstName" => $teacherReferent->getFirstName(),
            ":lastName" => $teacherReferent->getLastName(),
            ":email" => $teacherReferent->getEmail(),
            ":password" => $teacherReferent->getPassword(),
            ":idClass" => $teacherReferent->getIdClass(),
            ":idRole" => $teacherReferent->getIdRole()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $teacherReferentId = $this->db->lastInsertId();
        
        // Set the identifier for the created teacher
        $teacherReferent->setId($teacherReferentId);
        
        // Return the created teacherReferent object
        return $teacherReferent;
    }

    
    /**
     * Retrieves a teacherReferent by his id.
     *
     * @param String $teacherReferentId The id of the teacherReferent.
     *
     * @return TeacherReferent|null The retrieved teacherReferent or null if not found.
     */
    public function findTeacherReferentById(int $teacherReferentId): ?TeacherReferent
    {
        $query = $this->db->prepare("SELECT * FROM teachersReferents WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $teacherReferentId
            ];
        
        $query->execute($parameters);
        
        $teacherReferentData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($teacherReferentData)
        {
            $teacherReferent = new TeacherReferent(
                $teacherReferentData["id"],
                $teacherReferentData["idTeacher"],
                $teacherReferentData["idClass"],
                $teacherReferentData["idRole"],
                );
                
            return $teacherReferent;
        }
        // teacherReferent not found
        return null;
    }
    
    
    /**
     * Retrieves a teacher referent by his email.
     *
     * @param String $teacherReferentEmail The email of the teacherReferent.
     *
     * @return TeacherReferent|null The retrieved teacherReferent or null if not found.
     */
    public function findTeacherReferentByEmail(string $teacherReferentEmail): ?TeacherReferent
    {
        $query = $this->db->prepare("SELECT * FROM teachersReferents WHERE email = :email");
        
        // Bind parameters
        $parameters = [
            ":email" => $teacherReferentEmail
            ];
        
        $query->execute($parameters);
        
        $teacherReferentData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($teacherReferentData)
        {
            $teacherReferent = new TeacherReferent(
                $teacherReferentData["id"],
                $teacherReferentData["firstName"],
                $teacherReferentData["lastName"],
                $teacherReferentData["email"],
                $teacherReferentData["password"],
                $teacherReferentData["idClass"],
                $teacherReferentData["idRole"]
                );
                
            return $teacherReferent;
        }
        // teacherReferent not found
        return null;
    }
    
    
    
    /**
     * Retrieves a teacherReferent by his idClass.
     *
     * @param String $teacherReferentIdClass The idClass of the teacherReferent.
     *
     * @return TeacherReferent|null The retrieved teacherReferent or null if not found.
     */
    public function findTeacherReferentByIdClass(int $teacherReferentIdClass): ?TeacherReferent
    {
        $query = $this->db->prepare("SELECT teachersReferents.*, classes.* 
        FROM teachersReferents 
        JOIN classes
        ON idClass = classes.id
        WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $teacherReferentIdClass
            ];
        
        $query->execute($parameters);
        
        $teacherReferentData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($teacherReferentData)
        {
            $teacherReferent = new TeacherReferent(
                $teacherReferentData["id"],
                $teacherReferentData["idTeacher"],
                $teacherReferentData["idClass"],
                $teacherReferentData["idRole"]
                );
                
            return $teacherReferent;
        }
        // teacherReferent not found
        return null;
    }
    
    
     /**
     * Retrieves all teachers referents from the database
     * 
     * @return array|null An array of teacherReferent object representing all teachersReferents store into the database, or null if no teacher is found
     *
     */
    public function findAll(): ?array
    {
        // Prepare SQL query to select all teachersReferent
        $query = $this->db->prepare("SELECT * FROM teachersReferents");
        
        // Execute the query
        $query->execute();
        
        // Fetch teachersReferent data from the database
        $teachersReferentsData = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Check if teachers data is not empty
        if($teachersReferentsData)
        {
            $teachersReferents = [];
            
            // Loop through each teacherReferent data
            foreach($teachersReferentsData as $teacherReferentData)
            {
                // Create a teacher object for each teacher data
                $teacherReferent = new TeacherReferent(
                    $teacherReferentData["id"],
                    $teacherReferentData["firstName"],
                    $teacherReferentData["lastName"],
                    $teacherReferentData["email"],
                    $teacherReferentData["password"],
                    $teacherReferentData["idClass"],
                    $teacherReferentData["idRole"]
                    );
                    
                    // Add the created teacherReferent object to the teachersReferents array
                    $teachersReferents[] = $teacherReferent;
            }
            // Return the array of teacher objects
            return $teachersReferents;
        }
        // Return null if no teachersReferents are found
        return null;
        
    }
    
    
    
    
    /**
     * Updates a teacherReferent in the database.
     *
     * @param TeacherReferent $teacherReferent The teacherReferent to be updated.
     *
     * @return TeacherReferent The teacherReferent updated.
     */
    public function updateTeacherReferent(TeacherReferent $teacherReferent): TeacherReferent
    {
        // Prepare the UPDATE query
        $query = $this->db->prepare("UPDATE teachersReferents
            SET idTeacher = :idTeacher,
            idClass = :idClass,
            idRole = :idRole
            WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $teacherReferent->getId(),
            ":idTeacher" => $teacherReferent->getIdTeacher(),
            ":idClass" => $teacherReferent->getIdClass(),
            ":idRole" => $teacherReferent->getIdRole(),
        ];

        // Execute the query with parameters
       $success = $query->execute($parameters);
       if($success)
       {
           return $teacherReferent;
       }
       return null;
        
    }
    
    
    /**
     * Deletes a teacherReferent from the database.
     *
     * @param int $teacherReferentId The unique identifier of the teacherReferent to be deleted.
     *
     * @return bool True if the operation is successful and false if not.
     */
    public function deleteTeacherReferentById(int $teacherReferentId): bool
    {
        // Prepare the DELETE query
        $query = $this->db->prepare("DELETE FROM teachersReferents WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $teacherReferentId
            ];

        // Execute the query with parameters
        $success = $query->execute($parameters);

        // Return true if the deletion was successful, false otherwise
        return $success;
    }


}