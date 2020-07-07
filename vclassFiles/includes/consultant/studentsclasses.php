<div class="row" style="margin: 15px;">
	<center><a href="#addClass" data-toggle="modal" class="btn btn-xs btn-success br" style="text-decoration: none;"><span class="glyphicon glyphicon-plus-sign"></span> Add Class</a></center>
</div>

<div class="row" style="margin: 15px;" id="displayRes"></div>

<!--add class modal -->
<div id="addClass" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<center><h3 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> Classes</h3></center>
			</div>
			<div class="modal-body">
				<form method="post" action="#" class="form">
					<div class="form-group">
						<label for="class">Class:</label>
						<input type="text" id="class" name="class" placeholder="Class" class="form-control" required/>
					</div>
					<div class="form-group">
						<center><button type="submit" class="btn btn-xs btn-success br" name="addClassBtn"><span class="glyphicon glyphicon-plus-sign"></span> Add Class</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin: 15px;">
	<?php $this->genClassesTable($_SESSION['vclassadmin']); ?>
</div>

<?php 
	if(isset($_POST['addClassBtn'])){
		$this->addClassAdmin($_POST['class'],$_SESSION['vclassadmin']);
	}
?>