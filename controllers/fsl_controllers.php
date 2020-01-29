<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';   
/*
*
*   Sample controller functions showcased on FSL Launch Page
*
*/

function process_time(){
   $time = number_format( microtime(true) - LIM_START_MICROTIME, 6);
  //return("<BR>Controller Function Called: " .  option('routecallback') . "<BR>Request processed in $time seconds");
  return $time;
}

  function hello_world()
  {
     $arr = array('Error' => "You must provide a valid endpoint.");
   // status(202); //returns HTTP status code of 202
    status(500); //returns HTTP status code of 202
    return json($arr);
  }

/*
*
* create jwt token
* key
* id
*
*/

  function jwt()
  {
    
     if ((isset($_POST['key'])) && (!empty($_POST['key']))) {

        $key = $_POST['key'];
    } else {
           $arr = array('Error' => "No key provided.");
    status(500); //returns HTTP status code of 202
    return json($arr); 
     }
    
         if ((isset($_POST['id'])) && (!empty($_POST['id']))) {

        $id = $_POST['id'];
    } else {
           $arr = array('Error' => "No ID provided.");
    status(500); //returns HTTP status code of 202
    return json($arr); 
     }
    
   $token = array();
   $token['id'] = "$id";
   $token['iat'] = time();
   $testjwt =  fsl_jwt_encode($token, "$key");
   $jwtdecode = fsl_jwt_decode($testjwt,"$key");
   $time = process_time(); 
   $arr = array('Response' => array('Status' => "Success", 'JWT' => $testjwt, 'ID' => $id, 'Processing Time' => $time));
        status(200); //returns HTTP status code of 202
    return json($arr);
  //  return html("Token To Encode: " . $token['id'] ." <BR>JWT: $testjwt<BR>Decoded JWT: " . $jwtdecode->id . "<BR>". process_time());
  }




/*
*
* send mail COMMMAND
* server
* user
* pass
* port
* from
* to
* subject
* body
*
*/

  function mailer()
  {
           if ((isset($_POST['server'])) 
             && (!empty($_POST['server'])) 
             && (isset($_POST['user'])) 
             && (!empty($_POST['user'])) 
             && (isset($_POST['pass'])) 
             && (!empty($_POST['pass']))
             && (isset($_POST['port'])) 
             && (!empty($_POST['port'])) 
             && (isset($_POST['subject'])) 
             && (!empty($_POST['subject'])) 
             && (isset($_POST['body'])) 
             && (!empty($_POST['body']))             
             && (isset($_POST['from_name'])) 
             && (!empty($_POST['from_name'])) 
               && (isset($_POST['from'])) 
             && (!empty($_POST['from']))             
             && (isset($_POST['to'])) 
             && (!empty($_POST['to']))) {

        $server = $_POST['server'];
                   $user = $_POST['user'];
                   $pass = $_POST['pass'];
                  
    } else {
           $arr = array('Error' => "server, user, pass and command variables are all mandatory.");
    status(500); //returns HTTP status code of 202
    return json($arr); 
     }
    
 
    //PHPMailer Object
$mail = new PHPMailer;
    //Enable SMTP debugging. 
//$mail->SMTPDebug = 3;    
    $mail->SMTPDebug = false;
//Set PHPMailer to use SMTP.
$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = $_POST['server'];
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;                          
//Provide username and password     
$mail->Username = $_POST['user'];                 
$mail->Password = $_POST['pass'];                           
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";                           
//Set TCP port to connect to 
$mail->Port = $_POST['port']; //default to 587 if not provided
//From email address and name5
$mail->From = $_POST['from']; 
$mail->FromName = $_POST['from_name'];

//To address and name
//$mail->addAddress("recepient1@example.com", "Recepient Name");
$mail->addAddress($_POST['to']); //Recipient name is optional

//Address to which recipient will reply
//$mail->addReplyTo("reply@yesllc.io", "Reply");

//CC and BCC
//$mail->addCC("cc@example.com");
//$mail->addBCC("bcc@example.com");

//Send HTML or Plain Text email
$mail->isHTML(false);

$mail->Subject = $_POST['subject']; ;
$mail->Body = $_POST['body']; ;
//$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    $arr = array('Error' => $mail->ErrorInfo);
    status(401); //returns HTTP status code of 202
    return json($arr); 
} 
else 
{
           
   $time = process_time(); 
   $arr = array('Response' => array('Status' => "Message send successfully.", 'Processing Time' => $time));
    status(200); //returns HTTP status code of 202
    return json($arr); 
}
   
 
  }





/*
*
* RUN SSH COMMMAND
* server
* user
* pass
* command
*
*/

  function ssh()
  {
         if ((isset($_POST['server'])) && (!empty($_POST['server'])) && (isset($_POST['user'])) && (!empty($_POST['user'])) && (isset($_POST['pass'])) && (!empty($_POST['pass'])) && (isset($_POST['command'])) && (!empty($_POST['command']))) {

        $server = $_POST['server'];
                   $user = $_POST['user'];
                   $pass = $_POST['pass'];
                   $command = $_POST['command'];
    } else {
           $arr = array('Error' => "server, user, pass and command variables are all mandatory.");
    status(500); //returns HTTP status code of 202
    return json($arr); 
     }
    
    
   
    $ssh = new Net_SSH2($server);
     if (!$ssh->login($user, $pass)) {
                    $arr = array('Error' => "Login failed. Check server or credentials.");
    status(401); //returns HTTP status code of 202
    return json($arr); 
     }
 
     $result =  $ssh->exec($command);
   $time = process_time(); 
   $arr = array('Response' => array('Status' => "Success", 'Response' => $result, 'Processing Time' => $time));
  //  $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
   // status(202); //returns HTTP status code of 202
    status(200); //returns HTTP status code of 202
    return json($arr);
  }

