<?php
    class myConnector{
        public $connection;
        function create(){
            $servername = "localhost";
            $username = "root";
            $password = "gn10041990";
            $dbname = "ca_uim";
            
            // Create connection
            $this->connection = new mysqli($servername, $username, $password, $dbname);
            
            // Check connection
            if ($this->connection->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
           
        }
        function destroy(){
            mysqli_close($this->connection);
        }
    }
?>