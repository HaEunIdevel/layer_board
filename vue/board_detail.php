<?php
require_once __DIR__ . '/../common/config.php';



$boardSeq = !empty($_GET['boardSeq']) ? $_GET['boardSeq'] :'';


if(($boardSeq)){
    $query = "
        SELECT *
        FROM tb_board
        WHERE B_SEQ = '{$boardSeq}'
    
    ";

    $result= $_mysqli->query($query);

    $data = $result->fetch_assoc();
    echo <<< END
    <section>
<div>{$data['B_TITLE']}</div>
<div>{$data['B_CONTENTS']}</div>
<button>수정하기</button>
<button>삭제하기</button>
</section>
END;

}
