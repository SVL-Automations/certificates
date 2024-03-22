<?php
   						
    define ('hostnameorservername',"localhost");	 // Server Name or host Name 
    define ('serverusername','root'); // database Username 
    define ('serverpassword',''); // database Password 
    define ('databasename','svl_certificate'); // database Name 
    
    $project = "SVL Automations E-Certificates";
    $smallproject = "SVL Automations";
    $slogan = "Smart-Versatile-Limitless";
    $officename = "Kolhapur";
    $officename1 = "SVL";
    global $connection;
    $connection = @mysqli_connect(hostnameorservername,serverusername,serverpassword,databasename) or die('Connection could not be made to the SQL Server. Please contact report this system error at <font color="blue">88 5335 4141</font>');
   

?>
