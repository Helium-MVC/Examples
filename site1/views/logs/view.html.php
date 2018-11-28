<div class="container mt-5">
	<h1>Log</h1>
	
	<table class="table table-responsive table-striped">
		<?php foreach($log -> getIterator() -> getData() as $key => $value): ?>
			<tr>
				<td><strong><?= $key; ?></strong></td>
				<td><?= $value; ?></td>
			</tr>
		<?php endforeach; ?>
		
	</table>
</div>