<?php

session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
if(@$_POST['submit']){
$data = file_get_contents("./data/message.json");
$data = json_decode($data, true);
    $number=count($data)+1;
    $message=$_POST['message'];
    $userid= $_SESSION['id'];
    $username=$_SESSION['username'];
    $data[]=['messageId'=>$number, 'message' =>$message,'userid'=>$userid,'username'=>$username,'time'=>time()];
    $data = json_encode($data);
    file_put_contents('./data/message.json', $data); 
    header("location:main.php");
};
$data = file_get_contents("./data/message.json");
$data = json_decode($data, true);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>



    <div class="container w-40">

        <!-- Page header start -->
        <div class="page-title">
            <div class="row gutters">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <h5 class="title">Chat App</h5>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"> </div>
            </div>
        </div>
        <!-- Page header end -->

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row gutters w-100">

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                    <div class="card m-0">

                        <!-- Row start
                        <div class="row no-gutters">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3">
                                <div class="users-container">
                                    <div class="chat-search-box">
                                        <div class="input-group">
                                            <input class="form-control" placeholder="Search">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="users">
                                        <li class="person" data-chat="person1">
                                            <div class="user">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                                <span class="status busy"></span>
                                            </div>
                                            <p class="name-time">
                                                <span class="name">Steve Bangalter</span>
                                                <span class="time">15/02/2019</span>
                                            </p>
                                        </li>
                                        <li class="person" data-chat="person1">
                                            <div class="user">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar1.png" alt="Retail Admin">
                                                <span class="status offline"></span>
                                            </div>
                                            <p class="name-time">
                                                <span class="name">Steve Bangalter</span>
                                                <span class="time">15/02/2019</span>
                                            </p>
                                        </li>
                                        <li class="person active-user" data-chat="person2">
                                            <div class="user">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar2.png" alt="Retail Admin">
                                                <span class="status away"></span>
                                            </div>
                                            <p class="name-time">
                                                <span class="name">Peter Gregor</span>
                                                <span class="time">12/02/2019</span>
                                            </p>
                                        </li>
                                        <li class="person" data-chat="person3">
                                            <div class="user">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                                <span class="status busy"></span>
                                            </div>
                                            <p class="name-time">
                                                <span class="name">Jessica Larson</span>
                                                <span class="time">11/02/2019</span>
                                            </p>
                                        </li>
                                        <li class="person" data-chat="person4">
                                            <div class="user">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar4.png" alt="Retail Admin">
                                                <span class="status offline"></span>
                                            </div>
                                            <p class="name-time">
                                                <span class="name">Lisa Guerrero</span>
                                                <span class="time">08/02/2019</span>
                                            </p>
                                        </li>
                                        <li class="person" data-chat="person5">
                                            <div class="user">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar5.png" alt="Retail Admin">
                                                <span class="status away"></span>
                                            </div>
                                            <p class="name-time">
                                                <span class="name">Michael Jordan</span>
                                                <span class="time">05/02/2019</span>
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div> -->
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
                                <div class="selected-user">
                                    <span>To: <span class="name">Emily Russell</span></span>
                                </div>
                                <div class="chat-container">
                                    <ul class="chat-box chatContainerScroll">
                                        <li class="chat-left">
                                            <div class="chat-avatar">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                                <div class="chat-name">Russell</div>
                                            </div>
                                            <div class="chat-text">Hello, I'm Russell.
                                                <br>How can I help you today?
                                            </div>
                                            <div class="chat-hour">08:55 <span class="fa fa-check-circle"></span></div>
                                        </li>
                                        <li class="chat-right">
                                            <div class="chat-hour">08:56 <span class="fa fa-check-circle"></span></div>
                                            <div class="chat-text">Hi, Russell
                                                <br> I need more information about Developer Plan.
                                            </div>
                                            <div class="chat-avatar">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                                <div class="chat-name">Sam</div>
                                            </div>
                                        </li>
                                        <li class="chat-left">
                                            <div class="chat-avatar">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                                <div class="chat-name">Russell</div>
                                            </div>
                                            <div class="chat-text">Are we meeting today?
                                                <br>Project has been already finished and I have results to show you.
                                            </div>
                                            <div class="chat-hour">08:57 <span class="fa fa-check-circle"></span></div>
                                        </li>
                                        <li class="chat-right">
                                            <div class="chat-hour">08:59 <span class="fa fa-check-circle"></span></div>
                                            <div class="chat-text">Well I am not sure.
                                                <br>I have results to show you.
                                            </div>
                                            <div class="chat-avatar">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar5.png" alt="Retail Admin">
                                                <div class="chat-name">Joyse</div>
                                            </div>
                                        </li>
                                        <li class="chat-left">
                                            <div class="chat-avatar">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                                <div class="chat-name">Russell</div>
                                            </div>
                                            <div class="chat-text">The rest of the team is not here yet.
                                                <br>Maybe in an hour or so?
                                            </div>
                                            <div class="chat-hour">08:57 <span class="fa fa-check-circle"></span></div>
                                        </li>
                                        <li class="chat-right">
                                            <div class="chat-hour">08:59 <span class="fa fa-check-circle"></span></div>
                                            <div class="chat-text">Have you faced any problems at the last phase of the project?</div>
                                            <div class="chat-avatar">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar4.png" alt="Retail Admin">
                                                <div class="chat-name">Jin</div>
                                            </div>
                                        </li>
                                        <li class="chat-left">
                                            <div class="chat-avatar">
                                                <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                                <div class="chat-name">Russell</div>
                                            </div>
                                            <div class="chat-text">Actually everything was fine.
                                                <br>I'm very excited to show this to our team.
                                            </div>
                                            <div class="chat-hour">07:00 <span class="fa fa-check-circle"></span></div>
                                        </li>
                                    </ul>
                                    <div class="form-group mt-3 mb-0">
                                    <form action="" method="POST">
    <input type="text" name="message" required>
    <input type="submit" name="submit" value="send">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Row end -->
                    </div>

                </div>

            </div>
            <!-- Row end -->

        </div>
        <!-- Content wrapper end -->

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>