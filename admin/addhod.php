<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
}
else {
    if(isset($_POST['add'])) {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $department = $_POST['department'];
        $password = md5($_POST['password']);
        $status = 1;
        $sql = "INSERT INTO hod(UserName, Password, FullName, Email, Department, Status) VALUES(:username, :password, :fullname, :email, :department, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':department', $department, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            $msg="HOD Created Successfully";
        } else {
            $error="Something went wrong. Please try again";
        }
    }
    // Fetch departments for dropdown
    $sql = "SELECT DepartmentName FROM tbldepartments ORDER BY DepartmentName ASC";
    $query = $dbh->prepare($sql);
    $query->execute();
    $departments = $query->fetchAll(PDO::FETCH_OBJ);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <!-- Title -->
        <title>Admin | Add HOD</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet"> 
        <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            }
            .succWrap{
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            }
        </style>
    </head>
    <body>
        <?php include('includes/header.php');?>
        <?php include('includes/sidebar.php');?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Add HOD</div>
                </div>
                <div class="col s12 m12 l6">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <form class="col s12" name="addhod" method="post">
                                    <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
                                        else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="username" type="text" class="validate" autocomplete="off" name="username" required>
                                            <label for="username">Username</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="fullname" type="text" class="validate" autocomplete="off" name="fullname" required>
                                            <label for="fullname">Full Name</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="email" type="email" class="validate" autocomplete="off" name="email" required>
                                            <label for="email">Email</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <select name="department" id="department" required>
                                                <option value="" disabled selected>Choose Department</option>
                                                <?php foreach($departments as $dept) { ?>
                                                    <option value="<?php echo htmlentities($dept->DepartmentName); ?>"><?php echo htmlentities($dept->DepartmentName); ?></option>
                                                <?php } ?>
                                            </select>
                                            <label for="department">Department</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="password" type="password" class="validate" autocomplete="off" name="password" required>
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <button type="submit" name="add" class="waves-effect waves-light btn indigo m-b-xs">ADD</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
        </main>
        </div>
        <div class="left-sidebar-hover"></div>
        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/form_elements.js"></script>
        <script>
            // Initialize Materialize select
            document.addEventListener('DOMContentLoaded', function() {
                var elems = document.querySelectorAll('select');
                M.FormSelect.init(elems);
            });
        </script>
    </body>
    </html>
<?php 
} 
?>
