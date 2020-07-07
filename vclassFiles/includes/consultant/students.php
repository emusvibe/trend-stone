
<div class="row" style="margin: 5px;">
	<div class="col-md-3">
		<center><a href="#addStudent" data-toggle="modal" class="btn btn-xs btn-primary br"><span class="glyphicon glyphicon-plus-sign"></span> Add Client</a></center>
	</div>
	<div class="col-md-3">
		<center><button type="button" onclick="processDelete()" class="btn btn-xs btn-danger br"><span class="glyphicon glyphicon-remove-sign"></span> Delete Client</button></center>
	</div>
	<div class="col-md-3">
		<center><button type="button" class="btn btn-xs btn-warning br" onclick="processActivate()"><span class="glyphicon glyphicon-eject"></span> Activate/Deactivate All Clients</a></center>
	</div>
    <div class="col-md-3">
		<center><button type="button" onclick="processDelete()" class="btn btn-xs btn-danger br"><span class="glyphicon glyphicon-remove-sign"></span> Delete All Clients</button></center>
	</div>
	<div class="col-md-3">
		<!--<center><a href="#resetPassword" data-toggle="modal" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-lock"></span> Reset Password</a></center>-->
	</div>
</div>

<div class="row" id="displayRes" style="margin: 15px;"></div>

<div class="row" style="margin: 15px;">
	<div class="col-md-3">

	</div>
<!--	<div class="col-md-5">
		<center>
                 <?php
//                 $admin_details=$this->getFullDetailsPid($_SESSION['vclassadmin'],"admin_login"); 
//                 if($admin_details[4]==0){
//                    echo '<div class="form-group">
//				<select id="class" name="class" class="form-control" onchange="processList(this.value)">
//					
//            genConsultantSelect($_SESSION[vclassadmin]);
//                
//                  
//               				</select>
//			</div>';
//                   } else {
//                            echo '<div class="form-group">
//				<select id="class" name="class" class="form-control" onchange="processList(this.value)">
//		                
//				</select>
//			</div>';
//                   } 
                 ?>
                    
                    
			<div class="form-group">
				<select id="class" name="class" class="form-control" onchange="processList(this.value)">
					<?php // $this->genClassSelect($_SESSION['vclassadmin']); ?>
             <?php 
                
//                  echo $this->genConsultantSelect($_SESSION['vclassadmin']);
               
              ?>
				</select>
			</div>
		</center>
	</div>-->
<!--	<div class="col-md-1">
		<center>
			<div class="form-group">
				<button type="button" onclick="processListDownload()" style="margin-top: 5px;" class="btn btn-xs btn-info br tooltip-bottom" title="Download List(CSV)"><span class="glyphicon glyphicon-cloud-download"></span></button>
			</div>
		</center>
	</div>-->
	<div class="col-md-3">

	</div>
</div>
<hr>

<div class="row" style="margin: 15px;" id="viewList"></div>


<!--reset password modal -->
<div id="resetPassword" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-lock"></span> Reset Password</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="?students">
					<div class="form-group">
						<label for="student">Student:</label>
						<select id="student" name="student" class="form-control" required>

						</select>
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" id="password" name="password" class="form-control" placeholder="Password" required/>
					</div>
					<div class="form-group">
						<label for="password1">Confirm Password:</label>
						<input type="password" id="password1" name="password1" class="form-control" placeholder="Confirm Password" required/>
					</div>
					<div class="form-group">
						<center><button type="submit" name="resetBtn" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-refresh"></span> Reset</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!--add student modal -->
<div id="addStudent" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-education"></span> Clients</h3></center>
			</div>
			<div class="modal-footer">
				<div class="row" style="margin: 15px;">
					<div class="col-md-4">
						<center><a href="#addManual" data-dismiss="modal" data-toggle="modal" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Manually Add Client</a></center>
					</div>
					<div class="col-md-4">
						<!--<center><button type="button" onclick="window.open('../uploads/docs/sample.csv')" class="btn btn-xs btn-warning br"><span class="glyphicon glyphicon-cloud-download"></span> Download list format</button></center>-->
					</div>
					<div class="col-md-4">
						<!--<center><a href="#uploadList" data-dismiss="modal" data-toggle="modal" class="btn btn-xs btn-info br"><span class="glyphicon glyphicon-cloud-upload"></span> Upload List</a></center>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--upload student list -->
