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
    				'icons' => '[{"checked":"1","name":"Blip","title":"Blip","url":"https://www.blip.com/"},
								{"checked":"1","name":"Blipfm","title":"Blipfm","url":"https://www.blipfm.com/"},
								{"checked":"1","name":"Buzz","title":"Buzz","url":"https://www.buzz.com/"},
								{"checked":"1","name":"Delicious","title":"Delicious","url":"https://www.delicious.com/"},
								{"checked":"1","name":"Deviantart","title":"Deviantart","url":"https://www.deviantart.com/"},
								{"checked":"1","name":"Digg","title":"Digg","url":"https://www.digg.com/"},
								{"checked":"1","name":"Facebook","title":"Facebook","url":"http://www.facebook.com"},
								{"checked":"1","name":"Flaker","title":"Flaker","url":"https://www.flaker.com/"},
								{"checked":"1","name":"Flickr","title":"Flickr","url":"https://www.flickr.com/"},
								{"checked":"1","name":"Formspringme","title":"Formspringme","url":"https://www.formspringme.com/"},
								{"checked":"0","name":"Friendconnect","title":"Friendconnect","url":"https://www.friendconnect.com/"},
								{"checked":"0","name":"Friendfeed","title":"Friendfeed","url":"https://www.friendfeed.com/"},
								{"checked":"0","name":"Goldenline","title":"Goldenline","url":"https://www.goldenline.com/"},
								{"checked":"0","name":"Googleplus","title":"Googleplus","url":"http://www.google.com/+/"},
								{"checked":"0","name":"Grono","title":"Grono","url":"https://www.grono.com/"},
								{"checked":"0","name":"Imdb","title":"Imdb","url":"https://www.imdb.com/"},
								{"checked":"0","name":"Ising","title":"Ising","url":"https://www.ising.com/"},
								{"checked":"0","name":"Kciuk","title":"Kciuk","url":"https://www.kciuk.com/"},
								{"checked":"0","name":"Lastfm","title":"Lastfm","url":"https://www.lastfm.com/"},
								{"checked":"0","name":"Linkedin","title":"Linkedin","url":"https://www.linkedin.com/"},
								{"checked":"0","name":"Myspace","title":"Myspace","url":"https://www.myspace.com/"},
								{"checked":"0","name":"Naszaklasa","title":"Naszaklasa","url":"https://www.naszaklasa.com/"},
								{"checked":"0","name":"Networkedblogs","title":"Networkedblogs","url":"https://www.networkedblogs.com/"},
								{"checked":"0","name":"Newsletter","title":"Newsletter","url":"https://www.newsletter.com/"},
								{"checked":"0","name":"Orkut","title":"Orkut","url":"https://www.orkut.com/"},
								{"checked":"0","name":"Panoramio","title":"Panoramio","url":"https://www.panoramio.com/"},
								{"checked":"0","name":"Picasa","title":"Picasa","url":"https://www.picasa.com/"},
								{"checked":"0","name":"Rss","title":"Rss","url":"https://www.rss.com/"},
								{"checked":"0","name":"Sledzik","title":"Sledzik","url":"https://www.sledzik.com/"},
								{"checked":"0","name":"Soup","title":"Soup","url":"https://www.soup.com/"},
								{"checked":"0","name":"Spinacz","title":"Spinacz","url":"https://www.spinacz.com/"},
								{"checked":"0","name":"Tumblr","title":"Tumblr","url":"https://www.tumblr.com/"},
								{"checked":"0","name":"Twitter","title":"Twitter","url":"https://www.twitter.com/"},
								{"checked":"0","name":"Unifyer","title":"Unifyer","url":"https://www.unifyer.com/"},
								{"checked":"0","name":"Vimeo","title":"Vimeo","url":"https://www.vimeo.com/"},
								{"checked":"0","name":"Widget","title":"Widget","url":"https://www.widget.com/"},
								{"checked":"0","name":"Wykop","title":"Wykop","url":"https://www.wykop.com/"},
								{"checked":"0","name":"Youtube","title":"Youtube","url":"https://www.youtube.com/"}]'
    			);
    	
    	}
		
		
		
		
		/*options*/
		$sImage_path = '/_sdk/img/'.$APP_NAME.'/';
		
		$sIcons = json_decode($aUserSetting['icons'],true); #array of icons for the linkers
		
		/*if small or big*/
		$sIcon_type_class = $aUserSetting['size'];
		if($sIcon_type_class == "big_icon"){
			$iIcon_image_size = "big";
		}else{
			$iIcon_image_size = "small";
		}
		
		/*the type of target*/
		$sIcon_target_type = $aUserSetting['target'];
		
		/*if w/ title*/
		$bIcon_title = $aUserSetting['title'];
		
		
		
		/*icons visible on load*/
		$iCount = 0;
		foreach($sIcons as $key=>$val){
			if($val['checked'] == "1"){
				$iCount++;
			}
		}
		
		$iIcon_visible = ($iCount <= $aUserSetting['count']) ? $iCount : $aUserSetting['count'];
		
		/*position*/
		$sIcon_position = $aUserSetting['position'];
		
		if($sIcon_position == "right"){
			$bPosition = "_right";
		}else{
			$bPosition = "";
		}
		
		/*html assign*/
    	$sHTML_socialslider = '';
    	$sHTML_socialslider .= '<div class="'.$APP_NAME.'_wrap_con" >';
    	$sHTML_socialslider .= '<div class="'.$APP_NAME.'_wrap" >';
			$sHTML_socialslider .= '<div class="'.$APP_NAME.$bPosition.'">';
				$sHTML_socialslider .= '<div class="'.$APP_NAME.$bPosition.'_container">';
					$sHTML_socialslider .= '<div class="'.$APP_NAME.$bPosition.'_expand">';
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
										$sHTML_socialslider .= '<strong class="socialslider_name">'.$val['title'].'</strong>';
										$sHTML_socialslider .= '</a>';
									}
									
									$sHTML_socialslider .= '<a href="javascript:frontPageSocialslider.target(\''.$val['url'].'\',\''.$sIcon_target_type.'\');" >';
									$sHTML_socialslider .= '<div class="'.$iIcon_image_size.'_icon_'.strtolower ($val['name']).'"></div></a></li>';
								}
								
							}
				
							$sHTML_socialslider .= '</ul>';
						$sHTML_socialslider .= '</div>';
					$sHTML_socialslider .= '</div>';
				$sHTML_socialslider .= '</div>';
				
				$sHTML_socialslider .= '<div class="btn_prev"><a href="#" class="prev"></a></div>';
				$sHTML_socialslider .= '<div class="btn_next"><a href="#" class="next"></a></div>';
				
				/*hidden*/
				$sHTML_socialslider .= '<input type="hidden" class="'.$APP_NAME.'_icon_position" value="'.$sIcon_position.'" />';
				$sHTML_socialslider .= '<input type="hidden" class="'.$APP_NAME.'_icon_visible" value="'.$iIcon_visible.'" />';
				$sHTML_socialslider .= '<input type="hidden" class="'.$APP_NAME.'_icon_title" value="'.$bIcon_title.'" />';
				
			$sHTML_socialslider .= '</div>';
		$sHTML_socialslider .= '</div>';
		$sHTML_socialslider .= '</div>';
    	
    	
	$this->assign("display",$sHTML_socialslider);

    }
    
}
