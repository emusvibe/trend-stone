<div class="row" style="margin: 15px;">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<center><a href="#upload" style="text-decoration: none;" data-toggle="modal" class="btn btn-xs btn-warning br"><span class="glyphicon glyphicon-cloud-upload"></span> Upload Course Material</a></center>
	</div>
	</div>
	<div class="col-md-4"></div>
</div>
<div class="row" id="displayRes" style="margin: 15px;"></div>
<hr>

<div class="row" style="margin: 15px;">
	<?php $this->genCourseMaterials(); ?>
</div>

<!--upload modal -->
<div id="upload" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #035888; color: #fff;">
				<h3 class="panel-title"><center><span class="glyphicon glyphicon-bookmark"></span> Course Materials</center></h3>
			</div>
			<div class="modal-body">
				<form method="post" action="#" class="form" enctype="multipart/form-data">
					<div class="form-group">
						<input type="text" id="title" name="title" class="form-control" placeholder="Title" required>
					</div>
					<div class="form-group">
						<input type="file" name="file" id="file" class="form-control" required/>
					</div>
					<div class="form-group">
						<center><button type="submit" name="addMaterial" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-cloud-upload"></span> Upload</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
	if(isset($_POST['addMaterial'])){
		$this->addCourseMaterial();
	}
?>