<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['hodlogin'])==0) {   
    header('location:index.php');
    exit();
}
$hodid = $_SESSION['hodid'];
$sql = "SELECT Department FROM tblemployees WHERE id=:hodid";
$query = $dbh->prepare($sql);
$query->bindParam(':hodid', $hodid, PDO::PARAM_INT);
$query->execute();
$hodDept = $query->fetch(PDO::FETCH_OBJ)->Department;
$sql = "SELECT tblleaves.id as lid, tblemployees.FirstName, tblemployees.LastName, tblemployees.EmpId, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status, tblleaves.HODRemark, tblleaves.HODActionDate 
        FROM tblleaves 
        JOIN tblemployees ON tblleaves.empid = tblemployees.id 
        WHERE tblemployees.Department = :dept AND tblleaves.HODApprovalStatus = 2 
        ORDER BY tblleaves.id DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
$query->execute();
$resultsRejected = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>HOD | Rejected Leaves</title>
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
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Rejected Leave Requests</div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Rejected Leave Requests for <?php echo htmlentities($hodDept);?> Department</span>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="200">Employee Name</th>
                                        <th width="120">Leave Type</th>
                                        <th width="180">Posting Date</th>
                                        <th>HOD Remark</th>
                                        <th>Action Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $cnt=1; foreach($resultsRejected as $result) { ?>
                                    <tr>
                                        <td><b><?php echo htmlentities($cnt);?></b></td>
                                        <td><?php echo htmlentities($result->FirstName . " " . $result->LastName);?> (<?php echo htmlentities($result->EmpId);?>)</td>
                                        <td><?php echo htmlentities($result->LeaveType);?></td>
                                        <td><?php echo htmlentities($result->PostingDate);?></td>
                                        <td><?php echo htmlentities($result->HODRemark);?></td>
                                        <td><?php echo htmlentities($result->HODActionDate);?></td>
                                    </tr>
                                <?php $cnt++; } ?>
                                </tbody>
                            </table>
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
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/table-data.js"></script>
</body>
</html>
