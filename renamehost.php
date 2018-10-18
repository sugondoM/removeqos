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
<script type="text/javascript">
    var newhostname = "";
    $(document).ready(function(){        

        $('#qos_delete_button').click(function() {
          newhostname = $('#newhostname').val();
            if($('#hostnameSelector').val() == 0){
               alert( "please select hostname to be changed" );
            } else if (newhostname === undefined || newhostname == "" || newhostname == null) {
                alert( "please insert new hostname name" );
            } else {
                //alert(newhostname);
                  $.ajax({
                   url: "controller.php",
                   data: {hostname : $('#hostnameSelector option:selected').val(),newhostname:newhostname,command : "renameHost"},
                   type: "POST",
                   dataType: "json",
                   cache: false,
                   success: function(response){

                      alert(response);
                
                   }
                 });
            }
          
        });

         
    })
   
</script>
</head>
<body>
    <div class="main-wrapper">
    <div class="title"> <div class="logo"></div> QOS Remove Data </div>
    <div class ="list_holder">
        <span class="lable">Robot : </span>
        <select id="hostnameSelector">
<?php

    /*$sql = "";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        foreach ($row as $item) {
            echo "<option value=".$item['hostname'].">".$item['hostname']."</option>";
        }           
    }*/
    echo "<option value='0'>Select Hostname</option>";
    $sql = "SELECT source FROM s_qos GROUP BY source;";
    if($result = mysqli_query($thisconnect->connection, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                echo "<option value=".$row['source'].">".$row['source']."</option>";
            }
        }
    }
            
     $thisconnect->destroy();

?>
<?php
/*
        for ($i=0; $i<5; $i++) {
            echo "<option value=".$i.">cumi cumi ".$i."</option>";
        }  
*/
?>

        </select>
    </div>
  <div class="list_holder">
    <span class="lable">New Hostname Name: </span><input type="text" id="newhostname"/>
  </div>
  <div><input type="button" value="RENAME HOST NAME" id="qos_delete_button"></div>
  </div>
</body>
</html>
