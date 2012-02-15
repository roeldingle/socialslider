<?php
class frontPageSocialslider extends Controller_Front{

	protected $oGet;

    protected function run($aArgs)
    {

    require_once 'builder/builderInterface.php';
		
	$sInitScript = usbuilder()->init($this->Request->getAppID(), $aArgs);
	$this->writeJs($sInitScript);
    
 	/*assign objects*/
    $this->oGet = new modelGet;
 
	$this->display($aArgs);

    }

    protected function display($aArgs){
		
    	/*define page*/
    	$APP_NAME = "socialslider";
    	$this->assign("APP_NAME",$APP_NAME);
    	
    	/*assign url*/
    	$sUrl = usbuilder()->getUrl(__CLASS__);
    	$this->assign("sUrl",$sUrl);
    	
    	/*needed files*/
		$sSlider_url = "slider";
		$sFloating_url = "floating-1.7";
		$this->importJS($sSlider_url);
		$this->importJS($sFloating_url);
    	$this->importJS(__CLASS__);
    	$this->importCSS(__CLASS__);
		
		/*set the user setting*/
    	$aUserSetting = null;//$this->oGet->getRow(2,null);
    	
    	/*set default values*/
    	if(empty($aUserSetting)){
    		$aUserSetting = array(
    				'zoom_level' => 1,
    				'map_type' => "Normal",
    				'locations' => '[{"loc":"Los Angeles, CA, USA","lat":"34.0522342","lng":"-118.2436849","marker":"0"}]',
    				'icons' => '{"name":"facebook",url":"http//facebook.com"},{"name":"youtube","url":"http//youtube.com"}'
    			);
    	
    	}
		
		
		
		
		/*options*/
		$sImage_path = '/_sdk/img/'.$APP_NAME.'/';
		$sIcons = json_decode($aUserSetting['icons'],true);
		$sIcon_type_class = 'big_icon';
		
		$iIcon_image_size = 32;
		
		$sIcon_target_type = 'window';
    	
		/*html assign*/
    	$sHTML_socialslider = '';
    	$sHTML_socialslider .= '<div id="'.$APP_NAME.'_wrap" >';
			$sHTML_socialslider .= '<div class="'.$APP_NAME.'">';
				$sHTML_socialslider .= '<div class="'.$APP_NAME.'_container">';
					$sHTML_socialslider .= '<div class="'.$APP_NAME.'_expand">';
						$sHTML_socialslider .= '<div class="'.$APP_NAME.'_slide">';
						
							/*ul here*/
							$sHTML_socialslider .= '<ul class="'.$sIcon_type_class.'">';
							
								/*loop the icons*/
							foreach($sIcons as $key=>$val){
								$sHTML_socialslider .= '<li>';
								$sHTML_socialslider .= '<strong class="socialslider_name">facebook</strong>';
								$sHTML_socialslider .= '<a href="javascript:frontPageSocialslider.target('.$val['url'].','.$sIcon_target_type.');" >';
								$sHTML_socialslider .= '<img src="'.$sImage_path.'icons/'.$val['name'].'-'.$iIcon_image_size.'.png" /></a></li>';
								
								
							}
				
				
							$sHTML_socialslider .= '</ul>';
							
						$sHTML_socialslider .= '</div>';
					$sHTML_socialslider .= '</div>';
				$sHTML_socialslider .= '</div>';
			$sHTML_socialslider .= '</div>';
		$sHTML_socialslider .= '</div>';
    	
    	
	$this->assign(ucwords($APP_NAME),$sHTML_socialslider);
    }
    
}
