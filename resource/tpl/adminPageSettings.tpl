<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>

<!-- hidden values -->
<input type="hidden"  id="APP_NAME" value="<?php echo $APP_NAME;?>" />



	
<form name="<?php echo $APP_NAME;?>_form"   method="POST">                                             
	
<!-- basic settings -->
	<div id="socialslider_settings">
		<table>
			<colgroup>
				<col width="180px" />
				<col width="*" />
			</colgroup>
			<tr>
				<td><label>App ID :</label></td>
				<td>
					<p class="plugin_title"><?php echo $APP_NAME;?></p><br />
				</td>
			
			</tr>
			<tr>
				<td><label>Display position:</label></td>
				<td>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_position" value="left" /><label>Left</label></p>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_position" value="right"/><label>Right</label></p>
				</td>
			</tr>
			<tr>
				<td><label>Icon title:</label></td>
				<td>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_title" value="1" /><label>Yes</label></p>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_title" value="0"/><label>No</label></p>
				</td>
			</tr>
			<tr>
				<td>Icon size:</td>
				<td>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_size" value="small_icon"/><label>Small</label><img src="/_sdk/img/socialslider/small_set.png" /></p>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_size" value="big_icon"/><label>Big</label><img src="/_sdk/img/socialslider/big_set.png" /></p>
				</td>
			</tr>
			<tr>
				<td>Icon display count:</td>
				<td>
					<select id="<?php echo $APP_NAME;?>_icon_count" >
						<?php 
							for($iCount = 1;$iCount <= 10;$iCount++){
								echo '<option value="'.$iCount.'" >'.$iCount.'</option>';
							}
						?>
					</select>
				
				</td>
			</tr>
			<tr>
				<td>Icon target option:</td>
				<td>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_target" value="tab" /><label>Tab</label></p>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_target" value="window"/><label>Window</label></p>
					<p><input type="radio" name="<?php echo $APP_NAME;?>_icon_target" value="self"/><label>Self</label></p>
				</td>
			</tr>
			
			<tr>
				<td>Icon configuration:</td>
				<td>
					<table>
						<colgroup>
							<col width="140px" />
							<col width="180px" />
							<col width="*" />
						</colgroup>
						
						<?php
					
						foreach($aIcons as $key=>$val){
							
							$bChecked = ($val['checked'] == 1)? "checked":"";
							$bReadOnly = ($val['checked'] == 0)? "readonly":"";
							
							echo '<tr class="'.$APP_NAME.'_icon_wrap" >
									<td><input type="checkbox" name="'.$APP_NAME.'_icons[]" '.$bChecked.' />'.ucwords($val['name']).'</td>
									<td><input type="text" name="'.$APP_NAME.'_icon_name[]" '.$bReadOnly.' value="'.ucwords($val['name']).'" /></td>
									<td><input type="text" name="'.$APP_NAME.'_icon_url[]" '.$bReadOnly.' value="'.$val['url'].'"/></td>
								</tr>';
						
						}
						
						?>
						
					</table>
				</td>
			</tr>
			
		</table>
	
		
			
	</div>
	
	<div class="tbl_lb_wide_btn">
		<input type="button" value="Save" class="btn_apply" onclick="adminPageSettings.setting_submit()" />
		<a href="#" class="add_link" title="Reset to default" onclick="adminPageSettings.reset_default()" >Reset to Default</a>
	</div>	



</form>
<!--form for reset-->
<form method="POST" action="<?php echo $sUrl;?>" name="<?php echo $APP_NAME;?>_form_reset" id="<?php echo $APP_NAME;?>_form_reset" ><input type="hidden" name="<?php echo $APP_NAME;?>_reset" value="true" /></form>


</body>
</html>
