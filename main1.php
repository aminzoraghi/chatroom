<?php
require_once './src/function.php';
require_once 'config.php';

session_start();
session_regenerate_id();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if ($connectType == 'json') {
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
if (@$_POST['upload']) {
    $message = uploadimg();
    if ($message) {
         //seen check
    try{
        $sql='SELECT * FROM messages ORDER BY id DESC LIMIT 1';
        $stmt=$pdo->prepare($sql);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result['user_id']!==$userid){
            $sql='UPDATE messages SET is_seen=1';
            $stmt=$pdo->prepare($sql);
            $stmt->execute();
        }
    }catch(PDOException $e){
        echo" connection error: ".$e->getMessage();
    };
    try{
        $is_text=0;
        $sql='INSERT INTO messages (message,user_id,is_text) VALUES (:message,:userid,:is_text)';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":message",$message);
        $stmt->bindParam(":userid",$userid);
        $stmt->bindParam(":is_text",$is_text);
        $stmt->execute();
        header("location:main1.php");
        }catch(PDOException $e){
            echo" connection error: ".$e->getMessage();
    
        }
    } else {
        echo "file is must be image and less than 2 mb";
    }
}

//new massage
if (@$_POST['submit']) {
    //seen check
    try{
        $sql='SELECT * FROM messages ORDER BY id DESC LIMIT 1';
        $stmt=$pdo->prepare($sql);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result['user_id']!==$userid){
            $sql='UPDATE messages SET is_seen=1';
            $stmt=$pdo->prepare($sql);
            $stmt->execute();
        }
    }catch(PDOException $e){
        echo" connection error: ".$e->getMessage();
    }

   $message = $_POST['message'];

 if (strlen($message) < 100) {
    try{
    $sql='INSERT INTO messages (message,user_id) VALUES (:message,:userid)';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(":message",$message);
    $stmt->bindParam(":userid",$userid);
    $stmt->execute();
    header("location:main1.php");
    }catch(PDOException $e){
        echo" connection error: ".$e->getMessage();

    }
}else{
    echo "length must be less than 100";
        }
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
try {
    $sql = "SELECT 
m.id AS messageId,
m.is_text AS type,
m.created_time AS time,
m.message,
m.is_seen AS is_read,
u.username AS username,
u.id AS userid,
u.image_profile AS img,
u.status
FROM messages m
JOIN users u 
ON m.user_id = u.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // dd($data);
} catch (PDOException $e) {
    echo "connection error: " . $e->getMessage();
}

//load user status for admin action
try {
    $sql = 'SELECT * FROM users WHERE id=:userid';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":userid", $userid);
    $stmt->execute();
    $userdata = $stmt->fetch(PDO::FETCH_ASSOC);
    // //status and role;
    $is_admin = $userdata['is_admin'] == 0 ? false : true;
    $is_active = $userdata['status'] == 1 ? true : false;
} catch (PDOException $e) {
    echo "connection error: " . $e->getMessage();
}

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

        <p>welcome to chat <?php echo $userdata['name']; ?></p>
        <div class="chat-container">
            <?php foreach ($data as $item) {
                if ($item['userid'] == $userid) { ?>
                    <div class="container">
                        <img src="<?php echo $item['img']; ?>" alt="Avatar">
                        <p class="para"><?php
                                        if ($item['type'] == 1) {
                                            echo htmlspecialchars($item['message']); ?></p><?php } else { ?>
                        <p class="para"><img src="<?php echo $item['message']; ?>"></p><?php

                                                                                    } ?>
                    <span class="time-right"><?php if ($item['is_read']) {
                                                    echo 'seen';
                                                }
                                                echo $item['time']; ?></span>
                    <form action="" method="post">
                        <input type="hidden" name="messageid" value="<?php echo $item['messageId']; ?>">
                        <input type="submit" name="delete" value="delete">
                    </form>
                    <?php if ($item['type'] == 1) { ?>
                        <form action="" method="post">
                            <input type="text" name="message">
                            <input type="hidden" name="messageid" value="<?php echo $item['messageId']; ?>">
                            <input type="submit" name="edit" value="edit">

                        </form><?php } ?>
                    </div>

                <?php } else { ?>
                    <div class="container darker">
                        <img class="right" src="<?php echo $item['img']; ?>" alt="Avatar">
                        <p><?php if ($item['type'] == 1) {
                                echo htmlspecialchars($item['message']); ?></p><?php } else { ?>
                        <p class="para"><img src="<?php echo $item['message']; ?>"></p><?php

                                                                                    } ?>
                    <span class="time-left"><?php echo $item['time']; ?></span><br>
                    <?php if ($is_admin) { ?>
                        <form action="" method="post">
                            <input type="hidden" name="messageid" value="<?php echo $item['messageId']; ?>">
                            <input type="submit" name="delete" value="delete">
                        </form>
                        <form action="" method="post">
                            <input type="hidden" name="userid" value="<?php echo $item['userid']; ?>">
                            <input type="submit" name="block" value="<?php if ($item['status']) {
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
                <?php if($is_active){ ?>
                <input type="submit" name="upload" value="upload">
                <?php } ?>
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