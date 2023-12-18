<?php
    $boardSeq = !empty($_GET['boardSeq']) ? $_GET['boardSeq'] :'';
    $newGroupId_child = !empty($_GET['child_id']) ? $_GET['child_id'] :'';
    $newIndent_child = !empty($_GET['child_indent']) ? $_GET['child_indent'] :'';
    $newStep_child = !empty($_GET['child_step']) ? $_GET['child_step'] :'';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>게시글 쓰기</title>
</head>
<body>
<form action="../api/insert_board_depth1_1.php?boardSeq=<?=$boardSeq?>&child_id=<?=$newGroupId_child?>&child_indent=<?=$newIndent_child?>&child_step=<?=$newStep_child?>" method="post">
    <input type="text" name="intitle"/>
    <br>
    <br>
    <textarea name="contents" cols="60" rows="20" style="resize: none"></textarea>
    <br>
    <br>
<button>글쓰기</button>
</form>
</body>
</html>