<?php
	require "dbconfig.php";
	class Vclass{


		##############################################################################
		#############################.............API.........########################
		private $con;

		function __construct(){
			date_default_timezone_set("Africa/Accra");
			$this->con = new PDO("mysql:host=".HOST.";dbname=".DB_NAME."",DB_USERNAME,DB_PASSWORD);
			$this->con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$this->activatessl();
		}

		function activatessl(){
			/*echo "<script>
					if (window.location.protocol != \"https:\")
			    		window.location.href = \"https:\" + window.location.href.substring(window.location.protocol.length);
				</script>";*/
		}

		function sanitize($data){
			return htmlentities(trim($data));
		}

		function sanitize2($data){
			return trim($data);
		}

		function redirect($location){
			echo "<script>
					window.location.assign('".$location."');
				</script>";
		}

		function displayMsg($message,$status){
			$message=$this->sanitize($message);
			$status=$this->sanitize($status);

			if($status==1){
				echo "<script>
					$('#displayRes').html('<center><span class=\'alert alert-success\' role=\'alert\'>".$message."</span></center>').fadeOut(5000);
					</script>";
			}else{
				echo "<script>
					$('#displayRes').html('<center><span class=\'alert alert-danger\' role=\'alert\'>".$message."</span></center>').fadeOut(5000);
					</script>";
			}
		}

		function genPid(){
			return substr(md5(uniqid(mt_rand(), true)) , 0, 8);
		}

		function getFullDetailsPid($pid,$table){
			$pid=$this->sanitize($pid);
			$table=$this->sanitize($table);
			if($table=="courses"){
				$query="select * from ".$table." where course_pid=?";
			}elseif($table=="classes"){
				$query="select * from ".$table." where class_pid=?";
			}elseif($table=="student_details"){
				$query="select * from ".$table." where student_pid=?";
			}elseif($table=="student_login"){
				$query="select * from ".$table." where student_pid=?";
			}elseif($table=="assignments"){
				$query="select * from ".$table." where assignment_pid=?";
			}else{
				$query="select * from ".$table." where pid=?";
			}
			$result=$this->con->prepare($query);
			$result->bindParam("s",$pid);
			$result->execute(array($pid));
			return $result->fetch();
		}

		function getFullDetails($username,$table){
			$username=$this->sanitize($username);
			$table=$this->sanitize($table);
			$query="select * from ".$table." where username=?";
			$result=$this->con->prepare($query);
			$result->bindParam("s",$username);
			$result->execute(array($username));
			return $result->fetch();
		}

		function genDate(){
			return date('Y-m-d h:i:s');
		}

		function updateLastLogin($pid){
			$pid=$this->sanitize($pid);
			$curDate=$this->genDate();
			$query="insert into lastlogin(pid,date) values(?,?)";
			$result=$this->con->prepare($query);
			$result->bindParam("s",$pid);
			$result->bindParam("s",$curDate);
			$result->execute(array($pid,$curDate));
		}

		function updateLastPassChng($pid){
			$pid=$this->sanitize($pid);
			$curDate=$this->genDate();
			$query="insert into lastpasschng(pid,date) values(?,?)";
			$result=$this->con->prepare($query);
			$result->bindParam("s",$pid);
			$result->bindParam("s",$curDate);
			$result->execute(array($pid,$curDate));
		}

		function getLastLogin($pid){
			$data=null;
			$pid=$this->sanitize($pid);
			$query="select date from lastlogin where pid=? order by date desc limit 2";
			$result=$this->con->prepare($query);
			$result->bindParam("s",$pid);
			$result->execute(array($pid));
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data=$row['date'];
			}
			return $data;
		}

		function getLastPassChng($pid){
			$pid=$this->sanitize($pid);
			$query="select date from lastpasschng where pid=? order by date limit 1";
			$result=$this->con->prepare($query);
			$result->bindParam("s",$pid);
			$result->execute(array($pid));
			return $result->fetch();
		}


		function verifyCredentials($username,$password,$table){
			$username=$this->sanitize($username);
			$password=md5($this->sanitize($password));
			$table=$this->sanitize($table);

			$sql="select * from ".$table." where username=? and password=? and status=1";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$username);
			$result->bindParam("s",$password);
			$result->execute(array($username,$password));
			if($result->rowCount() >=1){
				return true;
			}else{
				return false;
			}

		}

		function updateAdminProfile($pid,$username,$fullname,$email,$mobileNo){
			$pid=$this->sanitize($pid);
			$username=$this->sanitize($username);
			$fullname=$this->sanitize($fullname);
			$email=$this->sanitize($email);
			$mobileNo=$this->sanitize($mobileNo);
			$query="update admin_login set username=? where pid=?";
			$result=$this->con->prepare($query);
			$result->bindParam("s",$username);
			$result->bindParam("s",$pid);
			if($result->execute(array($username,$pid))){
				$query1="update admin_details set fullname=?,email=?,mobileNo=? where pid=?";
				$result1=$this->con->prepare($query1);
				$result1->bindParam("s",$fullname);
				$result1->bindParam("s",$email);
				$result1->bindParam("s",$mobileNo);
				$result1->bindParam("s",$pid);
				if($result1->execute(array($fullname,$email,$mobileNo,$pid))){
					return true;
				}else{
					return false;
				}
			}else{	
				return false;
			}
		}

		function updatePassword($pid,$oldpassword,$newpassword,$table){
			$pid=$this->sanitize($pid);
			$oldpassword=md5($this->sanitize($oldpassword));
			$newpassword=md5($this->sanitize($newpassword));
			$table=$this->sanitize($table);
			if($table=="student_login"){
				$query="select * from ".$table." where student_pid=? and password=?";
			}else{
				$query="select * from ".$table." where pid=? and password=?";	
			}
			$result=$this->con->prepare($query);
			$result->bindParam("s",$pid);
			$result->bindParam("s",$oldpassword);
			$result->execute(array($pid,$oldpassword));
			if($result->rowCount()>=1){
				//user found
				if($table=="student_login"){
					$query1="update ".$table." set password=? where student_pid=?";
				}else{
					$query1="update ".$table." set password=? where pid=?";
				}
				$result1=$this->con->prepare($query1);
				$result1->bindParam("s",$newpassword);
				$result1->bindParam("s",$pid);
				if($result1->execute(array($newpassword,$pid))){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		function addUser($username,$password,$fullname,$email,$mobileNo){
			$username=$this->sanitize($username);
			$password=md5($this->sanitize($password));
			$fullname=$this->sanitize($fullname);
			$email=$this->sanitize($email);
			$mobileNo=$this->sanitize($mobileNo);
			$pid=$this->genPid();
			$uid=1;
			$status=1;
			$logo=date("Y-m-d-h-i-s").".png";
			copy("../consultant/uploads/images/dp.png", "../consultant/uploads/images/".$logo);
			$sql="insert into admin_login(pid,username,password,uid,status) values(?,?,?,?,?)";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$pid);
			$result->bindParam("s",$username);
			$result->bindParam("s",$password);
			$result->bindParam("i",$uid);
			$result->bindParam("i",$status);
			if($result->execute(array($pid,$username,$password,$uid,$status))){
				//processing second query
				$sql1="insert into admin_details(pid,fullname,email,mobileNo) values(?,?,?,?)";
				$result1=$this->con->prepare($sql1);
				$result1->bindParam("s",$pid);
				$result1->bindParam("s",$fullname);
				$result1->bindParam("s",$email);
				$result1->bindParam("s",$mobileNo);
				if($result1->execute(array($pid,$fullname,$email,$mobileNo))){
					//processing default dp
					$sql2="insert into dp(pid,image) values(?,?)";
					$result2=$this->con->prepare($sql2);
					$result2->bindParam("s",$pid);
					$result2->bindParam("s",$logo);
					if($result2->execute(array($pid,$logo))){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		function activateAccountPid($pid,$status,$table){
			$pid=$this->sanitize($pid);
			$status=$this->sanitize($status);
			$table=$this->sanitize($table);
			if($pid=="all"){
				$sql="update ".$table." set status=? where uid !=0";
				$result=$this->con->prepare($sql);
				$result->bindParam("i",$status);
				if($result->execute(array($status))){
					return true;
				}else{
					return false;
				}
			}else{
				$sql="update ".$table." set status=? where pid=?";
				$result=$this->con->prepare($sql);
				$result->bindParam("i",$status);
				$result->bindParam("s",$pid);
				if($result->execute(array($status,$pid))){
					return true;
				}else{
					return false;
				}
			}
		}

		function verifyDataApi($pid,$table){
			$pid=$this->sanitize($pid);
			$table=$this->sanitize($table);
			if($table=='courses'){
				$sql="select * from ".$table." where course_pid=?";
			}elseif($table=='student_login'){
				$sql="select * from ".$table." where student_pid=?";
			}else{
				$sql="select * from ".$table." where pid=?";
			}
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$pid);
			$result->execute(array($pid));
			if($result->rowCount() >=1){
				return true;
			}else{
				return false;
			}
		}

		function delAccount($pid,$table){
			$pid=$this->sanitize($pid);
			$table=$this->sanitize($table);
			if($pid=="all"){
				$sql="update ".$table." set status=2 where uid !=0";
				if($this->con->query($sql)){
					return true;
				}else{
					return false;
				}
			}else{
				$sql="update ".$table." set status=2 where pid=?";
				$result=$this->con->prepare($sql);
				$result->bindParam("s",$pid);
				if($result->execute(array($pid))){
					return true;
				}else{
					return false;
				}
			}
		}

		function resetPassword($pid,$password,$table){
			$pid=$this->sanitize($pid);
			$password=md5($this->sanitize($password));
			$table=$this->sanitize($table);
			if($table=="student_login"){
				$sql="update ".$table." set password=? where student_pid=?";
			}else{
				$sql="update ".$table." set password=? where pid=?";
			}
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$password);
			$result->bindParam("s",$pid);
			if($result->execute(array($password,$pid))){
				return true;
			}else{
				return false;
			}
		}

		function addCourses($course_name,$course_code,$admin_pid,$status){
			$course_name=$this->sanitize($course_name);
			$status=$this->sanitize($status);
			$course_pid="course".$this->genPid();
			$sql="insert into courses(course_name,course_code,course_pid,admin_pid,status) values(?,?,?,?,?)";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$course_name);
			$result->bindParam("s",$course_code);
			$result->bindParam("s",$course_pid);
			$result->bindParam("s",$admin_pid);
			$result->bindParam("i",$status);
			if($result->execute(array($course_name,$course_code,$course_pid,$admin_pid,$status))){
				return true;
			}else{
				return false;
			}
		}

		function updateStatusPid($pid,$table){
			$pid=$this->sanitize($pid);
			$table=$this->sanitize($table);
			if($table=='courses'){
				$sql="update courses set status=(status + 1)%2 where course_pid=?";
			}elseif($table=='student_login'){
				$sql="update ".$table." set status=(status + 1)%2 where student_pid=?";
			}elseif($table=='student_login2'){
				$sql="update student_login set status=(status + 1)%2 where class_pid=?";
			}else{
				$sql="update ".$table." set status=(status + 1)%2 where pid=?";
			}
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$pid);
			if($result->execute(array($pid))){
				return true;
			}else{
				return false;
			}
		}

		function deleteReq($pid,$table){
			$pid=$this->sanitize($pid);
			$table=$this->sanitize($table);
			if($table=='courses'){
				$sql="update courses set status=2 where course_pid=?";
			}elseif($table=='course_assignment'){
				$sql="delete from course_assignment where id=?";
			}elseif($table=='student_login'){
				$sql="update ".$table." set status=2 where student_pid=?";
			}elseif($table=='student_login2'){
				$sql="update student_login set status=2 where class_pid=?";
			}elseif($table=='assignments'){
				$assignments=$this->getFullDetailsPid($pid, "assignments");
				$sql="delete from assignments where assignment_pid=?";
				$this->rrmdir("../uploads/docs/".$assignments[4]);
			}elseif($table=='assign_assignment'){
				$sql="delete from ".$table." where id=?";
			}elseif($table=='classes'){
				$sql="delete from classes where id=?";
			}elseif($table=='course_materials'){
				$sql1="select file from course_materials where id=?";
				$result1=$this->con->prepare($sql1);
				$result1->bindParam("i",$pid);
				$result1->execute(array($pid));
				$data=$result1->fetch();
				unlink("../uploads/docs/".$data[0]);
				$sql="delete from course_materials where id=?";
			}else{
				$sql="update ".$table." set status=2 where pid=?";
			}
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$pid);
			if($result->execute(array($pid))){
				return true;
			}else{
				return false;
			}
		}

		function updateCourses($course_pid,$course_name,$course_code,$status){
			$course_pid=$this->sanitize($course_pid);
			$course_name=$this->sanitize($course_name);
			$course_code=$this->sanitize($course_code);
			$status=$this->sanitize($status);
			$sql="update courses set course_name=?,course_code=?,status=? where course_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$course_name);
			$result->bindParam("s",$course_code);
			$result->bindParam("i",$status);
			$result->bindParam("s",$course_pid);
			if($result->execute(array($course_name,$course_code,$status,$course_pid))){
				return true;
			}else{
				return false;
			}
		}

		function assignCourse($course_pid,$class_pid,$admin_pid){
			$course_pid=$this->sanitize($course_pid);
			$class_pid=$this->sanitize($class_pid);
			$admin_pid=$this->sanitize($admin_pid);
			$sql="insert into course_assignment(course_pid,class_pid,admin_pid) values(?,?,?)";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$course_pid);
			$result->bindParam("s",$class_pid);
			$result->bindParam("s",$admin_pid);
			if($result->execute(array($course_pid,$class_pid,$admin_pid))){
				return true;
			}else{
				return false;
			}
		}

		function addClass($class_name,$admin_pid){
			$class_name=$this->sanitize($class_name);
			$class_pid="class".$this->genPid();
			$admin_pid=$this->sanitize($admin_pid);
			$sql="insert into classes(class_name,class_pid,admin_pid) values(?,?,?)";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$class_name);
			$result->bindParam("s",$class_pid);
			$result->bindParam("s",$admin_pid);
			if($result->execute(array($class_name,$class_pid,$admin_pid))){
				return true;
			}else{
				false;
			}
		}

		function addStudent($class_pid,$admin_pid,$fullname,$id_number, $indexnumber, $email, $mobileNo,$address){
			$class_pid=$this->sanitize($class_pid);
			$admin_pid=$this->sanitize($admin_pid);
			$fullname=$this->sanitize($fullname);
                        $id_number=$this->sanitize($id_number);
			$indexnumber=$this->sanitize($indexnumber);
			$email=$this->sanitize($email);
			$mobileNo=$this->sanitize($mobileNo);
                        $address=$this->sanitize($address);
			$student_pid="std".$this->genPid();
			$password=md5($indexnumber);
//                        $fullname,$id_number, $indexnumber, $email, $mobileNo,$address../admin/uploads/images
			//adding default image
			$logo=date("Y-m-d-h-i-s").".png";
			copy("../vclass/uploads/images/dp.png", "../vclass/uploads/images/".$logo);

			$sql="insert into student_login(student_pid,username,password,class_pid,admin_pid) values(?,?,?,?,?)";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$student_pid);
			$result->bindParam("s",$indexnumber);
			$result->bindParam("s",$password);
			$result->bindParam("s",$class_pid);
			$result->bindParam("s",$admin_pid);
			if($result->execute(array($student_pid,$indexnumber,$password,$class_pid,$admin_pid))){
				$sql1="insert into student_details(student_pid,fullname,id_number,indexnumber,email,mobileNo,address,admin_pid) values(?,?,?,?,?,?,?,?)";
				$result1=$this->con->prepare($sql1);
				$result1->bindParam("s",$student_pid);
				$result1->bindParam("s",$fullname);
                                $result1->bindParam("s",$id_number);
				$result1->bindParam("s",$indexnumber);
				$result1->bindParam("s",$email);
				$result1->bindParam("s",$mobileNo);
                                $result1->bindParam("s",$address);
				$result1->bindParam("s",$admin_pid);
				if($result1->execute(array($student_pid,$fullname,$id_number,$indexnumber,$email,$mobileNo,$address,$admin_pid))){
					//adding default image
					$sql2="insert into dp(pid,image) values(?,?)";
					$result2=$this->con->prepare($sql2);
					$result2->bindParam("s",$student_pid);
					$result2->bindParam("s",$logo);
					if($result2->execute(array($student_pid,$logo))){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{	
				false;
			}

		}

		function updateStudent($student_pid,$username,$class_pid,$admin_pid,$fullname,$indexnumber,$email,$mobileNo){
			$student_pid=$this->sanitize($student_pid);
			$class_pid=$this->sanitize($class_pid);
			$admin_pid=$this->sanitize($admin_pid);
			$fullname=$this->sanitize($fullname);
			$indexnumber=$this->sanitize($indexnumber);
			$email=$this->sanitize($email);
			$mobileNo=$this->sanitize($mobileNo);
			$username=$this->sanitize($username);
			

			$sql="update student_login set username=?,class_pid=?,admin_pid=? where student_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$username);
			$result->bindParam("s",$class_pid);
			$result->bindParam("s",$admin_pid);
			$result->bindParam("s",$student_pid);
			if($result->execute(array($username,$class_pid,$admin_pid,$student_pid))){
				$sql1="update student_details set fullname=?, indexnumber=?,email=?,mobileNo=?,admin_pid=? where student_pid=?";
				$result1=$this->con->prepare($sql1);
				$result1->bindParam("s",$fullname);
				$result1->bindParam("s",$indexnumber);
				$result1->bindParam("s",$email);
				$result1->bindParam("s",$mobileNo);
				$result1->bindParam("s",$admin_pid);
				$result1->bindParam("s",$student_pid);
				if($result1->execute(array($fullname,$indexnumber,$email,$mobileNo,$admin_pid,$student_pid))){
					return true;
				}else{
					return false;
				}
			}else{	
				false;
			}

		}

		function addAssignment($topic,$admin_pid){
			$topic=$this->sanitize($topic);
			$assignment_pid="assignment".$this->genPid();
			$admin_pid=$this->sanitize($admin_pid);
			$sql="insert into assignments(topic,assignment_pid,admin_pid,path) values(?,?,?,?)";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$topic);
			$result->bindParam("s",$assignment_pid);
			$result->bindParam("s",$admin_pid);
			$result->bindParam("s",$assignment_pid);
			mkdir("../uploads/docs/".$assignment_pid);
			if($result->execute(array($topic,$assignment_pid,$admin_pid,$assignment_pid))){
				return true;
			}else{
				return false;
			}

		}

		function assignAssignment($class_pid,$start,$stop,$assignment_pid,$admin_pid){
				$class_pid=$this->sanitize($class_pid);
				$start=$this->sanitize($start);
				$stop=$this->sanitize($stop);
				$assignment_pid=$this->sanitize($assignment_pid);
				$admin_pid=$this->sanitize($admin_pid);
				$sql="insert into assign_assignment(class_pid,start,stop,assignment_pid,admin_pid) values(?,?,?,?,?)";
				$result=$this->con->prepare($sql);
				$result->bindParam("s",$class_pid);
				$result->bindParam("s",$start);
				$result->bindParam("s",$stop);
				$result->bindParam("s",$assignment_pid);
				$result->bindParam("s",$admin_pid);
				if($result->execute(array($class_pid,$start,$stop,$assignment_pid,$admin_pid))){
					return true;
				}else{
					return false;
				}
		}

		function addCourseMaterial(){
			$admin_pid=$this->sanitize($_SESSION['vclassadmin']);
			$title=$this->sanitize($_POST['title']);
			if(is_uploaded_file($_FILES['file']['tmp_name'])){
				//file uploaded
				$allowed=['pdf','docx','doc','zip','rar','tex'];
				$filename=$this->sanitize($_FILES['file']['name']);
				$ext=explode(".", $filename);
				$ext=strtolower(end($ext));
				if(in_array($ext,$allowed)){
					//correct file uploaded
					$new_file_name=$this->genPid()."_".$filename;
					if(move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/docs/".$new_file_name)){
						$sql="insert into course_materials(title,file,admin_pid) values(?,?,?)";
						$result=$this->con->prepare($sql);
						$result->bindParam("s",$title);
						$result->bindParam("s",$new_file_name);
						$result->bindParam("s",$admin_pid);
						if($result->execute(array($title,$new_file_name,$admin_pid))){
							$this->displayMsg("Course Material Added...", 1);
							$this->redirect("?courses&materials");
						}else{
							$this->displayMsg("Process failed..", 0);
							$this->redirect("?courses&materials");
						}
					}
				}else{
					$this->displayMsg("File not allowed!!!", 1);
					$this->redirect("?courses&materials");
				}
			}else{
				$this->displayMsg("Process failed..", 0);
				$this->redirect("?courses&materials");
			}
		}

		function genCourseMaterials(){
			$admin_pid=$this->sanitize($_SESSION['vclassadmin']);
			$data="<table id='tableList' class='table table-bordered table-condensed table-hover table-striped'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Title</center></th><th></th></tr></thead>";
			$data.="<tbody>";
			$sql="select * from course_materials order by title";
			$result=$this->con->query($sql);
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data.="<tr><td><center>".$count."</center></td><td>".$row['title']."</td><td>";
				$data.="<div class='row' style='margin: 0px; padding: 0px;'>
					<div class='col-md-6' style='padding: 0px; margin: 0px;'>
						<center><button type='button' class='btn btn-info br btn-xs' onclick=\"window.open('../uploads/docs/".$row['file']."')\"><span class='glyphicon glyphicon-cloud-download'></span></button></center>
					</div>
					<div class='col-md-6' style='padding: 0px; margin: 0px;'>
						<center><button type='button' class='btn btn-danger br btn-xs' onclick=\"deleteReq(".$row['id'].",'course_materials','?courses&materials')\"><span class='glyphicon glyphicon-remove-circle'></span></button></center>
					</div>
					</div>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}

		function genCourseMaterials2(){
			$student_pid=$this->sanitize($_SESSION['vclassuser']);
			$admin_pid=$this->getFullDetailsPid($student_pid, "student_login");
			$admin_pid=$admin_pid[6];
			$data="<table id='tableList' class='table table-bordered table-condensed table-hover table-striped'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Title</center></th><th></th></tr></thead>";
			$data.="<tbody>";
			$sql="select * from course_materials order by title";
			$result=$this->con->query($sql);
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data.="<tr><td><center>".$count."</center></td><td>".$row['title']."</td><td>";
				$data.="<div class='row' style='margin: 0px; padding: 0px;'>
						<center><button type='button' class='btn btn-info br btn-xs' onclick=\"window.open('uploads/docs/".$row['file']."')\"><span class='glyphicon glyphicon-cloud-download'></span></button></center>
					</div>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}
		############################...END OF API.....###################################
		#################################################################################
		

		############################.......Administrator........#########################
		function loadContentAdmin(){
			if(isset($_GET['home'])){
				include "admin/dashboard.php";
			}elseif(isset($_GET['logout'])) {
				unset($_SESSION['vclassadmin']);
				$this->redirect("login.php");
			}elseif(isset($_GET['passwd'])){
				include "admin/password.php";
			}elseif(isset($_GET['profile'])){
				include "admin/profile.php";
			}elseif(isset($_GET['users'])){
				if(isset($_GET['edit'])){
					include "admin/usersedit.php";
				}else{
					include "admin/users.php";
				}
			}elseif(isset($_GET['courses'])){
				if(isset($_GET['edit'])){
					include "admin/coursesedit.php";
				}elseif(isset($_GET['assign'])){
					include "admin/coursesassign.php";
				}elseif(isset($_GET['materials'])){
					include "admin/coursesmaterials.php";
				}else{
					include "admin/courses.php";	
				}
			}elseif(isset($_GET['students'])){
				if(isset($_GET['edit'])){
					include "admin/studentsedit.php";
				}elseif(isset($_GET['classes'])){
					include "admin/studentsclasses.php";
				}else{
					include "admin/students.php";
				}
			}elseif(isset($_GET['assignments'])){
				if(isset($_GET['assign'])){
					include "admin/assignmentsassign.php";
				}elseif(isset($_GET['submissions'])){
					include "admin/assignmentssubmissions.php";
				}else{
					include "admin/assignments.php";
				}
			}else{
				include "admin/dashboard.php";
			}
		}

               	function loadContentAnalyst(){
			if(isset($_GET['home'])){
				include "consultant/dashboard.php";
			}elseif(isset($_GET['logout'])) {
				unset($_SESSION['vclassadmin']);
				$this->redirect("login.php");
			}elseif(isset($_GET['passwd'])){
				include "consultant/password.php";
			}elseif(isset($_GET['profile'])){
				include "consultant/profile.php";
			}elseif(isset($_GET['users'])){
				if(isset($_GET['edit'])){
					include "consultant/usersedit.php";
				}else{
					include "consultant/users.php";
				}
			}elseif(isset($_GET['courses'])){
				if(isset($_GET['edit'])){
					include "consultant/coursesedit.php";
				}elseif(isset($_GET['assign'])){
					include "consultant/coursesassign.php";
				}elseif(isset($_GET['materials'])){
					include "consultant/coursesmaterials.php";
				}else{
					include "consultant/courses.php";	
				}
			}elseif(isset($_GET['students'])){
				if(isset($_GET['edit'])){
					include "consultant/studentsedit.php";
				}elseif(isset($_GET['classes'])){
					include "consultant/studentsclasses.php";
				}else{
					include "consultant/students.php";
				}
			}elseif(isset($_GET['assignments'])){
				if(isset($_GET['assign'])){
					include "consultant/assignmentsassign.php";
				}elseif(isset($_GET['submissions'])){
					include "consultant/assignmentssubmissions.php";
				}else{
					include "consultant/assignments.php";
				}
			}else{
				include "consultant/dashboard.php";
			}
		}
                
		function checkIfNotLoggedIn(){
			if(!isset($_SESSION['vclassuser'])){
				$this->redirect("login.php");
			}
		}

		function checkIfLoggedIn(){
			if(isset($_SESSION['vclassuser'])){
				$this->redirect("index.php");
			} 
		}

		function checkIfNotLoggedInAdmin(){
			if(!isset($_SESSION['vclassadmin'])){
				$this->redirect("login.php");
			}
		}

		function checkIfLoggedInAdmin(){
			if(isset($_SESSION['vclassadmin'])){
				$this->redirect("admin/index.php");
			}elseif(isset($_SESSION['vclassconsultant'])){
				$this->redirect("index.php");
			}
		}

		function verifyAdmin($username,$password){
			if($this->verifyCredentials($username, $password,"admin_login")){
				$details=$this->getFullDetails($username, "admin_login");
				$this->updateLastLogin($details[1]);
				$_SESSION['vclassadmin']=$details[1];
				$this->displayMsg("LogIn successful...", 1);
                                if ($details[4]==0){
                                  $this->redirect("../admin/index.php");
                                } else {
                                  $this->redirect("../consultant/index.php");
                                 }
				
			}else{
				$this->displayMsg("LogIn failed...", 0);
				$this->redirect("login.php");
			}
		}
                
		function updateAdminProfile2($pid,$username,$fullname,$email,$mobileNo){
			if($this->updateAdminProfile($pid, $username, $fullname, $email, $mobileNo)){
				$this->displayMsg("Profile updated..", 1);
				$this->redirect("?profile");
			}
		}

		function updateUserProfile($pid,$username,$fullname,$email,$mobileNo){
			if($this->updateAdminProfile($pid, $username, $fullname, $email, $mobileNo)){
				$this->displayMsg("Profile updated..", 1);
				unset($_SESSION['useredit']);
				$this->redirect("?users");
			}
		}

		function updateAdminPassword($pid,$oldpassword,$newpassword){
			if($this->updatePassword($pid, $oldpassword, $newpassword, "admin_login")){
				$this->updateLastPassChng($pid);
				$this->displayMsg("Password updated...", 1);
				$this->redirect("?logout");
			}else{
				$this->displayMsg("Process failed..", 0);
				$this->redirect("?passwd");
			}
		}

		function updateStudentPassword($pid,$oldpassword,$newpassword){
			if($this->updatePassword($pid, $oldpassword, $newpassword, "student_login")){
				$this->updateLastPassChng($pid);
				$this->displayMsg("Password updated...", 1);
				$this->redirect("?logout");
			}else{
				$this->displayMsg("Process failed..", 0);
				$this->redirect("?passwd");
			}
		}

		function changeProfilePicAdmin(){
			$pid=$this->sanitize($_SESSION['vclassadmin']);
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				//file uploaded
				$dp=$this->getFullDetailsPid($pid, "dp");
				$ext=['jpg','jpeg','png'];
				//getting file extension
				$file_ext=explode(".", $_FILES['image']['name']);
				$file_ext=strtolower(end($file_ext));
				if(in_array($file_ext, $ext)){
					//good
					$curDate=date('Y-m-d-h-i-s');
					$new_file_name=$curDate.".".$file_ext;
					if(move_uploaded_file($_FILES['image']['tmp_name'], '../admin/uploads/images/'.$new_file_name)){
						//file uploaded; delete previous image
						unlink("../admin/uploads/images/".$dp[2]);
						$query="update dp set image=? where pid=?";
						$result=$this->con->prepare($query);
						$result->bindParam("s",$new_file_name);
						$result->bindParam("s",$pid);
						if($result->execute(array($new_file_name,$pid))){
							$this->displayMsg("Profile Picture updated", 1);
						}else{
							$this->displayMsg("Process failed..", 0);
						}
					}else{
						$this->displayMsg("File Upload failed...", 0);
					}
				}else{
					$this->displayMsg("Wrong file uploaded...", 0);
				}
			}else{
				$this->displayMsg("No file uploaded...", 0);
			}
				$this->redirect("?dashboard");
		}

                function changeProfilePicAnalyst(){
			$pid=$this->sanitize($_SESSION['vclassadmin']);
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				//file uploaded
				$dp=$this->getFullDetailsPid($pid, "dp");
				$ext=['jpg','jpeg','png'];
				//getting file extension
				$file_ext=explode(".", $_FILES['image']['name']);
				$file_ext=strtolower(end($file_ext));
				if(in_array($file_ext, $ext)){
					//good
					$curDate=date('Y-m-d-h-i-s');
					$new_file_name=$curDate.".".$file_ext;
					if(move_uploaded_file($_FILES['image']['tmp_name'], '../consultant/uploads/images/'.$new_file_name)){
						//file uploaded; delete previous image
						unlink("../consultant/uploads/images/".$dp[2]);
						$query="update dp set image=? where pid=?";
						$result=$this->con->prepare($query);
						$result->bindParam("s",$new_file_name);
						$result->bindParam("s",$pid);
						if($result->execute(array($new_file_name,$pid))){
							$this->displayMsg("Profile Picture updated", 1);
						}else{
							$this->displayMsg("Process failed..", 0);
						}
					}else{
						$this->displayMsg("File Upload failed...", 0);
					}
				}else{
					$this->displayMsg("Wrong file uploaded...", 0);
				}
			}else{
				$this->displayMsg("No file uploaded...", 0);
			}
				$this->redirect("?dashboard");
		}

                
                
		function addAdminUser($username,$password,$fullname,$email,$mobileNo){
			if($this->addUser($username, $password, $fullname, $email, $mobileNo)){
				$this->displayMsg("User Added", 1);
			}else{
				$this->displayMsg("Process failed..", 0);
			}
			$this->redirect("?users");
		}

		function loadUsers(){
			$sql="select * from admin_login where uid !=0 and status != 2";
			$result=$this->con->query($sql);
			$data="<table class='table table-bordered table-condensed table-hover' id='tableList'>";
			$data.="<thead>
					<tr><th><center>No.</center></th><th><center>Full Name</center></th><th><center>Email</center></th><th><center>Mobile Number</center></th><th></th></tr>
				   </thead>";
			$data.="<tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$details=$this->getFullDetailsPid($row['pid'], "admin_details");
				$data.="<tr><td>".$count."</td><td>".$details[2]."</td><td>".$details[3]."</td><td>".$details[4]."</td>";
				$data.="<td>
					<div class='row' style='margin: 0px; padding: 0px;'>
						<div class='col-sm-4' style='padding: 0px;'>
							<center><a href='#".$row['pid']."toggle' data-toggle='modal' ";
							if($row['status']==1){
								$data.="class='btn btn-xs btn-success br tooltip-bottom' title='Click to deactivate'><span class='glyphicon glyphicon-eye-open'></span>";
							}else{
								$data.="class='btn btn-xs btn-warning br tooltip-bottom' title='Click to activate'><span class='glyphicon glyphicon-eye-close'></span>";
							}
							$data.="</a></center>
						</div>
						<div class='col-sm-4' style='padding: 0px;'>
							<center><button type='button' class='btn btn-xs btn-info br tooltip-bottom' title='View/Edit Details' onclick=\"view('".$row['pid']."','admin_login','?users&edit')\"><span class='glyphicon glyphicon-pencil'></span></button></center>
						</div>
						<div class='col-sm-4' style='padding: 0px;'>
							<center><a href='#".$row['pid']."delete' data-toggle='modal' class='btn btn-xs btn-danger br tooltip-bottom' title='Delete User Account'><span class='glyphicon glyphicon-remove-sign'></span></a></center>
						</div>
					</div>
				</td></tr>";
				$count++;
			}
			$data.="</tbody>";
			$data.="</table>";

			//generating toggle modals
			$sql="select * from admin_login where uid !=0";
			$result=$this->con->query($sql);
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data.="<div id='".$row['pid']."toggle' class='modal fade'>";
				$data.="<div class='modal-dialog modal-sm'>";
				$data.="<div class='modal-content'>";
				$data.="<div class='modal-header' style='background-color: #035888; color: #fff;'>";
				$data.="<center><h3 class='panel-title'><span class='glyphicon glyphicon-flash'></span> Account Status</h3></center>";
				$data.="</div>";
				$data.="<div class='modal-body'>";
				$data.="<div class='row' style='margin: 15px;'>";
				$data.="<div class='col-md-6'>";
				$data.="<form method='post' action='?users'>
					<center><button type='submit' class='btn btn-xs btn-success br' name='activateBtn' value='".$row['pid']."'><span class='glyphicon glyphicon-ok'></span> Activate</button></center>
					</form>";
				$data.="</div>";
				$data.="<div class='col-md-6'>";
				$data.="<form method='post' action='?users'>
					<center><button type='submit' class='btn btn-xs btn-danger br' name='deactivateBtn' value='".$row['pid']."'><span class='glyphicon glyphicon-remove'></span> Deactivate</button></center>
					</form>";
				$data.="</div>";
				$data.="</div>";
				$data.="</div>";
				$data.="</div>";
				$data.="</div>";	
				$data.="</div>";				
			}

			//generating delete modals
			$sql="select * from admin_login where uid !=0";
			$result=$this->con->query($sql);
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data.="<div id='".$row['pid']."delete' class='modal fade'>";
				$data.="<div class='modal-dialog modal-sm'>";
				$data.="<div class='modal-content'>";
				$data.="<div class='modal-header' style='background-color: #035888; color: #fff;'>";
				$data.="<center><h3 class='panel-title'><span class='glyphicon glyphicon-flash'></span> Delete Account</h3></center>";
				$data.="</div>";
				$data.="<div class='modal-body'>";
				$data.="<div class='row' style='margin: 15px;'>";
				$data.="<div class='col-md-6'>";
				$data.="<form method='post' action='?users'>
					<center><button type='submit' class='btn btn-xs btn-success br' name='deleteBtn' value='".$row['pid']."'><span class='glyphicon glyphicon-ok'></span> Delete Account</button></center>
					</form>";
				$data.="</div>";
				$data.="<div class='col-md-6'>";
				$data.="<center><a href='#' style='text-decoration: none;' class='btn btn-xs btn-danger br' data-dismiss='modal'><span class='glyphicon glyphicon-remove'></span> Close</a></center>";
				$data.="</div>";
				$data.="</div>";
				$data.="</div>";
				$data.="</div>";
				$data.="</div>";	
				$data.="</div>";				
			}

			echo $data;
		}
		//activateAccountPid($pid,$status,$table)

		function activateAccount($pid,$status,$table){
			if($this->activateAccountPid($pid,$status,$table)){
				$this->displayMsg("Account status updated..",1);
			}else{
				$this->displayMsg("Process failed..",0);
			}
			$this->redirect("?users");
		}

		function verifyData($pid,$table){
			if($this->verifyDataApi($pid,$table)){
				$_SESSION['useredit']=$this->sanitize($pid);
				echo 1;
			}else{
				echo 0;
			}
		}

		function deleteUserAccount($pid,$table){
			if($this->delAccount($pid,$table)){
				$this->displayMsg("Account Deleted..",1);
			}else{
				$this->displayMsg("Process failed..",0);
			}
			$this->redirect("?users");
		}

		function genUsersOption(){
			$sql="select pid from admin_login where status=1 or status=0";
			$result=$this->con->query($sql);
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$details=$this->getFullDetailsPid($row['pid'],"admin_details");
				echo "<option value='".$row['pid']."'>".$details[2]."</option>";
			}
		}

		function resetUserPassword($pid,$password,$table){
			if($this->resetPassword($pid,$password,$table)){
				$this->updateLastPassChng($pid);
				$this->displayMsg("Password updated..",1);
			}else{
				$this->displayMsg("Process failed...",0);
			}
			$this->redirect("?users");
		}

		function resetStudentPassword($pid,$password,$table){
			if($this->resetPassword($pid,$password,$table)){
				$this->updateLastPassChng($pid);
				$this->displayMsg("Password updated..",1);
			}else{
				$this->displayMsg("Process failed...",0);
			}
			$this->redirect("?students");
		}

		function genCourses($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from courses where admin_pid=? and  status=1 or status=0";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			$data="<table class='table table-bordered table-condensed table-hover' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Course Name</center></th><th><center>Course Code</center></th><th></th></tr></thead>";
			$data.="<tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data.="<tr><td><center>".$count."</center></td><td>".$row['course_name']."</td><td>".$row['course_code']."</td>";
				$data.="<td>";
				$data.="<div class='row' style='margin: 0px; paddding: 0px;'>";
				$data.="<div class='col-md-4' style='margin: 0px; padding: 0px;'>";
				$data.="<center>";
				if($row['status']==1){
					$data.="<button type='button' class='btn btn-xs btn-success br' onclick=\"updateStatusPid('".$row['course_pid']."','courses','?courses')\"><span class='glyphicon glyphicon-eye-open'></span></button>";
				}else{
					$data.="<button type='button' class='btn btn-xs btn-warning br' onclick=\"updateStatusPid('".$row['course_pid']."','courses','?courses')\"><span class='glyphicon glyphicon-eye-close'></span></button>";
				}
				$data.="</center>";
				$data.="</div>";

				$data.="<div class='col-md-4' style='padding: 0px; margin: 0px;'>";
				$data.="<center><button type='button' class='btn btn-xs btn-info br' onclick=\"view('".$row['course_pid']."','courses','?courses&edit')\"><span class='glyphicon glyphicon-pencil'></span></center>";
				$data.="</div>";

				$data.="<div class='col-md-4' style='padding: 0px; margin: 0px;'>";
				$data.="<center><button type='button' onclick=\"deleteReq('".$row['course_pid']."','courses','?courses')\" class='btn btn-xs btn-danger br'><span class='glyphicon glyphicon-remove'></span></center>";
				$data.="</div>";				

				$data.="</div>";
				$data.="</td></tr>";
				$count++;
			}
			$data.="</tbody>";
			$data.="</table>";
			echo $data;
		}

		function enable_disable(){
			echo "<option value='1'>Enable</option>
					<option value='0'>Disable</option>";
		}

		function enable_disable2($value){
			$value=$this->sanitize($value);
			if($value==1){
				echo "<option value='1'>Enable</option>
					<option value='0'>Disable</option>";
			}else{
					echo "<option value='0'>Disable</option>
					<option value='1'>Enable</option>";
			}
		}


		function addCourseAdmin($course_name,$course_code,$admin_pid,$status){
			if($this->addCourses($course_name,$course_code,$admin_pid, $status)){
				$this->displayMsg("Course Added...", 1);
			}else{
				$this->displayMsg("Process failed..", 0);
			}
				$this->redirect("?courses");
		}

		function updateStatusAdminPid($pid,$table){
			if($this->updateStatusPid($pid, $table)){
				echo 1;
			}else{
				echo 0;
			}
		}

		function deleteReqAdmin($status,$table){
			if($this->deleteReq($status, $table)){
				echo 1;
			}else{
				echo 0;
			}
		}

		function updateCoursesAdmin($course_pid,$course_name,$course_code,$status){
			if($this->updateCourses($course_pid, $course_name, $course_code, $status)){
				$this->displayMsg("Course updated...", 1);
			}else{
				$this->displayMsg("Process failed...", 0);
			}
				unset($_SESSION['useredit']);
				$this->redirect("?courses");
		}

		function genCoursesSelect($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from courses where status=1 and admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				echo "<option value='".$row['course_pid']."'>".$row['course_name']."</option>";
			}
		}

		function genAssignmentSelect($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from assignments where admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				echo "<option value='".$row['assignment_pid']."'>".$row['topic']."</option>";
			}	
		}


		function genAssignedAssignmentSelect($student_pid){
			$student_pid=$this->sanitize($student_pid);
			$curDate=$this->genDate();
			$class_pid=$this->getFullDetailsPid($student_pid, "student_login");
			$sql="select * from assign_assignment where class_pid=? and stop > ?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$class_pid[5]);
			$result->bindParam("s",$curDate);
			$result->execute(array($class_pid[5],$curDate));
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$details=$this->getFullDetailsPid($row['assignment_pid'], "assignments");
				echo "<option value='".$row['assignment_pid']."'>".$details[1]."</option>";
			}	
		}

		function genClassSelect($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from classes where admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				echo "<option value='".$row['class_pid']."'>".$row['class_name']."</option>";
			}	
		}
                function genConsultantSelect($pid){
			$pid=$this->sanitize($pid);
			$sql="select * from admin_login where uid !=0 and status != 2";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$pid);
			$result->execute(array($pid));
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				echo "<option value='".$row['pid']."'>".'Select Consultant'."</option>",
                            "<option value='".$row['pid']."'>".$row['username']."</option>";
			}	
		}
		function assignCourseAdmin($course_pid,$class_pid,$admin_pid){
			if($this->assignCourse($course_pid, $class_pid, $admin_pid)){
				$this->displayMsg("Course Assigned..", 1);
			}else{
				$this->displayMsg("Process failed..", 0);
			}
				$this->redirect("?courses&assign");
		}

		function genCourseAssignment($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from course_assignment where admin_pid=? order by course_pid";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			$data="<table class='table table-bordered table-condensed table-hover' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Course Name</center></th><th><center>Class</center></th><th></th></tr></thead>";
			$data.="<tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$courses=$this->getFullDetailsPid($row['course_pid'], "courses");
				$class=$this->getFullDetailsPid($row['class_pid'], "classes");
				$data.="<tr><td><center>".$count."</center></td><td>".$courses[1]."</td><td>".$class[1]."</td><td><center><button type='button' class='btn btn-xs btn-danger br' onclick=\"deleteReq('".$row['id']."','course_assignment','?courses&assign')\"><span class='glyphicon glyphicon-remove'></span></button></center></td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}


		function addClassAdmin($class_name,$admin_pid){
			if($this->addClass($class_name,$admin_pid)){
				$this->displayMsg("Class Added..", 1);
			}else{
				$this->displayMsg("Process failed..", 0);
			}
				$this->redirect("?students&classes");
		}

		function genClassesTable($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from classes where admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			$data="<table class='table table-bordered table-condensed table-hover table-striped' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Class Name</center></th><th></th></tr></thead>";
			$data.="<tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data.="<tr><td><center>".$count."</center></td><td>".$row['class_name']."</td><td><center><button type='button' class='btn btn-xs btn-danger br' onclick=\"deleteReq('".$row['id']."','classes','?students&classes')\"><span class='glyphicon glyphicon-remove'></span></button></center></td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}

		function addStudentAdmin($class_pid,$admin_pid,$fullname,$surname,$id_number,$indexnumber,$email,$mobileNo,$address){
			if($this->addStudent($class_pid, $admin_pid, $fullname,$surname, $id_number,$indexnumber,$email,$mobileNo,$address)){
				$this->displayMsg("Student Added...", 1);
			}else{
				$this->displayMsg("Process failed...", 0);
			}
				$this->redirect("?students");
		}

		function updateStudentAdmin($student_pid,$username,$class_pid,$admin_pid,$fullname,$surname,$id_number, $indexnumber, $email, $mobileNo,$address){
			if($this->updateStudent($student_pid,$username,$class_pid, $admin_pid, $fullname,$surname,$id_number, $indexnumber, $email, $mobileNo,$address)){
				$this->displayMsg("Student Details updated...", 1);
				unset($_SESSION['useredit']);
			}else{
				$this->displayMsg("Process failed...", 0);
			}
				$this->redirect("?students");
		}

		function updateStudentDetails($student_pid,$username,$class_pid,$admin_pid,$fullname,$surname,$id_number,$indexnumber,$email,$mobileNo,$address){
			if($this->updateStudent($student_pid,$username,$class_pid, $admin_pid, $fullname,$surname,$id_number, $indexnumber, $email, $mobileNo,$address)){
				$this->displayMsg("Details updated...", 1);
			}else{
				$this->displayMsg("Process failed...", 0);
			}
				$this->redirect("?profile");
		}

		function viewList($class_pid){
			$class_pid=$this->sanitize($class_pid);
			$sql="select * from student_login where admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$class_pid);
			$result->execute(array($class_pid));
			$data="<table class='table table-bordered table-condensed table-hover table-striped' id='tableList'>";
			$data.="<thead><tr>
                                <th><center>No.</center></th>
                                <th><center>Student Number</center></th>
                                <th><center>Full Names </center></th>
                                <th><center>ID No </center></th>
                                <th><center>Contact </center></th>
                                <th><center>Email </center></th>
                                <th><center>Address </center></th>
                                <th></th>
                                </tr></thead>";
			$data.="<tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				if($row['status']==2){
					continue;
				}
				$details=$this->getFullDetailsPid($row['student_pid'], "student_details");
				$data.="<tr>
                                        <td><center>".$count."</center></td>
                                        <td><center>".$details[5]."</center></td>
                                             <td><center>".$details[2]."  ".$details[3]."</center></td>
                                                 <td><center>".$details[4]."</center></td>
                                                     <td><center>".$details[7]."</center></td>
                                          <td><center>".$details[6]."</center></td>
                                        <td>".$details[8]."</td>";
                                
				$data.="<td>";
				$data.="<div class='row' style='margin: 0px; paddding: 0px;'>";
				$data.="<div class='col-md-4' style='margin: 0px; padding: 0px;'>";
				$data.="<center>";
				if($row['status']==1){
					$data.="<button type='button' class='btn btn-xs btn-success br' onclick=\"updateStatusPid('".$row['student_pid']."','student_login','?students')\"><span class='glyphicon glyphicon-eye-open'></span></button>";
				}else{
					$data.="<button type='button' class='btn btn-xs btn-warning br' onclick=\"updateStatusPid('".$row['student_pid']."','student_login','?students')\"><span class='glyphicon glyphicon-eye-close'></span></button>";
				}
				$data.="</center>";
				$data.="</div>";

				$data.="<div class='col-md-4' style='padding: 0px; margin: 0px;'>";
				$data.="<center><button type='button' class='btn btn-xs btn-info br' onclick=\"view('".$row['student_pid']."','student_login','?students&edit')\"><span class='glyphicon glyphicon-pencil'></span></center>";
				$data.="</div>";

				$data.="<div class='col-md-4' style='padding: 0px; margin: 0px;'>";
				$data.="<center><button type='button' onclick=\"deleteReq('".$row['student_pid']."','student_login','?students')\" class='btn btn-xs btn-danger br'><span class='glyphicon glyphicon-remove'></span></center>";
				$data.="</div>";
				$data.="</td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			$data.="<script>
					$('#tableList').DataTable({responsive: true});
				</script>";
			echo $data;
		}

                function viewList1($class_pid){
			$class_pid=$this->sanitize($class_pid);
			$sql="select * from student_login where admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$class_pid);
			$result->execute(array($class_pid));
			$data="<table class='table table-bordered table-condensed table-hover table-striped' id='tableList'>";
			$data.="<thead><tr>
                                <th><center>No.</center></th>
                                <th><center>Student Number</center></th>
                                <th><center>Full Names </center></th>
                                <th><center>ID No </center></th>
                                <th><center>Contact </center></th>
                                <th><center>Email </center></th>
                                <th><center>Address </center></th>
                                <th></th>
                                </tr></thead>";
			$data.="<tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				if($row['status']==2){
					continue;
				}
				$details=$this->getFullDetailsPid($row['student_pid'], "student_details");
				$data.="<tr>
                                        <td><center>".$count."</center></td>
                                        <td><center>".$details[5]."</center></td>
                                             <td><center>".$details[2]."  ".$details[3]."</center></td>
                                                 <td><center>".$details[4]."</center></td>
                                                     <td><center>".$details[7]."</center></td>
                                          <td><center>".$details[6]."</center></td>
                                        <td>".$details[8]."</td>";
                                
				$data.="<td>";
				$data.="<div class='row' style='margin: 0px; paddding: 0px;'>";
				$data.="<div class='col-md-4' style='margin: 0px; padding: 0px;'>";
				$data.="<center>";
				if($row['status']==1){
					$data.="<button type='button' class='btn btn-xs btn-success br' onclick=\"updateStatusPid('".$row['student_pid']."','student_login','?students')\"><span class='glyphicon glyphicon-eye-open'></span></button>";
				}else{
					$data.="<button type='button' class='btn btn-xs btn-warning br' onclick=\"updateStatusPid('".$row['student_pid']."','student_login','?students')\"><span class='glyphicon glyphicon-eye-close'></span></button>";
				}
				$data.="</center>";
				$data.="</div>";

				$data.="<div class='col-md-4' style='padding: 0px; margin: 0px;'>";
				$data.="<center><button type='button' class='btn btn-xs btn-info br' onclick=\"view('".$row['student_pid']."','student_login','?students&edit')\"><span class='glyphicon glyphicon-pencil'></span></center>";
				$data.="</div>";

				$data.="<div class='col-md-4' style='padding: 0px; margin: 0px;'>";
				$data.="<center><button type='button' onclick=\"deleteReq('".$row['student_pid']."','student_login','?students')\" class='btn btn-xs btn-danger br'><span class='glyphicon glyphicon-remove'></span></center>";
				$data.="</div>";
				$data.="</td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			$data.="<script>
					$('#tableList').DataTable({responsive: true});
				</script>";
			echo $data;
		}

		function studentListSelect($class_pid){
			$class_pid=$this->sanitize($class_pid);
			$sql="select * from student_login where class_pid=? and status=1 or status=0";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$class_pid);
			$result->execute(array($class_pid));
			$data=null;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$details=$this->getFullDetailsPid($row['student_pid'], "student_details");
				$data.="<option value='".$row['student_pid']."'>".$details[4]."</option>";
			}
			echo $data;
		}

		function processClassList($assignment_pid){
			$assignment_pid=$this->sanitize($assignment_pid);
			$sql="select class_pid from assign_assignment where assignment_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$assignment_pid);
			$result->execute(array($assignment_pid));
			$data="<option value=' '>Select</option>";
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$classes=$this->getFullDetailsPid($row['class_pid'], "classes");
				$data.="<option value='".$classes[2]."'>".$classes[1]."</option>";
			}
			echo $data;
		}

		function processSubmissonsList($assignment_pid,$class_pid){
			$assignment_pid=$this->sanitize($assignment_pid);
			$class_pid=$this->sanitize($class_pid);
			$admin_pid=$this->sanitize($_SESSION['vclassadmin']);
			$data="<table class='table table-bordered table-condensed table-striped table-responsive' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Index Number</center></th><th><center>Full Name</center></th><th><center>Assignment</center></th><th><center>Class</center></th><th></th></tr></thead><tbody>";

			$sql="select * from assignment_submissions where assignment_pid=? and class_pid=? and admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$assignment_pid);
			$result->bindParam("s",$class_pid);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($assignment_pid,$class_pid,$admin_pid));
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$student_details=$this->getFullDetailsPid($row['student_pid'], "student_details");
				$assignments=$this->getFullDetailsPid($row['assignment_pid'], "assignments");
				$classes=$this->getFullDetailsPid($row['class_pid'], "classes");
				$data.="<tr><td><center>".$count."</center></td><td>".$student_details[2]."</td><td>".$student_details[1]."</td><td>".$assignments[1]."</td><td>".$classes[1]."</td><td><center><button type='button' onclick=\"window.open('../uploads/docs/".$assignments[4]."/".$row['file']."')\" class='btn btn-info btn-xs br'><span class='glyphicon glyphicon-cloud-download'></span></button></center></td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}

		function loadAssignmentList($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from assignments where admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			$data="<table class='table table-bordered table-condensed table-hover table-striped' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Topic</center></th><th></th></tr></thead><tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data.="<tr><td><center>".$count."</center></td><td>".$row['topic']."</td><td><center><button type='button' class='btn btn-xs btn-danger br' onclick=\"deleteReq('".$row['assignment_pid']."','assignments','?assignments')\"><span class='glyphicon glyphicon-remove'></span></button></center></td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}


		function addAssignmentAdmin($topic,$admin_pid){
			if($this->addAssignment($topic, $admin_pid)){
				$this->displayMsg("Assignment Added", 1);
			}else{
				$this->displayMsg("Process failed..", 0);
			}
				$this->redirect("?assignments");
		}

		function loadAssignmentsAssigned($admin_pid){
			$admin_pid=$this->sanitize($admin_pid);
			$sql="select * from assign_assignment where admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($admin_pid));
			$data="<table class='table table-bordered table-condensed table-hover table-striped' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Assignment</center></th><th><center>Class</center></th><th><center>Start Date</center></th><th><center>Deadline</center></th><th></th></tr></thead><tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$assignment=$this->getFullDetailsPid($row['assignment_pid'], "assignments");
				$class=$this->getFullDetailsPid($row['class_pid'], "classes");
				$data.="<tr><td><center>".$count."</center></td><td>".$assignment[1]."</td><td>".$class[1]."</td><td><center>".$row['start']."</center></td><td><center>".$row['stop']."</center></td><td><center><button type='button' onclick=\"deleteReq('".$row['id']."','assign_assignment','?assignments&assign')\" class='btn btn-xs btn-danger br'><span class='glyphicon glyphicon-remove'></span></button></center></td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}

		function assignAssignmentAdmin($class_pid,$start,$stop,$assignment_pid,$admin_pid){
			if($this->assignAssignment($class_pid, $start, $stop, $assignment_pid, $admin_pid)){
				$this->displayMsg("Assignment assigned...", 1);
			}else{
				$this->displayMsg("Process failed...", 0);
			}
				$this->redirect("?assignments&assign");
		}

		function uploadList(){
			$class_pid=$this->sanitize($_POST['class']);
			if(is_uploaded_file($_FILES['list']['tmp_name'])){
				//file uploaded
				$accepted=['csv'];
				$file_name=$_FILES['list']['name'];
				$ext=explode(".",$file_name);
				$ext=strtolower(end($ext));
				$admin_pid=$_SESSION['vclassadmin'];
				if(in_array($ext, $accepted)){
					//csv file uploaded...
					$handle=fopen($_FILES['list']['tmp_name'], "r");
					$count=1;
					while(($data=fgetcsv($handle,1000,",")) !== false){
						if($count==1){
							$count++;
							continue;
						}
						//$class_pid,$admin_pid,$fullname,$indexnumber,$email,$mobileNo
						$this->addStudent($class_pid, $admin_pid, $data[1], $data[0], $data[2], $data[3]);
						$count++;
					}
						$this->displayMsg("Process complete..", 1);
						$this->redirect("?students");
				}else{
					$this->displayMsg("Process failed...", 0);
					$this->redirect("?students");
				}
			}else{
				$this->displayMsg("Process failed...", 0);
				$this->redirect("?students");
			}
		}

		function clearSubmissions($assignment_pid,$class_pid){
			$assignment_pid=$this->sanitize($assignment_pid);
			$class_pid=$this->sanitize($class_pid);
			$admin_pid=$this->sanitize($_SESSION['vclassadmin']);
			$sql="select path from assignments where assignment_pid=? and admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$assignment_pid);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($assignment_pid,$admin_pid));
			$path=$result->fetch();
			$this->rrmdir("../uploads/docs/".$path[0]."/");
			$sql1="delete from assignment_submissions where assignment_pid=? and class_pid=? and admin_pid=?";
			$result1=$this->con->prepare($sql1);
			$result1->bindParam("s",$assignment_pid);
			$result1->bindParam("s",$class_pid);
			$result1->bindParam("s",$admin_pid);
			$result1->execute(array($assignment_pid,$class_pid,$admin_pid));
			echo 1;
		}

		function rrmdir($dir) { 
		   if (is_dir($dir)) { 
		     $objects = scandir($dir); 
		     foreach ($objects as $object) { 
		       if ($object != "." && $object != "..") { 
		         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
		       } 
		     } 
		     reset($objects); 
		     rmdir($dir); 
		   } 
		 } 

		 function delTree($dir) { 
		    $files = glob( $dir . '*', GLOB_MARK ); 
		    foreach( $files as $file ){ 
		        if( substr( $file, -1 ) == '/' ) 
		            delTree( $file ); 
		        else 
		            unlink( $file ); 
		    } 

		    if (is_dir($dir)) rmdir( $dir ); 

		}

		function downloadZip(){
			$assignment_pid=$this->sanitize($_POST['assignment_pid']);
			$class_pid=$this->sanitize($_POST['class_pid']);
			$admin_pid=$this->sanitize($_SESSION['vclassadmin']);

			$zip = new ZipArchive();
			//getting storage path
			$sql="select * from assignments where assignment_pid=? and admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$assignment_pid);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($assignment_pid,$admin_pid));
			$assignment_result=$result->fetch();
			$path=$assignment_result[4];

		    $filename = "../uploads/docs/".$path.".zip";
		    if(file_exists($filename)){
		    	unlink($filename);
		    }

		    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		        exit("cannot open <$filename>\n");
		    }

		    $dir = '../uploads/docs/'.$path."/";
		    if (is_dir($dir)){

		        if ($dh = opendir($dir)){
		            while (($file = readdir($dh)) !== false){

		                if($file != '' && $file != '.' && $file != '..'){
		                    $zip->addFile($dir.$file);
		                }
		                    
		            }
		            closedir($dh);
		        }
		    }
		    $zip->close();

		    //download file
		    if (file_exists($filename)) {
		        echo "<script>
		        		window.open('".$filename."');
		        		window.location.assign('?assignments&submissions');
		        	</script>";
		    }
		}

		function downloadSubmissionList($data){
			$data=$this->sanitize($data);
			$admin_pid=$this->sanitize($_SESSION['vclassadmin']);
			if($data==1){
				$assignment_pid=$this->sanitize($_POST['ass_pid']);
				$class_pid=$this->sanitize($_POST['cl_pid']);

				//getting filename
				$sql="select * from assignments where assignment_pid=? and admin_pid=?";
				$result=$this->con->prepare($sql);
				$result->bindParam("s",$assignment_pid);
				$result->bindParam("s",$admin_pid);
				$result->execute(array($assignment_pid,$admin_pid));
				$assignment_result=$result->fetch();
				$filename="submitted_".$assignment_result[4].".csv";

				if(file_exists("../uploads/docs/".$filename)){
					unlink("../uploads/docs/".$filename);
				}

				//creating handler
				$handler=fopen("../uploads/docs/".$filename,"w");
				fputcsv($handler, array("No","Index Number","Full Name","Assignment","Class"));


				$sql="select * from assignment_submissions where assignment_pid=? and class_pid=? and admin_pid=?";
				$result=$this->con->prepare($sql);
				$result->bindParam("s",$assignment_pid);
				$result->bindParam("s",$class_pid);
				$result->bindParam("s",$admin_pid);
				$result->execute(array($assignment_pid,$class_pid,$admin_pid));
				$count=1;
				while($row=$result->fetch(PDO::FETCH_ASSOC)){
					$student_details=$this->getFullDetailsPid($row['student_pid'], "student_details");
					$assignments=$this->getFullDetailsPid($row['assignment_pid'], "assignments");
					$classes=$this->getFullDetailsPid($row['class_pid'], "classes");
					fputcsv($handler, array($count,$student_details[2],$student_details[1],$assignments[1],$classes[1]));
					$count++;
				}
					fclose($handler);

				if(file_exists("../uploads/docs/".$filename)){
					echo "<script>
						window.open('../uploads/docs/".$filename."');
						window.location.assign('?assignments&submissions');
					</script>";	
				}
			}elseif($data==0){
				$assignment_pid=$this->sanitize($_POST['ass_pid1']);
				$class_pid=$this->sanitize($_POST['cl_pid1']);
				$all_students_assigned=array();
				$failed_students=array();
				$submitted_students=array();

				$sql="select student_pid from student_login where class_pid=? and admin_pid=?";
				$result=$this->con->prepare($sql);
				$result->bindParam("s",$class_pid);
				$result->bindParam("s",$admin_pid);
				$result->execute(array($class_pid,$admin_pid));
				$count=0;
				while($row=$result->fetch(PDO::FETCH_ASSOC)){
					$all_students_assigned[$count]=$row['student_pid'];
					$count++;
				}

				//getting submitted students
				$sql1="select * from assignment_submissions where assignment_pid=? and class_pid=? and admin_pid=?";
				$result1=$this->con->prepare($sql1);
				$result1->bindParam("s",$assignment_pid);
				$result1->bindParam("s",$class_pid);
				$result1->bindParam("s",$admin_pid);
				$result1->execute(array($assignment_pid,$class_pid,$admin_pid));
				$count=0;
				while($row1=$result1->fetch(PDO::FETCH_ASSOC)){
					$submitted_students[$count]=$row1['student_pid'];
					$count++;
				}

				$count=0;
				for($i=0; $i < sizeof($all_students_assigned); $i++){
					if(!in_array($all_students_assigned[$i], $submitted_students)){
						$failed_students[$count]=$all_students_assigned[$i];
						$count++;
					}
				}

				//getting filename
				$sql2="select * from assignments where assignment_pid=? and admin_pid=?";
				$result2=$this->con->prepare($sql2);
				$result2->bindParam("s",$assignment_pid);
				$result2->bindParam("s",$admin_pid);
				$result2->execute(array($assignment_pid,$admin_pid));
				$assignment_result=$result2->fetch();
				$filename="failed_".$assignment_result[4].".csv";

				if(file_exists("../uploads/docs/".$filename)){
					unlink("../uploads/docs/".$filename);
				}

				//creating handler
				$handler=fopen("../uploads/docs/".$filename,"w");
				fputcsv($handler, array("No","Index Number","Full Name","Assignment","Class"));


				$count=1;
				for($i=0; $i < sizeof($failed_students);$i++){
					$student_details=$this->getFullDetailsPid($failed_students[$i], "student_details");
					$assignments=$this->getFullDetailsPid($assignment_pid, "assignments");
					$classes=$this->getFullDetailsPid($class_pid, "classes");
					fputcsv($handler, array($count,$student_details[2],$student_details[1],$assignments[1],$classes[1]));
					$count++;
				}
					fclose($handler);

				if(file_exists("../uploads/docs/".$filename)){
					echo "<script>
						window.open('../uploads/docs/".$filename."');
						window.location.assign('?assignments&submissions');
					</script>";	
				}
			}
		}

		function downloadList($class_pid){
			$class_pid=$this->sanitize($class_pid);
			$admin_pid=$this->sanitize($_SESSION['vclassadmin']);
			$sql="select * from student_login where class_pid=? and admin_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$class_pid);
			$result->bindParam("s",$admin_pid);
			$result->execute(array($class_pid,$admin_pid));

			//getting filename
			$classes=$this->getFullDetailsPid($class_pid, "classes");
			$handler=fopen("../uploads/docs/".$classes[2].".csv","w");
			fputcsv($handler, array("No","Index Number","Full Name","Email","Mobile Number","Class"));

			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$details=$this->getFullDetailsPid($row['student_pid'], "student_details");
				$class=$this->getFullDetailsPid($row["class_pid"], "classes");
				fputcsv($handler, array($count,$details[2],$details[1],$details[3],$details[4],$class[1]));
				$count++;
			}
			fclose($handler);
			echo "../uploads/docs/".$classes[2].".csv";
		}

		##########################...END OF ADMINISTRATOR...############################################################
	

		############################################################################
		#######################.......STUDENTS...........###########################
		function loadContentUserBeginer(){
			if(isset($_GET['logout'])){
				unset($_SESSION['vclassuser']);
				$this->redirect("login.php");
			}elseif(isset($_GET['intro_to_forex'])){
				if(isset($_GET['submissions'])){
					include "portal/assignmentssubmissions.php";
				}else{
					include "portal/intro_to_forex.php";
				}
			}elseif(isset($_GET['passwd'])){
				include "portal/password.php";
			}elseif(isset($_GET['profile'])){
				include "portal/profile.php";
			}elseif(isset($_GET['materials'])){
				include "portal/coursesmaterials.php";
			}else{
				include "portal/dashboard.php";
			}
		}
                function loadContentUserAdvanced(){
			if(isset($_GET['logout'])){
				unset($_SESSION['vclassuser']);
				$this->redirect("login.php");
			}elseif(isset($_GET['forex_advanced'])){
				if(isset($_GET['submissions'])){
					include "portal/assignmentssubmissions.php";
				}else{
					include "portal/forex_advanced.php";
				}
			}elseif(isset($_GET['passwd'])){
				include "portal/password.php";
			}elseif(isset($_GET['profile'])){
				include "portal/profile.php";
			}elseif(isset($_GET['materials'])){
				include "portal/coursesmaterials.php";
			}else{
				include "portal/dashboard.php";
			}
		}
		function verifyStudent($username,$password){
			if($this->verifyCredentials($username, $password, "student_login")){
				$this->displayMsg("LogIn successful...", 1);
				$details=$this->getFullDetails($username, "student_login");
				$_SESSION['vclassuser']=$details[1];
				$this->updateLastLogin($details[1]);
				$this->redirect("index.php?dashboard");

			}else{
				$this->displayMsg("LogIn failed...", 0);
				$this->redirect("login.php");
			}
		}

		function genAssignedAssignment($student_pid){
			$student_pid=$this->sanitize($student_pid);
			$class_pid=$this->getFullDetailsPid($student_pid, "student_login");
			$class=$this->getFullDetailsPid($class_pid[5], "classes");
			$curDate=$this->genDate();
			$sql="select * from assign_assignment where class_pid=? and stop > ?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$class_pid[5]);
			$result->bindParam("s",$curDate);
			$result->execute(array($class_pid[5],$curDate));
			$data="<table class='table table-bordered table-condensed table-hover table-striped' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Assignment</center></th><th><center>Class</center></th><th><center>Start Date</center></th><th><center>Deadline</center></th></tr></thead><tbody>";
			$count=1;
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$details=$this->getFullDetailsPid($row['assignment_pid'], "assignments");
				
				$sql1="select * from assign_assignment where class_pid=? and assignment_pid=?";
				$result1=$this->con->prepare($sql1);
				$result1->bindParam("s",$class_pid[5]);
				$result1->bindParam("s",$row['assignment_pid']);
				$result1->execute(array($class_pid[5],$row['assignment_pid']));
				$details1=$result1->fetch();

				$data.="<tr><td><center>".$count."</center></td><td>".$details[1]."</td><td>".$class[1]."</td><td>".$details1[2]."</td><td>".$details1[3]."</td></tr>";
				$count++;
			}	
			$data.="</tbody></table>";
			echo $data;
		}

		function loadSubmissions($student_pid){
			$student_pid=$this->sanitize($student_pid);
			$sql="select * from assignment_submissions where student_pid=?";
			$result=$this->con->prepare($sql);
			$result->bindParam("s",$student_pid);
			$result->execute(array($student_pid));
			$count=1;
			$data="<table class='table table-bordered table-condensed table-hover' id='tableList'>";
			$data.="<thead><tr><th><center>No.</center></th><th><center>Assignment</center></th><th><center>Submission Date</center></th><th><center>File</center></th></tr></thead><tbody>";
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$assignment=$this->getFullDetailsPid($row['assignment_pid'], "assignments");
				$data.="<tr><td><center>".$count."</center></td><td>".$assignment[1]."</td><td><center>".$row['date']."</center></td><td><center><button type='button' class='btn btn-xs btn-info br' onclick=\"window.open('uploads/docs/".$assignment[4]."/".$row['file']."')\"><span class='glyphicon glyphicon-cloud-download'></span></button></center></td></tr>";
				$count++;
			}
			$data.="</tbody></table>";
			echo $data;
		}

		function uploadAssignment(){
			if(is_uploaded_file($_FILES['file']['tmp_name'])){
				$assignment_pid=$this->sanitize($_POST['assignment']);
				$file_name=$this->sanitize($_FILES['file']['name']);
				//checking for required file
				$accepted=['pdf','docx','doc','zip','rar','ppt','pptx'];
				$ext=explode(".",$file_name);
				$ext=strtolower(end($ext));
				if(in_array($ext, $accepted)){
					//correct file uploaded
					$student_pid=$_SESSION['vclassuser'];
					$curDate=$this->genDate();
					$details=$this->getFullDetailsPid($student_pid, "student_login");
					$assignment=$this->getFullDetailsPid($assignment_pid, "assignments");
					$class_pid=$details[5];
					$admin_pid=$details[6];
					$new_file_name=$this->genPid()."_".$file_name;
					if(move_uploaded_file($_FILES['file']['tmp_name'], "uploads/docs/".$assignment[4]."/".$new_file_name)){
						//checking if previous file was existing
						$sql="select file from assignment_submissions where student_pid=? and assignment_pid=?";
						$result=$this->con->prepare($sql);
						$result->bindParam("s",$student_pid);
						$result->bindParam("s",$assignment_pid);
						$result->execute(array($student_pid,$assignment_pid));
						if($result->rowCount() >=1){
							//exists
							$data=$result->fetch();
							unlink("uploads/docs/".$assignment[4]."/".$data[0]);

							$sql1="update assignment_submissions set file=?,admin_pid=?,class_pid=? where student_pid=? and assignment_pid=?";
							$result1=$this->con->prepare($sql1);
							$result1->bindParam("s",$new_file_name);
							$result1->bindParam("s",$admin_pid);
							$result1->bindParam("s",$class_pid);
							$result1->bindParam("s",$student_pid);
							$result1->bindParam("s",$assignment_pid);
							if($result1->execute(array($new_file_name,$admin_pid,$class_pid,$student_pid,$assignment_pid))){
								$this->displayMsg("File uploaded..", 1);
								$this->redirect("?assignments&submissions");
							}else{
								$this->displayMsg("Process failed..", 0);
								$this->redirect("?assignments");
							}
						}else{
							//create
							$sql1="insert into assignment_submissions(student_pid,assignment_pid,admin_pid,date,file,class_pid) values(?,?,?,?,?,?)";
							$result1=$this->con->prepare($sql1);
							$result1->bindParam("s",$student_pid);
							$result1->bindParam("s",$assignment_pid);
							$result1->bindParam("s",$admin_pid);
							$result1->bindParam("s",$curDate);
							$result1->bindParam("s",$new_file_name);
							$result1->bindParam("s",$class_pid);
							if($result1->execute(array($student_pid,$assignment_pid,$admin_pid,$curDate,$new_file_name,$class_pid))){
								$this->displayMsg("File uploaded...", 1);
								$this->redirect("?assignments&submissions");
							}else{
								$this->displayMsg("Process failed..",0);
								$this->redirect("?assignments");
							}
						}
					}else{
								$this->displayMsg("Process failed..",0);
								$this->redirect("?assignments");
					}

				}else{
					$this->displayMsg("Wrong file format uploaded..", 0);
					$this->redirect("?assignments");
				}
			}else{
				$this->displayMsg("No file uploaded..", 0);
				$this->redirect("?assignments");
			}
		}
                
                function changeProfilePicUser(){
                    
			$student_pid=$this->sanitize($_SESSION['vclassuser']);
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				//file uploaded
				$dp=$this->getFullDetailsPid($student_pid, "dp");
				$ext=['jpg','jpeg','png'];
				//getting file extension
				$file_ext=explode(".", $_FILES['image']['name']);
				$file_ext=strtolower(end($file_ext));
				if(in_array($file_ext, $ext)){
					//good
					$curDate=date('Y-m-d-h-i-s');
					$new_file_name=$curDate.".".$file_ext;
					if(move_uploaded_file($_FILES['image']['tmp_name'], '../vclass/uploads/images/'.$new_file_name)){
						//file uploaded; delete previous image
						unlink("../vclass/uploads/images/".$dp[2]);
						$query="update dp set image=? where pid=?";
						$result=$this->con->prepare($query);
						$result->bindParam("s",$new_file_name);
						$result->bindParam("s",$student_pid);
						if($result->execute(array($new_file_name,$student_pid))){
							$this->displayMsg("Profile Picture updated", 1);
						}else{
							$this->displayMsg("Process failed..", 0);
						}
					}else{
						$this->displayMsg("File Upload failed...", 0);
					}
				}else{
					$this->displayMsg("Wrong file uploaded...", 0);
				}
			}else{
				$this->displayMsg("No file uploaded...", 0);
			}
				$this->redirect("?dashboard");
		}

		#############################################################################	
	}
?>