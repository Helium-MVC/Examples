<div class="container mt-5">
<h1>Activity Logs</h1>

	<table class="table table-responsive table-striped">
		<thead>
			<tr>
				<th>Action</th>
				<th>Entity Type</th>
				<th>Entity ID</th>
				<th>Date</th>
				<th>View</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($logs as $log): ?>
				<tr>
					<td><?= $log -> action; ?></td>
					<td><?= $log -> entity_type?></td>
					<td><?= $log -> entity_id; ?></td>
					<td><?= $log -> date_recoded; ?></td>
					<td><a href="/logs/view/<?= $log -> record_id; ?>">View</a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>