<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['hodlogin'])==0) {   
    header('location:index.php');
    exit();
}
$hodid = $_SESSION['hodid'];
// Get HOD's department
$sql = "SELECT Department FROM tblemployees WHERE id=:hodid";
$query = $dbh->prepare($sql);
$query->bindParam(':hodid', $hodid, PDO::PARAM_INT);
$query->execute();
$hodDept = $query->fetch(PDO::FETCH_OBJ)->Department;
// Fetch pending leaves for HOD's department
$sql = "SELECT tblleaves.id as lid, tblemployees.FirstName, tblemployees.LastName, tblemployees.EmpId, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status 
        FROM tblleaves 
        JOIN tblemployees ON tblleaves.empid = tblemployees.id 
        WHERE tblemployees.Department = :dept AND tblleaves.HODApprovalStatus = 0 
        ORDER BY tblleaves.id DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>HOD | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta charset="UTF-8">
    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

        
    <!-- Theme Styles -->
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <?php include('includes/header.php');?>
    <?php include('includes/sidebar.php');?>
    <main class="mn-inner">
        <div class="">
            <div class="row no-m-t no-m-b">
                <a href="pending-leaves.php">
                <div class="col s12 m12 l4">
                    <div class="card stats-card">
                        <div class="card-content">
                            <span class="card-title">Pending Leaves</span>
                            <span class="stats-counter">
                                <?php
                                $sql = "SELECT id FROM tblleaves WHERE HODApprovalStatus=0 AND empid IN (SELECT id FROM tblemployees WHERE Department=:dept)";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
                                $query->execute();
                                $pending = $query->rowCount();
                                ?>
                                <span class="counter"><?php echo htmlentities($pending);?></span>
                            </span>
                        </div>
                        <div class="progress stats-card-progress">
                            <div class="determinate" style="width: 70%"></div>
                        </div>
                    </div>
                </div></a>
                <a href="approved-leaves.php">
                <div class="col s12 m12 l4">
                    <div class="card stats-card">
                        <div class="card-content">
                            <span class="card-title">Approved Leaves</span>
                            <span class="stats-counter">
                                <?php
                                $sql = "SELECT id FROM tblleaves WHERE HODApprovalStatus=1 AND empid IN (SELECT id FROM tblemployees WHERE Department=:dept)";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
                                $query->execute();
                                $approved = $query->rowCount();
                                ?>
                                <span class="counter"><?php echo htmlentities($approved);?></span>
                            </span>
                        </div>
                        <div class="progress stats-card-progress">
                            <div class="determinate" style="width: 70%"></div>
                        </div>
                    </div>
                </div></a>
                <a href="rejected-leaves.php">
                <div class="col s12 m12 l4">
                    <div class="card stats-card">
                        <div class="card-content">
                            <span class="card-title">Rejected Leaves</span>
                            <span class="stats-counter">
                                <?php
                                $sql = "SELECT id FROM tblleaves WHERE HODApprovalStatus=2 AND empid IN (SELECT id FROM tblemployees WHERE Department=:dept)";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
                                $query->execute();
                                $rejected = $query->rowCount();
                                ?>
                                <span class="counter"><?php echo htmlentities($rejected);?></span>
                            </span>
                        </div>
                        <div class="progress stats-card-progress">
                            <div class="determinate" style="width: 70%"></div>
                        </div>
                    </div>
                </div></a>
            </div>
            <div class="row no-m-t no-m-b">
                <div class="col s12 m12 l12">
                    <div class="card invoices-card">
                        <div class="card-content">
                            <span class="card-title">Latest Leave Applications</span>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="200">Employee Name</th>
                                        <th width="120">Leave Type</th>
                                        <th width="180">Posting Date</th>
                                        <th>You (Status)</th>
                                        <th>Registratr</th>
                                        <th align="center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT tblleaves.id as lid, tblemployees.FirstName, tblemployees.LastName, tblemployees.EmpId, tblemployees.id, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status, tblleaves.HODApprovalStatus from tblleaves join tblemployees on tblleaves.empid=tblemployees.id WHERE tblemployees.Department=:dept order by lid desc limit 6";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0)
                                {
                                foreach($results as $result)
                                {         ?>  
                                    <tr>
                                        <td> <b><?php echo htmlentities($cnt);?></b></td>
                                        <td><?php echo htmlentities($result->FirstName." ".$result->LastName);?>(<?php echo htmlentities($result->EmpId);?>)</td>
                                        <td><?php echo htmlentities($result->LeaveType);?></td>
                                        <td><?php echo htmlentities($result->PostingDate);?></td>
                                        <td><?php $statsHod=$result->HODApprovalStatus;
                                            if($statsHod==1){
                                                echo '<span style="color: green">Approved</span>';
                                            } elseif($statsHod==2)  {
                                                echo '<span style="color: red">Not Approved</span>';
                                            } elseif($statsHod==0)  {
                                                echo '<span style="color: blue">waiting for approval</span>';
                                            }
                                        ?></td>
                                        <td><?php $stats=$result->Status;
                                            if($stats==1){
                                                echo '<span style="color: green">Approved</span>';
                                            } elseif($stats==2)  {
                                                echo '<span style="color: red">Not Approved</span>';
                                            } elseif($stats==0)  {
                                                echo '<span style="color: blue">waiting for approval</span>';
                                            }
                                        ?></td>
                            
                                        <td><a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid);?>" class="waves-effect waves-light btn blue m-b-xs">View Details</a></td>
                                    </tr>
                                <?php $cnt++;} }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="left-sidebar-hover"></div>
    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/table-data.js"></script>
</body>
</html>
