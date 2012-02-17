<?php
class frontPageSocialslider extends Controller_Front{

	protected $oGet;

    protected function run($aArgs)
    {

    require_once('builder/builderInterface.php');
	usbuilder()->init($this, $aArgs);
    
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
    	$aUserSetting = $this->oGet->getRow(2,null);
    	
    	/*set default values*/
    	if(empty($aUserSetting) || isset($aArgs['reset'])){
    		$aUserSetting = array(
    				'position' => 'left',
    				'title' => 1,
    				'size' => 'big_icon',
    				'count' => 5,
    				'target' => 'tab',
    				'icons' => '[
			    				{"name":"blip","url":"https://www.blip.com/"},
			    				{"name":"blipfm","url":"https://www.blipfm.com/"},
			    				{"name":"buzz","url":"https://www.buzz.com/"},
			    				{"name":"delicious","url":"https://www.delicious.com/"},
			    				{"name":"deviantart","url":"https://www.deviantart.com/"},
			    				{"name":"digg","url":"https://www.digg.com/"},
			    				{"name":"facebook","url":"http://www.facebook.com"},
			    				{"name":"flaker","url":"https://www.flaker.com/"},
			    				{"name":"flickr","url":"https://www.flickr.com/"},
			    				{"name":"formspringme","url":"https://www.formspringme.com/"},
			    				{"name":"friendconnect","url":"https://www.friendconnect.com/"},
			    				{"name":"friendfeed","url":"https://www.friendfeed.com/"},
			    				{"name":"goldenline","url":"https://www.goldenline.com/"},
			    				{"name":"googleplus","url":"http://www.google.com/+/"},	
			    				{"name":"grono","url":"https://www.grono.com/"},
			    				{"name":"imdb","url":"https://www.imdb.com/"},	
			    				{"name":"ising","url":"https://www.ising.com/"},
			    				{"name":"kciuk","url":"https://www.kciuk.com/"},
			    				{"name":"lastfm","url":"https://www.lastfm.com/"},	
			    				{"name":"linkedin","url":"https://www.linkedin.com/"},	
			    				{"name":"myspace","url":"https://www.myspace.com/"},
			    				{"name":"naszaklasa","url":"https://www.naszaklasa.com/"},		
			    				{"name":"networkedblogs","url":"https://www.networkedblogs.com/"},
			    				{"name":"newsletter","url":"https://www.newsletter.com/"},
			    				{"name":"orkut","url":"https://www.orkut.com/"},
			    				{"name":"panoramio","url":"https://www.panoramio.com/"},
			    				{"name":"picasa","url":"https://www.picasa.com/"},
			    				{"name":"rss","url":"https://www.rss.com/"},
			    				{"name":"sledzik","url":"https://www.sledzik.com/"},
			    				{"name":"soup","url":"https://www.soup.com/"},
			    				{"name":"spinacz","url":"https://www.spinacz.com/"},
			    				{"name":"tumblr","url":"https://www.tumblr.com/"},
			    				{"name":"twitter","url":"https://www.twitter.com/"},
			    				{"name":"unifyer","url":"https://www.unifyer.com/"},				
							    {"name":"vimeo","url":"https://www.vimeo.com/"},				
							    {"name":"widget","url":"https://www.widget.com/"},
			    				{"name":"wykop","url":"https://www.wykop.com/"},				
							    {"name":"youtube","url":"https://www.youtube.com/"}				
    							]'
    			);
    	
    	}
		
		
		
		
		/*options*/
		$sImage_path = '/_sdk/img/'.$APP_NAME.'/';
		
		$sIcons = json_decode($aUserSetting['icons'],true); #array of icons for the linkers
		
		/*if small or big*/
		$sIcon_type_class = $aUserSetting['size'];
		if($sIcon_type_class == "big_icon"){
			$iIcon_image_size = 32;
		}else{
			$iIcon_image_size = 20;
		}
		
		/*the type of target*/
		$sIcon_target_type = $aUserSetting['target'];
		
		/*if w/ title*/
		$bIcon_title = $aUserSetting['title'];
		
		/*icons visible on load*/
		$iIcon_visible = (count($sIcons) <= $aUserSetting['count']) ? count($sIcons) : $aUserSetting['count'];
		
    	
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
							foreach($sIcons as $key => $val){
								
								if($val['checked'] == 1){
									$sHTML_socialslider .= '<li>';
									
									/*the title can be shown or not*/
									if($bIcon_title == 1){
										$sHTML_socialslider .= '<a href="javascript:frontPageSocialslider.target(\''.$val['url'].'\',\''.$sIcon_target_type.'\');" >';
										$sHTML_socialslider .= '<strong class="socialslider_name">'.$val['name'].'</strong>';
										$sHTML_socialslider .= '</a>';
									}
									
									$sHTML_socialslider .= '<a href="javascript:frontPageSocialslider.target(\''.$val['url'].'\',\''.$sIcon_target_type.'\');" >';
									$sHTML_socialslider .= '<img src="'.$sImage_path.'icons/'.strtolower ($val['name']).'-'.$iIcon_image_size.'.png" /></a></li>';
								}
								
							}
				
				
							$sHTML_socialslider .= '</ul>';
							
						$sHTML_socialslider .= '</div>';
					$sHTML_socialslider .= '</div>';
				$sHTML_socialslider .= '</div>';
				
				$sHTML_socialslider .= '<div class="btn_prev"><a href="#" class="prev"></a></div>';
				$sHTML_socialslider .= '<div class="btn_next"><a href="#" class="next"></a></div>';
				
				/*hidden*/
				$sHTML_socialslider .= '<input type="hidden" id="'.$APP_NAME.'_icon_visible" value="'.$iIcon_visible.'" />';
				
			$sHTML_socialslider .= '</div>';
		$sHTML_socialslider .= '</div>';
    	
    	
	$this->assign(ucwords($APP_NAME),$sHTML_socialslider);
    }
    
}
