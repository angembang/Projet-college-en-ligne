<?php

/**
 * Manages Principal objects in the platform.
 */
class PrincipalManager extends AbstractManager
{
    /**
     * Creates a principal and persists him in the database.
     *
     * @param Principal|null $principal The principal object to be created.
     *
     * @return principal The created principal object with the assigned identifier.
     */
    public function createPrincipal(?Principal $principal): Principal
    {
        
        // Prepare the SQL query to insert a new principal into the database
        $query = $this->db->prepare("INSERT INTO principal 
        (firstName, lastName, email, password, idRole) 
        VALUES (:firstName, :lastName, :email, :password, :idRole)");
        
        // Bind the parameters
        $parameters = [
            ":firstName" => $principal->getFirstName(),
            ":lastName" => $principal->getLastName(),
            ":email" => $principal->getEmail(),
            ":password" => $principal->getPassword(),
            ":idRole" => $principal->getIdRole()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $principalId = $this->db->lastInsertId();
        
        // Set the identifier for the created principal
        $principal->setId($principalId);
        
        // Return the created principal object
        return $principal;
    }

    
    /**
     * Retrieves a principal by his email.
     *
     * @param String $principalEmail The email of the principal.
     *
     * @return Principal|null The retrieved principal or null if not found.
     */
    public function findPrincipalByEmail(string $principalEmail): ?Principal
    {
        $query = $this->db->prepare("SELECT * FROM principal WHERE email = :email");
        
        // Bind parameters
        $parameters = [
            ":email" => $principalEmail
            ];
        
        $query->execute($parameters);
        
        $principalData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($principalData)
        {
            $principal = new Principal(
                $principalData["id"],
                $principalData["firstName"],
                $principalData["lastName"],
                $principalData["email"],
                $principalData["password"],
                $principalData["idRole"],
                );
                
            return $principal;
        }
        // principal not found
        return null;
    }
    
    
    /**
     * Update a principal in the database.
     *
     * @param Principal $principal The principal to be updated.
     *
     * @return Principal The principal updated.
     */
    public function updatePrincipal(Principal $principal): Principal
    {
        // Prepare the UPDATE query
        $query = $this->db->prepare("UPDATE principal
            SET firstName = :firstName,
            lastName = :lastName,
            email = :email,
            password = :password,
            idRole = :idRole
            WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $principal->getId(),
            ":firstName" => $principal->getFirstName(),
            ":lastName" => $principal->getLastName(),
            ":email" => $principal->getEmail(),
            ":password" => $principal->getPassword(),
            ":idRole" => $principal->getIdRole(),
        ];

        // Execute the query with parameters
        $success = $query->execute($parameters);

        if ($success) {
            return $principal;
        }

        return null;
    }


}