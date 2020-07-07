<div class="row" style="margin: 15px;">
	<div class="col-md-3">
		<center>
			<form method="post" action="#" class="form">
				<input type="hidden" id="assignment_pid" name="assignment_pid" value=" "/>
				<input type="hidden" id="class_pid" name="class_pid" value=" "/>
				<button type="submit" name="downloadZip" id="downloadZip" class="btn btn-xs btn-warning br"><span class="glyphicon 	glyphicon-cloud-download"></span> Download Files</button>
			</form>
		</center>
	</div>
	<div class="col-md-3">
		<center><button type="button" id="clearSubmissionsBtn" onclick="clearSubmissions()" class="btn btn-xs btn-info br"><span class="glyphicon glyphicon-refresh"></span> Clear Submissions</button></center>
	</div>
	<div class="col-md-3">
		<center>
			<form method="post" action="#" class="form">
				<input type="hidden" name="ass_pid" id="ass_pid" value=" "/>
				<input type="hidden" name="cl_pid" id="cl_pid" value=" "/>
				<button type="submit" id="downloadListTrue" name="downloadListTrue" class="btn btn-xs btn-success br"><span class="glyphicon glyphicon-cloud-download"></span> Submitted List</button>
			</form>
		</center>
	</div>
	<div class="col-md-3">
		<center>
			<form method="post" action="#" class="form">
				<input type="hidden" name="ass_pid1" id="ass_pid1" value=" "/>
				<input type="hidden" name="cl_pid1" id="cl_pid1" value=" "/>
				<button type="submit" id="downloadListFalse" name="downloadListFalse" class="btn btn-xs btn-danger br"><span class="glyphicon glyphicon-cloud-download"></span> Not Submited List</button>
			</form>
		</center>
	</div>
</div>

<?php 
	if(isset($_POST['downloadZip'])){
		$this->downloadZip();
	}elseif(isset($_POST['downloadListTrue'])){
		$this->downloadSubmissionList(1);
	}elseif(isset($_POST['downloadListFalse'])){
		$this->downloadSubmissionList(0);
	}
?>

<div class="row" style="margin: 15px;" id="displayRes"></div>

<div class="row" style="margin: 15px;">
	<div class="col-md-1"></div>
	<div class="col-md-5">
		<center>
			<div class="form-group">
				<select id="assignment" name="assignment" class="form-control" onchange="processClassList(this.value)">
					<option value=" ">Select</option>
					<?php $this->genAssignmentSelect($_SESSION['vclassadmin']); ?>
				</select>
			</div>
		</center>
	</div>
	<div class="col-md-5">
		<center>
			<div class="form-group">
				<select id="class" name="class" class="form-control" onchange="processList()">
				</select>
			</div>
		</center>
	</div>
	<div class="col-md-1"></div>
</div>
<hr>

<div class="row" id="viewList" style="margin: 15px;"></div>

<script>
	//default list
	assignment_pid=$('#assignment').val();
	class_pid=$('#class').val();
	processClassList(assignment_pid);
	
	function processClassList(assignment_pid){
		$.post('ajax.php',{'processClassList':assignment_pid},function(data){
			if(data){
				$('#class').html(data);
			}
		});
		processList();
	}

	function processList(){
		assignment_pid=$('#assignment').val();
		class_pid=$('#class').val();

		if(class_pid==" " || assignment_pid ==" "){
			document.getElementById('downloadZip').setAttribute('disabled','true');
			document.getElementById('clearSubmissionsBtn').setAttribute('disabled','true');
			document.getElementById('downloadListTrue').setAttribute('disabled','true');
			document.getElementById('downloadListFalse').setAttribute('disabled','true');
		}else{
			document.getElementById('downloadZip').removeAttribute('disabled');
			document.getElementById('clearSubmissionsBtn').removeAttribute('disabled');
			document.getElementById('downloadListTrue').removeAttribute('disabled');
			document.getElementById('downloadListFalse').removeAttribute('disabled');
		}

		document.getElementById('assignment_pid').value=assignment_pid;
		document.getElementById('class_pid').value=class_pid;

		document.getElementById('ass_pid').value=assignment_pid;
		document.getElementById('cl_pid').value=class_pid;

		document.getElementById('ass_pid1').value=assignment_pid;
		document.getElementById('cl_pid1').value=class_pid;

		$.post('ajax.php',{'submissionsView':'y','assignment_pid':assignment_pid,'class_pid':class_pid},function(data){
			if(data){
				//alert(data);
				$('#viewList').html(data);
			}
			$('#tableList').DataTable({
				responsive: true
			});
		});
	}

	function clearSubmissions(){
		assignment_pid=$('#assignment').val();
		class_pid=$('#class').val();
		alertify.confirm(vname,"Reset?",function(e){
			$.post('ajax.php',{'clearSubmissions':'y','assignment_pid':assignment_pid,'class_pid':class_pid},function(data){
				if(data==1){
					//delete
					displayMessage('Process Complete..',1);
				}else{
					alert(data);
					displayMessage('Process failed',0);
				}
					//redirect("?assignments&submissions");
			});
		},function(e){
			displayMessage("Process cancelled",0);
		});
	}
</script>
