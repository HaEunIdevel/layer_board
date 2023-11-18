<?php
require_once __DIR__ . '/../_inc/config.php';
require_once __DIR__ . '/../_model/Main.php';

// 파라미터
$keyword = !empty($_GET['inputSearch'])     ? $_GET['inputSearch']  : '';
$dspYn   = !empty($_GET['dspYn'])           ? $_GET['dspYn']        : 'Y';
$sDate   = !empty($_GET['sDate'])           ? $_GET['sDate']        : '';
$eDate   = !empty($_GET['eDate'])           ? $_GET['eDate']        : '';
$page    = !empty($_GET['page'])            ? $_GET['page']         : 1;
$period  = !empty($_GET['period'])          ? $_GET['period']       : 0;
$RE_SIZE = !empty($_GET['RE_SIZE'])         ? $_GET['RE_SIZE']      : 10;
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
            <form id="frm">
                <div class="content">
                    <div class="top-title-wrap">
                        <h2 class="page-title">뉴스 등록</h2>
                    </div>
                    <!-- 검색 조건 설정 영역 -->
                    <div class="search-option regi">
                        <table>
                            <colgroup>
                                <col style="width: 15%">
                                <col style="width: 85%">
                            </colgroup>
                            <tr>
                                <th><p class="required">뉴스 제목</p></th>
                                <td>
                                    <div class="input-group">
                                        <label for="inputNews" class="hide">뉴스 제목</label>
                                        <input type="text" id="title" name="title" class="form-control" placeholder="뉴스 제목을 입력해 주세요.">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><p class="required">내용</p></th>
                                <td>
                                    <div id="toolbar-container"></div>
                                    <div id="editor">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><p class="required">메인페이지 전시 여부</p></th>
                                <td>
                                    <div class="col-12 d-flex flex-wrap">
                                        <div class="form-check radio mR30">
                                            <input class="form-check-input" type="radio" name="mainDspYn" id="radio03" value="Y" checked>
                                            <label class="form-check-label" for="radio03">전시</label>
                                        </div>
                                        <div class="form-check radio mR30">
                                            <input class="form-check-input" type="radio" name="mainDspYn" value="N" id="radio04">
                                            <label class="form-check-label" for="radio04">미전시</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><p class="required">전시 여부</p></th>
                                <td>
                                    <div class="col-12 d-flex flex-wrap">
                                        <div class="form-check radio mR30">
                                            <input class="form-check-input" type="radio" name="dspYn" id="radio01" value="Y" checked>
                                            <label class="form-check-label" for="radio01">전시</label>
                                        </div>
                                        <div class="form-check radio mR30">
                                            <input class="form-check-input" type="radio" name="dspYn" id="radio02" value="N">
                                            <label class="form-check-label" for="radio02">미전시</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--// 검색 조건 설정 영역 -->

                    <div class="d-flex align-items-center justify-content-end mT20">
                        <button type="button" class="btn btn-m btn-outline-dark mR10 mb-1" onclick="if (confirm('목록으로 돌아가시겠습니까?\n목록으로 돌아가시면 현재까지 등록한 내용이 모두 사라집니다.')) { location.href='/main_mng/news_list.php?inputSearch=<?=$keyword?>&dspYn=<?=$dspYn?>&sDate=<?=$sDate?>&eDate=<?=$eDate?>&page=<?=$page?>&period=<?=$period?>&RE_SIZE=<?=$RE_SIZE?>'; }">목록</button>
                        <button type="button" class="btn btn-m btn-dark mb-1 " onclick="save()">저장</button>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/super-build/ckeditor.js"></script>
        <script>
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {

                extraPlugins: [ 'ImageUpload' ], // 이미지 업로드 플러그인 추가
                ckfinder: {
                    uploadUrl: '../_lib/imageUpload.php?cate_id=news' // 이미지 업로드하는 페이지
                },
                mediaEmbed: {
                    previewsInData: true
                },
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                placeholder: ' ',
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                removePlugins: [
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    'MathType',
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'PasteFromOfficeEnhanced'
                ]
            }).then(editor => {
                // 에디터 초기화가 완료되면 에디터 객체를 editor 변수에 할당합니다.
                window.editor = editor;
            }).catch(error => {
                console.error('에디터를 생성하는 동안 오류가 발생했습니다.', error);
            });

            function save() {
                // 유효성체크
                var cont = window.editor.getData();

                // 저장
                let frm_data = $("#frm").serializeArray();
                let formData = new FormData();
                frm_data.forEach(function (data) {
                    formData.append(data["name"], data["value"]);
                });
                formData.append("cont", cont);
                if (confirm('정말로 저장하시겠습니까?')) {
                    $.ajax({
                        url: "../ajax/main/news_insert.php",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (json) {
                            console.log(json);

                            if (json.code === 200) {
                                alert(json.msg);
                                location.href = "news_list.php?inputSearch=<?=$keyword?>&dspYn=<?=$dspYn?>&sDate=<?=$sDate?>&eDate=<?=$eDate?>&page=<?=$page?>&period=<?=$period?>&RE_SIZE=<?=$RE_SIZE?>";

                            } else {
                                alert(json.msg);
                            }
                        },
                        beforeSend: function () {
                            $(".wrap-loading").removeClass("display-none");
                        },
                        complete: function () {
                            $(".wrap-loading").addClass("display-none");
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            }
        </script>


</body>
</html>