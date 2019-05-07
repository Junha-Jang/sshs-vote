<?php

require_once('vault.php');

//세션 설정 페이지(설곽 컴실에서 들어왔다는 걸 확인하기 위해 세팅한다.)

//접근 선행 조건 :
//(1).htaccess에서 잠금 해제
//(2)$_POST['password'] 에 올바른 초기화 비밀번호 설정

?>

<!doctype html>
<html>
<head>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
  <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</head>
<body>

<?php
if(!isset($_SESSION)){ session_start(); }
if($_SESSION['valid_vote_location']){
  $auth = '투표소';
}
else if($_SESSION['valid_vote_admin']){
  $auth = '관리실';
}
else{
  $auth = '미인증';
}

if(!isset($_POST) || !isset($_POST['password'])){
?>
<div class="mdl-layout mdl-js-layout">
  <header class="mdl-layout__header mdl-layout__header--scroll">
    <img class="mdl-layout-icon"></img>
    <div class="mdl-layout__header-row">
      <span class="mdl-layout__title">위치 인증</span>
    </div>
  </header>
  <main class="mdl-layout__content">
    <div>현재 상태 : <?php echo $auth; ?></div>
    <div>투표소의 컴퓨터라면 투표소의 비밀번호를, 관리실이라면 관리실의 비밀번호를 입력하세요.</div>
    <form method="POST" action="">
      <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-textfield__input" type="password" id="location_set" name="password">
        <label class="mdl-textfield__label" for="location_set">Password</label>
      </div>
    </form>
  </main>
</div>

<?php
} else{
    require_once('vault.php');
    ini_set('session.gc_maxlifetime', $time_end-$time_start+18000);
    session_set_cookie_params($time_end-$time_start+18000); //5시간 여유.

    if(password_verify($_POST['password'],'$2y$10$gfZJsY8xSa4Ig./8sDl62.uAVoL0vhq3NwpyT.cHO.BOR2KyzOVzi')){//set
        if(!isset($_SESSION)){ session_start(); }
        $_SESSION['valid_vote_admin']=FALSE;
        $_SESSION['valid_vote_location']=TRUE; //투표 컴퓨터 권한
        $message = "서울과학고 컴퓨터실 세션 설정이 완료되었습니다.";
    }
    else if(password_verify($_POST['password'],'$2y$10$V3RrFtHPq38u.F0.TPUNf.lV3BrUqsptfifE/H2pclOv3r/Pt2fq.')){//admin
        if(!isset($_SESSION)){ session_start(); }
        $_SESSION['valid_vote_location']=FALSE;
        $_SESSION['valid_vote_admin']=TRUE; //관리자 권한
        $message = "서울과학고 컴퓨터 관리실 세션 설정이 완료되었습니다.";
    }
    else{
        $message = "비밀번호가 틀립니다.";
        #$message = password_hash($_POST['password'], PASSWORD_DEFAULT)."\n"; //비밀번호 설정시 해시값을 복사
        #var_dump($message);
    }
    echo "<script type='text/javascript'>
      alert('$message');
      window.location.href='index.php';
    </script>";
    die();
}

?>

</body>
</html>
