<div class="row" style="margin: 15px;">
	<center><a href="#addAssignment" data-toggle="modal" class="btn btn-xs btn-success br" style="text-decoration: none;"><span class="glyphicon glyphicon-plus"></span> Add Assignment</a></center>
</div>

<div id="displayRes" class="row" style="margin: 15px;"></div>

<div class="row" style="margin: 15px;">
	<?php $this->loadAssignmentList($_SESSION['vclassadmin']); ?>
</div>

<!--add assignment modal -->
<div id="addAssignment" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> ASSIGNMENTS</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="?assignments" class="form" id="addAssignmentForm">
					<div class="form-group">
						<label for="topic">Topic:</label>
						<input type="text" id="topic" name="topic" placeholder="Topic" class="form-control" required/>
					</div>
					<div class="form-group">
						<center><button type="submit" class="btn btn-xs btn-success br" name="addAssignmentBtn"><span class="glyphicon glyphicon-plus-sign"></span> Add Assignment</button></center>
					</div>
				</form>
			</div>
		</div>	
	</div>
</div>
<?php 
	if(isset($_POST['addAssignmentBtn'])){
		$this->addAssignmentAdmin($_POST['topic'],$_SESSION['vclassadmin']);
	}
?>
<script>
	$('#addAssignmentForm').bootstrapValidator({
		message: 'Invalid',
		feedbackIcons:{
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields:{
			topic:{
				validators:{
					notEmpty:{
						message: 'Topic can\'t be empty'
					},
					stringLength:{
						min: 10,
						max: 500,
						message: 'Invalid topic length'
					},
				}
			}
		}
	});
</script>