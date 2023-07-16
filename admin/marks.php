<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        $_SESSION['error'] = "Login Required";
        header("Location: index.php");
        return;
    }
    else{
        require_once "connect.php";
        if(isset($_GET['id'])){
            $_SESSION['student_id'] = $_GET['id'];
            $stmt = $pdo->prepare('SELECT subject_id, subject_name, mark FROM marks JOIN subjects ON marks.subject_id = subjects.id WHERE student_id = :id');
            $stmt->execute(array(
                ':id' => $_SESSION['student_id']
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if(isset($_POST['confirm'])){
            $stmt = $pdo->prepare('UPDATE marks SET mark = :mark WHERE student_id = :student_id AND subject_id = :subject_id');
            foreach($_POST as $key => $value){
                if($key != 'confirm'){
                    $stmt->execute(array(
                        ':mark' => $value,
                        ':student_id' => $_SESSION['student_id'],
                        ':subject_id' => $key
                    ));
                }
            }
            $_SESSION['success'] = "Marks Updated";
            header("Location: student.php?id=".$_SESSION['student_id']);
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
    <title>Edit Marks | Student Result Management</title>
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

    <?php
        if(sizeof($rows) == 0){
            echo('<div class="h-5/6 flex items-center justify-center">
                    <div class="bg-white bg-opacity-70 px-8 py-5 font-medium text-lg w-full text-center">No Subject Opted</div>
                </div>');
        }
        else{
            echo('<div class="relative overflow-x-auto w-fit shadow-md sm:rounded-lg flex items-center justify-between mx-auto my-10">
                    <form method="post" action="marks.php" class="flex flex-wrap items-center justify-center bg-gray-50 bg-opacity-80">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 bg-opacity-70">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left">
                                        Subject
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right">
                                        Marks
                                    </th>
                                </tr>
                            </thead>
                            <tbody>');
                foreach($rows as $row){
                    echo('<tr class="bg-white bg-opacity-80 border-b hover:opacity-70">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-left">
                                '.$row['subject_name'].'
                            </th>
                            <td class="text-right">
                                <input type="text" name="'.$row['subject_id'].'" id="'.$row['subject_name'].'" value="'.$row['mark'].'" class="w-full text-right py-4 px-6">
                            </td>
                        </tr>');
                }
                echo('</tbody>
                    </table>
                    <input type="hidden" name="confirm" value="1">
                    <div class="w-full flex justify-center">
                        <button type="submit" class="m-4 text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center">Update</button>
                    </div>
                </form>
            </div>');
        }
    ?>
    <script src="../scripts.js"></script>
    </body>
</html>
