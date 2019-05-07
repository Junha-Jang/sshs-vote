<?php

//접근 선행 조건 :
//(1) $_SESSION['valid_vote_admin'] 설정. (/vote/location.php 접속)
//(2) codegened.txt가 지워져 있을 것.

session_start();

//서울과학고 컴퓨터 관리실.
if(!$_SESSION['valid_vote_admin']){
    http_response_code(403);
    exit;
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>CODEGEN</title>
</head>
<body>

<table>
<thead>
<tr><th>코드</th><th>학번</th></tr>
</thead>
<tbody>
<?php

require_once('vault.php');
if(file_exists('codegened.txt')) exit(); //코드를 다시 생성하려면 codegened.txt를 제거.
$file = fopen('codegened.txt','w') or die("인증 파일이 안열립니다");
#이게 각 반의 사람 수
$a = [
    1=>[
        1=>17,
        2=>16,
        3=>16,
        4=>16,
        5=>16,
        6=>16,
        7=>16,
        8=>16
    ],
    2=>[
        1=>16,
        2=>17,
        3=>16,
        4=>16,
        5=>15,
        6=>16,
        7=>16,
        8=>16
    ],
    3=>[
        1=>15,
        2=>15,
        3=>15,
        4=>15,
        5=>15,
        6=>16,
        7=>16,
        8=>16
    ]
];

$b = [];

$conn = new PDO('mysql:dbname=sshs_vote;host=localhost;','admin',$db_pw);
$conn->query('TRUNCATE TABLE voted_status');
$conn->query('TRUNCATE TABLE vote_result');
$conn->query('INSERT INTO vote_result VALUE(1, 0);');
$conn->query('INSERT INTO vote_result VALUE(2, 0);');
#voted_status 테이블에는

for($i=1;$i<=3;++$i){
    for($j=1;$j<=8;++$j){
        for($k=1;$k<=$a[$i][$j];++$k){
            $str = '';
            while(TRUE){
                $str = rand(0,0xFFFFF);
                $str = ($str * 16) + ($str % 11);
                if(!in_array($str,$b,TRUE)){
                    $b[]=$str;
                    $str = sprintf('%06x',$str);
                    break;
                }
            }

            $K = sprintf('%02d',$k);

            $query = $conn->prepare('INSERT INTO voted_status (ucode,voted) VALUES(:ucode,0)');
            $query->bindValue(':ucode',$str);
            $query->execute();

            echo '<tr><td>'.$str.'</td><td>'.$i.$j.$K.'</td></tr>';
        }
    }
}

$res = fwrite($file,'code generated');
fclose($file);
echo ($res);

?>
</tbody>
</table>

</body>
</html>
