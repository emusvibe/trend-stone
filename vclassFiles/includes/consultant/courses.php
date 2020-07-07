<div class="row" style="margin: 15px;">
	<center><a href="#addCourses" data-toggle="modal" class="btn btn-xs btn-success br" style="text-decoration:none;"><span class="glyphicon glyphicon-plus"></span> Add Course</a></center>
</div>

<div class="row" style="margin: 15px;" id="displayRes"></div>

<div class="row" style="margin: 15px;">
<?php $this->genCourses($_SESSION['vclassadmin']); ?>
</div>

<!--adding modals -->
<div id="addCourses" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> Courses</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="?courses" class="form">
					<div class="form-group">
						<label for="course_name">Course Name:</label>
						<input type="text" id="course_name" name="course_name" class="form-control" placeholder="Course Name" required/>
					</div>
					<div class="form-group">
						<label for="course_code">Course Code:</label>
						<input type="text" id="course_code" name="course_code" class="form-control" placeholder="Course Code" required/>
					</div>
					<div class="form-group">
						<label for="status">Status:</label>
						<select name="status" id="status" class="form-control" required>
							<?php $this->enable_disable(); ?>
						</select>
					</div>
					<div class="form-group">
						<center><button type="submit" name="addCourseBtn" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Add Course</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
	if(isset($_POST['addCourseBtn'])){
		$this->addCoursesAdmin($_POST['course_name'],$_POST['course_code'], $_SESSION['vclassadmin'],$_POST['status']);
	}
?>