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
				<td><label>Display position:</label></td>
				<td class="display_position">
					<p class="left"><input type="radio" name="<?php echo $APP_NAME;?>_icon_position" value="left" <?php if($aUserSetting['position'] == "left"){echo "checked";}?> /><label>Left</label></p>
					<p class="right"><input type="radio" name="<?php echo $APP_NAME;?>_icon_position" value="right" <?php if($aUserSetting['position'] == "right"){echo "checked";}?> /><label>Right</label></p>
				</td>
			</tr>
			<tr>
				<td><label>Icon title:</label></td>
				<td class="icon_title">
					<p class="left"><input type="radio" name="<?php echo $APP_NAME;?>_icon_title" value="1" <?php if($aUserSetting['title'] == 1){echo "checked";}?> /><label>Yes</label></p>
					<p class="right"><input type="radio" name="<?php echo $APP_NAME;?>_icon_title" value="0" <?php if($aUserSetting['title'] == 0){echo "checked";}?>/><label>No</label></p>
				</td>
			</tr>
			<tr>
				<td>Icon size:</td>
				<td class="icon_size">
					<p class="left"><input type="radio" name="<?php echo $APP_NAME;?>_icon_size" value="small_icon" <?php if($aUserSetting['size'] == "small_icon"){echo "checked";}?> /><label>Small</label><img src="/_sdk/img/socialslider/small_set.png" /></p>
					<p class="right"><input type="radio" name="<?php echo $APP_NAME;?>_icon_size" value="big_icon" <?php if($aUserSetting['size'] == "big_icon"){echo "checked";}?> /><label>Big</label><img src="/_sdk/img/socialslider/big_set.png" /></p>
				</td>
			</tr>
			
			<tr>
				<td>Icon target option:</td>
				<td class="target_option">
					<p class="left"><input type="radio" name="<?php echo $APP_NAME;?>_icon_target" value="tab"  <?php if($aUserSetting['target'] == "tab"){echo "checked";}?> /><label>Tab</label></p>
					<p class="middle"><input type="radio" name="<?php echo $APP_NAME;?>_icon_target" value="window" <?php if($aUserSetting['target'] == "window"){echo "checked";}?> /><label>Window</label></p>
					<p class="right"><input type="radio" name="<?php echo $APP_NAME;?>_icon_target" value="self" <?php if($aUserSetting['target'] == "self"){echo "checked";}?> /><label>Self</label></p>
				</td>
			</tr>
			
			<tr >
				<td>Icon display count:</td>
				<td>
					<select id="<?php echo $APP_NAME;?>_icon_count" >
						<?php 
							for($iCount = 1;$iCount <= 10;$iCount++){
								
								$bChecked = ($aUserSetting['count'] == $iCount) ? "selected" : "";
								
								echo '<option value="'.$iCount.'" '.$bChecked.' >'.$iCount.'</option>';
							}
						?>
					</select>
				
				</td>
			</tr>
			
			
			<tr>
				<td>Icon configuration:</td>
				<td>
					<table  class="icon_table" >
						<colgroup>
							<col width="180px" />
							<col width="140px" />
							<col width="240px" />
						</colgroup>
						
						<tr>
							<td><input type="checkbox" name="<?php echo $APP_NAME; ?>_all" class="<?php echo $APP_NAME; ?>_all" /> <label>All</label></td>
						</tr>
						
						<?php
					
						foreach($aIcons as $key=>$val){
							
							$bChecked = ($val['checked'] == 1)? "checked":"";
							$bReadOnly = "readonly";
							
							echo '<tr class="'.$APP_NAME.'_icon_wrap" >
									<td><input type="checkbox" name="'.$APP_NAME.'_icons[]" '.$bChecked.' value="'.$key.'" /><strong class="icon_'.$val['name'].'"><span class="hidden">'.$val['name'].'</span></strong><label>'.ucwords($val['name']).'</label></td>
									<td>
									<input type="hidden" id="'.$APP_NAME.'_icon_name_'.$key.'" name="'.$APP_NAME.'_icon_name[]" '.$bReadOnly.' value="'.ucwords($val['name']).'" />
									<input type="text"  maxlength="15" class="'.$APP_NAME.'_readonly"  name="'.$APP_NAME.'_icon_title[]" '.$bReadOnly.' value="'.ucwords($val['title']).'"/>
									</td>
									<td><input type="text"  class="'.$APP_NAME.'_readonly"  name="'.$APP_NAME.'_icon_url[]" '.$bReadOnly.' value="'.$val['url'].'"/></td>
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
<form method="POST" action="" name="<?php echo $APP_NAME;?>_form_reset" id="<?php echo $APP_NAME;?>_form_reset" ><input type="hidden" name="<?php echo $APP_NAME;?>_reset" value="true" /></form>


</body>
</html>
