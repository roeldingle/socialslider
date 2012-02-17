<?php
class adminPageSettings extends Controller_Admin
{

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
    	
    	$this->importJS(__CLASS__);
    	$this->importCSS(__CLASS__);
    	
    	/*save form validator*/
    	usbuilder()->validator(array('form' => $APP_NAME.'_form'));
    	
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
			    				{"checked":"1",name":"blip","url":"https://www.blip.com/"},
			    				{"checked":"0","name":"blipfm","url":"https://www.blipfm.com/"},
			    				{"checked":"0","name":"buzz","url":"https://www.buzz.com/"},
			    				{"checked":"0","name":"delicious","url":"https://www.delicious.com/"},
			    				{"checked":"0","name":"deviantart","url":"https://www.deviantart.com/"},
			    				{"checked":"0","name":"digg","url":"https://www.digg.com/"},
			    				{"checked":"0","name":"facebook","url":"http://www.facebook.com"},
			    				{"checked":"0","name":"flaker","url":"https://www.flaker.com/"},
			    				{"checked":"0","name":"flickr","url":"https://www.flickr.com/"},
			    				{"checked":"0","name":"formspringme","url":"https://www.formspringme.com/"},
			    				{"checked":"0","name":"friendconnect","url":"https://www.friendconnect.com/"},
			    				{"checked":"0","name":"friendfeed","url":"https://www.friendfeed.com/"},
			    				{"checked":"0","name":"goldenline","url":"https://www.goldenline.com/"},
			    				{"checked":"0","name":"googleplus","url":"http://www.google.com/+/"},	
			    				{"checked":"0","name":"grono","url":"https://www.grono.com/"},
			    				{"checked":"0","name":"imdb","url":"https://www.imdb.com/"},	
			    				{"checked":"0","name":"ising","url":"https://www.ising.com/"},
			    				{"checked":"0","name":"kciuk","url":"https://www.kciuk.com/"},
			    				{"checked":"0","name":"lastfm","url":"https://www.lastfm.com/"},	
			    				{"checked":"0","name":"linkedin","url":"https://www.linkedin.com/"},	
			    				{"checked":"0","name":"myspace","url":"https://www.myspace.com/"},
			    				{"checked":"0","name":"naszaklasa","url":"https://www.naszaklasa.com/"},		
			    				{"checked":"0","name":"networkedblogs","url":"https://www.networkedblogs.com/"},
			    				{"checked":"0","name":"newsletter","url":"https://www.newsletter.com/"},
			    				{"checked":"0","name":"orkut","url":"https://www.orkut.com/"},
			    				{"checked":"0","name":"panoramio","url":"https://www.panoramio.com/"},
			    				{"checked":"0","name":"picasa","url":"https://www.picasa.com/"},
			    				{"checked":"0","name":"rss","url":"https://www.rss.com/"},
			    				{"checked":"0","name":"sledzik","url":"https://www.sledzik.com/"},
			    				{"checked":"0","name":"soup","url":"https://www.soup.com/"},
			    				{"checked":"0","name":"spinacz","url":"https://www.spinacz.com/"},
			    				{"checked":"0","name":"tumblr","url":"https://www.tumblr.com/"},
			    				{"checked":"0","name":"twitter","url":"https://www.twitter.com/"},
			    				{"checked":"0","name":"unifyer","url":"https://www.unifyer.com/"},				
							    {"checked":"0","name":"vimeo","url":"https://www.vimeo.com/"},				
							    {"checked":"0","name":"widget","url":"https://www.widget.com/"},
			    				{"checked":"0","name":"wykop","url":"https://www.wykop.com/"},				
							    {"checked":"0","name":"youtube","url":"https://www.youtube.com/"}				
    							]'
    			);
    	
    	}
    	
    	/*assign url*/
    	$sUrl = usbuilder()->getUrl('adminPageSettings');
    	$this->assign("sUrl",$sUrl);
    	
 		$aIconsDb = json_decode($aUserSetting['icons'],true);
    	
    	/*assign settings*/
    	$this->assign("aUserSetting",$aUserSetting);
    	$this->assign("aIcons",$aIconsDb);
    	
    	

    	/*set the template*/
    	$this->view(__CLASS__);

    }
}
