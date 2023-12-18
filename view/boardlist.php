<?php
use _class\Paging;
use _class\Main;

require_once __DIR__ . '/../common/config.php';
require_once __DIR__ . '/../_class/Paging.php';
require_once __DIR__ . '/../_class/Main.php';



// 파라미터
$page    = !empty($_GET['page'])            ? $_GET['page']         : 1;
$period  = !empty($_GET['period'])          ? $_GET['period']       : 0;
$RE_SIZE = !empty($_GET['RE_SIZE'])         ? $_GET['RE_SIZE']      : 10;


//변수 정리
$size       = PAGING_SIZE;
if($RE_SIZE){
    $size = $RE_SIZE;
}
$offset     = ($page - 1) * $size;
$scale      = PAGING_SCALE;


$params = array(
    'offset'    => $offset,
    'limit'     => $size,
    'total'     => 0,
    'page'      => $page,
    'size'      => $size,
    'scale'     => $scale,
    'resize'    => $RE_SIZE,
    'period'    => $period
);

// 클래스 생성
$main = new Main();

// 리스트
$boardList = $main->newsGetList($params);
$total = $main->total;
$params['total'] = $total;
// 페이징
$paging = new Paging($params);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>게시판 목록</title>
    <style>
        .indent {
            margin-left: 10px; /* 들여쓰기 크기 조절 */
        }
    </style>
</head>
<body>
<div>
    <div>
<table style="border-collapse: collapse">
    <colgroup>
        <col style="width: 15%;">
        <col style="width:30%;">
        <col style="width: 25%;">
    </colgroup>
    <thead>
    <tr>
        <th>번호</th>
        <th>제목</th>
        <th>내용</th>
        <!--        <th>작성자</th>-->
        <!--        <th>낳짜</th>-->
    </tr>
    </thead>
    <tbody>
    <?php
        if (!empty($boardList)) {
            $no = $total - $offset;
            $i = 1;
            foreach ($boardList as $value) {
                $title = !empty($value['B_TITLE']) ? $value['B_TITLE'] : ''; // 제목
                $contents = !empty($value['B_CONTENTS']) ? $value['B_CONTENTS'] : ''; // 내용
                $createdAt = !empty($value['CREATED_AT']) ? $value['CREATED_AT'] : ''; // 등록일
                $boardSeq = !empty($value['B_SEQ']) ? $value['B_SEQ'] : ''; // 뉴스 시퀀스
                $depth = !empty($value['B_INDENT']) ? $value['B_INDENT'] : 0; // 깊이
                
                // 들여쓰기를 위한 HTML 공백 생성
                $indent = str_repeat('<span class="indent"></span>', $depth);
                
                echo <<<TR
                        <tr style="border-bottom: 1px solid red">
                            <td>{$i}</td>
                            <td>{$indent}<a href="board_detail.php?boardSeq={$boardSeq}">{$title}</a></td>
                            <td>{$contents}</td>
                        </tr>
                TR;
                
                $i++;
            }
        }
    ?>

    </tbody>
</table>
</div>
    <span>
        <?=$paging->getList();?>
    </span>
</div>
</body>
</html>