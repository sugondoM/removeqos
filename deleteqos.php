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
    var qosList = [];
    $(document).ready(function(){
        

        $('#hostnameSelector').on('change',function(){
            if($(this).val()!=0){
                qosList = [];
                $("#wkslist").empty();
                $.ajax({
                  url: "controller.php",
                  data: {hostname : $('#hostnameSelector option:selected').val(),command : "getQos"},
                  type: "POST",
                  dataType: "json",
                  cache: false,
                  success: function(response){
                    for (var i = 0; i < response["R_TABLE"].length; i++){
                         var li = $('<li id="li-' + response["R_TABLE"][i] + '"><input type="checkbox" class="qoscheckbox" name="' + response["R_TABLE"][i] + '" id="' + response["R_TABLE"][i] + '"  onchange="toggleCheckbox(this)"/>' +
                         '<label for="' + response["R_TABLE"][i] + '"></label></li>');
                         li.find('label').text(response["QOS"][i]);
                         $('#wkslist').append(li);                
                    }
                }
                });
            } else {
                qosList = [];
                $("#wkslist").empty();
            }
        });

        $('#qos_delete_button').click(function() {
            if (qosList === undefined || qosList.length == 0) {
                alert( "no_qos_selected" );
            } else {
                 $.ajax({
                  url: "controller.php",
                  data: {hostname : $('#hostnameSelector option:selected').val(),listqos:qosList,command : "deleteQos"},
                  type: "POST",
                  dataType: "json",
                  cache: false,
                  success: function(response){

                    for (i = 0; i < qosList.length; i++) { 
                        $('#li-'+qosList[i]).remove();
                    }
                    alert(response);
                    qosList = [];

                  }
                });
            }
          
        });

         
    })

    function toggleCheckbox(element){
        console.log(element.checked);
        if(!element.checked){
            var index = qosList.indexOf(element.id);    // <-- Not supported in <IE9
            if (index !== -1) {
                qosList.splice(element.id, 1);
            }
        }else{
             qosList.push(element.id);
        }
        console.log(element.id);
       
        console.log(qosList);
    }
   
</script>
</head>
<body>
    <div class="main-wrapper">
    <div class="title"> <div class="logo"></div> QOS Remove Data </div>
    <div id="hostname_holder">
        <span>Robot : </span>
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
    $sql = "SELECT robot FROM s_qos GROUP BY robot;";
    if($result = mysqli_query($thisconnect->connection, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                echo "<option value=".$row['robot'].">".$row['robot']."</option>";
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
  <div class="qos_holder">
    <div>List QOS : </div>
    <ul id="wkslist"></ul>
  </div>
  <div><input type="button" value="DELETE QOS DATA" id="qos_delete_button"></div>
  </div>
</body>
</html>
