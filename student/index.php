<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        header("Location: home.php");
        return;
    }
    if(isset($_POST['username']) && isset($_POST['password'])){
        require_once "connect.php";
        $stmt = $pdo->prepare('SELECT id, user_name, password FROM students where user_name = :username and password = :password');
        $stmt->execute(array(
            ':username' => $_POST['username'],
            ':password' => $_POST['password']
        ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row === false){
            $_SESSION['error'] = "Incorrect Username or Password";
            header("Location: index.php");
            return;
        }
        else{
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['success'] = "Logged In";
            header("Location: home.php");
            return;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Student Result Management</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
</head>
<body class="h-screen flex justify-center items-center bg-gradient-to-r from-green-300 via-yellow-300 to-pink-300" style="font-family: 'Montserrat', sans-serif;">
    <?php
        if(isset($_SESSION['error'])){
            echo('<div class="alert-box w-screen fixed top-0 flex items-center justify-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="text-center w-full font-medium">'.$_SESSION['error'].'</span><button type="button" class="close-button text-white bg-gradient-to-br from-pink-300 to-orange-200 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2">X</button></div>');
            unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
            echo('<div class="alert-box w-screen fixed top-0 flex items-center justify-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="text-center w-full font-medium">'.$_SESSION['success'].'</span><button type="button" class="close-button text-white bg-gradient-to-br from-green-200 to-green-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-100 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2">X</button></div>');
            unset($_SESSION['success']);
        }
    ?> 

    <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow sm:p-6 md:p-8">
        <form class="space-y-6" action="index.php" method="post">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white flex justify-center">Sign In</h5>
            <div>
                <!-- <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label> -->
                <input type="text" name="username" id="username" placeholder="Username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 placeholder-amber-500" required>
            </div>
            <div>
                <!-- <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label> -->
                <input type="password" name="password" id="password" placeholder="Password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 placeholder-amber-500" required>
            </div>
            <button type="submit" class="w-full text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center">Login</button>
        </form>
    </div>
    <script src="../scripts.js"></script>
</body>
</html>