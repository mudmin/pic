<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
$id = Input::get('task');


//Security Stuff

//make sure the id is a number
if(!is_numeric($id)){
	Redirect::to('index.php?err=Something+went+wrong');
}
$jobQ = $db->query("SELECT * FROM jobs WHERE id = ?",array($id));
$jobC = $jobQ->count();
//make sure the job exists
if($jobC < 1){
	Redirect::to("index.php?err=Job+not+found");
}else{
	$job = $jobQ->first();
}
$tasksQ = $db->query("SELECT * FROM job_tasks WHERE job = ?",array($id));
$tasksC = $tasksQ->count();
if($tasksC > 0){
	$tasks = $tasksQ->results();
}
//You must be an admin, the owner, or the worker to view the tasks
if(!hasPerm([2],$user->data()->id) && (($job->owner != $user->data()->id ) || $job->worker != $user->data()->id)){
	Redirect::to('index.php?err=You+do+not+have+permission+to+view+this+job');
}

?>
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading-->
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header">
				 Job Tasks
				</h1>
				<?php if($tasksC < 1){
					echo "This job does not have any tasks";
				}else{?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Task</th>
								<th>Amount</th>
								<th>Amount Complete</th>
								<th>Last Update</th>
								<th>Complete</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($tasks as $t){  ?>
								<tr>
									<td><a href="task_details.php?task=<?=$t->id?>&job=<?=$job->id?>"><?=$t->task?></a></td>
									<td><?=$t->amt?></td>
									<td><?=$t->amt_done?></td>
									<td><?=$t->last_update?></td>
									<td><?=bin($t->complete)?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php }?>
			</div> <!-- /.col -->
		</div> <!-- /.row -->
	</div> <!-- /.container -->
</div> <!-- /.wrapper -->


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
