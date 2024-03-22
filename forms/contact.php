<?php
 
  include "../mail/mail.php";
  
  // $receiving_email_address = 'info@svlautomations.in';
  $receiving_email_address = 'shdinde@gmail.com'; 

    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $subject = "Message from ".$name." on web";
    


  if(mailsend($email,$message, $subject,$name)=='Success'){
    echo "OK";
}
else{
  die( 'Unable to send email. Please, try again!!!');
}



  
?>
