<?php

// config
use _model\Main;

require_once __DIR__ . '/../../_inc/config.php';
require_once __DIR__ . '/../../_model/Main.php';

try {
    // 변수 정리
    $msg = '뉴스 삭제 중 오류가 발생했습니다. 관리자에게 문의해 주세요.';
    $code = 500;
    $arrRtn = array(
        'msg' => $msg,
        'code' => $code
    );
    $ip = IP;
    $main = new Main();

    $method = 'POST';
    $newsParams = array(
        'newsSeq' => '',
		'tableNm' => '',
		'enYn'          => '', // 국문,영문제품 여부 (영문일때만 Y값 넘어옴)
    );

    $params = requestParameter($newsParams, $method);
    $rtn = $main->newsDelete($params);

    if (!$rtn) {
        $code = 502;
        $msg = "뉴스 삭제 중 오류가 발생하였습니다.(code {$code})\n관리자에게 문의해 주세요.";
        throw new mysqli_sql_exception($msg, $code);
    }

    // 정상
    $arrRtn['msg'] = '삭제되었습니다.';
    $arrRtn['code'] = 200;

} catch (Exception $e) {
    $arrRtn['msg'] = $e->getMessage();
    $arrRtn['code'] = $e->getCode();

} finally {
    header('Content-type: application/json');
    echo json_encode($arrRtn);

}
