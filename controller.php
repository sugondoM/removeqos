<?php
   	include 'lib/mysqlconnector.php';

    function dbCallOracle($sql,$connection){
    	$stid = oci_parse($connection, $sql);
		oci_execute($stid);

		return oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    }

  //   function dbCall($sql,$connection){
  //       $row = "";
  //       $returnvalue = "";
  //       error_log("query: ".$sql);

      
		// if ($connection->connect_errno) {  
  //           error_log("no conn: ");
  //       }

  //       if($result = mysqli_query($connection, $sql)){

  //           if(mysqli_num_rows($result) > 0){
  //               while($row = mysqli_fetch_array($result)){
  //                    //error_log( print_r($row, TRUE) );
                   
  //               }
  //           }
  //       }
  //       error_log(print_r($result,true));
  //       return $result;
  //   }

   
    function getQos($hostname, $connection) {
    	$listQos = [];
    	$listRTable = [];

    	$sql = "SELECT QOS,R_TABLE FROM  ca_uim.s_qos";
		$sql .= " WHERE ROBOT like '".$hostname;
		$sql .= "' GROUP BY QOS,R_TABLE";
		$sql .= " order by qos";

		

		if($qresult = mysqli_query($connection, $sql)){
           if(mysqli_num_rows($qresult) > 0){
               while($row = mysqli_fetch_array($qresult)){
                    //error_log( print_r($row, TRUE) );
                    array_push($listQos, $row['QOS']);
                    array_push($listRTable, $row['R_TABLE']);
                 }
            }
        }

      	
		$result['QOS'] = $listQos;
		$result['R_TABLE'] = $listRTable;
		//error_log(print_r($result,true));
        return $result;
    }

    function getTableList($hostname, $qos, $connection){
    	$tableDetail = "";
    	$listTableId = "";

    	$sql = "SELECT table_id, R_TABLE FROM ca_uim.s_qos";
		$sql .= " WHERE ROBOT like '".$hostname;
		$sql .= "' AND R_TABLE like '".$qos;
		$sql .= "' GROUP BY table_id";

        
        if($qresult = mysqli_query($connection, $sql)){
           if(mysqli_num_rows($qresult) > 0){
               $i = 1;
              
               while($row = mysqli_fetch_array($qresult)){
                    $tableDetail = $row['R_TABLE'];

                    if(mysqli_num_rows($qresult)==$i){
                        $listTableId .=  "'".$row['table_id']."' ";
                    } else {
                        $listTableId .=  "'".$row['table_id']."', ";
                    }
                   

                    $i++;
                 }
            }
        }

		$result['table_detail'] = $tableDetail;
		$result['list_table_id'] = $listTableId;
		return $result;
    }

    

    function deleteQos($hostname, $listTableId, $tableDetail, $connection, $message){

    	$result = $message;
    	//$sqlcount = "SELECT COUNT(*) as total FROM ca_uim." .$table_detail;
      $sqlcount = "SELECT COUNT(*) as total FROM ca_uim.r_table" ;
    	$sqlcount .= " WHERE TABLE_ID IN (".$listTableId.")";
        $queryResultCount = 0;
        
    	if($qresultcount = mysqli_query($connection, $sqlcount)){
            $data=mysqli_fetch_array($qresultcount);
            $queryResultCount = $data['total'];
        }

        error_log($queryResultCount);
    	
        if ($queryResultCount > 0) {
    		//$sql = "DELETE FROM ca_uim.".$table_detail."";
          $sql = "DELETE FROM ca_uim.r_table";
          $sql .= " WHERE TABLE_ID IN (".$listTableId.")";

			//$queryResult = dbCall($sql, $connection);
            //mysqli_query($connection, $sql);

			$result .= "Delete success, ".$queryResultCount." rows has been deleted from ".$tableDetail;		
		}else{
      $result .= "No rows found for qos in ".$tableDetail;
    }
		
		$sql2 =  "DELETE FROM ca_uim.S_QOS_DATA";
		$sql2 .= " WHERE TABLE_ID IN (".$listTableId.")";
         //mysqli_query($connection, $sql2);

		//$queryResult2 = dbCall($sql2, $connection);

		return $result;
    }

    function doCommit(){
    	$sql = "commit;";
    }

    function deleteBulkQos($hostname, $listRTable, $connection){
	     	
    	$result = "";
		foreach ($listRTable as $key => $rTable) {

  
			$listTable = getTableList($hostname, $rTable, $connection);
            //error_log(print_r(mysqli_escape_string($connection, $listTable['list_table_id']),true));
			$deletingProgress = deleteQos($hostname, $listTable['list_table_id'], $listTable['table_detail'], $connection, $result);
			$result = $deletingProgress."\n";
		}
		//doCommit();
		$result .= "all changes has been commited";
    	
    	return $result;
    }

    function renameHost($hostname, $newhostname, $connection){
      $result = "";

      $sqlcount = "SELECT COUNT(*) as total FROM ca_uim.s_qos" ;
      $sqlcount .= " WHERE source like '".$hostname."'";

      $queryResultCount = 0;
        
      if($qresultcount = mysqli_query($connection, $sqlcount)){
        $data=mysqli_fetch_array($qresultcount);
        $queryResultCount = $data['total'];
      }

      if ($queryResultCount > 0) {
        $sql = "UPDATE ca_uim.s_qos SET source = '".$newhostname."' where source = '".$hostname."'";
        $result .= "hostname change from: ".$hostname.", to: ".$newhostname;
      } else {
        $result .= "hostname not found: ".$tableDetail;
      }

      return $result;

    }


    function mainRoute(){
    	$thisconnect = new myConnector();
    	$thisconnect->create(); 
        $callResponse = "no response";

    	if ($_POST['command'] == "getQos") {
            if($_POST['hostname'] != "" || $_POST['hostname'] != null) {
    		  $callResponse = getQos($_POST['hostname'], $thisconnect->connection);
            }
    	} else if ($_POST['command'] == "deleteQos"){
            error_log("cumi-cumi");
            
            if($_POST['hostname'] != "" || $_POST['hostname'] != null && $_POST['listqos'] != "" || $_POST['listqos'] != null) {
              $callResponse = deleteBulkQos($_POST['hostname'], $_POST['listqos'], $thisconnect->connection);
            }
      } else if ($_POST['command'] == "renameHost"){
            error_log("cumi-cumi");
            
            if($_POST['hostname'] != "" || $_POST['hostname'] != null && $_POST['newhostname'] != "" || $_POST['newhostname'] != null) {
              $callResponse = renameHost($_POST['hostname'], $_POST['newhostname'], $thisconnect->connection);
            }
      }

        $thisconnect->destroy();   
        echo json_encode($callResponse); 
    }

    mainRoute();
   

    //echo $_POST['hostname'];

?>