<?php
require_once 'include/global/class.body.php';
require_once 'include/global/class.menuTask.php';
require_once 'include/global/class.notification.php';
require_once 'include/global/class.loginInfo.php';

$body = new body ();
$menuTask = new MenuTask();
$notification = new Notification();
$loginInfo = new loginInfo();

if (! $body->isUserLogged ( true ))
	$body->redirectUrl ( './login.php' );

$body->startMainBody ();
$body->startBody('global', 'LMENUL0', 'Index Page');
?>
<br />
<div class="clear"></div>
<div style="width: 49%; float: left">
	<div class="inputs">
		<fieldset class="formelements">
			<div class="legend">
				<span>Pending Task Listing</span>
			</div>
			<dl>
				<dd style="padding-left: 20px">
				<ul>
					<?php 
						$menuTaskIds = $menuTask->getUserMenuTaskRecords($body->getLoggedUserId(), 1);
						$i = 0;
						foreach ($menuTaskIds as $menuTaskId){
							if($i > 10)
								break;
							$details = $menuTask->getTableIdDetails($menuTaskId);
							echo "<a href=\"#\" onclick=\"loadPageIntoDisplay('".$details['menu_url']."')\"><li>".$details['comments']."</li></a>";
							++$i;
						}
						if($i == 0)
							echo "No Pending Task";
					?>
				</ul>
				</dd>
			</dl>
		</fieldset>
	</div>
</div>
<div style="width: 49%; float: right">
	<div class="inputs">
		<fieldset class="formelements">
			<div class="legend">
				<span>Future Task Assignment</span>
			</div>
			<dl>
				<dd style="padding-left: 30px;">
				<ul>
					<?php 
						$menuTaskIds = $menuTask->getUserFutureMenuTaskRecords($body->getLoggedUserId(), 1);
						$i = 0;
						foreach ($menuTaskIds as $menuTaskId){
							if($i > 10)
								break;
							$details = $menuTask->getTableIdDetails($menuTaskId);
							echo "<li>".$details['comments']."</li>";
							++$i;
						}
						if($i == 0)
							echo "No Future Task Assigned";
					?>
				</ul>
				</dd>
			</dl>
		</fieldset>
	</div>
</div>
<br />
<br />
<div class="clear"></div>
<div class="datatable buttons" id="displayDatatable">
	<fieldset class="formelements">
		<div class="legend">
			<span>Your Login History</span>
		</div>
		<table class="display" id="groupRecords">
			<thead>
				<tr>
					<th>Datetime</th>
					<th>Local Ip</th>
					<th>Global Ip</th>					
					<th>Browser</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				$loginIds = $loginInfo->getLoginLogIds($body->getLoggedUserId());
				$i = 0;
				foreach($loginIds as $loginId){
					if($i > 10)
						break;
					$details = $body->getTableIdDetails($loginId);
					echo "
						<tr>
							<td>".$details['login_datetime']."</td>	
							<td>".$details['local_ip']."</td>
							<td>".$details['global_ip']."</td>							
							<td>".$details['browser']."</td>
							<td>".$details['success']."</td>		
						</tr>";
					++$i;
				}
			?>
			</tbody>
		</table>
	</fieldset>
</div>

<?php
$body->endMainBody ();

?>