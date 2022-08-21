<?php
// Initialize the session
session_start();
 require_once "conf.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
        $sql = "SELECT * FROM doctor_table  ";
    
       $result = $link->query($sql);
        // $result = mysqli_query( $link,$sql );
        $link->close(); 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
      
        table {
            margin: 0 auto;
            font-size: large;
            border: 1px solid black;
        }
  
        h1 {
            text-align: center;
            color: #006600;
            font-size: xx-large;
            font-family: 'Gill Sans', 'Gill Sans MT', 
            ' Calibri', 'Trebuchet MS', 'sans-serif';
        }
  
     
        th,
        td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            background-color: #E4F5D4;
            border: 1px solid black;
        
        }
  
        td {
            font-weight: lighter;
        }
    
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>

    <section>
        <h1>DATA</h1>
      
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
               
            </tr>
            
            <?php   
            
                while($rows = mysqli_fetch_array($result))
                {
             ?>
            <tr>
              
                <td><?php echo $rows['id'];?></td>
                <td><?php echo $rows['name'];?></td>
              
                
            </tr>
            <?php
                }
             ?>
        </table>
    </section>

    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
</body>
</html>