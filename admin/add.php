<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        $_SESSION['error'] = "Login Required";
        header("Location: index.php");
        return;
    }
    else{
        if(isset($_POST['student_name']) && isset($_POST['branch_id']) && isset($_POST['user_name']) && isset($_POST['password'])){
            require_once "connect.php";
            $stmt = $pdo->prepare('INSERT INTO students (student_name, branch_id, user_name, password) VALUES (:student_name, :branch_id, :user_name, :password)');
            $stmt->execute(array(
                ':student_name' => $_POST['student_name'],
                ':branch_id' => $_POST['branch_id'],
                ':user_name' => $_POST['user_name'],
                ':password' => $_POST['password']
            ));
            $_SESSION['success'] = "Student Added";
            header("Location: home.php");
            return;
        }
        require_once "connect.php";
        $branch_list = $pdo->query('SELECT id, branch_name FROM branches');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student | Student Result Management</title>
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

    <nav class="bg-white bg-opacity-80 border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="home.php" class="flex items-center">
                <span class="self-center text-xl md:text-2xl font-semibold whitespace-nowrap">Student Result Management System</span>
            </a>
            <div class="flex md:order-2">
                <button type="button" class="w-full text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
    </nav>

    <div class="h-5/6 flex items-center justify-center">
        <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow sm:p-6 md:p-8">
            <form class="space-y-6" action="add.php" method="post">
                <h5 class="text-xl font-medium text-gray-900 dark:text-white flex justify-center">Add Student</h5>
                <div>
                    <label for="student_name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input type="text" name="student_name" id="student_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required>
                </div>
                
                <label for="branch_id" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
                <select name="branch_id" id="branch_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                    <option selected></option>
                    <?php
                        while($row = $branch_list->fetch(PDO::FETCH_ASSOC)){
                            echo('<option value="'.$row['id'].'">'.$row['branch_name'].'</option>');
                        }
                    ?>
                </select>

                <div>
                    <label for="user_name" class="block mb-2 text-sm font-medium text-gray-900">User Name</label>
                    <input type="text" name="user_name" id="user_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required>
                </div>
                <button type="submit" class="w-full text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center">Add</button>
            </form>
        </div>
    </div>
    <script src="../scripts.js"></script>
</body>
</html>