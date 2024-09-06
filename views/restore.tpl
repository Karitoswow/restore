<script type="text/javascript">
	$(document).ready(function()
	{
		function initializeRestore()
		{
			if(typeof Restore != "undefined")
			{
				Restore.User.initialize({$dp});
			}
			else
			{
				setTimeout(initializeRestore, 50);
			}
		}
		initializeRestore();
	});
</script>
<section id="character_tools">
	<section id="select_character">
		{foreach from=$realms item=realm}
			<div class="table-responsive text-nowrap" style="text-align: center">
				<table class="nice_table mb-3">
					<thead>
					<tr>
						<th scope="col" colspan="6" class="h4 text-center">{$realm->getName()}</th>
					</tr>
					</thead>
					{if $this->GetCountDeleteAccount($realm->getId())}
						<thead>
						<tr>
							<th>Character</th>
							<th>Level</th>
							<th>Date Deleted</th>
							<th>Actions</th>
						</tr>
						</thead>
						{foreach from=$this->Getcharacterdeleted($realm->getId()) item=character}
							<tr>
								<td class="col-0">
									<img src="{$url}application/images/stats/{$character.race}-{$character.gender}.gif">

									<img src="{$url}application/images/stats/{$character.class}.gif" width="20px">
									{$character.deleteInfos_Name}
								</td>
								<td class="col-5">Lv{$character.level}</td>
								<td class="col-4"> {date("Y/m/d h:m:s",$character.deleteDate)}</td>
								<td>
									<div class="select_character">
										<div class="character store_item">
											<section class="character_buttons">
												<a href="javascript:void(0)" class="nice_button" onClick="Restore.selectCharacter(this, {$realm->getId()}, {$character.guid}, '{$character.deleteInfos_Name}')">
													Select
												</a>
											</section>
										</div>
									</div>
								</td>
							</tr>
						{/foreach}
					{else}
						<tr style="padding-top:10px;"><td>no found character</td></tr>
					{/if}

				</table>
			</div>
		{/foreach}
	</section>

	<center>
		<div style="padding: 8px 17px 8px 17px; margin-bottom: 20px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5); background-color: #0b0c0b; border: 1px solid #455340; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
			<div>
				<div style="text-align: center;color: green;">
					<div class="table-responsive text-nowrap">
						<table class="nice_table mb-3" style="text-align: center;">
							<tr>
								<td class="col-0">

									{lang("Server_fee", "restore")}
									{if $config->item('type_price')}
										<img src="{$url}application/images/icons/coins.png" align="absmiddle"> {$config->item('type')} {$config->item('type_price')}
									{else}
										Free
									{/if}
								</td>
								<td>
									<a href="javascript:void(0)" class="nice_button" onClick="Restore.submit(this)">
										{lang("restore", "restore")}
									</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</center>
</section>