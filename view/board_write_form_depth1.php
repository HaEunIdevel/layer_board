<?php
    $boardSeq = !empty($_GET['boardSeq']) ? $_GET['boardSeq'] :'';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>게시글 쓰기</title>
</head>
<body>
<form action="../api/insert_board_depth1.php?boardSeq=<?=$boardSeq?>" method="post">
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