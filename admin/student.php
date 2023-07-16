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
            $stmt = $pdo->prepare('SELECT student_name, branch_name, user_name FROM students JOIN branches ON students.branch_id = branches.id WHERE students.id = :id');
            $stmt->execute(array(
                ':id' => $_GET['id']
            ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row === false){
                $_SESSION['error'] = "Student Not Found";
                header("Location: home.php");
                return;
            }
            else{
                $student_name = $row['student_name'];
                $branch_name = $row['branch_name'];
                $user_name = $row['user_name'];
                $stmt = $pdo->prepare('SELECT subject_name, mark FROM marks JOIN subjects ON marks.subject_id = subjects.id WHERE student_id = :id');
                $stmt->execute(array(
                    ':id' => $_GET['id']
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title><=$student_name?> | Student Result Management</title>
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

    <div class="block max-w-sm p-6 bg-white border border-gray-200 bg-opacity-80 rounded-lg shadow mx-auto mt-8 lg:mx-0 lg:mt-0 lg:rounded-none lg:rounded-br-full">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900"><?=$student_name?></h5>
        <p class="font-normal text-gray-700 dark:text-gray-400"><?=$branch_name?></p>
        <p class="font-normal text-gray-700 dark:text-gray-400"><span>@</span><?=$user_name?></p>
    </div>

    <?php
        if(sizeof($rows) == 0){
            echo('<div class="h-5/6 flex items-center justify-center">
                    <div class="bg-white bg-opacity-70 px-8 py-5 font-medium text-lg w-full text-center">No Subject Opted</div>
                </div>');
        }
        else{
            echo('<div class="relative overflow-x-auto w-fit shadow-md sm:rounded-lg flex items-center justify-between mx-auto my-10">
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
                        <td class="text-right py-4 px-6">'
                            .$row['mark'].
                        '</td>
                    </tr>');
            }
            echo('</tbody>
                </table>
            </div>');
        }
    ?>

    <div class="w-full flex items-center justify-end fixed bottom-0">
        <button class="w-1/2 md:w-3/12 py-5 text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-t-full text-sm text-center" onclick="window.location.href='marks.php?id=<?php echo $_GET['id'] ?>'">Edit Marks</button>
        <button class="w-1/2 md:w-3/12 py-5 text-white bg-orange-500 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-t-full text-sm text-center" onclick="window.location.href='subject.php?id=<?php echo $_GET['id'] ?>'">Add New Subject</button>
    </div>
    <script src="../scripts.js"></script>
    </body>
</html>
