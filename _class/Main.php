<?php
namespace _class;




class Main
{
    private $db;
    public $total;

    public function __construct()
    {
        global $_mysqli;
        $this->db = $_mysqli;
    }

    public function newsGetList($params)
    {

        // 변수 정리
        $where = '';
        $offset = !empty($params['offset']) ? $params['offset'] : 0;
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $keyword = !empty($params['keyword']) ? $params['keyword'] : '';


        $query = "
            SELECT SQL_CALC_FOUND_ROWS
                *
            FROM TB_BOARD
            WHERE USE_YN = 'Y'
            {$where}
            ORDER BY CREATED_AT DESC
            LIMIT {$offset}, {$limit}
        ";
        $result = $this->db->query($query);
        $this->num_rows = $result->num_rows;

        // total
        $_q = "SELECT FOUND_ROWS() AS total";
        $_r = $this->db->query($_q);
        $_d = $_r->fetch_assoc();
        $this->total = $_d['total'];

        // 전체 데이터
        $arrList = $result->fetch_all(MYSQLI_ASSOC);
        return $arrList;
    }

    public function newsGetListEn($params)
    {

        // 변수 정리
        $where = '';
        $offset = !empty($params['offset']) ? $params['offset'] : 0;
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $keyword = !empty($params['keyword']) ? $params['keyword'] : '';
        $dspYn = !empty($params['dspYn']) ? $params['dspYn'] : '';
        $sDate = !empty($params['sDate']) ? $params['sDate'] : '';
        $eDate = !empty($params['eDate']) ? $params['eDate'] : '';
        $period = !empty($params['period']) ? $params['period'] : '';

        if (!empty($keyword)) {
            $where .= "AND NEWS_TITLE LIKE '%{$keyword}%'";
        }

        if (!empty($dspYn)) {
            $where .= "AND DSP_YN = '{$dspYn}'";
        }

        if (!empty($sDate)) {
            $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= '{$sDate}'";
        }

        if (!empty($eDate)) {
            $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') <= '{$eDate}'";
        }

        if (!empty($period)) {
            if ($period == '0') { //전체
                $where .= "";
            } else if ($period == '1') { //당일
                $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";
            } else if ($period == '2') { //1주
                $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
            } else if ($period == '3') { //1개월
                $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_ADD(NOW(), INTERVAL -30 DAY)";
            } else if ($period == '4') { //3개월
                $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
            }

        }

        $query = "
            SELECT SQL_CALC_FOUND_ROWS
                *, DATE_FORMAT(CREATED_AT, '%Y-%m-%d') AS CREATED_AT
            FROM NEWS_EN
            WHERE DEL_YN = 'N'
            {$where}
            ORDER BY NEWS_EN.CREATED_AT DESC
            LIMIT {$offset}, {$limit}
        ";
        $result = $this->db->query($query);
        $this->num_rows = $result->num_rows;

        // total
        $_q = "SELECT FOUND_ROWS() AS total";
        $_r = $this->db->query($_q);
        $_d = $_r->fetch_assoc();
        $this->total = $_d['total'];

        // 전체 데이터
        $arrList = $result->fetch_all(MYSQLI_ASSOC);
        return $arrList;
    }

    // 게시물 글쓰기
    public function newInsert($params)
    {

        // 새로운 글쓰기의 경우 => 바로 insert


        // 아닐경우 그룹넘버넣고, 답글의 경우. 그러니까
        $query = "
            INSERT INTO tb_board
                ( B_CONTENTS, B_TITLE)
            VALUES 
                ('{$params['cont']}','{$params['title']}')
    ";
        $this->db->query($query);

        $group_id = $this->db->insert_id;
        $update_grp_id = "
                UPDATE TB_BOARD SET
                B_GROUP_ID = '{$group_id}'
                WHERE B_SEQ = '{$group_id}'
        ";
        $this->db->query($update_grp_id);
        return true;
    }

