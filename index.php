<?php
require_once 'include/global/class.body.php';
require_once 'include/global/class.menuTask.php';
require_once 'include/global/class.notification.php';
require_once 'include/global/class.loginInfo.php';

$body = new body ();
$menuTask = new MenuTask ();
$notification = new Notification ();
$loginInfo = new loginInfo ();

if (! $body->isUserLogged ( true ))
	$body->redirectUrl ( './login.php' );

$body->startMainBody ();
$body->startBody ( 'global', 'LMENUL0', 'Index Page' );
?>
<br />
<div class="clear"></div>

<ul class="tabs">
	<li><a href="#">Pending Tasks</a></li>
	<li><a href="#">Completed Tasks</a></li>
	<li><a href="#">Future Tasks</a></li>
</ul>
<div class="panes">
	<div>
		<ul style="padding-left: 20px">
			<?php
			$menuTaskIds = $menuTask->getUserMenuTaskRecords ( $body->getLoggedUserId (), 1 );
			$i = 0;
			foreach ( $menuTaskIds as $menuTaskId ) {
				if ($i > 10)
					break;
				$details = $menuTask->getTableIdDetails ( $menuTaskId );
				$url = $menuTask->getBaseServer().$details['menu_url']."?referenceId=".$menuTaskId;
				echo "<li><a href=\"#\" onclick=\"loadPageIntoDisplay('" . $url . "')\">" . $details ['comments'] . "</a></li>";
				
				++ $i;
			}
			if ($i == 0)
				echo "No Pending Task";
			?>
		</ul>
	</div>
	<div>
		<ul style="padding-left: 20px">
			<?php
			$menuTaskIds = $menuTask->getUserMenuTaskRecords ( $body->getLoggedUserId (), 0 );
			$i = 0;
			foreach ( $menuTaskIds as $menuTaskId ) {
				if ($i > 10)
					break;
				$details = $menuTask->getTableIdDetails ( $menuTaskId );
				echo "<li>" . $details ['comments'] . "</li>";
				++ $i;
			}
			if ($i == 0)
				echo "No Task Completed";
			?>
		</ul>
	</div>
	<div>
		<ul>
			<?php
			$menuTaskIds = $menuTask->getUserFutureMenuTaskRecords ( $body->getLoggedUserId (), 1 );
			$i = 0;
			foreach ( $menuTaskIds as $menuTaskId ) {
				if ($i > 10)
					break;
				$details = $menuTask->getTableIdDetails ( $menuTaskId );
				echo "<li>" . $details ['comments'] . "</li>";
				++ $i;
			}
			if ($i == 0)
				echo "No Future Task Assigned";
			?>
		</ul>
	</div>
</div>
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
			$loginIds = $loginInfo->getLoginLogIds ( $body->getLoggedUserId () );
			$i = 0;
			foreach ( $loginIds as $loginId ) {
				if ($i > 10)
					break;
				$details = $body->getTableIdDetails ( $loginId );
				echo "
						<tr>
							<td>" . $details ['login_datetime'] . "</td>	
							<td>" . $details ['local_ip'] . "</td>
							<td>" . $details ['global_ip'] . "</td>							
							<td>" . $details ['browser'] . "</td>
							<td>" . $details ['success'] . "</td>		
						</tr>";
				++ $i;
			}
			?>
			</tbody>
		</table>
	</fieldset>
</div>
<?php
$body->endBody ( 'global', 'LMENUL0' );
$body->endMainBody ();
?>