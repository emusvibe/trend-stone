<?php 
		$details=$this->getFullDetailsPid($_SESSION['vclassuser'],"student_login");
		$details1=$this->getFullDetailsPid($_SESSION['vclassuser'],"student_details");
		$class_pid=$this->getFullDetailsPid($details[5],"classes");
?>
<div class="row" id="displayRes"></div>
<div class="row" style="margin: 0px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bgblue">
				<h3 class="panel-title"><center><span class="glyphicon glyphicon-pushpin"></span> User Profile</center></h3>
			</div>
			<div class="modal-body">
				<form method="post" action="#" class="form well" id="profileForm">
					<div class="row" style="margin: 15px;">
						<div class="col-md-5 well">
							<div class="form-group">
								<label for="indexnumber">Index Number:</label>
								<input type="text" id="indexnumber" class="form-control" name="indexnumber" placeholder="Index Number" value="<?php echo $details1[2]; ?>" required/>
							</div>
							<div class="form-group">
								<label for="fullname">Full Name:</label>
								<input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo $details1[1]; ?>" placeholder="Full Name" required/>
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
								<input type="text" id="email" name="email" class="form-control" value="<?php echo $details1[3]; ?>" placeholder="Email" required/>
							</div>
							<div class="form-group">
								<label for="mobileNo">Mobile Number:</label>
								<input type="text" maxlength="10" id="mobileNo" name="mobileNo" class="form-control" placeholder="Mobile Number" value="<?php echo $details1[4]; ?>" required/>
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
						<center><button type="submit" name="addManualBtn2" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Update Details</button>&nbsp;<a href="?home" class="btn btn-xs btn-danger br"><span class="glyphicon glyphicon-remove"></span> Close</a></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
	if(isset($_POST['addManualBtn2'])){
		$this->updateStudentDetails($_SESSION['vclassuser'],$_POST['username'],$_POST['class'],$details[6],$_POST['fullname'],$_POST['indexnumber'],$_POST['email'],$_POST['mobileNo']);
	}
?>
<script type="text/javascript">
	$('#profileForm').bootstrapValidator({
		message: 'This is not valid',
		feedbackIcons:{
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields:{
			username:{
				validators:{
					notEmtpy:{
						message: 'User Name can\'t be empty'
					},
					stringLength:{
						min: 5,
						max: 50,
						message: 'Invalid character length'
					},
					regexp:{
						regexp: /^[a-zA-Z0-9\-\ \.]+$/,
						message: 'Invalid input character'
					}
				}
			},
			fullname:{
				validators:{
					notEmtpy:{
						message: 'Full Name can\'t be empty'
					},
					stringLength:{
						min: 8,
						max: 50,
						message: 'Invalid character length'
					},
					regexp:{
						regexp: /^[a-zA-Z0-9\-\ \.]+$/,
						message: 'Invalid input character'
					}
				}
			},
			email:{
				validators:{
					notEmtpy:{
						message: 'Email can\'t be empty'
					},
					stringLength:{
						min: 8,
						max: 100,
						message: "Invalid character length"
					},
					regexp:{
						regexp: /^[a-zA-Z0-9\-\.\@]+$/,
						message: 'Invalid input character'
					},
					email:{
						message: 'Invalid email'
					}
				}
			},
			mobileNo:{
				validators:{
					notEmpty:{
						message: 'Mobile Number can\'t be empty'
					},
					stringLength:{
						min: 10,
						max: 15,
						message: 'Invalid input length'
					},
					regexp:{
						regexp: /^[0-9\+]+$/,
						message: 'Invalid input character'
					}
				}
			},
		}
	});
</script>