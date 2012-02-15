<?php
class loader
{
    /**
     * 인스턴스 관리 배열
     */
    private static $_aInstance = array();

    /**
     * 인스턴스 생성 및 반환(모델,뷰,언어팩,옵티마이저의 인터페이스를 사용가능)
     * @param String $sClassName 클레스 이름
     * @return Object 인스턴스
     */
    final public static function getInstance($sClassName, $bIsSingleton=true)
    {
        if(class_exists($sClassName) === false) return false;

        if(array_key_exists($sClassName, self::$_aInstance) === false || $bIsSingleton === false) {
            self::$_aInstance[$sClassName] = new $sClassName();
        }

        return self::$_aInstance[$sClassName];
    }

    /**
    final public function getClassPath($sClassName)
    {
        $aClassName = getClassNameRegexp($sClassName);

        for ($i = 0; $i < count($aClassName[0]) - 1; ++$i) {
            $sClassPath .= $aClassName[0][$i] . '/';
        }
        $sClassPath = 'builder/' . $sClassPath;

        return $sClassPath . $sClassName . '.php';
    }
    **/
}