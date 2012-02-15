<?php
/**
 * 클레스 이름에 대한 정규식을 사용해서 반환 합니다.
 * @param String $sString 문자열
 * @param String $sRegxp 정규식
 * @return Array
 */
function getClassNameRegexp($sString, $sRegxp='[A-Z][a-z]+')
{
    preg_match_all('/'.$sRegxp.'/', $sString, $aMatch);
    return $aMatch;
}

/**
 * alias - sysLibLoader get
 * 해당 클레스의 인스턴스를 가져 옵니다.
 * @param String $sClassName 클레스 이름
 * @param Mix $mParams 초기화 데이터값
 * @param String $sClassPath 클레스 경로
 * @param Bool $bIsSingleton 싱글톤 사용여부
 * @return Object 인스턴스
 */
function getInstance($sClassName, $mParams=null, $sClassPath=null, $bIsSingleton=true)
{
    $oInstance = loader::getInstance($sClassName, $mParams, $sClassPath, $bIsSingleton);
    if($oInstance === false) error($sClassName . ' 클레스를 찾을 수 없습니다.');
    return $oInstance;
}