    // 20230613 김지수 (뉴스상세)
    public function newsDetail($newsSeq)
    {
        $newsSeq = !empty($newsSeq) ? $newsSeq : 0;
        $query = "
        SELECT * 
        FROM NEWS
        WHERE NEWS_SEQ = $newsSeq
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    public function newsDetailEn($newsSeq)
    {
        $newsSeq = !empty($newsSeq) ? $newsSeq : 0;
        $query = "
        SELECT * 
        FROM NEWS_EN
        WHERE NEWS_SEQ = $newsSeq
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    // 20230614 김지수 (뉴스 수정)
    public function newsEdit($params)
    {
        $tableNm = ($params['enYn'] == 'Y')? 'NEWS_EN': 'NEWS';

        $query = "
            UPDATE {$tableNm} SET
                NEWS_TITLE  = '{$params['title']}',
                NEWS_CONT   = '{$params['cont']}',
                MAIN_DSP_YN = '{$params['mainDspYn']}',
                DSP_YN      = '{$params['dspYn']}'
                        
            WHERE NEWS_SEQ =   {$params['newsSeq']}      
            
        ";
        return $this->db->query($query);
    }

    public function newsDelete($params)
    {
        $tableNm = ($params['enYn'] == 'Y')? 'NEWS_EN': 'NEWS';

        $query = "
            UPDATE {$tableNm} SET
            DEL_YN = 'Y'
            WHERE NEWS_SEQ IN ({$params['newsSeq']})      
        ";
        return $this->db->query($query);
    }

    public function newsChangeDspYn($params)
    {
        $query = "
            UPDATE NEWS SET
                DSP_YN      = '{$params['dspYn']}'
                        
            WHERE NEWS_SEQ =   {$params['seq']}      
        ";
        return $this->db->query($query);
    }

    public function mainGetList($params)
    {

        // 변수 정리
        $where = '';
        $offset = !empty($params['offset']) ? $params['offset'] : 0;
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $keyword = !empty($params['keyword']) ? $params['keyword'] : '';
        $dspYn = !empty($params['dspYn']) ? $params['dspYn'] : '';
        $sDate = !empty($params['sDate']) ? $params['sDate'] : '';
        $eDate = !empty($params['eDate']) ? $params['eDate'] : '';
        $period = !empty($params['period']) ? $params['period'] : '';
        $mvType = !empty($params['mvType']) ? $params['mvType'] : '';

        if (!empty($keyword)) {
            $where .= "AND MV_NM LIKE '%{$keyword}%'";
        }

        if (!empty($dspYn)) {
            $where .= "AND DSP_YN = '{$dspYn}'";
        }

        if (!empty($sDate)) {
            $where .= "AND DATE_FORMAT(DSP_ST_DT, '%Y-%m-%d') >= '{$sDate}'";
        }

        if (!empty($eDate)) {
            $where .= "AND DATE_FORMAT(DSP_END_DT, '%Y-%m-%d') <= '{$eDate}'";
        }

        /*        if (!empty($period)) {
                    if ($period == '0') { //전체
                        $where .= "";
                    } else if ($period == '1') { //당일
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";
                    } else if ($period == '2') { //1주
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
                    } else if ($period == '3') { //1개월
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_ADD(NOW(), INTERVAL -30 DAY)";
                    } else if ($period == '4') { //3개월
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
                    }
                }*/

        if (!empty($mvType)){
            if ($mvType == 'A'){
            }else{
                $where .= "AND MV_TYPE = '{$mvType}'";
            }
        }

        $query = "
            SELECT SQL_CALC_FOUND_ROWS
                *, 
                DATE_FORMAT(DSP_ST_DT, '%Y-%m-%d') AS DSP_ST_DT,
                DATE_FORMAT(DSP_END_DT, '%Y-%m-%d') AS DSP_END_DT
                
            FROM MAIN_VISUAL
            WHERE DEL_YN = 'N'
            {$where}
            ORDER BY ODR_NO DESC
            LIMIT {$offset}, {$limit}
        ";
        $result = $this->db->query($query);
        $this->num_rows = $result->num_rows;

        // total
        $_q = "SELECT FOUND_ROWS() AS total";
        $_r = $this->db->query($_q);
        $_d = $_r->fetch_assoc();
        $this->total = $_d['total'];

        // 전체 데이터
        $arrList = $result->fetch_all(MYSQLI_ASSOC);
        return $arrList;
    }

    public function mainGetListEn($params)
    {

        // 변수 정리
        $where = '';
        $offset = !empty($params['offset']) ? $params['offset'] : 0;
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $keyword = !empty($params['keyword']) ? $params['keyword'] : '';
        $dspYn = !empty($params['dspYn']) ? $params['dspYn'] : '';
        $sDate = !empty($params['sDate']) ? $params['sDate'] : '';
        $eDate = !empty($params['eDate']) ? $params['eDate'] : '';
        $period = !empty($params['period']) ? $params['period'] : '';
        $mvType = !empty($params['mvType']) ? $params['mvType'] : '';

        if (!empty($keyword)) {
            $where .= "AND MV_NM LIKE '%{$keyword}%'";
        }

        if (!empty($dspYn)) {
            $where .= "AND DSP_YN = '{$dspYn}'";
        }

        if (!empty($sDate)) {
            $where .= "AND DATE_FORMAT(DSP_ST_DT, '%Y-%m-%d') >= '{$sDate}'";
        }

        if (!empty($eDate)) {
            $where .= "AND DATE_FORMAT(DSP_END_DT, '%Y-%m-%d') <= '{$eDate}'";
        }

        /*        if (!empty($period)) {
                    if ($period == '0') { //전체
                        $where .= "";
                    } else if ($period == '1') { //당일
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";
                    } else if ($period == '2') { //1주
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
                    } else if ($period == '3') { //1개월
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_ADD(NOW(), INTERVAL -30 DAY)";
                    } else if ($period == '4') { //3개월
                        $where .= "AND DATE_FORMAT(CREATED_AT, '%Y-%m-%d') >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
                    }
                }*/

        if (!empty($mvType)){
            if ($mvType == 'A'){
            }else{
                $where .= "AND MV_TYPE = '{$mvType}'";
            }
        }

        $query = "
            SELECT SQL_CALC_FOUND_ROWS
                *, 
                DATE_FORMAT(DSP_ST_DT, '%Y-%m-%d') AS DSP_ST_DT,
                DATE_FORMAT(DSP_END_DT, '%Y-%m-%d') AS DSP_END_DT
                
            FROM MAIN_VISUAL_EN
            WHERE DEL_YN = 'N'
            {$where}
            ORDER BY ODR_NO DESC
            LIMIT {$offset}, {$limit}
        ";
        $result = $this->db->query($query);
        $this->num_rows = $result->num_rows;

        // total
        $_q = "SELECT FOUND_ROWS() AS total";
        $_r = $this->db->query($_q);
        $_d = $_r->fetch_assoc();
        $this->total = $_d['total'];

        // 전체 데이터
        $arrList = $result->fetch_all(MYSQLI_ASSOC);
        return $arrList;
    }

    // 메인비주얼 상세
    public function visualDetail($mvSeq){
        $query = "
                SELECT
                    *
                FROM MAIN_VISUAL M
                WHERE 1=1
                AND M.MV_SEQ = {$mvSeq}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    public function visualDetailEn($mvSeq){
        $query = "
                SELECT
                    *
                FROM MAIN_VISUAL_EN M
                WHERE 1=1
                AND M.MV_SEQ = {$mvSeq}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    // 20230620 조원영 (메인 비주얼 등록)
    public function visualInsert($params){
        $tableNm = ($params['enYn'] == 'Y')? 'MAIN_VISUAL_EN': 'MAIN_VISUAL';

        // 현재 까지 등록된 노출 순서 조회
        $query = "
                SELECT
                ODR_NO
                FROM {$tableNm}
                WHERE 1=1
                AND DEL_YN = 'N'
                ORDER BY ODR_NO DESC
                LIMIT 1
        ";
        $result = $this->db->query($query);
        $data   = $result->fetch_assoc();
        $odrNo = !empty($data['ODR_NO']) ? $data['ODR_NO']  : 0;
        /*if ($odrNo == 0){
            return false;
        }*/
        $odrNo++;

        $params['mainVisualNm'] = $this->db->real_escape($params['mainVisualNm']);
        $params['mainVisualNm'] = strip_tags($params['mainVisualNm']);
        $params['inputUrl'] = $this->db->real_escape($params['inputUrl']);
        $params['inputUrl'] = strip_tags($params['inputUrl']);

        $query = "
                INSERT INTO {$tableNm}
                (MV_TYPE, MV_NM, LINK, LINK_TYPE, DSP_ST_DT, DSP_ST_TIME, DSP_END_DT, DSP_END_TIME, DSP_YN, ODR_NO, CREATED_IP)
                VALUES
                ('{$params['type']}', '{$params['mainVisualNm']}', '{$params['inputUrl']}', '{$params['linkType']}', '{$params['sDate']}', '{$params['sTime']}', 
                 '{$params['eDate']}', '{$params['eTime']}', '{$params['viewYn']}', {$odrNo}, '{$params['ip']}')
        ";

        $this->db->query($query);
        return $this->db->insert_id();
    }

    // 20230620 조원영 (비주얼 수정)
    public function visualUpdate($params){
        $tableNm = ($params['enYn'] == 'Y')? 'MAIN_VISUAL_EN': 'MAIN_VISUAL';

        $query = "
                UPDATE {$tableNm} SET
                MV_TYPE = '{$params['type']}',
                MV_NM = '{$params['mainVisualNm']}',
                LINK = '{$params['inputUrl']}',
                LINK_TYPE = '{$params['linkType']}',
                DSP_ST_DT = '{$params['sDate']}',
                DSP_ST_TIME = '{$params['sTime']}',
                DSP_END_DT = '{$params['eDate']}',
                DSP_END_TIME = '{$params['eTime']}',
                DSP_YN = '{$params['viewYn']}',
                UPDATED_IP = '{$params['ip']}'
                WHERE 1=1
                AND MV_SEQ = {$params['mvSeq']}
        ";
        return $this->db->query($query);
    }

    // 비주얼 삭제
    public function visualDelete($params)
    {
        $tableNm = ($params['enYn'] == 'Y')? 'MAIN_VISUAL_EN': 'MAIN_VISUAL';

        $query = "
            UPDATE {$tableNm} SET
            DEL_YN = 'Y'
            WHERE 1=1
             AND MV_SEQ IN ({$params['visualSeq']})      
        ";
        return $this->db->query($query);
    }

    // 20230621 조원영 (비주얼 순서 변경)
    public function updateOrder($params)
    {
        $list       = explode(",", $params['list']);
        $beforeList = explode(",", $params['beforeList']);

        foreach ($beforeList as $key => $value) {
            $query = "
                    UPDATE {$params['tableNm']} set
                    ODR_NO = {$value}
                    WHERE {$params['columNm']} = '{$list[$key]}'
            ";
            $result = $this->db->query($query);
        }
        return $result;
    }

    // 20230621 조원영 (상단 배너 리스트 조회)
    public function getBannerList($params){
        // 변수 정리
        $where = '';
        $offset = !empty($params['offset']) ? $params['offset'] : 0;
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $keyword = !empty($params['keyword']) ? $params['keyword'] : '';
        $dspYn = !empty($params['dspYn']) ? $params['dspYn'] : '';
        $sDate = !empty($params['sDate']) ? $params['sDate'] : '';
        $eDate = !empty($params['eDate']) ? $params['eDate'] : '';
        $period = !empty($params['period']) ? $params['period'] : '';
        $mvType = !empty($params['mvType']) ? $params['mvType'] : '';

        if (!empty($keyword)) {
            $where .= "AND TBN_NAME LIKE '%{$keyword}%'";
        }

        if (!empty($dspYn)) {
            $where .= "AND DSP_YN = '{$dspYn}'";
        }

        if (!empty($sDate)) {
            $where .= "AND DATE_FORMAT(DSP_ST_DT, '%Y-%m-%d') >= '{$sDate}'";
        }

        if (!empty($eDate)) {
            $where .= "AND DATE_FORMAT(DSP_END_DT, '%Y-%m-%d') <= '{$eDate}'";
        }

        $query = "
                SELECT SQL_CALC_FOUND_ROWS
                *
                FROM TOP_BANNER
                WHERE 1=1
                AND DEL_YN = 'N'
                   {$where}
                ORDER BY TBN_SEQ DESC
                LIMIT {$offset}, {$limit}
        ";

        $result = $this->db->query($query);
        $this->num_rows = $result->num_rows;

        // total
        $_q = "SELECT FOUND_ROWS() AS total";
        $_r = $this->db->query($_q);
        $_d = $_r->fetch_assoc();
        $this->total = $_d['total'];

        // 전체 데이터
        $arrList = $result->fetch_all(MYSQLI_ASSOC);
        return $arrList;
    }

    public function getBannerListEn($params){
        // 변수 정리
        $where = '';
        $offset = !empty($params['offset']) ? $params['offset'] : 0;
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $keyword = !empty($params['keyword']) ? $params['keyword'] : '';
        $dspYn = !empty($params['dspYn']) ? $params['dspYn'] : '';
        $sDate = !empty($params['sDate']) ? $params['sDate'] : '';
        $eDate = !empty($params['eDate']) ? $params['eDate'] : '';
        $period = !empty($params['period']) ? $params['period'] : '';
        $mvType = !empty($params['mvType']) ? $params['mvType'] : '';

        if (!empty($keyword)) {
            $where .= "AND TBN_NAME LIKE '%{$keyword}%'";
        }

        if (!empty($dspYn)) {
            $where .= "AND DSP_YN = '{$dspYn}'";
        }

        if (!empty($sDate)) {
            $where .= "AND DATE_FORMAT(DSP_ST_DT, '%Y-%m-%d') >= '{$sDate}'";
        }

        if (!empty($eDate)) {
            $where .= "AND DATE_FORMAT(DSP_END_DT, '%Y-%m-%d') <= '{$eDate}'";
        }

        $query = "
                SELECT SQL_CALC_FOUND_ROWS
                *
                FROM TOP_BANNER_EN
                WHERE 1=1
                AND DEL_YN = 'N'
                   {$where}
                ORDER BY TBN_SEQ DESC
                LIMIT {$offset}, {$limit}
        ";

        $result = $this->db->query($query);
        $this->num_rows = $result->num_rows;

        // total
        $_q = "SELECT FOUND_ROWS() AS total";
        $_r = $this->db->query($_q);
        $_d = $_r->fetch_assoc();
        $this->total = $_d['total'];

        // 전체 데이터
        $arrList = $result->fetch_all(MYSQLI_ASSOC);
        return $arrList;
    }

    // 20230621 조원영 (상단 배너 등록)
    public function bannerInsert($params){
        $tableNm = ($params['enYn'] == 'Y')? 'TOP_BANNER_EN': 'TOP_BANNER';

        $query = "
                INSERT INTO {$tableNm}
                (TBN_NAME, LINK, LINK_TYPE, DSP_ST_DT, DSP_ST_TIME, DSP_END_DT, DSP_END_TIME, DSP_YN, CREATED_IP)
                VALUES
                ('{$params['bannerNm']}', '{$params['inputUrl']}', '{$params['linkType']}', '{$params['sDate']}',
                 '{$params['sTime']}', '{$params['eDate']}', '{$params['eTime']}', '{$params['viewYn']}', '{$params['ip']}')
        ";
        $this->db->query($query);
        return $this->db->insert_id();
    }

    // 20230621 조원영 (배너 상세조회)
    public function bannerDetail($seq){
        $query = "
                SELECT
                *
                FROM TOP_BANNER
                WHERE 1=1
                AND TBN_SEQ = {$seq}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    public function bannerDetailEn($seq){
        $query = "
                SELECT
                *
                FROM TOP_BANNER_EN
                WHERE 1=1
                AND TBN_SEQ = {$seq}
        ";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    // 20230621 조원영 (배너 수정)
    public function bannerUpdate($params){
        $tableNm = ($params['enYn'] == 'Y')? 'TOP_BANNER_EN': 'TOP_BANNER';

        $query = "
                UPDATE {$tableNm} SET
                TBN_NAME = '{$params['inputName']}',
                LINK = '{$params['inputUrl']}',
                LINK_TYPE = '{$params['linkType']}',
                DSP_ST_DT = '{$params['sDate']}',
                DSP_ST_TIME = '{$params['sTime']}',
                DSP_END_DT = '{$params['eDate']}',
                DSP_END_TIME = '{$params['eTime']}',
                DSP_YN = '{$params['viewYn']}',
                UPDATED_IP = '{$params['ip']}'
                WHERE 1=1
                AND TBN_SEQ = {$params['tbnSeq']}
        ";
        return $this->db->query($query);
    }

    // 20230622 조원영 (메인 비주얼 삭제 이후 순서 재정렬)
    public function resetOrder($deletedSize, $params){
        $tableNm = ($params['enYn'] == 'Y')? 'MAIN_VISUAL_EN': 'MAIN_VISUAL';

        $query = "
                SELECT
                    MV_SEQ
                FROM {$tableNm}
                WHERE 1=1
                AND DEL_YN = 'N'
                ORDER BY ODR_NO
        ";
        $result     = $this->db->query($query);
        $mainArray  = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($mainArray as $key => $value){
            $key2 = $key+1;
            $query = "
                    UPDATE {$tableNm} SET
                    ODR_NO = {$key2}
                    WHERE 1=1
                    AND DEL_YN = 'N'
                    AND MV_SEQ = {$value['MV_SEQ']}
                    ";
            $result = $this->db->query($query);
            if (!$result){
                return false;
            }
        }
        return true;
    }
}