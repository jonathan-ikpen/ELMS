<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['hodlogin'])==0) {   
    header('location:index.php');
    exit();
}
$leaveid = intval($_GET['leaveid']);
$msg = "";
$hodid = $_SESSION['hodid'];
// Get HOD's department
$sql = "SELECT Department FROM tblemployees WHERE id=:hodid";
$query = $dbh->prepare($sql);
$query->bindParam(':hodid', $hodid, PDO::PARAM_INT);
$query->execute();
$hodDept = $query->fetch(PDO::FETCH_OBJ)->Department;

// Handle HOD action (approve/reject)
if(isset($_POST['update'])) {
    $hodstatus = intval($_POST['status']);
    $remark = $_POST['description'];
    $sql = "UPDATE tblleaves l JOIN tblemployees e ON l.empid = e.id SET l.HODApprovalStatus=:hodstatus, l.HODRemark=:remark, l.HODActionDate=NOW() WHERE l.id=:leaveid AND e.Department=:dept";
    $query = $dbh->prepare($sql);
    $query->bindParam(':hodstatus', $hodstatus, PDO::PARAM_INT);
    $query->bindParam(':remark', $remark, PDO::PARAM_STR);
    $query->bindParam(':leaveid', $leaveid, PDO::PARAM_INT);
    $query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
    $query->execute();
    $msg = ($hodstatus == 1) ? "Leave Approved as HOD" : "Leave Rejected as HOD";
}

// Fetch leave details
$sql = "SELECT l.*, e.FirstName, e.LastName, e.EmpId, e.Gender, e.Phonenumber, e.EmailId, e.Department FROM tblleaves l JOIN tblemployees e ON l.empid = e.id WHERE l.id=:leaveid AND e.Department=:dept";
$query = $dbh->prepare($sql);
$query->bindParam(':leaveid', $leaveid, PDO::PARAM_INT);
$query->bindParam(':dept', $hodDept, PDO::PARAM_STR);
$query->execute();
$leave = $query->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>HOD | Leave Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
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
            <div class="page-title" style="font-size:24px;">Leave Details</div>
        </div>
        <div class="col s12 m12 l12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Leave Details</span>
                    <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php }?>
                    <?php if($leave) { ?>
                    <table id="example" class="display responsive-table ">
                        <tbody>
                        <tr>
                            <td style="font-size:16px;"> <b>Employee Name :</b></td>
                            <td><?php echo htmlentities($leave->FirstName." ".$leave->LastName);?></td>
                            <td style="font-size:16px;"><b>Emp Id :</b></td>
                            <td><?php echo htmlentities($leave->EmpId);?></td>
                            <td style="font-size:16px;"><b>Gender :</b></td>
                            <td><?php echo htmlentities($leave->Gender);?></td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;"><b>Emp Email id :</b></td>
                            <td><?php echo htmlentities($leave->EmailId);?></td>
                            <td style="font-size:16px;"><b>Emp Contact No. :</b></td>
                            <td><?php echo htmlentities($leave->Phonenumber);?></td>
                            <td style="font-size:16px;"><b>Department :</b></td>
                            <td><?php echo htmlentities($leave->Department);?></td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;"><b>Leave Type :</b></td>
                            <td><?php echo htmlentities($leave->LeaveType);?></td>
                            <td style="font-size:16px;"><b>Leave Date :</b></td>
                            <td>From <?php echo htmlentities($leave->FromDate);?> to <?php echo htmlentities($leave->ToDate);?></td>
                            <td style="font-size:16px;"><b>Posting Date</b></td>
                            <td><?php echo htmlentities($leave->PostingDate);?></td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;"><b>Employee Leave Description : </b></td>
                            <td colspan="5"><?php echo htmlentities($leave->Description);?></td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;"><b>Admin Approval Status :</b></td>
                            <td colspan="5">
                                <?php $adminstats=$leave->Status;
                                if($adminstats==1){ ?>
                                    <span style="color: green">Approved by Admin</span>
                                <?php } elseif($adminstats==2)  { ?>
                                    <span style="color: red">Rejected by Admin</span>
                                <?php } elseif($adminstats==0)  { ?>
                                    <span style="color: blue">Waiting for Admin Approval</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;"><b>HOD Approval Status :</b></td>
                            <td colspan="5">
                                <?php $hodstats=$leave->HODApprovalStatus;
                                if($hodstats==1){ ?>
                                    <span style="color: green">Approved by HOD</span>
                                <?php } elseif($hodstats==2)  { ?>
                                    <span style="color: red">Rejected by HOD</span>
                                <?php } elseif($hodstats==0)  { ?>
                                    <span style="color: blue">Waiting for HOD Approval</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;"><b>HOD Remark: </b></td>
                            <td colspan="5"><?php
                                if($leave->HODRemark==""){
                                    echo "Waiting for Approval";
                                } else {
                                    echo htmlentities($leave->HODRemark);
                                }
                            ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;"><b>HOD Action taken date : </b></td>
                            <td colspan="5"><?php
                                if($leave->HODActionDate==""){
                                    echo "NA";
                                } else {
                                    echo htmlentities($leave->HODActionDate);
                                }
                            ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <a class="modal-trigger waves-effect waves-light btn" href="#modal1">Take&nbsp;Action</a>
                                <form name="hodaction" method="post" id="hodactionform">
                                    <div id="modal1" class="modal modal-fixed-footer" style="height: 60%">
                                        <div class="modal-content" style="width:90%">
                                            <h4>Leave take action</h4>
                                            <select class="browser-default" name="status" required="">
                                                <option value="">Choose your option</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Not Approved</option>
                                            </select>
                                            <p><textarea id="textarea1" name="description" class="materialize-textarea" placeholder="Description" length="500" maxlength="500" required></textarea></p>
                                        </div>
                                        <div class="modal-footer" style="width:90%">
                                            <button type="submit" class="waves-effect waves-light btn blue m-b-xs" name="update" value="Submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
});
</script>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <div class="errorWrap"><strong>Error</strong> : Leave not found or not accessible.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
<script src="../assets/plugins/materialize/js/materialize.min.js"></script>
<script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
<script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/alpha.min.js"></script>
<script src="../assets/js/pages/table-data.js"></script>
<script src="assets/js/pages/ui-modals.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
});
</script>
</body>
</html>