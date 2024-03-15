<?php

/**
 * Manages the retrieval and persistence of Collegian object in the platform.
 */
class CollegianManager extends AbstractManager
{
    /**
     * Creates a new collegian and persists him in the database.
     *
     * @param Collegian|null $collegian The collegian object to be created.
     *
     * @return Collegian The created collegian object with the assigned identifier.
     */
    public function createCollegian(?Collegian $collegian): Collegian
    {
        
        // Prepare the SQL query to insert a new collegian into the database
        $query = $this->db->prepare("INSERT INTO collegians 
        (firstName, lastName, email, password, idClass, idLanguage, idRole) 
        VALUES (:firstName, :lastName, :email, :password, :idClass, :idLanguage, :idRole)");
        
        // Bind the parameters
        $parameters = [
            ":firstName" => $collegian->getFirstName(),
            ":lastName" => $collegian->getLastName(),
            ":email" => $collegian->getEmail(),
            ":password" => $collegian->getPassword(),
            ":idClass" => $collegian->getIdClass(),
            ":idLanguage" => $collegian->getIdLanguage(),
            ":idRole" => $collegian->getIdRole()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $collegianId = $this->db->lastInsertId();
        
        // Set the identifier for the created collegian
        $collegian->setId($collegianId);
        
        // Return the created collegian object
        return $collegian;
    }
    
    
    /**
     * Retrieves a collegian by his unique identifier.
     *
     * @param int $collegianId The unique identifier of the collegian.
     *
     * @return Collegian|null The retrieved collegian or null if not found.
     */
    public function findCollegianById(int $collegianId): ?Collegian
    {
        $query = $this->db->prepare("SELECT * FROM collegians WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $collegianId
            ];
        
        $query->execute($parameters);
        
        $collegianData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($collegianData)
        {
            $collegian = new Collegian(
                $collegianData["id"],
                $collegianData["firstName"],
                $collegianData["lastName"],
                $collegianData["email"],
                $collegianData["password"],
                $collegianData["idClass"],
                $collegianData["idLanguage"],
                $collegianData["idRole"],
                );
                
            return $collegian;
        }
        // collegian not found
        return null;
    }
    

    
    /**
     * Retrieves a collegian by his email.
     *
     * @param String $collegianEmail The email of the collegian.
     *
     * @return Collegian|null The retrieved collegian or null if not found.
     */
    public function findCollegianByEmail(string $collegianEmail): ?Collegian
    {
        $query = $this->db->prepare("SELECT * FROM collegians WHERE email = :email");
        
        // Bind parameters
        $parameters = [
            ":email" => $collegianEmail
            ];
        
        $query->execute($parameters);
        
        $collegianData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($collegianData)
        {
            $collegian = new Collegian(
                $collegianData["id"],
                $collegianData["firstName"],
                $collegianData["lastName"],
                $collegianData["email"],
                $collegianData["password"],
                $collegianData["idClass"],
                $collegianData["idLanguage"],
                $collegianData["idRole"],
                );
                
            return $collegian;
        }
        // collegian not found
        return null;
    }
    
    
    
    
    /**
     * Retrieves a collegians by their class identifier.
     *
     * @param int $collegianClassId The class identifier of collegians.
     *
     * @return array|null An array of collegians or null if none is found.
     */
    public function findCollegiansByClassId(int $collegianClassId): ?array
    {
        // Use a JOIN query to fetch collegian information based on the class level.
        $query = $this->db->prepare("SELECT collegians.*, classes.* 
        FROM collegians
        JOIN classes
        ON idClass = classes.id
        WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $collegianClassId
        ];

        // Execute the query with parameters
        $query->execute($parameters);

        // Fetch collegian data
        $collegiansData = $query->fetchAll(PDO::FETCH_ASSOC);

        // If data is found, create an array of Collegians
        if ($collegiansData) 
        {
            $collegians = [];
            
            foreach($collegiansData as $collegianData)
            {
                $collegian = new Collegian(
                    $collegianData["id"],
                    $collegianData["firstName"],
                    $collegianData["lastName"],
                    $collegianData["email"],
                    $collegianData["password"],
                    $collegianData["idClass"],
                    $collegianData["idLanguage"],
                    $collegianData["idRole"]
                );
                $collegians[] = $collegian;
            }
            

            return $collegians;
        }

        // No collegian found
        return null;
    }
    
    
    /**
     * Retrieves collegians by their language identifier.
     *
     * @param int $collegianLanguageId The language identifier of collegians.
     *
     * @return array|null An array of Collegians or null if none is found.
     */
    public function findCollegiansByLanguageId(int $collegianLanguageId): ?array
    {
        // Use a JOIN query to fetch collegian information based on the language name.
        $query = $this->db->prepare("SELECT collegians.*, languages.* 
        FROM collegians
        JOIN languages
        ON idLanguage = languages.id
        WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $collegianLanguageId
        ];

        // Execute the query with parameters
        $query->execute($parameters);

        // Fetch collegians data as an array
        $collegiansData = $query->fetchAll(PDO::FETCH_ASSOC);

        // If data is found, create an array of Collegians
        if ($collegiansData) {
            $collegians = [];
            foreach ($collegiansData as $collegianData) {
                $collegian = new Collegian(
                    $collegianData["id"],
                    $collegianData["firstName"],
                    $collegianData["lastName"],
                    $collegianData["email"],
                    $collegianData["password"],
                    $collegianData["idClass"],
                    $collegianData["idLanguage"],
                    $collegianData["idRole"]
                );
                $collegians[] = $collegian;
            }

            return $collegians;
        }

        // No collegian found
        return null;
    }
    
    
    /**
     * Updates a collegian in the database.
     *
     * @param Collegian $collegian The collegian to be updated.
     *
     * @return Collegian The collegian updated.
     */
    public function updateCollegian(Collegian $collegian): Collegian
    {
        // Prepare the UPDATE query
        $query = $this->db->prepare("UPDATE collegians
            SET firstName = :firstName,
            lastName = :lastName,
            email = :email,
            password = :password,
            idClass = :idClass,
            idLanguage = :idLanguage,
            idRole = :idRole
            WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $collegian->getId(),
            ":firstName" => $collegian->getFirstName(),
            ":lastName" => $collegian->getLastName(),
            ":email" => $collegian->getEmail(),
            ":password" => $collegian->getPassword(),
            ":idClass" => $collegian->getIdClass(),
            ":idLanguage" => $collegian->getIdLanguage(),
            ":idRole" => $collegian->getIdRole(),
        ];

        // Execute the query with parameters
       $success = $query->execute($parameters);
       if($success)
       {
           return $collegian;
       }
       return null;
        
    }

    /**
     * Deletes a collegian from the database.
     *
     * @param int $collegianId The unique identifier of the collegian to be deleted.
     *
     * @return bool True if the operation is successful, false if not.
     */
    public function deleteCollegianById(int $collegianId): bool
    {
        // Prepare the DELETE query
        $query = $this->db->prepare("DELETE FROM collegians WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $collegianId
            ];

        // Execute the query with parameters
        $success = $query->execute($parameters);

        // Return true if the deletion was successful, false if not
        return $success;
    }
}