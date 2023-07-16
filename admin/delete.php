<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        $_SESSION['error'] = "Login Required";
        header("Location: index.php");
        return;
    }
    else{
        if(isset($_GET['id'])){
            $_SESSION['id'] = $_GET['id'];
        }
        if(isset($_POST['confirm'])){
            require_once "connect.php";
            $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
            $result = $stmt->execute(array(
                ':id' => $_SESSION['id']
            ));
            if($result){
                $_SESSION['success'] = "Student Deleted Successfully";
                header("Location: home.php");
                return;
            }
            else{
                $_SESSION['error'] = "Error Deleting Student";
                header("Location: home.php");
                return;
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
        <title>Delete Student | Student Result Management</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    </head>
    <body class="h-screen bg-gradient-to-r from-green-300 via-yellow-300 to-pink-300" style="font-family: 'Montserrat', sans-serif;">
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

        <nav class="bg-white bg-opacity-80 border-gray-200">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="home.php" class="flex items-center">
                    <span class="self-center text-xl md:text-2xl font-semibold whitespace-nowrap">Student Result Management System</span>
                </a>
                <div class="flex md:order-2">
                    <button type="button" class="w-full text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center" onclick="window.location.href='logout.php'">Logout</button>
                </div>
            </div>
        </nav>

        <div class="h-5/6 w-full flex items-center justify-center">
            <div class="px-8 pb-4 rounded-lg flex flex-wrap items-center justify-center bg-white bg-opacity-90 border-gray-200">
                <div class="py-4 flex items-center justify-center w-full my-6 mx-auto text-lg font-semibold border-b-2 border-yellow-300">Are You Sure?</div>
                <div class="flex justify-around w-full">
                    <form action="delete.php" method="post">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="w-fit px-5 py-2.5 text-white bg-red-500 hover:bg-orange-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium md:rounded-full rounded-b-full text-sm text-center" onclick="window.location.href='delete.php'">Delete</button>
                    </form>
                    <button class="w-fit px-5 py-2.5 text-white bg-blue-500 hover:bg-sky-400 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium md:rounded-bl-full rounded-full text-sm text-center" onclick="window.location.href='home.php'">Cancel</button>
                </div>
            </div>
        </div>
        <script src="../scripts.js"></script>
    </body>
</html>