<?php 
  $admin_details=$vclass->getFullDetailsPid($_SESSION['vclassuser'],"admin_login");
?>
<div class="row">
	<!-- Static navbar -->
      <nav class="navbar navbar-default bgblue" style="padding: 5px; margin: 0px;">
        <div class="container-fluid bgblue">
          <div class="navbar-header bgblue">
            <button type="button" class="navbar-toggle collapsed bgblue" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only" style="color: #fff;">Toggle navigation</span>
              <span class="icon-bar" style="color: #fff;"></span>
              <span class="icon-bar" style="color: #fff;"></span>
              <span class="icon-bar" style="color: #fff;"></span>
            </button>
            <a class="navbar-brand bgblue flower" href="?dashboard" style="color: #fff;"><span style="margin-left: 15px;">Trendstones Virtual-Classroom</span></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse bgblue">
            <ul class="nav navbar-nav navbar-right bgblue">
<!--              <li><a href="#" onclick="alertify.alert('Virtual-Classroom','Coming Soon')" data-toggle="modal" style="color: #fff;"><span class="glyphicon glyphicon-book"></span> Online Quizzes</a></li>
              <li><a href="?materials" style="color: #fff;"><span class="glyphicon glyphicon-book"></span> Course Materials</a></li>
              <li><a href="#assignments" data-toggle="modal" style="color: #fff;"><span class="glyphicon glyphicon-book"></span> Assignments</a></li>-->
              <li><a href="?dashboard" style="color: #fff;"><span class="glyphicon glyphicon-book"></span> Dashboard</a></li>
              <li class="dropdown">
	                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: #fff;"><span class="glyphicon glyphicon-wrench"></span> Settings <span class="caret"></span></a>
	                <ul class="dropdown-menu">
	                  <li><a href="?passwd" style="color: #000;"><span class="glyphicon glyphicon-lock"></span> Change Password</a></li>
	                  <li><a href="?profile" style="color: #000;"><span class="glyphicon glyphicon-pencil"></span> Edit Profile Details</a></li>
                    <li><a href="?logout" style="color: #000;"><span class="glyphicon glyphicon-off"></span> Log Out</a></li>
	                </ul>
	             </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

</div>
<div class="row" style="background-color: black; height: 5px;">
	&nbsp;
</div>

<!--assignment modal -->
<div id="assignments" class="modal fade">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #035888; color: #fff;">
        <center><h3 class="panel-title"><span class="glyphicon glyphicon-tag"></span> ASSIGNMENTS</h3></center>
      </div>
      <div class="modal-body">
        <div class="row" style="margin: 5px;">
          <div class="col-md-6">
            <center><a href="?assignments" style="text-decoration: none;" class="btn btn-link"><span class="glyphicon glyphicon-book"></span> ASSIGNMENTS</a></center>
          </div>
          <div class="col-md-6">
            <center><a href="?assignments&submissions" style="text-decoration: none;" class="btn btn-link"><span class="glyphicon glyphicon-book"></span> SUBMISSIONS</a></center>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="students" class="modal fade">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #035888; color: #fff;">
            <center><h3 class="panel-title"><span class="glyphicon glyphicon-education"></span> STUDENTS</h3></center>
        </div>
        <div class="modal-body">
            <div class="row" style="margin: 2px;">
              <div class="col-md-6">
                <a href="?students" style="text-decoration: none;" class="btn btn-link"><span class="glyphicon glyphicon-book"></span> LIST OF STUDENTS</a>
              </div>
              <div class="col-md-6">
                <a href="?students&classes" style="text-decoration: none;" class="btn btn-link"><span class="glyphicon glyphicon-book"></span> CREATE CLASSES</a>
              </div>
            </div>
        </div>
      </div>  
  </div>
</div>

<div id="courses" class="modal fade"> 
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #035888; color: #fff;">
            <center><h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> COURSES</h3></center>
        </div>
        <div class="modal-body">
            <div class="row" style="margin: 2px;">
              <div class="col-md-6">
                <a href="?courses" style="text-decoration: none;" class="btn btn-link"><span class="glyphicon glyphicon-book"></span> LIST OF COURSES</a>
              </div>
              <div class="col-md-6">
                <a href="?courses&assign" style="text-decoration: none;" class="btn btn-link"><span class="glyphicon glyphicon-book"></span> ASSIGN COURSES</a>
              </div>
            </div>
        </div>
      </div>  
  </div>
</div>