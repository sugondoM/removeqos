<?php 
    include 'lib/mysqlconnector.php';
    
    $thisconnect = new myConnector();
    $thisconnect->create();
?>

<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<link rel="stylesheet" type="text/css" href="css/main.css"  />
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</head>
<body>
    <div class="main-wrapper">
    <div class="title"> <div class="logo"></div>List Obsolete Data</div>
    <div class="list_holder">
        <a href="/qosremove/deleteqos.php" target="_blank" rel="noopener noreferrer">Go To Delete QOS</a>
    </div>
     <div class="list_holder">
        <a href="/qosremove/renamehost.php" target="_blank" rel="noopener noreferrer">Go To Rename Host</a>
    </div>
    <div class="list_holder">
        <span>Robot : </span>
<?php

    // $sql = "SELECT s1.SAMPLETIME, S3.ROBOT,S3.QOS, S3.TARGET from RN_QOS_DATA_0014 s1 ";
    // $sql .= "inner join ( SELECT MAX(SAMPLETIME) SAMPLETIME, TABLE_ID from RN_QOS_DATA_0014 group by TABLE_ID ) S2 ";
    // $sql .= "ON S1.TABLE_ID = S2.TABLE_ID AND S1.SAMPLETIME = S2.SAMPLETIME INNER JOIN S_QOS_DATA S3 ON S3.TABLE_ID = S1.TABLE_ID ";
    // $sql .= "WHERE TARGET = S3.ROBOT and S1.SAMPLETIME < SYSDATE - INTERVAL '14' day GROUP BY S1.TABLE_ID, S1.SAMPLETIME, S3.ROBOT, S3.QOS, S3.TARGET ORDER BY S1.SAMPLETIME DESC";

    $sql = "SELECT s1.SAMPLETIME, S3.ROBOT,S3.QOS, S3.TARGET from r_table s1 ";
    $sql .= "inner join ( SELECT MAX(SAMPLETIME) SAMPLETIME, TABLE_ID from r_table group by TABLE_ID ) S2 ";
    $sql .= "ON S1.TABLE_ID = S2.TABLE_ID AND S1.SAMPLETIME = S2.SAMPLETIME INNER JOIN S_QOS S3 ON S3.TABLE_ID = S1.TABLE_ID ";
    $sql .= "WHERE TARGET = S3.ROBOT GROUP BY S1.TABLE_ID, S1.SAMPLETIME, S3.ROBOT, S3.QOS, S3.TARGET ORDER BY S1.SAMPLETIME DESC";

   
    if($result = mysqli_query($thisconnect->connection, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                echo "<div>".$row['ROBOT']."</div>";
            }
        }
    }
            
?>
    </div>
    <div class="list_holder">
        <span>Network Device : </span>
<?php

    // $sql2 = "SELECT s1.SAMPLETIME, S3.source,S3.QOS,S3.TARGET from RN_QOS_DATA_0094 s1 "; 
    // $sql2 .= "inner join ( SELECT MAX(SAMPLETIME) SAMPLETIME, TABLE_ID from RN_QOS_DATA_0094 group by TABLE_ID ) S2 ";
    // $sql2 .= "ON S1.TABLE_ID = S2.TABLE_ID AND S1.SAMPLETIME = S2.SAMPLETIME INNER JOIN S_QOS_DATA S3 ON S3.TABLE_ID = S1.TABLE_ID ";
    // $sql2 .= "WHERE S1.SAMPLETIME < SYSDATE - INTERVAL '14' DAY GROUP BY S1.TABLE_ID, S1.SAMPLETIME, S3.source, S3.QOS, S3.TARGET ORDER BY S1.SAMPLETIME DESC";
    
    $sql2 = "SELECT s1.SAMPLETIME, S3.source,S3.QOS,S3.TARGET from r_table s1 "; 
    $sql2 .= "inner join ( SELECT MAX(SAMPLETIME) SAMPLETIME, TABLE_ID from r_table group by TABLE_ID ) S2 ";
    $sql2 .= "ON S1.TABLE_ID = S2.TABLE_ID AND S1.SAMPLETIME = S2.SAMPLETIME INNER JOIN S_QOS S3 ON S3.TABLE_ID = S1.TABLE_ID ";
    $sql2 .= "GROUP BY S1.TABLE_ID, S1.SAMPLETIME, S3.source, S3.QOS, S3.TARGET ORDER BY S1.SAMPLETIME DESC";

    error_log($sql2);


    if($result2 = mysqli_query($thisconnect->connection, $sql2)){
        if(mysqli_num_rows($result2) > 0){
            while($row2 = mysqli_fetch_array($result2)){
                echo "<div>".$row2['source']."</div>";
            }
        }
    }
            
     $thisconnect->destroy();

?>
    </div>
  </div>
</body>
</html>
