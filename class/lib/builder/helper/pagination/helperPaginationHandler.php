<?php
/**
 * 페이징 라이브러리 클래스
 *
 * @package     MessageBox
 * @author      Choi Kwangmyung <kmchoi@simplexi.com>
 * @since       2010. 11. 22.
 * @version     1.1.0
  */
final class helperPaginationHandler
{
    const PAGING_BUTTON_TYPE_FIRST      = 0x01;
    const PAGING_BUTTON_TYPE_LAST       = 0x02;
    const PAGING_BUTTON_TYPE_PREV       = 0x03;
    const PAGING_BUTTON_TYPE_NEXT       = 0x04;
    const PAGING_BUTTON_TYPE_CURRENT    = 0x05;

    const PAGING_STYLE_NORMAL = 0x21;
    const PAGING_STYLE_GOOGLE = 0x22;

    private $sBaseUrl;                      // 페이징 라이브러리 클래스의 인스턴스를 생성한 페이지 주소
    private $iCurrentPage;                  // 현재 페이지
    private $iDisplayRowsCountPerPage;      // 한페이지에 보여줄 게시물 갯수
    private $iRowsCountTotal;               // 전체 게시물 갯수
    private $iPagesCountTotal;              // 전체 페이지 갯수
    private $aParametersToAdd = array();    // URL에 넘겨줄 파라미터

    // 아래는 옵션 멤버 변수로써 클래스 생성자에 의해 새로운 값을 할당할 수 있다.
    private $iPageLinksCountPerGroup = 10;  // 페이지 그룹에 보여줄 페이지 카운트(기본값 10)
    private $sPageParameterName = 'page';   // 페이지 파라미터명

    private $sButtonNormalStyle = '';       // 페이지 이동링크 버튼의 스타일
    private $sButtonNormalCssClass = '';    // 페이지 이동링크 버튼의 CSS 클래스명
    private $sButtonNormalCssClassCurr = '';// 페이지 이동링크 버튼의 현재 페이지의 CSS 클래스명
    private $sButtonNormalForward = '';     // 페이지 이동링크 버튼의 앞에 표시될 문자열(HTML테그등)
    private $sButtonNormalBackward = '';    // 페이지 이동링크 버튼의 뒤에 표시될 문자열
    private $sButtonNormalTitle = '';       // 페이지 이동링크 버튼의 타이틀

    private $sButtonFirstPage = 'FIRST';    // 첫번째 페이지 이동 링크 버튼
    private $sButtonFirstPageStyle = '';    // 첫번째 페이지 이동 링크 버튼의 스타일
    private $sButtonFirstPageCssClass = ''; // 첫번째 페이지 이동 링크 버튼의 CSS 클래스명
    private $sButtonFirstPageForward = '';  // 첫번째 페이지 이동 링크 버튼의 앞에 표시될 문자열(HTML테그등)
    private $sButtonFirstPageBackward = ''; // 첫번째 페이지 이동 링크 버튼의 뒤에 표시될 문자열
    private $sButtonFirstPageTitle = '';    // 첫번째 페이지 이동 링크 버튼의 타이틀

    private $sButtonLastPage = 'LAST';      // 마지막 페이지 이동 링크 버튼
    private $sButtonLastPageStyle = '';     // 마지막 페이지 이동 링크의 스타일
    private $sButtonLastPageCssClass = '';  // 마지막 페이지 이동 링크의 CSS 클래스명
    private $sButtonLastPageForward = '';   // 마지막 페이지 이동 링크의 앞에 표시될 문자열(HTML테그등)
    private $sButtonLastPageBackward = '';  // 마지막 페이지 이동 링크의 뒤에 표시될 문자열
    private $sButtonLastPageTitle = '';     // 마지막 페이지 이동 링크 버튼의 타이틀

