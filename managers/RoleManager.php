<?php

/**
 * Class RoleManager
 * Manages the retrieval and persistence of Role object in the database.
 */
class RoleManager extends AbstractManager
{
    /**
     * Creates a new role and persists it in the database.
     *
     * @param Role $role The role object to be created.
     *
     * @return Role The created role object with the assigned identifier.
     */
    public function createRole(Role $role): Role
    {
        // Prepare the SQL query to insert a new role into the database
        $query = $this->db->prepare("INSERT INTO roles (name) VALUES (:name)");

        // Bind parameters
        $parameters = [
            ":name" => $role->getName()
        ];
        
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $idRole = $this->db->lastInsertId();
        
        // Set the identifier for the created role
        $role->setId($idRole);
        
        // Return the created role object
        return $role;
    }
    
    
    /**
     * Finds a role by its name in the database.
     *
     * @param string $roleName The name of the role to be found.
     *
     * @return Role|null The role object if found, or null if not found.
     */
    public function findRoleByName(string $name): ?Role
    {
        // Prepare the SQL query to select a role by name
        $query = $this->db->prepare("SELECT * FROM roles WHERE name = :name");

        // Bind the parameters
        $parameters = [":name" => $name];
        
        // Execute the query
        $query->execute($parameters);
        
        // Fetch role data from the database
        $roleData = $query->fetch(PDO::FETCH_ASSOC);
        
        // Check if a role was found
        if ($roleData) {
            // Create a new Role object with retrieved data
            $role = new Role(
                $roleData["id"],
                $roleData["name"]
            );

            // Return the found Role object
            return $role;
        }

        // Return null if no role was found
        return null;
    }

    /**
     * Finds a role by its identifier in the database.
     *
     * @param int $roleId The identifier of the role to be found.
     *
     * @return Role|null The role object if found, or null if not found.
     */
    public function getRoleById(int $roleId): ?Role
    {
        // Prepare the SQL query to select a role by ID
        $query = $this->db->prepare("SELECT * FROM roles WHERE id = :id");

        // Bind the parameters
        $parameters = [
            ":id" => $roleId
            ];

        // Execute the query
        $query->execute($parameters);

        // Fetch role data from the database
        $roleData = $query->fetch(PDO::FETCH_ASSOC);

        // Check if a role was found
        if ($roleData) {
            // Create a new Role object with retrieved data
            $role = new Role(
                $roleData["id"],
                $roleData["name"]
            );

            // Return the found Role object
            return $role;
        }

        // Return null if no role was found
        return null;
    }
    
    
    /**
     * Retrieves all roles from the database.
     *
     * @return array|null An array of role objects representing all roles stored in the database, or null if no roles is found.
     */
    public function findAll(): ?array
    {
        // Prepare SQL query to select all roles
        $query = $this->db->prepare("SELECT * FROM roles");
    
        // Execute the query
        $query->execute();
    
        // Fetch roles data from the database
        $rolesData = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Check if roles data is not empty
        if($rolesData)
        {
            $roles = [];
        
            // Loop through each role data
            foreach($rolesData as $roleData)
            {
                // Create a role object for each role data
                $role = new Role(
                    $roleData["id"],
                    $roleData["name"]
                );
            
                // Add the created role object to the roles array
                $roles[] = $role;
            }
        
            // Return the array of role objects
            return $roles;
        }
    
    // Return null if no roles are found
    return null;
}
}