<div id="uploadList" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-cloud-upload"></span> Student List</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="#" enctype="multipart/form-data">
					<div class="form-group">
						<center><span style='color: red; text-decoration: underline;'>Upload only csv file</span></center>
					</div>
					<div class="form-group">
						<label for="class">Class:</label>
						<select id="class" name="class" class="form-control" required>
							<?php $this->genClassSelect($_SESSION['vclassadmin']); ?>
						</select>
					</div>
					<div class="form-group">
						<label for="list">List:</label>
						<input type="file" id="list" name="list" class="form-control" placeholder="List" required/>
					</div>
					<div class="form-group">
						<center><button type="submit" name="uploadBtn" class='btn btn-xs btn-success'><span class='glyphicon glyphicon-cloud-upload'></span> Upload</button></center> 
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<!--manually add -->
<div id="addManual" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-education"></span> Client</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="?students" class="form">
					<div class="form-group">
						<label for="indexnumber">Student Number:</label>
						<input type="hidden" id="indexnumber" class="form-control" name="indexnumber" placeholder="Student Number" required/>
					</div>
					<div class="form-group">
						<label for="fullname">Full Names:</label>
						<input type="text" id="fullname" name="fullname" class="form-control" placeholder="Full Name" required/>
					</div>
                                       <div class="form-group">
						<label for="fullname">SurName:</label>
						<input type="text" id="fullname" name="surname" class="form-control" placeholder="SurName" required/>
					</div>
                                        <div class="form-group">
						<label for="id_No">ID Number:</label>
						<input type="text" id="id_No" name="id_number" class="form-control" placeholder="ID Number" required/>
					</div>
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="text" id="email" name="email" class="form-control" placeholder="Email" required/>
					</div>
					<div class="form-group">
						<label for="mobileNo">Mobile Number:</label>
						<input type="text" maxlength="10" id="mobileNo" name="mobileNo" class="form-control" placeholder="Mobile Number" required/>
					</div>	
                                      <div class="form-group">
						<label for="address">Address:</label>
						<input type="text" id="Address" name="address" class="form-control" placeholder="Address" required/>
					</div>
					<div class="form-group">
<!--						<label for="class">Class:</label>
						<select id="class" name="class" class="form-control" placeholder="Class" required>
							<?php $this->genClassSelect($_SESSION['vclassadmin']); ?>
						</select>-->
					</div>
					<div class="form-group">
						<center><button type="submit" name="addManualBtn" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Add Student</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php 
	if(isset($_POST['addManualBtn'])){
		$this->addStudentAdmin($_POST['class'],$_SESSION['vclassadmin'],
                                        $_POST['fullname'],
                                        $_POST['surname'],
                                        $_POST['id_number'],
                                        $_POST['indexnumber'],
                                        $_POST['email'],
                                        $_POST['mobileNo'],
                                        $_POST['address']);
	}elseif(isset($_POST['resetBtn'])){
		$this->resetStudentPassword($_POST['student'],$_POST['password'],"student_login");
	}elseif(isset($_POST['uploadBtn'])) {
		$this->uploadList();
	}
?>

<script>
	//default list
//	class_pid=$('#class').val();
//	processList(class_pid);

//	function processList(class_pid){
//		class_pid=$('#class').val();
//		//console.log(class_pid);
//		$.post('ajax.php',{'viewList':class_pid},function(data){
//			if(data){
//				$('#viewList').html(data);
//			}
//		});	
//		$.post('ajax.php',{'studentListSelect':class_pid},function(data){
//			if(data){
//				$('#student').html(data);
//			}
//		});
//	}
admin_pid=$('#class').val();
	processList(admin_pid);
function processList(admin_pid){
		admin_pid=$('#class').val();
		//console.log(class_pid);
		$.post('ajax.php',{'viewList':admin_pid},function(data){
			if(data){
				$('#viewList').html(data);
			}
		});	
		$.post('ajax.php',{'studentListSelect':class_pid},function(data){
			if(data){
				$('#student').html(data);
			}
		});
	}

	//processing delete
	function processDelete(){
		class_pid=$('#class').val();
		deleteReq(class_pid,"student_login2","?students");
	}

	//processing activate
	function processActivate(){
		class_pid=$('#class').val();
		updateStatusPid(class_pid,"student_login2","?students");
	}

	//processing list download
	function processListDownload(){
		class_pid=$('#class').val();
		$.post('ajax.php',{'downloadList':class_pid},function(data){
			if(data){
				window.open(data);
				//alert(data);
			}
		});
	}
</script>