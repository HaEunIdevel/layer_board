<?php

use _lib\Paging;
use _model\Main;

require_once __DIR__ . '/../_inc/config.php';
require_once __DIR__ . '/../_lib/Paging.php';
require_once __DIR__ . '/../_model/Main.php';
// 10진법 -> 2진법
$auth = getMemberAuth($_SESSION['SE_CD'], $_mysqli);
$binaryData = decbin($auth);

if($binaryData[3] != 1){
    echo '<script>alert(\'해당 페이지에 접근 권한이 없습니다.\');</script>';
    $prevPage = $_SERVER['HTTP_REFERER'];
    echo '<script>window.location.href = "../login/";</script>';
}
// 파라미터
$keyword = !empty($_GET['inputSearch'])     ? $_GET['inputSearch']  : '';
$dspYn   = !empty($_GET['dspYn'])           ? $_GET['dspYn']        : 'Y';
$sDate   = !empty($_GET['sDate'])           ? $_GET['sDate']        : '';
$eDate   = !empty($_GET['eDate'])           ? $_GET['eDate']        : '';
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
    "keyword"   => $keyword,
    "dspYn"     => $dspYn,
    "sDate"     => $sDate,
    "eDate"     => $eDate,
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
$newsList = $main->newsGetList($params);

$total = $main->total;
$params['total'] = $total;

// 페이징
$paging = new Paging($params);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?php
    // head
    include_once __DIR__ . '/../_common/head.php';
    ?>
