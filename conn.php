
        <?php
        $host = "mariadbtest.rtac.in";
        $user ="cbs";
        $pass = "P1I6oK8OPEPv" ;
        $db = "Amruta";
        
        $conn = new mysqli("$host", "$user", "$pass", "$db");
        
        if(!$conn){
            exit(json_encode(["error" => "Connection Failed"]));
        }
    