    private $sButtonPrevPage = 'PREV';      // 이전 페이지 이동 링크 버튼
    private $sButtonPrevPageStyle = '';     // 이전 페이지 이동 링크 버튼의 스타일
    private $sButtonPrevPageCssClass = '';  // 이전 페이지 이동 링크 버튼의 CSS 클래스명
    private $sButtonPrevPageForward = '';   // 이전 페이지 이동 링크 버튼의 앞에 표시될 문자열(HTML테그등)
    private $sButtonPrevPageBackward = '';  // 이전 페이지 이동 링크 버튼의 뒤에 표시될 문자열
    private $sButtonPrevPageTitle = '';     // 이전 페이지 이동 링크 버튼의 타이틀

    private $sButtonNextPage = 'NEXT';      // 다음 페이지 이동 링크 버튼
    private $sButtonNextPageStyle = '';     // 다음 페이지 이동 링크 버튼의 스타일
    private $sButtonNextPageCssClass = '';  // 다음 페이지 이동 링크 버튼의 CSS 클래스명
    private $sButtonNextPageForward = '';   // 다음 페이지 이동 링크 버튼의 앞에 표시될 문자열(HTML테그등)
    private $sButtonNextPageBackward = '';  // 다음 페이지 이동 링크 버튼의 뒤에 표시될 문자열
    private $sButtonNextPageTitle = '';     // 다음 페이지 이동 링크 버튼의 타이틀

    private $bButtonFirstVisible = true;    // 첫번째 페이지 이동 링크 버튼 보이기
    private $bButtonLastVisible = true;     // 마지막 페이지 이동 링크 버튼 보이기
    private $bButtonPrevVisible = true;     // 이전 페이지 이동 링크 버튼 보이기
    private $bButtonNextVisible = true;     // 다음 페이지 이동 링크 버튼 보이기

    /**
     * 초기화
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 11. 30.
     *
     * @param   int     $iCurrentPage               현재 페이지 번호
     * @param   int     $iDisplayRowsCountPerPage   한 페이지에 출력할 ROW수
     * @param   int     $iRowsCountTotal            전체 게시물 갯수
     * @param   array   $aOption                    페이징에 적용할 옵션 배열값
     * @param   array   $aParametersToAdd           URL로 넘겨줄 파라미터
     * @param   array   $sBaseUrl           기본 URL
     */
    public function prepare($iCurrentPage, $iDisplayRowsCountPerPage, $iRowsCountTotal, $aOption = null, $aParametersToAdd = array(), $sBaseUrl = null)
    {
        $this->sBaseUrl = $sBaseUrl ? $sBaseUrl : $_SERVER['REQUEST_URI'];
        $this->iCurrentPage = $iCurrentPage;
        $this->iDisplayRowsCountPerPage = $iDisplayRowsCountPerPage;
        $this->iRowsCountTotal = $iRowsCountTotal;
        $this->iPagesCountTotal = ceil($this->iRowsCountTotal / $this->iDisplayRowsCountPerPage);

        if ($this->iPagesCountTotal < 1) {
            $this->iPagesCountTotal = 1;
        }

        // 옵션을 설정한다.
        if (!is_null($aOption)) {
            foreach ($aOption as $key => $val) {
                $this->setOption($key, $val);
            }
        }

        $this->setParameters($aParametersToAdd);
    }

    /**
     * 페이징 링크에서 다음 페이지로 전달할 파라미터 설정
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 03. 07.
     *
     * @param   array       파라미터 키 & 값
     */
    public function setParameters($aParametersToAdd)
    {
        $this->aParametersToAdd = $aParametersToAdd;
    }

    /**
     * 페이징에 적용할 옵션 설정
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 11. 24.
     *
     * @param   string      옵션명
     * @param   string      옵션값
     */
    public function setOption($sOptionKey, $sOptionVal)
    {
        $aFilterKey = array(
            'sBaseUrl', 'iCurrentPage', 'iArticlesCountPerPage', 'iArticlesCountTotal', 'iPagesCountTotal'
        );

        if (in_array($sOptionKey, array($aFilterKey)) || !isset($sOptionVal)) {
            return false;
        }

        $this->$sOptionKey = (is_numeric($sOptionVal) === true) ? intval($sOptionVal) : $sOptionVal;
    }

    /**
     * 전체 페이지 수 반환
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 11.22.
     *
     * @return  int 전체 페이지 갯수
     */
    public function getPagesCountTotal()
    {
        return $this->iPagesCountTotal;
    }