</head>
<body>

    <div id="wrap">
        <!-- sideHeader -->
        <?php
        // sideHeader
        include_once __DIR__ . '/../_common/sideHeader.php';
        ?>
        <!-- //sideHeader -->
        <main id="main">
            <!-- topHeader -->
            <?php
            include_once __DIR__ . '/../_common/topHeader.php';
            ?>
            <!-- //topHeader -->
            <div class="content">
                <form id="frm" action="news_list.php" method="get">
                    <div class="top-title-wrap">
                        <h2 class="page-title">뉴스 관리</h2>
                    </div>
                    <!-- 검색 조건 설정 영역 -->
                    <div class="search-option list-thead">
                        <table>
                            <tr>
                                <th>전시 여부</th>
                                <td>
                                    <div class="col-12 d-flex flex-wrap">
                                        <div class="form-check radio mR30">
                                            <input class="form-check-input" type="radio" name="dspYn" id="radio01" value="Y" <?php if ($dspYn == 'Y')echo "checked" ?>>
                                            <label class="form-check-label" for="radio01">전시</label>
                                        </div>
                                        <div class="form-check radio mR30">
                                            <input class="form-check-input" type="radio" name="dspYn" id="radio02" value="N" <?php if ($dspYn == 'N')echo "checked" ?>>
                                            <label class="form-check-label" for="radio02">미전시</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>등록일</th>
                                <td>
                                    <div class="col-12 d-flex flex-wrap align-content-center">
                                        <div class="mR30">
                                            <label for="date01" class="hide">노출기간 시작</label>
                                            <input type="date" id="date01" name="sDate" value="<?=$sDate?>">
                                            ~
                                            <label for="date02" class="hide">노출기간 종료일</label>
                                            <input type="date" id="date02" name="eDate" value="<?=$eDate?>">
                                        </div>
                                        <div class="btn-group radio-group" role="group">
                                            <input type="radio" class="btn-check" name="period" id="btnRadio1" autocomplete="off" value="0" onclick="date_up(0)" <?php if ($period == '0')echo "checked" ?>>
                                            <label class="btn btn-outline-danger" for="btnRadio1">전체</label>

                                            <input type="radio" class="btn-check" name="period" id="btnRadio2" autocomplete="off" value="1" onclick="date_up(1)" <?php if ($period == '1')echo "checked" ?>>
                                            <label class="btn btn-outline-danger" for="btnRadio2">오늘</label>

                                            <input type="radio" class="btn-check" name="period" id="btnRadio3" autocomplete="off" value="2" onclick="date_up(2)" <?php if ($period == '2')echo "checked" ?>>
                                            <label class="btn btn-outline-danger" for="btnRadio3">1주일</label>

                                            <input type="radio" class="btn-check" name="period" id="btnRadio4" autocomplete="off" value="3" onclick="date_up(3)" <?php if ($period == '3')echo "checked" ?>>
                                            <label class="btn btn-outline-danger" for="btnRadio4">1개월</label>

                                            <input type="radio" class="btn-check" name="period" id="btnRadio5" autocomplete="off" value="4" onclick="date_up(4)" <?php if ($period == '4')echo "checked" ?>>
                                            <label class="btn btn-outline-danger" for="btnRadio5">3개월</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>제목</th>
                                <td>
                                    <div class="input-group">
                                        <label for="inputSearch" class="hide">검색어 입력</label>
                                        <input type="text" id="inputSearch" name="inputSearch" class="form-control" placeholder="검색어를 입력해 주세요." value="<?=$keyword?>">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-center mT20"><button type="submit" class="btn btn-m btn-dark">검색</button></div>
                    <!--// 검색 조건 설정 영역 -->
                    <div class="filter-area justify-content-between align-items-center">
                        <div class="left-area">
                            <p>검색결과 총 <strong><?=$total?>건</strong>이 있습니다.</p>
                        </div>
                        <div class="right-area d-flex align-items-center">
                            <button type="button" class="btn btn-m btn-dark mR10" onclick="location.href = 'news_regi.php?inputSearch=<?=$keyword?>&dspYn=<?=$dspYn?>&sDate=<?=$sDate?>&eDate=<?=$eDate?>&page=<?=$page?>&period=<?=$period?>&RE_SIZE=<?=$RE_SIZE?>'">등록</button>
                            <button type="button" class="btn btn-m btn-outline-danger mR10 " onclick="del()">선택삭제</button>
                            <div class="input-group">
                                <label for="inputGroupSelect02" class="hide">셀렉트2</label>
                                <select class="form-select" id="inputGroupSelect02" onchange="filter();">
                                    <option value="10" <?php if ($RE_SIZE == '10') echo 'selected'; ?>>10개씩</option>
                                    <option value="20" <?php if ($RE_SIZE == '20') echo 'selected'; ?>>20개씩</option>
                                    <option value="30" <?php if ($RE_SIZE == '30') echo 'selected'; ?>>30개씩</option>
                                    <option value="40" <?php if ($RE_SIZE == '40') echo 'selected'; ?>>40개씩</option>
                                    <option value="50" <?php if ($RE_SIZE == '50') echo 'selected'; ?>>50개씩</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="board-wrap">
                        <div class="default-table line border">
                            <table>
                                <colgroup>
                                    <col style="width: 5%;">
                                    <col style="width: 5%;">
                                    <col style="width: 70%;">
                                    <col style="width: 10%;">
                                    <col style="width: 10%;">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input all-check" type="checkbox" id="check01">
                                            <label class="form-check-label mL10" for="check01"></label>
                                        </div>
                                    </th>
                                    <th>번호</th>
                                    <th>제목</th>
                                    <th>등록일</th>
                                    <th>전시여부</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($newsList)){
                                    $no = $total - $offset;
                                    foreach ($newsList as $value) {
                                        $title      = !empty($value['NEWS_TITLE'])  ? $value['NEWS_TITLE']  : ''; // 제목
                                        $createdAt  = !empty($value['CREATED_AT'])  ? $value['CREATED_AT']  : ''; // 등록일
                                        $dspYn2      = !empty($value['DSP_YN'])      ? $value['DSP_YN']      : ''; // 전시여부
                                        $newsSeq    = !empty($value['NEWS_SEQ'])    ? $value['NEWS_SEQ']    : ''; // 뉴스시퀀스

                                        // 전시여부
                                        $dspY   = "";
                                        $dspN   = "";
                                        if ($dspYn2 == 'Y'){
                                            $dspY = "selected";
                                        }else{
                                            $dspN = "selected";
                                        }

                                        echo <<<TR
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="check02" name="allCheck[]" value="$newsSeq">
                                            <label class="form-check-label mL10" for="check02"></label>
                                        </div>
                                    </td>
                                    <td>{$no}</td>
                                    <td class="ellipsis">
                                        <a href="news_edit.php?newsSeq={$newsSeq}&inputSearch=$keyword&dspYn=$dspYn&sDate=$sDate&eDate=$eDate&page=$page?>&period=$period&RE_SIZE=$RE_SIZE" class="link">
                                            {$title}
                                        </a>
                                    </td>
                                    <td>{$createdAt}</td>
                                    <td>
                                        <label for="inputGroupSelect1" class="hide">전시/미전시</label>
                                        <select class="form-select" name="dspYn2" id="inputGroupSelect{$newsSeq}" onchange="changeDspYn({$newsSeq});">
                                            <option value="Y" {$dspY}>전시</option>
                                            <option value="N" {$dspN}>미전시</option>
                                        </select>
                                    </td>
                                </tr>
TR;
                                        $no --;
                                    }
                                }else{
                                    echo <<< TR
                        <tr>
                          <td colspan="5"> 리스트가 없습니다.</td>
                        </tr>
TR;

                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <?=$paging->getList();?>
                            </ul>
                        </nav>
                    </div>
                </form>
            </div>
        </main>
    </div>
<script>
    function del() {
        let isChecked   = false;

        // 배열 선언
        let checkbox = []; // 빈 배열을 생성.
        let formData = new FormData();

        $("input:checkbox[name='allCheck[]']").each(function () {
            if ($(this).is(":checked")) {
                isChecked = true;
                checkbox.push($(this).val()); // 배열에 값을 추가.
            }
        });

        if (!isChecked) {
            alert("삭제할 글을 선택해 주세요.");
            return false;
        }

        if (confirm("정말 삭제하시겠습니까?")) {
            formData.append("newsSeq", checkbox);

            $.ajax({
                url: "../ajax/main/news_delete.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                dataType: "json",
                success: function (data) {
                    alert(data.msg);
                    if (data.code === 200) {
                        location.href="news_list.php";
                    }
                },
                beforeSend: function () {
                    $(".wrap-loading").removeClass('display-none');
                },
                complete: function () {
                    $(".wrap-loading").addClass('display-none');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    }

    function filter() {
        var selected = $("#inputGroupSelect02 option:selected").val();
        location.href="news_list.php?RE_SIZE="+selected;
    }

    function changeDspYn(newsSeq) {
        var selected = $("#inputGroupSelect"+newsSeq).val();
        let formData    = new FormData();
        formData.append("dspYn", selected);
        formData.append("seq", newsSeq);
        formData.append("tableNm", "NEWS");
        formData.append("columNm", "NEWS_SEQ");

        $.ajax({
            url: "../ajax/common/changeDspYn.php",
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            dataType: "json",
            success: function (data) {
                alert(data.msg);
                if (data.code === 200) {
                     location.href="news_list.php";
                }
            },
            beforeSend: function () {
                $(".wrap-loading").removeClass('display-none');
            },
            complete: function () {
                $(".wrap-loading").addClass('display-none');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }

    const today = new Date();
    var year = today.getFullYear();
    var s_day = "";

    function date_up(num){
        const dd = new Date();
        let month = ('0' + (today.getMonth() + 1)).slice(-2);
        let day = ('0' + today.getDate()).slice(-2);
        let dateString = year + '-' + month  + '-' + day;
        let dep = 0;
        let period = '';

        switch(num){
            case 0:
                dep = 1; //전체
                $("#period").val('전체');
                break;
            case 1: //오늘
                $("#period").val('1');
                break;
            case 2:// 1주일전
                $("#period").val('-7');
                dd.setDate(day-7);
                break;
            case 3:// 1개월전
                $("#period").val('-30');
                dd.setDate(day-30);
                break;
            case 4:// 3개월전
                $("#period").val('-90');
                dd.setDate(day-90);
                break;
            default:
                break;
        }

        if(dep == 0){
            year = dd.getFullYear();
            month = ('0' + (dd.getMonth() + 1)).slice(-2);
            day = ('0' + dd.getDate()).slice(-2);
            s_day = year + '-' + month  + '-' + day;
        }
        else{
            s_day = "";
            dateString = "";
        }

        $("#date01").val(s_day);
        $("#date02").val(dateString);
        $("#period").val(period);
    }
</script>
</body>
</html>