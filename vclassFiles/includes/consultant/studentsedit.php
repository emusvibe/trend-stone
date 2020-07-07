<?php 
	if(!isset($_SESSION['useredit'])){
		$this->redirect("?students");
	}else{
		$details=$this->getFullDetailsPid($_SESSION['useredit'],"student_login");
		$details1=$this->getFullDetailsPid($_SESSION['useredit'],"student_details");
		$class_pid=$this->getFullDetailsPid($details[5],"classes");
	}
?>
<div class="row" style="margin: 15px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-education"></span> Student</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="#" class="form">
                                    <div class="form-group">
						<center><button type="submit" name="addManualBtn2" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Update Details</button>&nbsp;<a href="?students" class="btn btn-xs btn-danger br"><span class="glyphicon glyphicon-remove"></span> Close</a></center>
					</div>
					<div class="row" style="margin: 15px;">
						<div class="col-md-5 well">
							<div class="form-group">
								<label for="indexnumber">Index Number:</label>
								<input type="text" id="indexnumber" class="form-control" name="indexnumber" placeholder="Index Number" value="<?php echo $details1[4]; ?>" required/>
							</div>
							<div class="form-group">
								<label for="fullname">Full Name:</label>
								<input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo $details1[2]; ?>" placeholder="Full Name" required/>
							</div>
							<div class="form-group">
								<label for="username">Username:</label>
								<input type="text" id="username" class="form-control" name="username" placeholder="Username" value="<?php echo $details[2]; ?>" required/>
							</div>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-5 well">
							<div class="form-group">
								<label for="email">Email:</label>
								<input type="text" id="email" name="email" class="form-control" value="<?php echo $details1[5]; ?>" placeholder="Email" required/>
							</div>
							<div class="form-group">
								<label for="mobileNo">Mobile Number:</label>
								<input type="text" maxlength="10" id="mobileNo" name="mobileNo" class="form-control" placeholder="Mobile Number" value="<?php echo $details1[6]; ?>" required/>
							</div>	
							<div class="form-group">
								<label for="class">Class:</label>
								<select id="class" name="class" class="form-control" placeholder="Class" required>
									<?php 
										echo "<option value='".$class_pid[2]."'>".$class_pid[1]."</option>";
										$this->genClassSelect(); 
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<center><button type="submit" name="addManualBtn2" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Update Details</button>&nbsp;<a href="?students" class="btn btn-xs btn-danger br"><span class="glyphicon glyphicon-remove"></span> Close</a></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	if(isset($_POST['addManualBtn2'])){
		$this->updateStudentAdmin($_SESSION['useredit'],$_POST['username'],$_POST['class'],$_SESSION['vclassadmin'],$_POST['fullname'],$_POST['indexnumber'],$_POST['email'],$_POST['mobileNo']);
	}
?>