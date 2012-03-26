<?php
class helperBlocksParser
{
    /**
     * @var libSsh
     */
    protected $sApplicationXmlData;

    protected $sLangDefault;
    protected $sLangSupport;

    protected $aApi;
    protected $aAdmin;
    protected $aFront;

    public function __construct()
    {
        //$this->sApplicationXmlData = file_get_existing_contents(APP_PATH . '/application.xml');
        $this->sApplicationXmlData = file_get_existing_contents(APP_PATH . '/application.xml');

        $this->_parse();
    }

    public function getInterface()
    {
        return array
        (
            'Api'=>$this->aApi,
            'Admin'=>$this->aAdmin,
            'Front'=>$this->aFront
        );
    }

    public function getLangDefault()
    {
        return $this->sLangDefault;
    }

    public function getLangSupport()
    {
        return $this->sLangSupport;
    }

    private function _parse()
    {
        $XMLApplicationInfo = new SimpleXMLElement($this->sApplicationXmlData, LIBXML_ERR_NONE | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NOENT);

        $aCodeAssist = array();

        /* @var $LangElement SimpleXMLElement */
        $ApiElement = $XMLApplicationInfo->{api};
        $sApiUse = $ApiElement['use']? strtolower((string)$ApiElement['use']) : 'off';
        $aApiIfs = array();

        if ($sApiUse=='on') {
            /* @var $ApiIfsElement SimpleXMLElement */
            $ApiIfsElement = $ApiElement->{ifs};

            foreach ($ApiIfsElement->{'if'} as $IfsElement) {
                $aApiIfs[] = array
                (
                    'class'=>(string)$IfsElement['class'],
                	'desc'=>(string)$IfsElement['desc'],
                );
            }
        }

        $this->aApi = array
        (
            'Use'=>$sApiUse,
            'Ifs'=>$aApiIfs
        );


        /* @var $AdminElement SimpleXMLElement */
        $AdminElement = $XMLApplicationInfo->{admin};
        $sAdminUse = $AdminElement['use']? strtolower((string)$AdminElement['use']) : 'off';
        $sAdminIndex = $AdminElement['index']? strtolower((string)$AdminElement['index']) : 'adminIndex';
        $aAdminIfs = array();
        $aAdminMenus = array();

        if ($sAdminUse=='on') {
            /* @var $AdminIfsElement SimpleXMLElement */
            $AdminIfsElement = $AdminElement->{ifs};

            foreach ($AdminIfsElement->{'if'} as $IfsElement) {
                $aAdminIfs[] = array
                (
                    'class'=>(string)$IfsElement['class'],
                    'permssion'=>$IfsElement['permission']? strtolower((string)$IfsElement['permission']) : 'off',
                	'desc'=>(string)$IfsElement['desc'],
                );
            }

            /* @var $AdminMenusElement SimpleXMLElement */
            $AdminMenusElement = $AdminElement->{menus};

            if ($AdminMenusElement) {
                foreach ($AdminMenusElement->{menu} as $MenusElement) {
                    $aAdminMenus[] = array
                    (
                        'class'=>(string)$MenusElement['class'],
                    	'name'=>(string)$MenusElement['name']
                    );
                }
            }
        }

        $this->aAdmin = array
        (
            'Use'=>$sAdminUse,
            'Index'=>$sAdminIndex,
            'Ifs'=>$aAdminIfs,
            'Menus'=>$aAdminMenus
        );


        /* @var $FrontElement SimpleXMLElement */
        $FrontElement = $XMLApplicationInfo->{front};
        $sFrontUse = $FrontElement['use']? strtolower((string)$FrontElement['use']) : 'off';
        $aFrontIfs = array();
        $aFrontCodeAssist = array();

        if ($sFrontUse=='on') {
            /* @var $FrontIfsElement SimpleXMLElement */
            $FrontIfsElement = $FrontElement->{ifs};

            foreach ($FrontIfsElement->{'if'} as $IfsElement) {

                $aFrontIfsByTemp = array
                (
                    'class'=>(string)$IfsElement['class'],
                	'code-assist'=>$IfsElement['code-assist']? strtolower((string)$IfsElement['code-assist']) : 'off',
                    'permssion'=>$IfsElement['permission']? strtolower((string)$IfsElement['permission']) : 'off',
                	'desc'=>(string)$IfsElement['desc'],
                );

                if ($aFrontIfsByTemp['code-assist']=='on') {
                    $aVar = array();
                    $aOption = array();
                    $sCodeHint = file_get_existing_contents(APP_PATH . '/resource/assist/codehint/'.$aFrontIfsByTemp['class'].'.xml');
                    $sSampleHTML = file_get_existing_contents(APP_PATH . '/resource/assist/sample/'.$aFrontIfsByTemp['class'].'.html');
                    $sSampleCSS = file_get_existing_contents(APP_PATH . '/resource/assist/sample/'.$aFrontIfsByTemp['class'].'.css');

                    if ($sCodeHint) {
                        $XMLCodeHint = new SimpleXMLElement($sCodeHint, LIBXML_ERR_NONE | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NOENT);

                        foreach ($XMLCodeHint->{'var'} as $VarElement) {
                            $aVar[] = array
                            (
                            	'value'=>(string)$VarElement['value'],
                            	'name'=>(string)$VarElement['name']
                            );
                        }

                        foreach ($XMLCodeHint->{'option'} as $OptionElement) {
                            $aOption[] = array
                            (
                            	'value'=>(string)$VarElement['value'],
                            	'name'=>(string)$VarElement['name'],
                            	'type'=>(string)$OptionElement['type'],
                            	'default'=>(string)$OptionElement['default']
                            );
                        }
                    }

                    $aFrontCodeAssist[$aFrontIfsByTemp['class']] = array
                    (
                        'SampleHTML'=>$sSampleHTML,
                    	'SampleCSS'=>$sSampleCSS,
                        'Var'=>$aVar,
                        'Option'=>$aOption,
                    );
                }

                $aFrontIfs[] = $aFrontIfsByTemp;
            }
        }

        $this->aFront = array
        (
            'Use'=>$sFrontUse,
            'Ifs'=>$aFrontIfs,
            'Code-Assist'=>$aFrontCodeAssist,
        );


        /* @var $LangElement SimpleXMLElement */
        $LangElement = $XMLApplicationInfo->{langs};

        $sLangSupport = trim((string)$LangElement['support']);
        $aLangSupport = explode(',', $sLangSupport);

        array_walk($aLangSupport,
            function (&$var, $key)
            {
                $var = trim($var);
            }
        );

        $this->sLangSupport = implode(',', $aLangSupport);
        $this->sLangDefault = trim((string)$LangElement['default']);
        if (!$this->sLangSupport) {
            $this->sLangSupport = $this->sLangDefault;
        }
    }
}
