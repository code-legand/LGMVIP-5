<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        $_SESSION['error'] = "Login Required";
        header("Location: index.php");
        return;
    }
    else{
        require_once "connect.php";
        $student_list = $pdo->prepare('SELECT students.id, student_name, branch_name FROM students JOIN branches ON students.branch_id = branches.id');
        $student_list->execute();
        $rows = $student_list->fetchAll(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Student Result Management</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-300 via-yellow-300 to-pink-300" style="font-family: 'Montserrat', sans-serif;">
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

    <div class="flex items-center justify-center">
        <button class="w-7/12 md:w-3/12 py-5 text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium md:rounded-bl-full rounded-b-full text-sm text-center" onclick="window.location.href='add.php'">Add Student</button>
    </div>

    <?php
        if(sizeof($rows) == 0){
            echo('<p>No Student Found</p>');
        }
        else{
            echo('<div class="relative overflow-x-auto shadow-md sm:rounded-lg max-w-screen-xl flex flex-wrap items-center justify-between mx-auto my-10">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 bg-opacity-70">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Id
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Student Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Branch
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Delete</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>');
            foreach($rows as $row){
                echo('<tr class="bg-white bg-opacity-80 border-b hover:cursor-pointer hover:opacity-70" onclick="window.location.href=\'student.php?id='.$row['id'].'\'">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            '.$row['id'].'
                        </th>
                        <td class="px-6 py-4">
                            '.$row['student_name'].'
                        </td>
                        <td class="px-6 py-4">
                            '.$row['branch_name'].'
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="delete.php?id='.$row['id'].'" class="font-medium text-red-600 hover:underline">Delete</a>
                        </td>
                    </tr>');
            }
            echo('</tbody>
                </table>
            </div>');
        }
    ?>
    <script src="../scripts.js"></script>

</body>
</html>