<?php

require_once('vault.php');

function alert_redir($msg, $success = false){
    if($success){
         http_response_code(201);
    }
    else{
         http_response_code(403);
    }
    echo '<script>alert("'.$msg.'");document.location.href="/index.php";</script>';
    exit();
}
session_start();
if(!isset($_SESSION)){ alert_redir('올바른 경로가 아닙니다.', false); }

if($time_start<time() && time()<$time_end){
    if(!$_SESSION['valid_vote_entry']){
        alert_redir('정상적인 접근이 아닙니다. (AJAX 등을 이용한 요청은 허용되지 않습니다.)');
        exit;
    }
    if(!$_SESSION['valid_vote_location'] || $_SESSION['valid_vote_admin']){
        alert_redir('정상적인 접근이 아닙니다. (서울과학고 컴퓨터실 외에 투표는 허용되지 않습니다.)');
        exit;
    }
    if(!isset($_POST['ucode']) || !isset($_POST['candId'])){
        alert_redir('정상적인 접근이 아닙니다. (POST 요청에서 필요한 변수가 없습니다.)');
        exit;
    }
    $conn = new PDO('mysql:dbname=sshs_vote;host=localhost;','admin',$db_pw);

    //코드 확인하기
    $query = $conn->prepare('SELECT * FROM `voted_status` WHERE `ucode` = :ucode');
    $query->bindValue(':ucode',$_POST['ucode']);
    $query->execute();
    if($query->rowCount()===0){
        alert_redir('코드가 맞지 않습니다. 코드를 확인하고 다시 투표해 주세요.');
        exit;
    }
    if($query->fetch(PDO::FETCH_ASSOC)['voted']==1){
        alert_redir('이미 투표하셨습니다.');
        exit;
    }

    //투표 참여 표시하기
    $query = $conn->prepare('UPDATE `voted_status` SET `voted` = 1 WHERE `ucode` = :ucode AND `voted` = 0');
    $query->bindValue(':ucode',$_POST['ucode']);
    $query->execute();
    if($query->rowCount()===0){
        //이 부분은 일어나지 않는 게 정상
        alert_redir('투표권한이 없습니다. 코드를 잘못 입력했거나, 이미 투표하셨습니다.');
        exit;
    }

    //투표하기
    $query = $conn->prepare('UPDATE `vote_result` SET `voteCount` = `voteCount` + 1 WHERE `candId` = :candId');
    $query->bindValue(':candId',$_POST['candId'],PDO::PARAM_INT);
    $query->execute();
    if($query->rowCount()===0){
        alert_redir('정상적인 접근이 아닙니다. (올바른 후보가 아닙니다.)');
        exit;
    }
    //코드가 맞지만 정상적인 접근을 하지 않았을 경우 투표권이 사라지는 상황이 발생 가능..한가?

    alert_redir('성공적으로 투표되었습니다.',true);

}
else{
    alert_redir('선거 기간이 아닙니다!');
}
?>
