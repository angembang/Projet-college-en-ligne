<?php

/**
 * Manages the retrieval and persistence of Classe object in the platform.
 */
class ClasseManager extends AbstractManager
{
    /**
     * Creates a new classe and persists its in the database.
     *
     * @param Classe|null $classe The classe object to be created.
     *
     * @return Classe The created classe object with the assigned identifier.
     */
    public function createClasse(?Classe $classe): Classe
    {
        
        // Prepare the SQL query to insert a new classe into the database
        $query = $this->db->prepare("INSERT INTO classes 
        (level) 
        VALUES (:level)");
        
        // Bind the parameters
        $parameters = [
            ":level" => $classe->getLevel()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $classeId = $this->db->lastInsertId();
        
        // Set the identifier for the created classe
        $classe->setId($classeId);
        
        // Return the created classe object
        return $classe;
    }

    
    /**
     * Retrieves a classe by its level.
     *
     * @param String $classeLevel The level of the classe.
     *
     * @return Classe|null The retrieved classe or null if not found.
     */
    public function findClasseByLevel(string $classeLevel): ?Classe
    {
        $query = $this->db->prepare("SELECT * FROM classes WHERE level = :level");
        
        // Bind parameters
        $parameters = [
            ":level" => $classeLevel
            ];
        
        $query->execute($parameters);
        
        $classeData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($classeData)
        {
            $classe = new Classe(
                $classeData["id"],
                $classeData["level"]
                );
                
            return $classe;
        }
        // classe not found
        return null;
    }
    
    
    /**
     * Retrieves a classe by its unique identifier.
     *
     * @param int $classeId The unique identifier of the classe.
     *
     * @return Classe|null The retrieved classe or null if not found.
     */
    public function findClasseById(int $classeId): ?Classe
    {
        $query = $this->db->prepare("SELECT * FROM classes WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $classeId
            ];
        
        $query->execute($parameters);
        
        $classeData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($classeData)
        {
            $classe = new Classe(
                $classeData["id"],
                $classeData["level"]
                );
                
            return $classe;
        }
        // classe not found
        return null;
    }
    
    
}