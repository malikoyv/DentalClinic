<!DOCTYPE html>
<html>
<head>
    <style type="text/css" media="screen">
        input.largerCheckbox { 
            width: 20px; 
            height: 20px; 
        } 
    </style>
</head>
</html>

<?php
    session_start();
    if(isset($_POST['Delete']))
    {
        if(!empty($_POST['check_list']))
        {
            $tasks = $_POST['check_list'];
            $length = count($tasks);
            for ($i = 0; $i < $length; $i++) {
                deleteTodoItem($_SESSION['email'], $tasks[$i]);
            }
        }
    }
    else if(isset($_POST['Save']))
    {
        $conn = connectdatabase();
        $sql = "UPDATE todo.tasks SET done = 0";
        $result = mysqli_query($conn, $sql); 
        mysqli_close($conn);

        if(!empty($_POST['check_list']))
        {
            $tasks = $_POST['check_list'];
            $length = count($tasks);
            if($length > 0) {
                for ($i = 0; $i < $length; $i++) {
                    updateDone($tasks[$i]);
                }
            }
        }
    }

    function connectdatabase() {
        return mysqli_connect("127.0.0.1:3306", "root", "", "todo");
    }

    function loggedin() {
        return isset($_SESSION['email']);
    }

    function logout() {
        $_SESSION['error'] = "&nbsp; Succesfully logout !!";
        unset($_SESSION['email']);
    }

    function spaces($n) {
        for($i=0; $i<$n; $i++)
            echo "&nbsp;";
    }

    function userexist($email) 
    {
        $conn = connectdatabase();
        $sql = "SELECT * FROM todo.users WHERE email = '".$email."'"; 
        $result = mysqli_query($conn,$sql);
        mysqli_close($conn);

        if(!$result || mysqli_num_rows($result) == 0) { 
           return false;
        }
        return true;
    }

    function validuser($email, $password) 
    {
        $conn = connectdatabase();
        $sql = "SELECT * FROM todo.users WHERE email = '".$email."'AND password = '".$password."'"; 
        $result = mysqli_query($conn,$sql);
        mysqli_close($conn);

        if(!$result || mysqli_num_rows($result) == 0) { 
           return false;
        }
        return true;
    }

    function error() 
    {
        if(isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        }
    }

    function updatepassword($email, $password) {
        $conn = connectdatabase();
        $sql = "UPDATE todo.users SET password = '".$password."' WHERE email = '".$email."';";
        $result = mysqli_query($conn, $sql);

        $_SESSION['error'] = "<br> &nbsp; Password Updated !! ";
        header('location:index.php');
    }

    function deleteaccount($email) {
        $conn = connectdatabase();
        $sql = "DELETE FROM todo.tasks WHERE email = '".$email."';";
        $result = mysqli_query($conn, $sql);

        $sql = "DELETE FROM todo.users WHERE email = '".$email."';";
        $result = mysqli_query($conn, $sql);

        $_SESSION['error'] = "&nbsp; Account Deleted !! ";
        unset($_SESSION['email']);
        header('location:login.php');
    }

    function createUser($email, $password)
    {
        if(!userexist($email))
        {
            $conn = connectdatabase();
            $sql = "INSERT INTO todo.users (email, password) VALUES ('".$email."','".$password."')";
            $result = mysqli_query($conn, $sql);

            $_SESSION["email"] = $email;
            header('location:index.php');
        }
        else
        {
            $_SESSION['error'] = "&nbsp; email already exists !! ";
            header('location:newuser.php');
        }
    }
    
    function isValid($email, $password, $usercaptcha)
    {
        $conn = connectdatabase();
        $capcode = $_SESSION['captcha'];

        if(!strcmp($usercaptcha,$capcode))
        {
            if(validuser($email, $password))
            {
                $_SESSION["email"] = $email;
                header('location:index.php');
            }
            else
            {
                $_SESSION['error'] = "&nbsp; Invalid email or Password !! ";
                header('location:login.php');
            }
            mysqli_close($conn);
        }
        else
        {
            $_SESSION['error'] = "&nbsp; Invalid captcha code !! ";
            header('location:login.php');
        }
    }
    
    function getTodoItems($email) {

        $conn = connectdatabase();
        $sql = "SELECT * FROM tasks WHERE email = '".$email."'";
        
        $result = mysqli_query($conn, $sql);

        echo "<form method='POST'>";
        echo "<pre>";
        if ($result and mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {

                spaces(15);
                if($row['done']) {
                    echo "<input type='checkbox' checked class='largerCheckbox' name='check_list[]' value='".$row["taskid"] ."'>";
                }
                else {
                    echo "<input type='checkbox' class='largerCheckbox' name='check_list[]' value='".$row["taskid"] ."'>";
                }
                echo "<td> " . $row["task"] . "</td>";
                echo "<br>";
            }
        }
        echo "</pre> <hr>";
        spaces(35);
        echo "<input type='submit' name='Delete' value='Delete'/>";
        spaces(10);
        echo "<input type='submit' name='Save' value='Save'/>";
        echo "</form>";
        echo "<br><br>";
        mysqli_close($conn);
    }

    function addTodoItem($email, $todo_text) 
    {
        $conn = connectdatabase();
        $sql = "INSERT INTO todo.tasks(email, task, done) VALUES ('".$email."','".$todo_text."',0);";
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
    }
    
    function deleteTodoItem($email, $todo_id) 
    {
        $conn = connectdatabase();
        $sql = "DELETE FROM todo.tasks WHERE taskid = ".$todo_id." and email = '".$email."';";
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
    }

    function updateDone($todo_id) 
    {
        $conn = connectdatabase();
        $sql = "UPDATE todo.tasks SET done = '1' WHERE (taskid = '".$todo_id."');";
        $result = mysqli_query($conn, $sql);   
        mysqli_close($conn);
    }
?>