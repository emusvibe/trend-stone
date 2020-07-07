<div class="row" style="margin: 15px;">
	<center><a href="#assignCourse" data-toggle="modal" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Assign course</a></center>
</div>

<div class="row" style="margin: 15px;" id="displayRes"></div>

<div class="row" style="margin: 15px;">
	<?php $this->genCourseAssignment($_SESSION['vclassadmin']); ?>
</div>

<!-- modal -->
<div id="assignCourse" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> Assign Courses</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="#" class="form">
					<div class="form-group">
						<label for="course">Course:</label>
						<select id="course" name="course" class="form-control" placeholder="Course" required>
							<?php $this->genCoursesSelect($_SESSION['vclassadmin']); ?>
						</select>
					</div>
					<div class="form-group">
						<label for="class">Class:</label>
						<select id="class" name="class" class="form-control" placeholder="Class" required>
							<?php $this->genClassSelect($_SESSION['vclassadmin']); ?>
						</select>
					</div>
					<div class="form-group">
						<center><button type="submit" name="assignCourseBtn" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Assign</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php 
	if(isset($_POST['assignCourseBtn'])){
		$this->assignCourseAdmin($_POST['course'],$_POST['class'],$_SESSION['vclassadmin']);
	}
?>