    /**
     * 페이징 링크 빌드
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2011. 03. 07.
     *
     * @param   boolean $iStyle             페이지 링크의 스타일
     * @param   boolean $bArray             생성된 페이지 링크를 배열로 리턴 받을지 여부
     * @param   integer $iPageGroupCount    현재 페이지 번호를 기준으로 앞뒤로 보여줄 링크 번호 갯수(구글스타일에 적용)
     * @return  string  페이징 링크
     */
    public function build($iStyle, $bArray = false, $iPageGroupCount = 9, $bAssign = false)
    {
        switch ($iStyle) {
            case self::PAGING_STYLE_GOOGLE:
                $aResult = $this->makeGooglePageLink($bArray, $iPageGroupCount, $bAssign);
                break;

            default:
                $aResult = $this->makeNormalPageLink($bArray, $bAssign);
                break;
        }

        if ($bAssign == false) {
            if ($bArray === true) return $aResult;
            else return $this->fromArray($aResult);
        }
    }

    /**
     * 배열로부터 페이징 링크 빌드
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 12. 1.
     *
     * @param   array   $aPagingLinks   페이징 링크 배열
     * @return  string  페이징 링크
     */
    public function fromArray($aPagingLinks)
    {
        return implode('&nbsp;', $aPagingLinks);
    }

    /**
     * 일반 페이지 링크 생성
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2011. 03. 07.
     *
     * @param   boolean $bArray     링크를 배열로 반환 받을지 여부
     * @return  string  페이징 링크
     */
    private function makeNormalPageLink($bArray, $bAssign = false)
    {
        $aPagingLinks = array();

        // 페이지 링크 그룹의 시작 번호를 구한다.
        if ($this->iCurrentPage < $this->iPageLinksCountPerGroup) {
            $iGroupNumberStart = (intval($this->iCurrentPage / $this->iPageLinksCountPerGroup) * $this->iPageLinksCountPerGroup) + 1;
        } else {
            $iGroupNumberStart = ((ceil($this->iCurrentPage / $this->iPageLinksCountPerGroup) - 1) * $this->iPageLinksCountPerGroup) + 1;
        }

        // 페이지 링크 그룹의 끝 번호를 구한다.
        $iGroupNumberEnd = $iGroupNumberStart + ($this->iPageLinksCountPerGroup - 1);

        if ($iGroupNumberEnd > $this->iPagesCountTotal) {
            $iGroupNumberEnd = $this->iPagesCountTotal;
        }

        if ($iGroupNumberEnd < 1) {
            $iGroupNumberEnd = 1;
        }

        // 페이지 링크 그룹의 이전 및 다음 번호를 구한다.
        $iPrevPage = $iGroupNumberStart - 1;
        $iNextPage = $iGroupNumberEnd + 1;

        // 첫번째 페이지 이동 링크 버튼의 테그를 생성한다.
        if ($this->iCurrentPage > 1 && $this->bButtonFirstVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink(1, self::PAGING_BUTTON_TYPE_FIRST, self::PAGING_STYLE_NORMAL, $bAssign);
        }elseif($bAssign == true){
            $this->assignDisplayForModule('first', false);
        }

        // 이전 페이지 이동 링크 버튼의 테그를 생성한다.
        if ($this->iCurrentPage > $this->iPageLinksCountPerGroup && $this->bButtonPrevVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink($iPrevPage, self::PAGING_BUTTON_TYPE_PREV, self::PAGING_STYLE_NORMAL, $bAssign);
        }elseif($bAssign == true){
            $this->assignDisplayForModule('prev', false);
        }

        // 페이지 이동 링크의 테그를 생성한다.
        for ($i = $iGroupNumberStart; $i <= $iGroupNumberEnd; $i++) {
            if ($i == $this->iCurrentPage) {
                $aPagingLinks[] = $this->wrapPageLink($i, self::PAGING_BUTTON_TYPE_CURRENT, self::PAGING_STYLE_NORMAL, $bAssign);
            } else {
                $aPagingLinks[] = $this->wrapPageLink($i, null, self::PAGING_STYLE_NORMAL, $bAssign);
            }
        }

        // 다음 페이지 이동 링크 버튼의 테그를 생성한다.
        if ($this->iCurrentPage < $iNextPage && $this->iPagesCountTotal > $iNextPage && $this->bButtonNextVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink($iNextPage, self::PAGING_BUTTON_TYPE_NEXT, self::PAGING_STYLE_NORMAL, $bAssign);
        }elseif($bAssign == true){
            $this->assignDisplayForModule('next', false);
        }

        // 마지막 페이지 이동 링크 버튼의 테그를 생성한다.
        if ($this->iCurrentPage < $this->iPagesCountTotal && $this->bButtonLastVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink($this->iPagesCountTotal, self::PAGING_BUTTON_TYPE_LAST, self::PAGING_STYLE_NORMAL, $bAssign);
        }elseif($bAssign == true){
            $this->assignDisplayForModule('last', false);
        }

        // 현재 페이지의 파라미터 유무에 따른 처리
        if (strpos($this->sBaseUrl, '?') === false) {
            $iCount = count($aPagingLinks);

            for ($i = 0; $i < $iCount; $i++) {
                $sPagingLink = $aPagingLinks[$i];
                $iPosition = strpos($sPagingLink, '&');

                if ($iPosition !== false && $iPosition > 0) {
                    $aPagingLinks[$i] = substr($sPagingLink, 0, $iPosition) . '?' . substr($sPagingLink, $iPosition + 1);
                }
            }
        }

//        echo $this->iPagesCountTotal;

        if ($bAssign == false) {
            return $aPagingLinks;
        } else {
            $i = 0;

            foreach ($aPagingLinks as $key => $value){
                if ($value) {
                    $aPagination[$i] = $value;
                    $i++;
                }
            }

            if ($this->iPagesCountTotal < 2) {
                $this->assignForModule('link_first', '');
                $this->assignForModule('parameter_prev', '');
                $this->assignForModule('parameter_next', '');
                $this->assignForModule('link_last', '');
            }

            $this->assignLoop($aPagination);
        }
    }

