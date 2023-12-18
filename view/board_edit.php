<?php
    use _class\Main;
    
    require_once __DIR__ . '/../common/config.php';
    require_once __DIR__ . '/../_class/Main.php';
    $boardSeq = !empty($_GET['boardSeq']) ? $_GET['boardSeq'] :'';
    
    $main = new Main();
    $data = $main->boarDetail($boardSeq);
    
    if(!empty($data)){
        echo <<<END
        <form action="../api/edit_board.php?boardSeq={$boardSeq}" method="post">
    <input type="text" name="intitle" value="{$data['B_TITLE']}"/>
    <br>
    <br>
    <textarea name="contents" cols="60" rows="20" style="resize: none">{$data['B_CONTENTS']}</textarea>
    <br>
    <br>
<button>수정하기</button>
END;

    }