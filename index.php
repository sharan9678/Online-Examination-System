<?php session_start(); ?>
<html>
    <head>
        <title>Online Examination System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="styles/index.css">
        <?php
            if (isset($_POST['login'])) {
                if (isset($_POST['usertype']) && isset($_POST['username']) && isset($_POST['pass'])) {
                    // require_once('sql.php');
                    $host = 'localhost';
                    $user = 'root';
                    $project = 'online_examination';
                    $ps = '';
                    // connection setup
                    $conn = mysqli_connect($host, $user, $ps, $project);
                    if(!$conn) {
                        echo "<script>alert(\"Database error retry after some time!\")</script>";
                    }
                    $type = mysqli_real_escape_string($conn, $_POST['usertype']);
                    $username = mysqli_real_escape_string($conn, $_POST['username']);
                    $password = mysqli_real_escape_string($conn, $_POST['pass']);
                    $password = crypt($password, 'sharanraj');
                    $sql = "select * from " . $type . " where mail='{$username}'";
                    $res = mysqli_query($conn, $sql);
                    if ($res == true) {
                        global $dbmail, $dbpw;
                        while ($row = mysqli_fetch_array($res)) {
                            $dbpw = $row['pw'];
                            $dbmail = $row['mail'];
                            $_SESSION["name"] = $row['name'];
                            $_SESSION["type"] = $type;
                            $_SESSION["username"] = $dbmail;
                        }
                        if ($dbpw === $password) {
                            if ($type === 'student') {
                                header("location:homestud.php");
                                // header("location:dummy.php");
                            } elseif ($type === 'staff') {
                                header("Location: homestaff.php");
                                // header("location:dummy.php");
                            }
                        } elseif ($dbpw !== $password && $dbmail === $username) {
                            echo "<script>alert('password is wrong');</script>";
                        } elseif ($dbpw !== $password && $dbmail !== $username) {
                            echo "<script>alert('username name not found SIGN UP');</script>";
                        }
                    }
                }
            }
        ?>
    </head>
    <body>
        <div class="bg">
            <center>
                <h1 class="w3-container" id="heading">
                    Online Examination System
                </h1>
            </center>
            <center>
                <div class="w3-card" id="login">
                    <form method="post">
                        <div class="select-user">
                            <input type="radio" name="usertype" value="student" required>STUDENT
                            <input type="radio" name="usertype" value="staff" required>STAFF
                        </div>
                        <br>
                        <br>
                        <div class="signin">
                            <label for="username" style="text-transform: uppercase;">Username</label>
                            <input type="email" name="username" placeholder=" Email" class="inp" required>
                            <br>
                            <br>
                            <label for="password" style="text-transform: uppercase;">Password</label>
                            <input type="password" name="pass" placeholder="******" class="inp" required>
                            <br>
                            <br>
                            <input name="login" class="sub" type="submit" value="Login"><br>
                        </div>
                    </form>
                    <br>
                    <a href="reset.php">Forgot password?</a>
                    &nbsp;
                    New user!:
                    <a href="signup.php">Sign Up</a>
                </div>
            </center>
        </div>
        <?php require("footer.php"); ?>
    </body>
</html>