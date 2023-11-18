<?php

namespace _lib;
class Paging
{
    private $total = 0;
    private $page = 0;
    private $scale = 0;
    private $start_page = 0;
    private $page_max = 0;
    private $block = 0;
    private $tails = '';

    public $offset = 0;
    public $size = 0;


    public function __construct($params)
    {
        $this->total = !empty($params['total']) ? $params['total'] : 0;
        $this->page = !empty($params['page']) ? $params['page'] : 1;
        $this->size = !empty($params['size']) ? $params['size'] : 0;
        $this->scale = !empty($params['scale']) ? $params['scale'] : 0; // 10
        $resize = !empty($params['resize']) ? $params['resize'] : 0;
        $schType = !empty($params['schType']) ? $params['schType'] : array();
        $arrSch = !empty($params['arrSch']) ? $params['arrSch'] : array();
        $seq = !empty($params['seq']) ? $params['seq'] : 0;
        $period = !empty($params['period']) ? $params['period'] : 0;
        $startYear = !empty($params['startYear']) ? $params['startYear'] : '';
        $endYear = !empty($params['endYear']) ? $params['endYear'] : '';
        $type = !empty($params['type']) ? $params['type'] : '';
        $tableRows = !empty($params['tableRows'])  ? $params['tableRows']   : 0;
        $keyword = !empty($params['keyword'])   ? $params['keyword']    : '';
        $sDate = !empty($params['sDate'])   ? $params['sDate']    : '';
        $eDate = !empty($params['eDate'])   ? $params['eDate']    : '';
        $inputSearch = !empty($params['inputSearch'])   ? $params['inputSearch']    : '';
        $schKey = !empty($params['schKey'])   ? $params['schKey']    : '';
        $large = !empty($params['large'])   ? $params['large']    : '';
        $middle = !empty($params['middle'])   ? $params['middle']    : '';
        $small = !empty($params['small'])   ? $params['small']    : '';
        $dspYn = !empty($params['dspYn'])   ? $params['dspYn']    : '';
        $editPdCode = !empty($params['editPdCode'])   ? $params['editPdCode']    : '';


        // N개씩 보기
        $this->tails = 'RE_SIZE=' . $resize . '&';
        // 계산
        $this->page_max = ceil($this->total / $this->size);
        $this->offset = ($this->page - 1) * $this->size;
        $this->block = floor(($this->page - 1) / $this->scale);
        $this->start_page = $this->block * $this->scale + 1;

        // 검색
        if (is_array($arrSch)) {
            foreach ($arrSch as $key => $value) {
                $this->tails .= $key . '=' . $value . '&';
            }
        }
/*        if (is_array($schType)) {
            foreach ($schType as $value) {
                $this->tails .= 'schType%5B%5D=' . $value . '&';
            }
        }*/
        if ($seq != 0) {
            $this->tails .= 'seq=' . $seq . '&';
        }
        if ($period != 0) {
            $this->tails .= 'period=' . $period . '&';
        }
        if (!empty($startYear)) {
            $this->tails .= 'startYear=' . $startYear . '&';
        }
        if (!empty($endYear)) {
            $this->tails .= 'endYear=' . $endYear . '&';
        }
        if (!empty($type)) {
            $this->tails .= 'type=' . $type . '&';
        }
        if (!empty($tableRows)) {
            $this->tails .= 'tableRows=' . $tableRows . '&';
        }
        if (!empty($keyword)) {
            $this->tails .= 'inputSearch=' . $keyword . '&';
        }
        if (!empty($sDate)) {
            $this->tails .= 'sDate=' . $sDate . '&';
        }
        if (!empty($eDate)) {
            $this->tails .= 'eDate=' . $eDate . '&';
        }
        if (!empty($inputSearch)) {
            $this->tails .= 'inputSearch=' . $inputSearch . '&';
        }
        if (!empty($schKey)) {
            $this->tails .= 'inputSearch=' . $schKey . '&';
        }
        if (!empty($large)) {
            $this->tails .= 'largeTopic=' . $large . '&';
        }
        if (!empty($middle)) {
            $this->tails .= 'middleTopic=' . $middle . '&';
        }
        if (!empty($small)) {
            $this->tails .= 'smallTopic=' . $small . '&';
        }
        if (!empty($small)) {
            $this->tails .= 'smallTopic=' . $small . '&';
        }
        if (!empty($dspYn)) {
            $this->tails .= 'dspYn=' . $dspYn . '&';
        }
        if (!empty($editPdCode)) {
            $this->tails .= 'editPdCode=' . $editPdCode . '&';
        }
        if (!empty($schType)) {
            $this->tails .= 'schType=' . $schType . '&';
        }

        $this->tails = substr($this->tails, 0, -1);
    }

