<?php

/**
 * Manages the retrieval and persistence of language object in the platform.
 */
class LanguageManager extends AbstractManager
{
    /**
     * Creates a new language and persists its in the database.
     *
     * @param Language $language The language object to be created.
     *
     * @return Language The created language object with the assigned identifier.
     */
    public function createLanguage(Language $language): Language
    {
        
        // Prepare the SQL query to insert a new language into the database
        $query = $this->db->prepare("INSERT INTO languages 
        (name) 
        VALUES (:name)");
        
        // Bind the parameters
        $parameters = [
            ":name" => $classe->getName()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $languageId = $this->db->lastInsertId();
        
        // Set the identifier for the created classe
        $language->setId($languageId);
        
        // Return the created classe object
        return $language;
    }

    
    /**
     * Retrieves a language by its name.
     *
     * @param String $languageName The name of the language.
     *
     * @return Language|null The retrieved language or null if not found.
     */
    public function findLanguageByName(string $languageName): ?Language
    {
        $query = $this->db->prepare("SELECT * FROM languages WHERE name = :name");
        
        // Bind parameters
        $parameters = [
            ":name" => $languageName
            ];
        
        $query->execute($parameters);
        
        $languageData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($languageData)
        {
            $language = new Language(
                $languageData["id"],
                $languageData["name"]
                );
                
            return $language;
        }
        // language not found
        return null;
    }
    
    
    /**
     * Retrieves a language by its unique identifier.
     *
     * @param int $languageId The unique identifier of the language.
     *
     * @return Language|null The retrieved language or null if not found.
     */
    public function findNanguageById(int $languageId): ?Language
    {
        $query = $this->db->prepare("SELECT * FROM languages WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $languageId
            ];
        
        $query->execute($parameters);
        
        $languageData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($languageData)
        {
            $language = new Language(
                $languageData["id"],
                $languageData["name"]
                );
                
            return $language;
        }
        // language not found
        return null;
    }
    
    
    /**
     * Retrieves all languages from the database.
     *
     * @return array|null An array of language objects representing all languages stored in the database, or null if no languages is found.
     */
    public function findAll(): ?array
    {
        // Prepare SQL query to select all languages
        $query = $this->db->prepare("SELECT * FROM languages");
    
        // Execute the query
        $query->execute();
    
        // Fetch languages data from the database
        $languagesData = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Check if languages data is not empty
        if($languagesData)
        {
            $languages = [];
        
            // Loop through each language data
            foreach($languagesData as $languageData)
            {
                // Create a language object for each language data
                $language = new Language(
                    $languageData["id"],
                    $languageData["name"]
                );
            
                // Add the created language object to the languages array
                $languages[] = $language;
            }
        
            // Return the array of language objects
            return $languages;
        }
    
    // Return null if no languages are found
    return null;
}
    
    
}