<?php
// Include config file
require_once "conf.php";
 
// Define variables and initialize with empty values
$username = $password = $no_error = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    // echo trim($_POST["username"]);
    // Validate username
    if(empty(trim($_POST["username"])))
    {
        $username_err = "Please enter a username.";
    } 
    else
    {
        $sql = "SELECT id FROM user_table WHERE username = ?";
       
        if($stmt = mysqli_prepare($link, $sql))
        { echo "New1";  
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
           
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                /* store result */
               
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 0)
                {
                    $username_err = "This username is already taken.";
                } 
                else
                {
                    echo "New";
                    // $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
                echo  $username;
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
             $phoneNumber = $_POST['mobileno'];

            if(!empty($phoneNumber)) // phone number is not empty
            {
                if(preg_match('/^\d{10}$/',$phoneNumber)) // phone number is valid
                {
                $phoneNumber = '0' . $phoneNumber;

                // your other code here
                }
                else // phone number is not valid
                {
                    $no_error ='Phone number invalid !';
                }
            }
            else // phone number is empty
            {
                $no_error = 'You must provid a phone number !';
            }
            
             $doc=$_POST["doc_list"];
            
             $gen=$_POST['gender'];
             
                if($gen == "Male")
                {
                   $gen="Male";
                }
                else if ($gen == "Female")
                {
                    $gen="Female";
                }
                else
                {
                    $gen="Other";
                }
                echo "username -".$username."-".$gen."-".$phoneNumber."-".$doc."-".$password;
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users_table (name,gender,appointment,mobile,password) VALUES (?,?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username,$param_gen,$param_phoneNumber,$param_doc, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_gen=$gen;
            $param_phoneNumber=$phoneNumber;
            $param_doc=$doc;
            echo $param_username."-".$param_gen."-".$param_phoneNumber."-".$param_doc."-".$param_password;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

                <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="male" checked="checked" value="Male">
                <label class="form-check-label" for="male">Male</label>
                </div>
                
                <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                <label class="form-check-label" for="female">Female</label>
                </div>
                
                <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="other" value="Other">
                <label class="form-check-label" for="other">Other</label>
                </div>

                <div>
                <label class="form-check-label" >Select Appoinment </label>
                <select name="doc_list" class="form-select form-select-sm" aria-label=".form-select-sm example" >
                    <!-- <option selected>Open this select menu</option> -->
                    <?php
                      $subjectName = "SELECT *FROM doctor_table";
                      $subject = mysqli_query( $link,$subjectName );
                    ?>
                    <?php while($subjectData = mysqli_fetch_array($subject))
                    {?>
                        <option id="doc" value="<?php echo $subjectData['name'];?>"> <?php echo $subjectData['name'];?>
                        </option>
                    <?php
                    }
                    ?>
                  
                </select>
                </div>   

                <div class="form-group">
                <label>Mobile No</label>
                <input type="text" name="mobileno" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $no_error; ?></span>
              </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="index.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>