    public function getList()
    {
        $pre_page = !empty($_GET['page']) ? $_GET['page'] : 1;
        $get_page = 0;
        // 변수 정리
        $list = '';
        $pageclass = "";
        $lastPage = 0;

        $phpSelf = $_SERVER['PHP_SELF'];
        $fileName = basename($phpSelf);

        if ($fileName === 'index.php') {
            // index.php를 생략하여 출력
            $output = str_replace('index.php', '', $phpSelf);
        } else {
            $output = $phpSelf;
        }
        // 이전
        $prev_block = ($this->page) - 1;

        if ($prev_block == 0) {
            $prev_block = 1;
        }

        $list = '<li><a class="page-link first" href="' . $output . '?' . $this->tails . '&page=' . 1 . '" aria-label="Previous"></a></li>'; // 처음으로

        //$list = '<button type="button" onclick="location.href=\'' . $_SERVER['PHP_SELF'] . '?' . $this->tails . '&page=' . $prev_block . '\'" class="img"><</a> ';
        $list .= '<li><a class="page-link prev" href="' . $output . '?' . $this->tails . '&page=' . $prev_block . '" onclick="" aria-label="Previous"></a></li>';
        // 목록
        for ($i = 1; $i <= $this->scale && $this->start_page <= $this->page_max; $i++, $this->start_page++) {
            // 변수 정리
            $url = $output . '?page=' . $this->start_page . '&' . $this->tails;
            if ($pre_page % 10 == 0) {
                $get_page = 10;
            } else {
                $get_page = $pre_page % 10;
            }
            if ($get_page == $i)//현재 페이지 찾기
            {
                $pageclass = " active";
            } else {
                $pageclass = "";
            }

            if ($this->start_page == $this->page) {
//                $list   .= "<button type=\"button\" $pageclass onclick=\"go_url('{$url}');\">{$this->start_page}</button>";
                $list .= '<li><a class="page-link' . $pageclass . '" href="' . $url . '">' . $this->start_page . '</a></li>';

            } else {
//                $list   .= "<button type=\"button\" $pageclass onclick=\"go_url('{$url}');\">{$this->start_page}</button>";
                $list .= '<li><a class="page-link' . $pageclass . '" href="' . $url . '">' . $this->start_page . '</a></li>';
            }
        }
        // 다음
        $next_block = ($this->page) + 1;
        if (ceil($this->total / $this->scale) < $next_block) {
            $next_block = $next_block - 1;
        }
        //$list .= ' <button type="button" onclick="location.href=\'' . $_SERVER['PHP_SELF'] . '?' . $this->tails . '&page=' . $next_block . '\'" class="img">></button>';
        $list .= '<li><a class="page-link next" href="' . $output . '?' . $this->tails . '&page=' . $next_block . '" aria-label="Next"></a></li>';


        $lastPage = ceil($this->total / $this->scale); // 마지막 페이지계산

        // 마지막으로
        $list .= '<li><a class="page-link end" href="' . $output . '?' . $this->tails . '&page=' . $lastPage . '" aria-label="Next"></a></li>'; // 처음으로

        return $list;
    }
}