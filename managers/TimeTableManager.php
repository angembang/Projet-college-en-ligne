<?php

/**
 * Manages the retrieval and persistence of TimeTable object in the platform.
 */
class TimeTableManager extends AbstractManager
{
    /**
     * Creates a new timeTable and persists its in the database.
     *
     * @param TimeTable  $timeTable  The TimeTable  object to be created.
     *
     * @return TimeTable |null The created timeTable  object with the assigned identifier or Null if not create.
     */
    public function createTimeTable (TimeTable  $timeTable ): ?TimeTable 
    {
        
        // Prepare the SQL query to insert a new timeTable into the database
        $query = $this->db->prepare("INSERT INTO timesTables  
        (weekDay, startTime, endTime) 
        VALUES (:weekDay, :startTime, :endTime)");
        
        // Bind the parameters
        $parameters = [
            ":weekDay" => $timeTable->getWeekDay(),
            ":startTime" => $timeTable->getStartTime(),
            ":endTime" => $timeTable->getEndTime()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $timeTableId = $this->db->lastInsertId();
        
        // Set the identifier for the created timeTable
        $timeTable->setId($timeTableId);
        
        // Return the created timeTable object
        return $timeTable;
    }

    
    /**
     * Retrieves  timesTables by their weekDay.
     *
     * @param String $timeTableWeekDay The weekDay of the timeTable.
     *
     * @return Array|null The array of retrieved timesTables or null if not found.
     */
    public function findTimesTablesByWeekDay(string $timeTableWeekDay): ?array
    {
        $query = $this->db->prepare("SELECT * FROM timesTables WHERE weekDay = :weekDay");
        
        // Bind parameters
        $parameters = [
            ":weekDay" => $timeTableWeekDay
            ];
        
        $query->execute($parameters);
        
        $timesTablesData = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if($timesTablesData)
        {
            $timesTables = [];
            
            foreach($timesTablesData as $timeTable)
            {
                $timeTable = new TimeTable(
                $timeTableData["id"],
                $timeTableData["weekDay"],
                $timeTableData["startTime"],
                $timeTableData["endTime"]
                );
                $timesTables[] = $timeTable;
                
            }
            
            return $timesTables;
        }
        // timesTables not found
        return null;
    }
    
    
    /**
     * Retrieves a timeTable by its unique identifier.
     *
     * @param int $timeTableId The unique identifier of the timeTable.
     *
     * @return TimeTable|null The timeTable of retrieved timeTable or null if not found.
     */
    public function findTimeTableById(string $timeTableId): ?TimeTable
    {
        $query = $this->db->prepare("SELECT * FROM timesTables WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $timeTableId
            ];
        
        $query->execute($parameters);
        
        $timeTableData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($timeTableData)
        {
        
            $timeTable = new TimeTable(
            $timeTableData["id"],
            $timeTableData["weekDay"],
            $timeTableData["startTime"],
            $timeTableData["endTime"]
            );
            
            return $timeTable;
        }
        // $timeTable not found
        return null;
    }
    
    
    /*
    * Retrieves all timesTables from the database
    *
    *@return array|null An array of timeTable object representing all timastables store into the database
    *
    */
    public function findAll(): ?array
    {
        // Prepare SQL query to select all timesTables
        $query = $this->db->prepare("SELECT * FROM timesTables");
        
        // Execute the query
        $query->execute();
        
        // Fetch timesTables data from the database
        $timesTablesData = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Check if timesTables data is not empty
        if($timesTablesData)
        {
            $timestables = [];
            
             // Loop through each tmeTable data
            foreach($timesTablesData as $timeTableData)
            {
                // Create a timeTable object for each timeTable data
                $timeTable = new TimeTable(
                    $timeTableData["id"],
                    $timeTableData["weekDay"],
                    $timeTableData["startTime"],
                    $timeTableData["endTime"]
                    );
                    
                    // Add the created timeTable object to the timesTables array
                    $timesTables[] = $timeTable;
            }
            // retun an array of timeTable object
            return $timesTables;
        }
        // return null if no timeTable is found
        return null;
    }
    
}