    /**
     * 구글 스타일 페이지 링크 생성
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2011. 03. 07.
     *
     * @param   boolean $bArray             링크를 배열로 반환 받을지 여부
     * @param   integer $iPageGroupCount    현재 페이지 번호를 기준으로 앞뒤로 보여줄 링크 번호 갯수
     * @return  string  페이징 링크
     */
    private function makeGooglePageLink($bArray=false, $iPageGroupCount, $bAssign = false)
    {
        $aPagingLinks = array();

        // 시작번호
        if ($this->iCurrentPage > $iPageGroupCount) {
            $iGroupNumberStart = $this->iCurrentPage - $iPageGroupCount;
        } else {
            $iGroupNumberStart = 1;
        }

        // 끝번호
        if ($this->iCurrentPage + $iPageGroupCount < $this->iPagesCountTotal) {
            $iGroupNumberEnd = $this->iCurrentPage + $iPageGroupCount;
        } else {
            $iGroupNumberEnd = $this->iPagesCountTotal;
        }

        // 첫번째 페이지 이동 링크 버튼의 테그를 생성한다.
        /*if ($this->iCurrentPage > ($iPageGroupCount + 1) && $this->bButtonFirstVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink(1, self::PAGING_BUTTON_TYPE_FIRST);
        }*/

        // 이전 페이지 이동 링크 버튼의 테그를 생성한다.
        if ($this->bButtonPrevVisible == true) {
            $iPrevPage = ($this->iCurrentPage - 1 > 0) ? $this->iCurrentPage - 1 : 1;
            $aPagingLinks[] = $this->wrapPageLink($iPrevPage, self::PAGING_BUTTON_TYPE_PREV, self::PAGING_STYLE_GOOGLE, $bAssign);
        }

        if ($this->iCurrentPage > ($iPageGroupCount + 1) && $this->bButtonFirstVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink(1, self::PAGING_BUTTON_TYPE_FIRST, self::PAGING_STYLE_GOOGLE, $bAssign);
        }elseif($bAssign == true){
            $this->assignDisplayForModule('first', false);
        }

        // 페이지 이동 링크의 테그를 생성한다.
        for ($i = $iGroupNumberStart; $i <= $iGroupNumberEnd; $i++) {
            if ($i == $this->iCurrentPage) {
                $aPagingLinks[] = $this->wrapPageLink($i, self::PAGING_BUTTON_TYPE_CURRENT, self::PAGING_STYLE_GOOGLE, $bAssign);
            } else {
                $aPagingLinks[] = $this->wrapPageLink($i, null, self::PAGING_STYLE_GOOGLE, $bAssign);
            }
        }

        if ($this->iCurrentPage < ($this->iPagesCountTotal - $iPageGroupCount) && $this->bButtonLastVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink($this->iPagesCountTotal, self::PAGING_BUTTON_TYPE_LAST, self::PAGING_STYLE_GOOGLE, $bAssign);
        }elseif($bAssign == true){
            $this->assignDisplayForModule('last', false);
        }

        // 다음 페이지 이동 링크 버튼의 테그를 생성한다.
        if ($this->bButtonNextVisible == true) {
            $iNextPage = ($this->iCurrentPage + 1 < $this->iPagesCountTotal) ? $this->iCurrentPage + 1 : $this->iPagesCountTotal;
            $aPagingLinks[] = $this->wrapPageLink($iNextPage, self::PAGING_BUTTON_TYPE_NEXT, self::PAGING_STYLE_GOOGLE, $bAssign);
        }

        // 마지막 페이지 이동 링크 버튼의 테그를 생성한다.
        /*if ($this->iCurrentPage < ($this->iPagesCountTotal - $iPageGroupCount) && $this->bButtonLastVisible == true) {
            $aPagingLinks[] = $this->wrapPageLink($this->iPagesCountTotal, self::PAGING_BUTTON_TYPE_LAST);
        }*/

        // 현재 페이지의 파라미터 유무에 따른 처리
        if (strpos($this->sBaseUrl, '?') === false) {
            $iCount = count($aPagingLinks);

            for ($i = 0; $i < $iCount; $i++) {
                $sPagingLink = $aPagingLinks[$i];
                $iPosition = strpos($sPagingLink, '&');

                if ($iPosition !== false && $iPosition > 0) {
                    $aPagingLinks[$i] = substr($sPagingLink, 0, $iPosition) . '?' . substr($sPagingLink, $iPosition + 1);
                }
            }
        }

        if ($bAssign == false) {
            return $aPagingLinks;
        } else {
            $i = 0;

            foreach ($aPagingLinks as $key => $value){
                if ($value) {
                    $aPagination[$i] = $value;
                    $i++;
                }
            }

            $this->assignLoop($aPagination);
        }
    }

