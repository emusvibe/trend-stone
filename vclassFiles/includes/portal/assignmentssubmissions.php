<div class="row" style="margin: 15px;">
	<center><legend><span class="glyphicon glyphicon-tags"></span> Assignment Submissions</legend></center>
</div>

<div id="displayRes" class="row" style="margin: 15px;"></div>

<div class="row" style="margin: 15px;">
	<?php $this->loadSubmissions($_SESSION['vclassuser']); ?>
</div>
