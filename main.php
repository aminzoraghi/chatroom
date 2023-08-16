<?php
require_once './src/function.php';
require_once 'config.php';

session_start();
session_regenerate_id();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if($connectType=='mysql') {
    header("Location: logout.php");
    exit;

}

$userid = $_SESSION['id'];
$username = $_SESSION['username'];
//block and unblock user
if (@$_POST['block']) {
    $id = $_POST['userid'];
    $userdata = file_get_contents("./data/user.json");
    $userdata = json_decode($userdata, true);
    if ($_POST['block'] == 'block') {
        $userdata[$id]['status'] = false;
    } else {
        $userdata[$id]['status'] = true;
    };
    $userdata = json_encode($userdata, JSON_PRETTY_PRINT);
    file_put_contents('./data/user.json', $userdata);
    header("location:main.php");
}
//image uoload
if(@$_POST['upload']){
    $message=uploadimg();
    if($message){
        $data = file_get_contents("./data/message.json");
        $data = json_decode($data, true);
        $number = array_search(end($data), $data) + 1;
        //seen check
        if ($data[$number - 1]['userid'] !== $userid) {
            foreach ($data as $key => $item) {
                $item['read'] = true;
                $data[$key] = $item;
            };
        };
        $data[$number] = ['messageId' => $number, 'message' => $message,'type'=>'img', 'userid' => $userid, 'username' => $username, 'time' => time(), 'read' => false];
        $data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('./data/message.json', $data);
        header("location:main.php");

    }else{
        echo "file is must be image and less than 2 mb";
    }

}

//new massage
if (@$_POST['submit']) {
    $data = file_get_contents("./data/message.json");
    $data = json_decode($data, true);
    $number = array_search(end($data), $data) + 1;
    //seen check
    if ($data[$number - 1]['userid'] !== $userid) {
        foreach ($data as $key => $item) {
            $item['read'] = true;
            $data[$key] = $item;
        };
    };
    $message = $_POST['message'];

    if(strlen($message)<100){
    $data[$number] = ['messageId' => $number, 'message' => $message,'type'=>'text', 'userid' => $userid, 'username' => $username, 'time' => time(), 'read' => false];
    $data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('./data/message.json', $data);}else{
        echo "length must be less than 100";
    }
    //header("location:main.php");
};
//delete
if (@$_POST['delete']) {
    $data = file_get_contents("./data/message.json");
    $data = json_decode($data, true);
    unset($data[$_POST["messageid"]]);
    $data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('./data/message.json', $data);
    header("location:main.php");
}
//edit
if (@$_POST['edit']) {
    $data = file_get_contents("./data/message.json");
    $data = json_decode($data, true);
    $data[$_POST["messageid"]]["message"] = $_POST["message"];
    $data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('./data/message.json', $data);
    header("location:main.php");
}
//load message
$data = file_get_contents("./data/message.json");
$data = json_decode($data, true);
//load user status for admin action
$userdata = file_get_contents("./data/user.json");
$userdata = json_decode($userdata, true);
//status and role;
$is_admin = $userdata[$userid]['admin'];
$is_active =$userdata[$userid]['status'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./assets/main.css" rel="stylesheet">
</head>

<body>
    <div class="box">
         <button><a href="logout.php">log out</a></button><button><a href="profile.php">profile</a></button>
         
         <p>welcome to chat <?php echo $userdata[$userid]['name'];?></p>
    <div class="chat-container">
        <?php foreach ($data as $item) {
            if ($item['userid'] == $userid) { ?>
                <div class="container">
                    <img src="<?php echo $userdata[$item['userid']]['img']; ?>" alt="Avatar">
                    <p class="para"><?php 
                    if($item['type']=='text'){
                    echo htmlspecialchars($item['message']); ?></p><?php }else{?>
                    <p class="para"><img src="<?php echo $item['message'];?>"></p><?php

                    }?>
                    <span class="time-right"><?php if ($item['read']) {
                                                    echo 'seen';
                                                }
                                                echo date('Y-M-D h:i:s', $item['time']); ?></span>
                    <form action="" method="post">
                        <input type="hidden" name="messageid" value="<?php echo $item['messageId']; ?>">
                        <input type="submit" name="delete" value="delete">
                    </form>
                     <?php if($item['type']=='text'){?>
                    <form action="" method="post">
                        <input type="text" name="message">
                        <input type="hidden" name="messageid" value="<?php echo $item['messageId']; ?>">
                        <input type="submit" name="edit" value="edit">

                    </form><?php }?>
                </div>

            <?php } else { ?>
                <div class="container darker">
                    <img class="right" src="<?php echo $userdata[$item['userid']]['img']; ?>" alt="Avatar">
                    <p><?php if($item['type']=='text'){
                    echo htmlspecialchars($item['message']); ?></p><?php }else{?>
                    <p class="para"><img src="<?php echo $item['message'];?>"></p><?php

                    }?>
                    <span class="time-left"><?php echo date('Y-M-D h:i:s', $item['time']); ?></span><br>
                    <?php if ($is_admin) { ?>
                        <form action="" method="post">
                            <input type="hidden" name="messageid" value="<?php echo $item['messageId']; ?>">
                            <input type="submit" name="delete" value="delete">
                        </form>
                        <form action="" method="post">
                            <input type="hidden" name="userid" value="<?php echo $item['userid']; ?>">
                            <input type="submit" name="block" value="<?php if ($userdata[$item['userid']]['status']) {
                                                                            echo "block";
                                                                        } else {
                                                                            echo "unblock";
                                                                        } ?>">
                        </form>


                    <?php } ?>
                </div>
        <?php  }
        } ?>
</div>

        <div class="send">
            <form action="" method="POST">
                <input id="inputField" class="message" type="text" name="message" required>
                <?php if ($is_active) { ?>
                    <input class="submit" id="submit" type="submit" name="submit" value="send">
                <?php } ?>
                <div id="charactersRemaining"></div>

            </form>
            <form action="" method="post" enctype="multipart/form-data">
            <label for="fileUpload"><i class="fa fa-upload"></i></label>
            <input type="file" id="fileUpload" name="file" required style="display: none;">
            <input type="submit" name="upload" value="upload">
                </form>
             </div>
       
    </div>
    <script src="https://kit.fontawesome.com/9b6267f4dd.js" crossorigin="anonymous"></script>
    <script>
// JavaScript
const inputField = document.getElementById('inputField');
const charactersRemaining = document.getElementById('charactersRemaining');
const maxCharacters = 100;
const form = document.getElementById('submit');

inputField.addEventListener('input', function() {
    const inputValue = inputField.value;
    const remainingCharacters = maxCharacters - inputValue.length;
    
    charactersRemaining.textContent = "باقی مانده: " + remainingCharacters + " کاراکتر";
    
    if (inputValue.length > maxCharacters) {
       // alert('تعداد کاراکترها بیشتر از حد مجاز است!');
        form.disabled = true;
    } else {
        form.disabled = false;
    }
});
</script>
</body>

</html>