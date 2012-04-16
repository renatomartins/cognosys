<table border="1">
	<tr>
		<th>Date</th>
		<th>Title</th>
		<th>Category</th>
		<th>Author</th>
		<th>Comments</th>
		<th>Active</th>
	</tr>
	<?php foreach ($this->news as $item): ?>
	<tr>
		<td><?=$item->creation_date()->format('M j, Y')?></td>
		<td><?=$item->title()?></td>
		<td><?=$item->category()->title()?></td>
		<td><?=$item->author()->login()?></td>
		<td><?=count($item->comments())?></td>
		<td><?=$item->active()?></td>
	</tr>
	<?php endforeach; ?>
</table>
