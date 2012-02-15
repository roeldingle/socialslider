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
 * You can call the instance of the class with this method, and it calls instance in the form of singleton
 * @param String $sClassName The name of the class
 * @param Mix $mParams 초기화 데이터값
 * @param String $sClassPath 클레스 경로
 * @param Bool $bIsSingleton 싱글톤 사용여부
 * @return Object Instance
 */
function getInstance($sClassName, $mParams=null, $sClassPath=null, $bIsSingleton=true)
{
    $oInstance = loader::getInstance($sClassName, $mParams, $sClassPath, $bIsSingleton);
    if($oInstance === false) echo $sClassName . ' is not found';
    return $oInstance;
}