<?php 
	$details=$vclass->getFullDetailsPid($_SESSION['vclassadmin'],"admin_details");
	$pwd=$vclass->getLastPassChng($_SESSION['vclassadmin']);
        $dp=$vclass->getFullDetailsPid($_SESSION['vclassadmin'],"dp");
?>
<div class="row" style="margin-top: 15px;">
	<div class="panel panel-default" style="border: 1px solid black;">
            <div class="panel-heading bgblue" style="background-color: #035888;">
              <h3 class="panel-title bgblue"><span class="glyphicon glyphicon-user"></span> Admin User Details</h3>
            </div>
            <div class="panel-body">
                <hr style='height: 0px; margin: 7px;'/>       
                <center><a href="#profilePic" data-toggle="modal" style="text-decoration: none;"><img src="../admin/uploads/images/<?php echo $dp[2]; ?>" class='img-circle img-rounded' style="width: 100px; height: auto;"></a></center>
                <hr style='height: 0px; margin: 7px;'/>
              	<?php echo $details[2]; ?>
              	<hr style='height: 0px; margin: 7px;'/>
              	<?php echo $details[3]; ?>
              	<hr  style='height: 0px; margin: 7px;'/>
              	<?php echo $details[4]; ?>
              	<hr  style='height: 0px; margin: 7px;'/>
            </div>
    </div>
    <div class="panel panel-default" style="border: 1px solid black;">
            <div class="panel-heading bgblue" style="background-color: #035888;">
              <h3 class="panel-title bgblue"><span class="glyphicon glyphicon-log-in"></span>  Last LogIn</h3>
            </div>
            <div class="panel-body">
              <?php echo $vclass->getLastLogin($_SESSION['vclassadmin']); ?>
            </div>
    </div>
    <div class="panel panel-default" style="border: 1px solid black;">
            <div class="panel-heading bgblue" style="background-color: #035888;">
              <h3 class="panel-title bgblue"><span class="glyphicon glyphicon-lock"></span>  Last Password Changed</h3>
            </div>
            <div class="panel-body">
              <?php echo $pwd[0]; ?>
            </div>
    </div>
</div>

<!-- change profile pic modal -->
<div id="profilePic" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #035888; color: #fff;">
        <h3 class="panel-title"><center><span class="glyphicon glyphicon-picture"></span> Profile Picture</center></h3>
      </div>
      <div class="modal-body">
          <form method="post" action="?dashboard" class="form" enctype="multipart/form-data">
              <div class="form-group">
                  <label for="image"><span class="glyphicon glyphicon-picture"></span> Profile Picture:</label>
                  <input type="file" accept="image/*" id="image" name="image" class="form-control" placeholder="Profile Picture" autofocus required/>
              </div>
              <div class="form-group">
                  <center><img id="displayImg" src="../admin/uploads/images/<?php echo $dp[2]; ?>" style="width: 150px; height: auto"/></center>
              </div>
              <div class="form-group">
                  <center><button type="submit" class="btn btn-xs btn-success br" name="uploadImg"><span class="glyphicon glyphicon-cloud-upload"></span> Upload</button></center>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>