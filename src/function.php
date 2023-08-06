<?php
function dd($var){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}
function checkValidate($username,$email,$name,$password){
    $erorr=[];
    if(filter_var($email, FILTER_VALIDATE_EMAIL)==false){
        $erorr['email']='email format is incorrect';
    };
    if(preg_match('/^([A-Za-z0-9_]){3,32}$/', $username)==0){
        $erorr['username']='username format is incorrect';
    }
    if(preg_match('/^([A-Za-z\s]){3,32}$/', $name)==0){
        $erorr['name']='name format is incorrect';
    }
    if(preg_match('/^([A-Za-z0-9#?!@$%^&*-]){4,32}$/', $password)==0){
        $erorr['password']='password format is incorrect';
    }
    return $erorr;
}
function checkValidateLogin($username,$password){
    $erorr=[];
    if(preg_match('/^([A-Za-z0-9_]){3,32}$/', $username)==0){
        $erorr['username']='username format is incorrect';
    };
    if(preg_match('/^([A-Za-z0-9#?!@$%^&*-]){4,32}$/', $password)==0){
        $erorr['password']='password format is incorrect';
    }
    return $erorr;
}


















function uploadimg(){
$target_dir = "uploads/";
$target_file = $target_dir .time(). basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check file size
if ($_FILES["file"]["size"] > 200000) {
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 1) {
 move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
return $target_file;
}else{
    return false ;
}}
