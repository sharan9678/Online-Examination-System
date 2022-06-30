<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Examination System</title>
    <link rel="stylesheet" href="styles/homestaff.css">
    <?php
        session_start();
        require_once 'sql.php';
                        $conn = mysqli_connect($host, $user, $ps, $project);if (!$conn) {
            echo "<script>alert(\"Database error retry after some time !\")</script>";
        } else {
            $type1 = $_SESSION["type"];
            $username1 = $_SESSION["username"];
            $sql = "select * from " . $type1 . " where mail='{$username1}'";
            $res =   mysqli_query($conn, $sql);
            if ($res == true) {
                global $dbmail, $dbpw, $dbusn;
                while ($row = mysqli_fetch_array($res)) {
                    $dbmail = $row['mail'];
                    $dbname = $row['name'];
                    $dbusn = $row['staffid'];
                    $dbphno = $row['phno'];
                    $dbgender = $row['gender'];
                    $dbdob = $row['DOB'];
                    $dbdept = $row['dept'];
                }
            }
            if (isset($_POST['submit'])) {
                $qname = strtolower($_POST['quizname']);
                $_SESSION["qname"]=$qname;
                $sql1 = "insert into quiz(quizname,mail) values('$qname','$username1')";
                $res1 = mysqli_query($conn, $sql1);
                if ($res1 == true) {
                    $sql = "select quizid from quiz where quizname='" . $qname . "';";
                    $res = mysqli_query($conn, $sql);
                    if ($res == true) {
                        header("location: addqs.php");
                    } else {
                        echo "<script>alert(\"some error occured\");</script>";
                    }
                } else {
                    echo "<script>alert(\"Already name exists\");</script>";
                }
            }
            if (isset($_POST['submit1'])) {
                $qid1 = strtolower($_POST['quizid']);
                $sql1 = "delete from quiz where quizid='{$qid1}'";
                $res1 = mysqli_query($conn, $sql1);
                if ($res1 == true) {
                    echo "<script>alert(\"Quiz successfully deleted\");</script>";
                } else {
                    echo "<script>alert(\"Unknown error occured during deletion of quiz\");</script>";

                }
            }
            if (isset($_POST['submit2'])) {
                $qid1 =$_POST['quizid'];
                $sql1 = "select quizid from quiz where quizid='{$qid1}'";
                $res1 = mysqli_query($conn, $sql1);
                if ($res1 == true) {
                    echo "<script>window.location.replace(\"viewq.php?qid=".$qid1."\");</script>";
                } else {
                    echo "<script>alert(\"Unknown error occured during viweing of quiz\");</script>";

                }
            }
        }
    ?>
