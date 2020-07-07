<?php 
	session_start();
	require "../vclassFiles/includes/functions.php";
	$vclass = new Vclass();
	$vclass->checkIfNotLoggedInAdmin();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<title>Trendstones | VirtualClass</title>
	<?php include "../vclassFiles/includes/scripts.php"; ?>
</head> 
<body>

<!-- header -->
	<?php include "../vclassFiles/includes/admin/header.php"; ?>
<!-- end of header -->

<div class="row" style="margin: 15px;">
	<div class="col-md-3">
		<?php include "../vclassFiles/includes/admin/sidebar.php"; ?>
	</div>
	<div class="col-md-9">
		<div class="row" style="margin: 15px;">
			<?php $vclass->loadContentAdmin(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	var vname="Virtual-Classroom";
	function displayMessage(message,status){
		if(status==1){
			$('#displayRes').html('<center><span class=\'alert alert-success\' role=\'alert\'>'+message+'</span></center>').fadeOut(5000);
		}else{
			$('#displayRes').html('<center><span class=\'alert alert-danger\' role=\'alert\'>'+message+'</span></center>').fadeOut(5000);
		}
	}

	function redirect(location){
		window.location.assign(location);
	}

	function updateStatusPid(pid,table,location){
		alertify.confirm(vname,'Update status?',function(e){
			if(e){
				$.post('ajax.php',{'updateStatusPid':'y','pid':pid,'table':table},function(data){
				if(data==1){
					//delete
					displayMessage('Status Updated',1);
				}else{
					//alert(data);
					displayMessage('Process failed',0);
				}
					redirect(location);
				});
			}else{

			}
		},function(e){
			displayMessage("Process cancelled");
		});
	}

	function deleteReq(pid,table,location){
		alertify.confirm(vname,"Delete?",function(e){
			$.post('ajax.php',{'deleteReq':'y','pid':pid,'table':table},function(data){
				if(data==1){
					//delete
					displayMessage('Details Deleted',1);
				}else{
					//alert(data);
					displayMessage('Process failed',0);
				}
					redirect(location);
			});
		},function(e){
			displayMessage("Process cancelled",0);
		});
	}

	function view(pid,table,location){
		$.post('ajax.php',{'edit':'y','pid':pid,'table':table},function(data){
			if(data==1){
				redirect(location);
			}
		});
	}
	$('#tableList').DataTable({
		responsive: true
	});
</script>
</body>
</html>