/*
*
* RUN sftp COMMMAND
* server
* user
* pass
* command
* filename
* data
*
*/

  function sftp()
  {
         if ((isset($_POST['server'])) && (!empty($_POST['server'])) && (isset($_POST['user'])) && (!empty($_POST['user'])) && (isset($_POST['pass'])) && (!empty($_POST['pass'])) ) {

        $server = $_POST['server'];
                   $user = $_POST['user'];
                   $pass = $_POST['pass'];
                   $command = $_POST['command'];
    } else {
           $arr = array('Error' => "server, user, pass  variables are all mandatory.");
    status(500); //returns HTTP status code of 202
    return json($arr); 
     }
    
    if  (empty(params('command')))  {
                    $arr = array('Error' => "Missing a transfer command (get, put, etc.)");
    status(401); //returns HTTP status code of 202
    return json($arr); 
}
    
    
   
  $sftp = new NET_SFTP($server);
if (!$sftp->login($user, $pass )) {
                    $arr = array('Error' => "Login failed. Check server or credentials.");
    status(401); //returns HTTP status code of 202
    return json($arr); 
}

// puts a three-byte file named filename.remote on the SFTP server
//$result = $sftp->put('filename.remote', 'xxx');
     
     if ((isset($_POST['dir'])) && (!empty($_POST['dir'])))
           $sftp->chdir($_POST['dir']); 
    
    $result =   $sftp->pwd();
    if (params('command') == 'put'){
          $result =   $sftp->put($_POST['filename'], $_POST['data']);
    }
    elseif (params('command') == 'get') {
          $result =    $sftp->get($_POST['filename']);
    }
    else{
        $arr = array('Error' => "invalid command " . params('command'));
    status(500); //returns HTTP status code of 202
    return json($arr); 
    }
 //  print_r($sftp->nlist());
// puts an x-byte file named filename.remote on the SFTP server,
// where x is the size of filename.local
//$sftp->put('filename.remote', 'filename.local', SFTP::SOURCE_LOCAL_FILE);
    
     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
   $time = process_time(); 
   $arr = array('Response' => array('Status' => "Success", 'Response' => $result, 'Processing Time' => $time));
  //  $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
   // status(202); //returns HTTP status code of 202
    status(200); //returns HTTP status code of 202
    return json($arr);
  }



/*
*
* This is an example on how to make a RESTful JSON Response and Set A Status Code
*
*/

  function api()
  {
    $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
   // status(202); //returns HTTP status code of 202
    status(202); //returns HTTP status code of 202
    return json($arr);
  }


function showip()
  {
    $ip = $_SERVER['REMOTE_ADDR'];
    set_or_default('name', params('who'), "everybody");

    //session data example

    $session = (empty(fsl_session_check('crop'))) ? "No session exists" : fsl_session_check('crop');

    //Encryption Example
    $estring = fsl_encrypt($session);
    $dstring = fsl_decrypt($estring);
    
    
  
    return html("Your IP is $ip.<BR>Your session data: $session. <BR>Session Data encrypted.<BR>Encrypt session data: $estring<BR>Session Data decrypted.<BR>Decrypted session data: $dstring <BR>" . process_time());
  } 

 function kill_session()
  {
    set_or_default('name', params('who'), "everybody");
    session_destroy();
    return html("Session Is Destroyed.<BR>" . process_time());
  }

 function welcome()
  {
    set_or_default('name', params('name'), "everybody");    
    return html("html_welcome");
  }

 function are_you_ok($name = null)
  {
    if(is_null($name))
    {
      $name = params('name');
      if(empty($name)) halt(NOT_FOUND, "Undefined name.");

    }
    set('name', $name);
    return html("Are you ok $name ?<BR>". process_time());
  }

 function how_are_you()
  {
    $name = params('name');
    if(empty($name)) halt(NOT_FOUND, "Undefined name.");
    # you can call an other controller function if you want
    if(strlen($name) < 4) return are_you_ok($name);
    set('name', $name);
    return html("I hope you are fine, $name.<BR>". process_time());
  }

 function image_show()
  {
    $ext = file_extension(params('name'));
    $filename = option('public_dir')."/".basename(params('name'), ".$ext");
    if(params('size') == 'thumb') $filename .= ".thb";
    $filename .= '.jpg';
 
    if(!file_exists($filename)) halt(NOT_FOUND, "$filename doesn't exists");
    render_file($filename);
  }

 function image_show_jpeg_only()
  {
    $ext = file_extension(params(0));
    $filename = option('public_dir').params(0);
    if(params('size') == 'thumb') $filename .= ".thb";
    $filename .= '.jpg';
  
    if(!file_exists($filename)) halt(NOT_FOUND, "$filename doesn't exists");
    render_file($filename);
  }

?>