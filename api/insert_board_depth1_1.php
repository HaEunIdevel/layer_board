<?php
// config
use _class\Main;
require_once __DIR__ . '/../common/config.php';
require_once __DIR__ . '/../_class/Main.php';



try {
    // 변수 정리
    $boardSeq = !empty($_GET['boardSeq']) ? $_GET['boardSeq'] :'';
    $newGroupId_child = !empty($_GET['child_id']) ? $_GET['child_id'] :'';
    $newIndent_child = !empty($_GET['child_indent']) ? $_GET['child_indent'] :'';
    $newStep_child = !empty($_GET['child_step']) ? $_GET['child_step'] :'';
    
    $msg = '게시물 등록중 오류가 발생했습니다. 관리자에게 문의해 주세요.';
    $code = 500;
    $arrRtn = array(
        'msg' => $msg,
        'code' => $code
    );

    $main = new Main();
    $title = !empty($_POST['intitle']) ? $_POST['intitle'] : '';
    $contents = !empty($_POST['contents']) ? $_POST['contents'] : '';

    $method     = 'POST';
    $newsParams = array(
        'title' =>  $title, //제목
        'cont'  =>  $contents, //내용
        'seq'  => $boardSeq,
        'child_id'  => $newGroupId_child,
        'child_indent' => $newIndent_child,
        'child_step' => $newStep_child
    );

    
    $rtn = $main->newInsertDepth1_1($newsParams);
    if (!$rtn) {
        $code   = 502;
        $msg    = "게시물 등록 중 오류가 발생하였습니다.(code {$code})\n관리자에게 문의해 주세요.";
        throw new mysqli_sql_exception($msg, $code);
    }

    // 정상
    $arrRtn['msg']  = '저장되었습니다.';
    $arrRtn['code'] = 200;




} catch (Exception $e) {
    $arrRtn['msg']  = $e->getMessage();
    $arrRtn['code'] = $e->getCode();

} finally {
    // JavaScript 리디렉션 블록
    echo '<script>';
    echo 'var response = ' . json_encode($arrRtn) . ';';
    echo 'if (response.code === 200) {';
    echo '    alert(response.msg);';
    echo '    window.location.href = "../view/boardlist.php";';
    echo '} else {';
    echo '    alert(response.msg);';
    echo '}';
    echo '</script>';


    echo json_encode($arrRtn);


}



