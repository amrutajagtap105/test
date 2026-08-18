
        <?php
        include "../conn.php";
        $files=[];
      $result = $conn->query("Select id, name from phpfile");
      if($result && $result->num_rows >0){
          while($rows=$result->fetch_assoc()){
             $files[]= $rows;
          }
      }
      
      echo json_encode($files);
        ?>
  