<?php
    use _class\Main;
    
    require_once __DIR__ . '/../common/config.php';
    require_once __DIR__ . '/../_class/Main.php';
    $boardSeq = !empty($_GET['boardSeq']) ? $_GET['boardSeq'] :'';
    $indent = !empty($_GET['indent']) ? $_GET['indent'] :''; // depth
    
    $main = new Main();
    $data = $main->boarDetail($boardSeq);
    $newGroupId_parent = $data['B_GROUP_ID'];
    $newIndent_parent = $data['B_INDENT'];
    $newStep_parent = $data['B_STEP'];
    // 자식 글의 정보를 설정한다.
    $newGroupId_child = $newGroupId_parent;
    $newIndent_child = $newIndent_parent + 1;
    $newStep_child = 1; // 처음에는 1로 설정

// 이후에는 1씩 증가
    
    
    if(!empty($data)){
   
    echo <<< END
    <section>
<div>{$data['B_TITLE']}</div>
<div>{$data['B_CONTENTS']}</div>
<button onclick="location.href='board_edit.php?boardSeq={$boardSeq}'">수정하기</button>
<button onclick="location.href='../api/board_delete.php?boardSeq={$boardSeq}&indent={$indent}'">삭제하기</button>
</section>
END;
if($data['B_INDENT'] > 0){
    echo <<<END
<button onclick="location.href ='board_write_form_depth1_1.php?boardSeq={$boardSeq}&child_id={$newGroupId_child}&child_indent={$newIndent_child}&child_step={$newStep_child}'">글쓰기</button>
END;

}else{
    echo <<<END
<button onclick="location.href ='board_write_form_depth1.php?boardSeq={$boardSeq}'">글쓰기</button>

END;

}
}
