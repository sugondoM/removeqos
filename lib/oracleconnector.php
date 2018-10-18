<?php
    class myConnector{
        public $connection;
        function create(){
            $serverName = "10.21.62.225"; //serverName\instanceName
            $connectionInfo = array( "Database"=>"CA_UIM", "UID"=>"sa", "PWD"=>"P@ssw0rd");
            $connection = sqlsrv_connect( $serverName, $connectionInfo);
            if( $connection ) {
                echo "Connection established.<br />";
            } else { 
                echo "Connection could not be established.<br />";
                die( print_r( sqlsrv_errors(), true));
            } 
        }
        function destroy(){
            sqlsrv_close( $this->connection );
        }
    }
?>