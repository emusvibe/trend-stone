<div id="displayRes"></div>
<div class="row" style="margin: 15px;">
	<center><h1 style="font-family: flower; color: #035888;">TIS - VIRTUAL-CLASSROOM - ADMIN</h1></center>
	<center><img id="imgsrc" src="../admin/uploads/images/logo.png" class="img-responsive" style="width: 250px; height: auto;" /></center><br/>
	<center><p style="font-family: flower; color: #035888;font-size: 25px;">Copyright &copy; <?php echo date('Y'); ?>: Powered by<a href="//motifsdigital.co.za" class="btn btn-link" target="_blank" style="font-size: 25px; color: #035888; font-family: flower;"> <img src="../admin/uploads/images/motifsdigital.png" class="img-circle" style="width: 40px; height: auto;"/>MOTIFS-DIGITAL</a></p></center>
</div>
<?php 
	if(isset($_POST['uploadImg'])){
		$this->changeProfilePicAdmin();
	}
?>
<script>
	var count=0;
	setInterval(function(){
		if(count %3==0){
			document.getElementById('imgsrc').setAttribute('src','../admin/uploads/images/logo.png');
		}else if(count%3==1){
			document.getElementById('imgsrc').setAttribute('src','../admin/uploads/images/logo.png');
		}else{
			document.getElementById('imgsrc').setAttribute('src','../admin/uploads/images/logo.png');
		}
		count++;
	},3000);
</script>