<?php
require "./src/function.php";
session_start();
session_regenerate_id();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$id = $_SESSION['id'];
if (@$_POST['submit']) {
    $submit = $_POST['submit'];
    $data = file_get_contents("./data/user.json");
    $data = json_decode($data, true);
    switch ($submit) {
        case 'newname':
            $data[$id]['name'] = $_POST['name'];
            if($_POST['name']){
            file_put_contents("./data/user.json", json_encode($data,JSON_PRETTY_PRINT));}else{
                echo 'enter the new name';
            };
            break;
        case 'newbio':
            $data[$id]['bio'] = $_POST['bio'];
            file_put_contents("./data/user.json", json_encode($data,JSON_PRETTY_PRINT));
            break;
        case 'upload':
            if (@uploadimg()) {
                $data[$id]['img'] = uploadimg();
                file_put_contents("./data/user.json", json_encode($data,JSON_PRETTY_PRINT));
            } else {
                echo 'upload faild';
            }

            break;
    }
}
//load data user
$userdata = file_get_contents("./data/user.json");
$userdata = json_decode($userdata, true);
?>
<!DOCTYPE html>
<html>
<head>
    <title>صفحه پروفایل</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        .profile-container {
            width: 300px;
            margin: 0 auto;
            margin-top:20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto;
            background-color: #ccc;
        }
        .pic{
            width: 150px;
            height: 150px;
            border-radius: 50%;

        }

        .profile-name {
            text-align: center;
            margin-top: 30px;
            font-size: 20px;
        }

        .profile-username {
            text-align: center;
            margin-top: 5px;
        }

        .profile-email {
            text-align: center;
            margin-top: 5px;
        }

        .profile-status {
            text-align: center;
            margin-top: 5px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>صفحه پروفایل</h1>
    <div class="profile-container">
        <div class="profile-picture">
            <img class="pic" src="<?php echo $userdata[$id]['img'];?>" alt="عکس کاربر"><br>
        </div>
        <h2 class="profile-name"><?php echo htmlspecialchars($userdata[$id]['name']);?></h2>
        <p class="profile-username"><?php echo htmlspecialchars($userdata[$id]['username']);?></p>
        <p class="profile-email"><?php echo htmlspecialchars($userdata[$id]['email']);?></p>
        <p class="profile-status"><?php if($userdata[$id]['status']){echo 'active';}else{echo 'block';}?></p>
        <p class="profile-email"><?php echo htmlspecialchars($userdata[$id]['bio']);?></p>
        <p class="profile-email"><?php echo date('Y-M-D h:i:s',$userdata[$id]['time']);?></p>
        <p class="profile-status"><?php if($userdata[$id]['admin']){echo 'you are admin';}else{echo 'you are member';}?></p>
    </div>
    <div class="profile-container">
    <form action="" method="post">
        <input type="text" name="name" placeholder="enter your new name" required>
        <input type="submit" name="submit" value="newname">
    </form>
    <form action="" method="post">
        <input type="text" name="bio" placeholder="enter your bio">
        <input type="submit" name="submit" value="newbio">
    </form>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file"  placeholder="enter your new image" required>
        <input type="submit" name="submit" value="upload">
    </form>
    <form action="" method="post">
        <input type="text" name="friend" required placeholder="enter your username">
        <input type="submit" name="submit" value="addfriend">
    </form>
    </div>
    
    <div class="profile-container">
    <button><a href="logout.php">log out</button><button><a href="main.php">chat room</button>
    </div>
    <script src="https://kit.fontawesome.com/9b6267f4dd.js" crossorigin="anonymous"></script>
 
</body>
</html>