    /**
     * 페이징 링크 생성 for US Builder
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 12. 31.
     *
     * @return  string  페이징 링크
     */
    public function buildForUS()
    {
        $aOption = array(
            'sButtonFirstPage' => '1',
            'sButtonFirstPageCssClass' => 'num',
            'sButtonFirstPageTitle' => 'first page',
            'sButtonPrevPage' => 'prev',
            'sButtonPrevPageCssClass' => 'activity',
            'sButtonPrevPageTitle' => 'Previous',
            'sButtonNormalCssClassCurr' => 'current',
            'sButtonNormalCssClass' => 'num',
            'sButtonNextPage' => 'next',
            'sButtonNextPageCssClass' => 'activity',
            'sButtonNextPageTitle' => 'Next',
            'sButtonLastPage' => $this->iPagesCountTotal,
            'sButtonLastPageCssClass' => 'num',
            'sButtonLastPageTitle' => 'Last page'
        );

        foreach ($aOption as $key => $val) {
            $this->setOption($key, $val);
        }

        $sHeader = '<div class="sdk_pagination">';
        $sFooter = '</div>';

        return $sHeader . $this->build(self::PAGING_STYLE_GOOGLE, false) . $sFooter;
    }

    /**
     * 페이징 링크 생성 for US Builder Front
     *
     * @access public
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 12. 31.
     *
     * @return  string  페이징 링크
     */
    public function buildForUSFront($sType="Google")
    {
        if($sType=="Google"){
            $aOption = array(
                'sButtonFirstPage' => '1',
                'sButtonNormalCssClassCurr' => 'selected',
                'sButtonLastPage' => $this->iPagesCountTotal
            );
            $iStyle = self::PAGING_STYLE_GOOGLE;
            $iPageGroupCount = 9;
        }else if($sType=="Normal"){
            $aOption = array(
                'sButtonNormalCssClassCurr' => 'current'
            );
            $iStyle = self::PAGING_STYLE_NORMAL;
            $iPageGroupCount = null;
        }

        foreach ($aOption as $key => $val) {
            $this->setOption($key, $val);
        }

        $this->build($iStyle, true, $iPageGroupCount, true);
    }

