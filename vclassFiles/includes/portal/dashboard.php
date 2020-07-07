<div id="displayRes"></div>
<div class="row" style="margin: 15px;">
	<center><img id="imgsrc" src="../vclass/uploads/images/logo.png" class="img-responsive" style="width: 250px; height: auto;" /><h1 style="font-family: flower; color: #035888;">TIS - VIRTUAL-CLASSROOM</h1></center>
<!--	<center><img id="imgsrc" src="../vclass/uploads/images/logo.png" class="img-responsive" style="width: 250px; height: auto;" /></center><br/>-->
        <br>
        <div class="container">
                <div class="col-lg-3 col-md-4">
                    <div class="panel panel-green">
                        <div class="panel-heading">

<h1 class="panel-title "><span class="fa fa-gears fa-4x "></span>&nbsp;&nbsp;&nbsp;Trading Software</h1>

                        </div>
                        <a href="https://blackstonefutures.co.za/wp-content/uploads/2018/02/finsapty4setup.exe_.zip" target="_blank">

                            <div class="panel-footer">
                                <span class="pull-left">Install Software</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                       <h1 class="panel-title"><span class="fa fa-book fa-4x huge"></span>&nbsp;&nbsp;&nbsp;Study Material</h1>
                        </div>
                        <a href="?intro_to_forex">
                            <div class="panel-footer">
                                <span class="pull-left">For Beginers</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                          <h1 class="panel-title"><span class="fa fa-book fa-4x huge"></span>&nbsp;&nbsp;&nbsp;Study Material</h1>
                        </div>
                        <a href="?forex_advanced">
                            <div class="panel-footer">
                                <span class="pull-left">For Advanced</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
 
             
    </div>	
      
 </div> 
<div class="row" style="margin: 15px;">      
        <center><p style="font-family: flower; color: #035888;font-size: 25px;">Copyright &copy; <?php echo date('Y'); ?>: Powered by<a href="//motifsdigital.co.za" class="btn btn-link" target="_blank" style="font-size: 25px; color: #035888; font-family: flower;"> <img src="../vclass/uploads/images/motifsdigital.png" class="img-circle" style="width: 40px; height: auto;"/>MOTIFS-DIGITAL</a></p></center>
</div>
<?php 
	if(isset($_POST['uploadImg'])){
		$this->changeProfilePicUser();
	}
?>
<script>
	var count=0;
	setInterval(function(){
		if(count %3==0){
			document.getElementById('imgsrc').setAttribute('src','../vclass/uploads/images/logo.png');
		}else if(count%3==1){
			document.getElementById('imgsrc').setAttribute('src','../vclass/uploads/images/logo.png');
		}else{
			document.getElementById('imgsrc').setAttribute('src','../vclass/uploads/images/logo.png');
		}
		count++;
	},3000);
</script>