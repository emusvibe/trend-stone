<div class="row" style="margin: 15px;">
	<center><a href="#assign" data-toggle="modal" style="text-decoration: none;" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Assign</a></center>
</div>

<div class="row" id="displayRes" style="margin: 15px;"></div>

<div class="row" style="margin: 15px;">
	<?php $this->loadAssignmentsAssigned($_SESSION['vclassadmin']); ?>
</div>

<!--assign modal -->
<div id="assign" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> Assign Assignment</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="#" class="form" id="assignForm">
					<div class="form-group">
						<label for="assignment">Assignment:</label>
						<select id="assignment" name="assignment" class="form-control" required>
						<?php $this->genAssignmentSelect($_SESSION['vclassadmin']); ?>
						</select>
					</div>
					<div class="form-group">
						<label for="class">Class:</label>
						<select id="class" name="class" class="form-control" required>
							<?php $this->genClassSelect($_SESSION['vclassadmin']); ?>
						</select>
					</div>
					<div class="form-group">
						<label for="start">Start Date:</label>
						<input type="text" id="start" name="start" placeholder="Start Date" class="form-control startDate" readonly required>
					</div>
					<div class="form-group">
						<label for="stop">Deadline:</label>
						<input type="text" id="stop" class="form-control stopDate" name="stop" placeholder="Deadline" readonly required/>
					</div>
					<div class="form-group">
						<center><button type="submit" name="assignBtn" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-plus-sign"></span> Assign</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
	if(isset($_POST['assignBtn'])){
		$this->assignAssignmentAdmin($_POST['class'],$_POST['start'],$_POST['stop'],$_POST['assignment'],$_SESSION['vclassadmin']);
	}
?>
<script type="text/javascript">

	$('#assignForm').bootstrapValidator({
		message: 'Invalid',
		feedbackIcons:{
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields:{
			assignment:{
				validators:{
					notEmpty:{
						message: 'Assignment can\'t be empty'
					}
				}
			},
			class:{
				validators:{
					notEmpty:{
						message: 'Class can\'t be empty'
					}
				}
			},
			start:{
				validators:{
					notEmpty:{
						message: 'Invalid'
					},
				}
			}
		}
	});
</script>