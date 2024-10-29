<?php

class MySQL
{
    public static function connect($servername, $username, $password, $database)
    {
        try {
            $new_connect = mysqli_connect($servername, $username, $password, $database);
            if ($new_connect->connect_error) {
                throw new Exception('Connect Error (' . $new_connect->connect_errno . ') ' . $new_connect->connect_error);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo 'Caught exception: ' . $e->getMessage() . PHP_EOL;
        }
        return  $new_connect;
    }
    public static function checkConnection($conn)
    {
        if ($conn->connect_error) {
            die("Connection Failed!" . $conn->connect_error);
        }
        echo "Connected successfully" . PHP_EOL;
    }
    static public function query_insertInto($table, $conn)
    {
        $sql = "
        INSERT INTO " .
            $table .
            " (planet_id, planet_name, region, population, discovered_date) 
        VALUES 
        (15, 'Neptuno', 'Core Worlds', 0, '1999-01-01')
        ";
        if ($conn->query($sql)) {
            echo "New record created sucessfully" . PHP_EOL;
        } else {
            echo "New Error: " . $sql . $conn->error . PHP_EOL;
        }
        echo "Distroying connection" . PHP_EOL;
        $conn->close();
    }
    static public function selectFrom($conn, $column, $table, $whereConditional, $whereValue)
    {
        $sql =  "
            SELECT " . $column .
            " FROM " . $table .
            " WHERE " . $whereConditional . ">" . $whereValue;

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    print_r($row) . PHP_EOL;
                }
            }
        } else {
            echo '0 results';
        }
        echo "Distroying connection" . PHP_EOL;
        $conn->close();
    }
    static public function updateSet($conn, $table, $column, $newValue, $whereConditional, $whereValue)
    {
        $sql = "
                UPDATE " . $table .
            " SET " . $column . "=" . "'{$newValue}'" .
            " WHERE " . $whereConditional . "=" . $whereValue;

        if ($conn->query($sql)) {
            echo 'The number of affected rows is: ' . $conn->affected_rows . "" . PHP_EOL;
            echo "Record updated succesfully" . PHP_EOL;
        } else {
            echo " Error updating error: " . $conn->error;
        }
        echo "Distroying connection" . PHP_EOL;
        $conn->close();
    }
    static public function deleteRows($conn, $table, $whereConditional, $whereValue)
    {

        $sql = "
                 DELETE FROM " . $table .
            " WHERE " . $whereConditional . "=" . $whereValue;

        if ($conn->query($sql)) {
            echo 'The number of affected rows is: ' . $conn->affected_rows . "" . PHP_EOL;
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        $conn->close();
    }
    public static function createDatabase($conn, $servername, $username, $password, $databaseName)
    {
        $sql = "CREATE DATABASE $databaseName";

        if ($conn->query($sql)) {
            echo "NEW DATABASE created sucessfully" . PHP_EOL;
        } else {
            echo "New Error: " . $sql . $conn->error . PHP_EOL;
        }
        echo "Distroying connection" . PHP_EOL;
        $conn->close();
    }
    public static function createTable($conn, $servername, $username, $password, $databaseName, $tableName)
    {
        if (self::viewDatabase($conn) == $databaseName) {
            // Falta mejorar la logica del viewDatabase
            $sql = "CREATE TABLE $tableName";
            if ($conn->query($sql)) {
                echo 'NEW Table created successfully';
            } else {
                echo "New Error: " . $sql . $conn->error . PHP_EOL;
            }
        }
    }
    private static function dumpCopyDB($servername, $username, $password, $database, $DumpPath)
    {
        $command = "mysqldump -h $servername -u $username .p$password $database > $DumpPath";
        $output = shell_exec($command);

        if ($output === null) {
            echo "Database dump created successfully at $DumpPath" . PHP_EOL;
        } else {
            echo "Error creating database dump: " . $output . PHP_EOL;
        }
    }
    public static function viewDatabase($conn)
    {
        $sql = "SELECT DATABASE()";
        $output = $conn->query($sql);

        // El output de esto es un objeto tipo msqli objetc -> MEJORAR LA LOGICA DE ESTO

        if ($output) {
            echo 'YOU ARE USING THIS DATABASE: ' . PHP_EOL;
            print_r(value: $output) . PHP_EOL;
        }
        $conn->close();
        return $output;
    }
    public static function showDatabases($conn)
    {
        $sql = "SHOW DATABASES";
        $output = $conn->query($sql);

        // El output de esto es un objeto tipo msqli objetc -> MEJORAR LA LOGICA DE ESTO

        if ($output) {
            echo 'YOU ARE USING THIS DATABASE: ' . PHP_EOL;
            print_r($output) . PHP_EOL;
        }
        $conn->close();
        return $output;
    }
    public static function deleteDatabase($conn, $servername, $username, $password, $databaseName)
    {
        $sql = "DROP DATABASE $databaseName";

        if ($conn->query($sql)) {
            echo "DATABASE DELETED sucessfully" . PHP_EOL;
        } else {
            echo "New Error: " . $sql . $conn->error . PHP_EOL;
        }
        echo "Distroying connection" . PHP_EOL;
        $conn->close();
    }
}

