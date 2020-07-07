<?php 
	if(!isset($_SESSION['useredit'])){
		$this->redirect("?courses");
	}else{
		$details=$this->getFullDetailsPid($_SESSION['useredit'],"courses");
	}
?>

<div class="row" id="displayRes" style="margin: 15px;"></div>

<div class="row" style="margin: 15px;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> Courses</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="#" class="form">
					<div class="form-group">
						<label for="course_name">Course Name:</label>
						<input type="text" id="course_name" name="course_name" class="form-control" placeholder="Course Name" value="<?php echo $details[1]; ?>" required/>
					</div>
					<div class="form-group">
						<label for="course_code">Course Code:</label>
						<input type="text" id="course_code" name="course_code" class="form-control" placeholder="Course Code" value="<?php echo $details[2]; ?>" required/>
					</div>
					<div class="form-group">
						<label for="status">Status:</label>
						<select name="status" id="status" class="form-control" required>
							<?php $this->enable_disable2($details[5]); ?>
						</select>
					</div>
					<div class="form-group">
						<center><button type="submit" name="addCourseBtn" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-pencil"></span> Update Course</button> &nbsp; <a href="?courses" style="text-decoration: none;" class="btn btn-xs btn-danger br"><span class="glyphicon glyphicon-remove"></span> Close</a></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php 
	if(isset($_POST['addCourseBtn'])){
		$this->updateCoursesAdmin($_SESSION['useredit'],$_POST['course_name'],$_POST['course_code'],$_POST['status']);
	}
?>