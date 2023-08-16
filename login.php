<?php
require_once './src/function.php';
require_once 'config.php';
session_start();
session_regenerate_id();
if (@$_POST['submit']) {

    $type = $_POST['submit'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $vpassword = $_POST['vpassword'] ?? '';
    $email = $_POST['email'] ?? "";
    $name = $_POST['name'] ?? '';
    if ($connectType == 'json') {

        $data = file_get_contents("./data/user.json");
        $data = json_decode($data, true);
        $number = array_search(end($data), $data) + 1;
        $n = 0;
        if ($type == 'register') {
            $erorr = checkValidate($username, $email, $name, $password);

            if ($erorr) {
                $n++;
                foreach ($erorr as $key => $item) {
                    echo "$key:$item </br>";
                }
            }
            if ($password == $vpassword) {
                foreach ($data as $item) {
                    if ($item['username'] == $username or $item['email'] == $email) {
                        echo "username is valid";
                        $n++;
                    }
                }
            } else {
                echo "password incorrect";
                $n++;
            }
            if ($n == 0) {
                $data[$number] = [
                    'username' => $username, 'password' => md5($password), 'email' => $email,
                    'name' => $name, 'id' => $number, 'admin' => false, 'status' => true, 'img' => "https://randomuser.me/api/portraits/women/$number.jpg", 'bio' => '', 'time' => time()
                ];
                $data = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents('./data/user.json', $data);
                echo "register is Successed";
            }
        } else {
            $erorr = checkValidateLogin($username, $password);
            if ($erorr) {
                foreach ($erorr as $key => $item) {
                    echo "$key:$item </br>";
                }
            } else {
                foreach ($data as $key => $item) {
                    if ($item['username'] == $username && $item['password'] == md5($password)) {
                        $_SESSION['login'] = true;
                        $_SESSION['username'] = $item['username'];
                        $_SESSION['id'] = $item['id'];
                        header("location:main.php");
                    }
                };
                echo "invalid input data";
            }
        }
    } else {
        $n = 0;
        if ($type == 'register') {
            $erorr = checkValidate($username, $email, $name, $password);

            if ($erorr) {
                $n++;
                foreach ($erorr as $key => $item) {
                    echo "$key:$item </br>";
                }
            }
            if ($password == $vpassword) {
                try {
                    $sql = 'SELECT * FROM users WHERE username=:username OR email=:email';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":email", $email);
                    $stmt->execute();
                    $result = $stmt->fetchall(PDO::FETCH_ASSOC);
                    if (count($result) > 0) {
                        $n++;
                        echo "username or email is valid";
                    }
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            } else {
                echo "password incorrect";
                $n++;
            }
            if ($n == 0) {
                try {
                    $number=rand(1,100);
                    $imageurl="https://randomuser.me/api/portraits/women/$number.jpg";
                    $sql = "INSERT INTO users (username,name,email,password,image_profile) VALUES (:username, :name, :email, :password,:image_profile)";
                    $stmt = $pdo->prepare($sql);
                    $password = md5($password);
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":name", $name);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":password", $password);
                    $stmt->bindParam(":image_profile", $imageurl);
                    $stmt->execute();
                    echo "register is Successed";

                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            }
        } else {
            $erorr = checkValidateLogin($username, $password);
            if ($erorr) {
                foreach ($erorr as $key => $item) {
                    echo "$key:$item </br>";
                }
            } else {
                try {
                    $password = md5($password);
                    $sql = 'SELECT * FROM users WHERE username=:username AND password=:password';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":password", $password);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $_SESSION['login'] = true;
                        $_SESSION['username'] = $result['username'];
                        $_SESSION['id'] = $result['id'];
                        header("location:main1.php");
                    } else {
                        echo "invalid input data";
                    }
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
    <style>
        .container {
            width: 500px;
            margin-top: 60px;
        }
    </style>
</head>

<body class="container">


    <!-- Pills navs -->
    <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="tab-login" data-mdb-toggle="pill" href="#pills-login" role="tab" aria-controls="pills-login" aria-selected="true">Login</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-register" data-mdb-toggle="pill" href="#pills-register" role="tab" aria-controls="pills-register" aria-selected="false">Register</a>
        </li>
    </ul>
    <!-- Pills navs -->

    <!-- Pills content -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
            <form action="" method="POST">
                <div class="text-center mb-3">
                    <p>Sign in with:</p>
                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-facebook-f"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-google"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-twitter"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-github"></i>
                    </button>
                </div>

                <p class="text-center">or:</p>

                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input type="text" id="loginName" class="form-control" name="username" required />
                    <label class="form-label" for="loginName">username</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="loginPassword" class="form-control" name="password" required />
                    <label class="form-label" for="loginPassword">Password</label>
                </div>

                <!-- 2 column grid layout -->
                <div class="row mb-4">
                    <div class="col-md-6 d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-check mb-3 mb-md-0">
                            <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                            <label class="form-check-label" for="loginCheck"> Remember me </label>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                        <!-- Simple link -->
                        <a href="#!">Forgot password?</a>
                    </div>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4" name="submit" value="login">login</button>

                <!-- Register buttons -->
            </form>
        </div>
        <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
            <form action="" method="POST">
                <div class="text-center mb-3">
                    <p>Sign up with:</p>
                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-facebook-f"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-google"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-twitter"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-github"></i>
                    </button>
                </div>

                <p class="text-center">or:</p>

                <!-- Username input -->
                <div class="form-outline mb-4">
                    <input type="text" id="registerUsername" class="form-control" name="username" required pattern="^([A-Za-z0-9_]){3,32}$" />
                    <label class="form-label" for="registerUsername">Username</label>
                </div>
                <!-- email input -->
                <div class="form-outline mb-4">
                    <input type="email" id="registeremail" class="form-control" name="email" required pattern="^[a-zA-Z0-9_\-\.%\+]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$" />
                    <label class="form-label" for="registeremail">email</label>
                </div>
                <!-- name input -->
                <div class="form-outline mb-4">
                    <input type="text" id="registername" class="form-control" name="name" required pattern="^([A-Za-z\s]){3,32}$" />
                    <label class="form-label" for="registername">name</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="registerPassword" class="form-control" name="password" required pattern="^([A-Za-z0-9#?!@$%^&*-]){4,32}$" />
                    <label class="form-label" for="registerPassword">Password</label>
                </div>

                <!-- Repeat Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="registerRepeatPassword" class="form-control" name="vpassword" required pattern="^([A-Za-z0-9#?!@$%^&*-]){4,32}$" />
                    <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                </div>

                <!-- Checkbox -->
                <div class="form-check d-flex justify-content-center mb-4">
                    <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" checked aria-describedby="registerCheckHelpText" />
                    <label class="form-check-label" for="registerCheck">
                        I have read and agree to the terms
                    </label>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-3" name="submit" value="register">Register</button>
            </form>
        </div>
    </div>
    <!-- Pills content -->
    <!-- Pills content -->
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>