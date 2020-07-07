<?php
    require "../vclassFiles/includes/functions.php";
    $ajax = new Vclass();
    if(isset($_POST['edit'])){
        //edit user details
        $ajax->verifyData($_POST['pid'],$_POST['table']);
    }elseif(isset($_POST['updateStatusPid'])){
    	$ajax->updateStatusAdminPid($_POST['pid'],$_POST['table']);
    }elseif(isset($_POST['deleteReq'])){
    	$ajax->deleteReqAdmin($_POST['pid'],$_POST['table']);
    }elseif(isset($_POST['viewList'])){
    	$ajax->viewList($_POST['viewList']);
    }elseif(isset($_POST['studentListSelect'])){
    	$ajax->studentListSelect($_POST['studentListSelect']);
    }elseif(isset($_POST['processClassList'])){
        $ajax->processClassList($_POST['processClassList']);
    }elseif(isset($_POST['submissionsView'])){
        $ajax->processSubmissonsList($_POST['assignment_pid'],$_POST['class_pid']);
    }elseif(isset($_POST['clearSubmissions'])){
        $ajax->clearSubmissions($_POST['assignment_pid'],$_POST['class_pid']);
    }elseif(isset($_POST['downloadList'])){
        $ajax->downloadList($_POST['downloadList']);
    }
?>