<?php

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