</head>
<body>
    <div id="main">
        <div class="navbar">
            <section style="margin: 1.5vw">ONLINE EXAMINATION SYSTEM</section>
            <ul style="display: inline-flex; padding: 0 !important; margin:0; float: right; right: 0; position: fixed; width: 50vw;">
                <li onclick="dash()">Dashboard</li>
                <li onclick="prof()">Profile</li>
                <li onclick="score()">Quiz</li>
                <li onclick="lo()">Sign Out</li>
            </ul>
        </div><br><br>
        <center><section style="width:100vw;margin:0vw;margin-top:4vw;font-size:2vw;">Welcome to Online Examination System&nbsp;<?php echo $dbname ?></section></center>
        <section class="dash" style="margin: 5vw;width: 90vw;">
            <center><h1 style="font-weigth:bolder; font-size:3vw;">Dashboard</h1></center>
            <center><button onclick="addquiz()">Add Quiz</button><button onclick="delquiz()">Delete Quiz</button><button onclick="viewq()">View Quiz</button></center>
            <center>
                <section id="addq" style="display:none;">
                <form style="width:30vw" method="post">
                <h1>Add quiz</h1>
                        <label for="quizname">Quiz name</label>
                        <input type="text" name="quizname" placeholder="enter quiz name" required><br><br>
                        <input type="submit" name="submit" value="submit" style="height: 3vw;width: 10vw;font-family: 'Courier New', Courier, monospace;font-weight: bolder;border-radius: 10px;border: 2px solid black;background-color: lightblue;">
                </form>
                </section>
            </center>
            <center>
                <section id="delq" style="display:none;">
                    <form style="margin: 1vw;width: 30vw" method="post">
                        
                            <h1>Delete Quiz</h1>
                            <label for="quizid">Quiz Id</label>
                            <input type="number" name="quizid" placeholder="enter quiz id" required><h7 onclick="score()" style="padding:0;color: #fff;font-size:1vw;text-decoration:underline">get Quiz ID</h7><br><br>
                            <input type="submit" name="submit1" value="submit" style="height: 3vw;width: 10vw;font-family: 'Courier New', Courier, monospace;font-weight: bolder;border-radius: 10px;border: 2px solid black;background-color: lightblue;">
                    </form>
                </section>
            </center>
            <center>
                <section id="viewq" style="display:none;">
                    <form style="margin: 1vw;width: 30vw" method="post">
                        
                            <h1>View Quiz</h1>
                            <label for="quizid">Quiz Id</label>
                            <input type="number" name="quizid" placeholder="enter quiz id" required><h7 onclick="score()" style="padding:0;color: #fff;font-size:1vw;text-decoration:underline">get Quiz ID</h7><br><br>
                            <input type="submit" name="submit2" value="submit" style="height: 3vw;width: 10vw;font-family: 'Courier New', Courier, monospace;font-weight: bolder;border-radius: 10px;border: 2px solid black;background-color: lightblue;">                        
                    </form>
                </section>
            </center>
        </section>
        <section id="score" style="display:none;">
            <?php 
            $sql ="select * from quiz where mail='{$username1}'";
            $res=mysqli_query($conn,$sql);
            if($res)
            {
                echo "<h1>List of Quiz added by U</h1>";
                echo "<table id=\"sc\"><thead><tr><td>Quiz id</td>&nbsp;<td>Quiz Title</td><td>Created on</td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {                
                    echo "<tr><td>".$row["quizid"]."</td><td>".$row["quizname"]."</td><td>".$row["date_created"]."</td></tr>"; 
                }
                echo "</table>";
            }
            ?>
        </section>
        <section style="color:#fff !important">
            <?php
            $sql="select quizname,s.name,score,totalscore from student s,staff st,score sc,quiz q where q.quizid=sc.quizid and s.mail=sc.mail and q.mail=st.mail and q.mail='{$username1}' ORDER BY score DESC";
            $res=mysqli_query($conn,$sql);
            if($res)
            {
                echo "<center><h1 style=\"font-size: 3vw\">Leaderboard</h1></center>";
                echo "<table id=\"le\"><thead><tr><td>Quiz Title</td>&nbsp;<td>Student name</td><td>score obtained</td><td>Max Score</td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {                
                    echo "<tr><td>".$row["quizname"]."</td><td>".$row["name"]."</td><td>".$row["score"]."</td><td>".$row["totalscore"]."</td></tr>"; 
                }
                echo "</table><br><br>";
            }
            else{
                echo mysqli_error($conn);
            }
            ?>
        </section>
    </div>
    <?php require("footer.php"); ?>
</body>
<?php
    echo '<script>' .
    "function prof(){" .
    "document.getElementById(\"prof\").style=\"display: block !important;\";" .
    "document.getElementById(\"score\").style=\"display: none !important;\";" .
    "}" .
    "function score(){" .
    "document.getElementById(\"prof\").style=\"display: none !important;\";" .
    "document.getElementById(\"score\").style=\"display: block !important;\";" .
    "}" .
    "function dash(){" .
    "document.getElementById(\"prof\").style=\"display: none !important;\";" .
    "document.getElementById(\"score\").style=\"display: none !important;\";" .
    "}" .
    "function lo(){" .
    "alert(\"Thank You for Using our Online Examination System\");";
    //session_unset();
    //session_destroy();
    echo "window.location.replace(\"index.php\");" .
    "}" .
    "function addquiz(){" .
    "document.getElementById(\"addq\").style=\"display: initial;\";" .
    "document.getElementById(\"delq\").style=\"display: none;\";" .
    "document.getElementById(\"viewq\").style=\"display: none;\";" .

    "}" .
    "function delquiz(){" .
        "document.getElementById(\"delq\").style=\"display: initial;\";" .
        "document.getElementById(\"addq\").style=\"display: none;\";" .
        "document.getElementById(\"viewq\").style=\"display: none;\";" .
        "}" .
    "function viewq(){" .
        "document.getElementById(\"viewq\").style=\"display: initial;\";" .
        "document.getElementById(\"delq\").style=\"display: none;\";" .
        "document.getElementById(\"addq\").style=\"display: none;\";" .
        "}" .
    "</script>";
?>
</html>