    /**
     * URL에 포함된 파라미터 키의 값을 치환
     *
     * @access private
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 11. 22.
     *
     * @param   int     $iPage      현재 페이지 번호
     * @param   string  $sUrl       페이지 파라미터 값을 치환할 URL
     *
     * @return  string  페이지 파라미터 값이 치환된 URL
     */
    private function replacePageParameterValue($iPage, $sUrl)
    {
        return preg_replace('/^(.*)(' . $this->sPageParameterName . '=[0-9]+)(.*)?$/i', '\1' . $this->sPageParameterName . '=' . $iPage . '\3', $sUrl);
    }

    /**
     * Query Strnig 파싱
     *
     * @access private
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 11. 30.
     *
     * @return  array  Query String으로 부터 파싱된 파라미터 배열
     */
    private function parseQueryString()
    {
        $aParameters = explode('&', $_SERVER['QUERY_STRING']);
        $aQueryString = array();

        foreach ($aParameters as $sParameter) {
            list($key, $val) = explode('=', $sParameter);

            $aQueryString[$key] = $val;
        }

        return $aQueryString;
    }

    /**
     * 페이지 링크를 Tag로 래핑
     *
     * @access private
     * @author Choi Kwangmyung <kmchoi@simplexi.com>
     * @since 2010. 11. 22.
     *
     * @param   int     $iPage              페이징 링크 테그로 연결될 페이지
     * @param   int     $iLinkButtonType    페이지 링크 버튼 타입
     * @param   boolean $iStyle             페이지 링크의 스타일
     * @return  string  Tag로 래핑된  페이지 링크
     */
    private function wrapPageLink($iPage, $iLinkButtonType, $iStyle = self::PAGING_STYLE_NORMAL, $bAssign = false)
    {
        $sUrl = $this->sBaseUrl;

        $aParameters = $this->parseQueryString();
        $aPagination = array();

        if (array_key_exists($this->sPageParameterName, $aParameters) === true) {
            $sUrl = $this->replacePageParameterValue($iPage, $sUrl);
        } else {
            if(preg_match('/\?/', $sUrl)) $sUrl .= '&';
            else $sUrl .= '?';

            $sUrl .= $this->sPageParameterName . '=' . $iPage;
        }

        if (is_array($this->aParametersToAdd) === true && count($this->aParametersToAdd) > 0) {
            foreach ($this->aParametersToAdd as $key => $val) {
                if (in_array($key, array_keys($aParameters)) === false) {
                    $sUrl .= '&' . $key . '=' . $val;
                }
            }
        }
        $sParameter = preg_replace('/^(.)+\?/', '', $sUrl);

        switch ($iLinkButtonType) {
            case self::PAGING_BUTTON_TYPE_FIRST:
                $sLinkTag = '<a href="' . $sUrl . '" style="' . $this->sButtonFirstPageStyle . '" class="' . $this->sButtonFirstPageCssClass . '" title="' . $this->sButtonFirstPageTitle . '">';
                $sLinkTag .= $this->sButtonFirstPageForward. $this->sButtonFirstPage . $this->sButtonFirstPageBackward;
                $sLinkTag .= '</a>';

                if ($iStyle == self::PAGING_STYLE_GOOGLE) $sLinkTag .= '...';

                if ($bAssign == true){
                    $this->assignForModule('page_first', $this->sButtonFirstPage);
                    $this->assignForModule('first_url', "?".$sParameter);
                }
                break;

            case self::PAGING_BUTTON_TYPE_LAST:
                if ($iStyle == self::PAGING_STYLE_GOOGLE) $sLinkTag = '...';

                $sLinkTag .= '<a href="' . $sUrl . '" style="' . $this->sButtonLastPageStyle . '" class="' . $this->sButtonLastPageCssClass . '" title="' . $this->sButtonLastPageTitle . '">';
                $sLinkTag .= $this->sButtonLastPageForward . $this->sButtonLastPage . $this->sButtonLastPageBackward;
                $sLinkTag .= '</a>';

                if ($bAssign == true){
                    $this->assignForModule('page_last', $this->sButtonLastPage);
                    $this->assignForModule('last_url', "?".$sParameter);

                }
                break;

            case self::PAGING_BUTTON_TYPE_PREV:
                if ($this->iCurrentPage > 1) $sLinkTag = '<a href="' . $sUrl . '" style="' . $this->sButtonPrevPageStyle . '" class="' . $this->sButtonPrevPageCssClass . '" title="' . $this->sButtonPrevPageTitle . '">';
                else $sLinkTag = '<span title="' . $this->sButtonPrevPageTitle . '">';

                $sLinkTag .= $this->sButtonPrevPageForward . $this->sButtonPrevPage . $this->sButtonPrevPageBackward;

                if ($this->iCurrentPage > 1) $sLinkTag .= '</a>';
                else $sLinkTag .= '</span>';

                if ($bAssign == true){
                    $this->assignForModule('class_prev_activated', ($this->iCurrentPage != $iPage ? 'activity' : ''));
                    $this->assignForModule('prev_url', "?".$sParameter);
                }
                break;

            case self::PAGING_BUTTON_TYPE_NEXT:
                if ($this->iCurrentPage < $this->iPagesCountTotal) $sLinkTag = '<a href="' . $sUrl . '" style="' . $this->sButtonNextPageStyle . '" class="' . $this->sButtonNextPageCssClass . '" title="' . $this->sButtonNextPageTitle . '">';
                else $sLinkTag = '<span title="' . $this->sButtonNextPageTitle . '">';

                $sLinkTag .= $this->sButtonNextPageForward . $this->sButtonNextPage . $this->sButtonNextPageBackward;

                if ($this->iCurrentPage < $this->iPagesCountTotal) $sLinkTag .= '</a>';
                else $sLinkTag .= '</span>';

                if ($bAssign == true){
                    $this->assignForModule('class_next_activated', ($this->iCurrentPage != $iPage ? 'activity' : ''));
                    $this->assignForModule('next_url', "?".$sParameter);
                }
                break;

            case self::PAGING_BUTTON_TYPE_CURRENT:
                $sLinkTag = '<a href="' . $sUrl . '" style="' . $this->sButtonNormalStyle . '" class="' . $this->sButtonNormalCssClassCurr . '" title="' . $this->sButtonNormalTitle . '">';
                $sLinkTag .= $this->sButtonNormalForward . $iPage . $this->sButtonNormalBackward;
                $sLinkTag .= '</a>';

                $aPagination = array(
                    "page_url"=>"?".$sParameter,
                    "class_selected"=>$this->sButtonNormalCssClassCurr,
                    "page_number"=>$iPage
                );
                break;

            default:
                $sLinkTag = '<a href="' . $sUrl . '" style="' . $this->sButtonNormalStyle . '" class="' . $this->sButtonNormalCssClass . '" title="' . $this->sButtonNormalTitle . '">';
                $sLinkTag .= $this->sButtonNormalForward . $iPage . $this->sButtonNormalBackward;
                $sLinkTag .= '</a>';

                $aPagination = array(
                    "page_url"=>"?".$sParameter,
                    "class_selected"=>$this->sButtonNormalCssClass,
                    "page_number"=>$iPage
                );
                break;
        }

        if ($bAssign == false) {
            return $sLinkTag;
        } else {
            return $aPagination;
        }
    }



    /**
     * @return addonPaginationFrontControl
     */
    public function front()
    {
    	return getInstance("addonPaginationFrontControl");
    }
}
