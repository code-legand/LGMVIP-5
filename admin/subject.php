<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        $_SESSION['error'] = "Login Required";
        header("Location: index.php");
        return;
    }
    if(isset($_GET['id'])){
        require_once "connect.php";
        $_SESSION['student_id'] = $_GET['id'];
        $stmt = $pdo->prepare('SELECT subject_name, id from subjects where id not in (SELECT subject_id FROM marks WHERE student_id = :id)');
        $stmt->execute(array(
            ':id' => $_SESSION['student_id']
        ));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if(isset($_POST['confirm'])){
        require_once "connect.php";
        $stmt = $pdo->prepare('INSERT INTO marks (student_id, subject_id, mark) VALUES (:student_id, :subject_id, :mark)');
        $stmt->execute(array(
            ':student_id' => $_SESSION['student_id'],
            ':subject_id' => $_POST['subject_id'],
            ':mark' => $_POST['marks']
        ));
        $_SESSION['success'] = "Marks Added";
        header("Location: marks.php?id=".$_SESSION['student_id']);
        return;
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

    <?php
        if(sizeof($rows) == 0){
            echo('<div class="h-5/6 flex items-center justify-center">
                    <div class="bg-white bg-opacity-70 px-8 py-5 font-medium text-lg w-full text-center">No Subject Opted</div>
                </div>');
        }
        else{
            $options = "";
            foreach($rows as $row){
                $options .= '<option value="'.$row['id'].'">'.$row['subject_name'].'</option>';
            }
            echo('<div class="h-5/6 flex items-center justify-center">
                    <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow sm:p-6 md:p-8">
                        <form class="space-y-6" action="subject.php" method="post">
                            <h5 class="text-xl font-medium text-gray-900 dark:text-white flex justify-center">Add Student</h5>
                            <label for="subject_id" class="block mb-2 text-sm font-medium text-gray-900">Subject</label>
                            <select name="subject_id" id="subject_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                <option selected></option>'.
                                $options
                            .'</select>
                            <div>
                                <label for="marks" class="block mb-2 text-sm font-medium text-gray-900">Marks</label>
                                <input type="text" name="marks" id="marks" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required>
                            </div>
                            <input type="hidden" name="confirm" value="confirm">
                            <button type="submit" class="w-full text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center">Add</button>
                        </form>
                    </div>
                </div>');
        }
    ?>
    
    <script src="../scripts.js"></script>
</body>
</html>