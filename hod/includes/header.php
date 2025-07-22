<div class="loader-bg"></div>
<div class="mn-content fixed-sidebar">
    <header class="mn-header navbar-fixed">
        <nav class="cyan darken-1">
            <div class="nav-wrapper row">
                <section class="material-design-hamburger navigation-toggle">
                    <a href="#" data-activates="slide-out" class="button-collapse show-on-large material-design-hamburger__icon">
                        <span class="material-design-hamburger__layer"></span>
                    </a>
                </section>
                <div class="header-title col s3">
                    <span class="chapter-title">ELMS | HOD Dashboard</span>
                </div>
                <ul class="right col s9 m3 nav-right-menu">
                    <li class="hide-on-med-and-down"><a href="#" class="waves-effect waves-light"><i class="material-icons">search</i></a></li>
                    <!-- Notifications Dropdown -->
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-button waves-effect waves-light" data-activates="notifications-dropdown"><i class="material-icons">notifications</i>
                            <?php 
                            $isread=0;
                            $sql = "SELECT id from tblleaves where IsRead=:isread";
                            $query = $dbh -> prepare($sql);
                            $query->bindParam(':isread',$isread,PDO::PARAM_STR);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $unreadcount=$query->rowCount();?>
                            <span class="badge"><?php echo htmlentities($unreadcount);?></span></a></li>
                        </a>
                    </li>
                    <li><a href="logout.php" class="waves-effect waves-light"><i class="material-icons">exit_to_app</i></a></li>
                </ul>
                <!-- Notifications Dropdown Structure -->
                <ul id="notifications-dropdown" class="dropdown-content notifications-dropdown">
                    <li><span class="grey-text text-darken-2">You have <?php echo htmlentities($unreadcount);?> new notifications</span></li>
                    <li class="notificatoins-dropdown-container">
                        <ul>
                            <li class="notification-drop-title">Notifications</li>
                                <?php 
                                $isread=0;
                                $sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblleaves.PostingDate from tblleaves join tblemployees on tblleaves.empid=tblemployees.id where tblleaves.IsRead=:isread";
                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':isread',$isread,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0)
                                {
                                foreach($results as $result) { 
                                    ?>  
                                        <li>
                                            <a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid);?>">
                                            <div class="notification">
                                                <div class="notification-icon circle cyan"><i class="material-icons">done</i></div>
                                                <div class="notification-text"><p><b><?php echo htmlentities($result->FirstName." ".$result->LastName);?><br />(<?php echo htmlentities($result->EmpId);?>)</b> applied for leave</p><span>at <?php echo htmlentities($result->PostingDate);?></b</span></div>
                                            </div>
                                            </a>
                                        </li>
                                    <?php 
                                }} 
                                ?> 
                            </li>   
                        </ul>
                    </li>
                    <!-- <li><a href="pending-leaves.php"><i class="material-icons">assignment</i>New leave request pending</a></li>
                    <li><a href="approved-leaves.php"><i class="material-icons">check_circle</i>Leave approved</a></li>
                    <li><a href="rejected-leaves.php"><i class="material-icons">cancel</i>Leave rejected</a></li> -->
                </ul>
            </div>
        </nav>
    </header>
