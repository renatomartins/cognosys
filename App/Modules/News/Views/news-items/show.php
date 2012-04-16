<table id="news_item" border="1">
	<tr>
		<td>Id</td>
		<td><?=$this->item->id()?></td>
	</tr>
	<tr>
		<td>Title</td>
		<td><?=$this->item->title()?></td>
	</tr>
	<tr>
		<td>Summary</td>
		<td><?=$this->item->summary()?></td>
	</tr>
	<tr>
		<td>Body</td>
		<td><?=$this->item->body()?></td>
	</tr>
	<tr>
		<td>Creation Date</td>
		<td><?=$this->item->creation_date()->format('M j, Y')?></td>
	</tr>
	<tr>
		<td>Author</td>
		<td><?=$this->item->author()->login()?></td>
	</tr>
	<tr>
		<td>Category</td>
		<?php if ($this->item->category() instanceof App\Modules\News\Models\Entities\Category): ?>
		<td><?=$this->item->category()->title()?></td>
		<?php else: ?>
		<td>No category...</td>
		<?php endif; ?>
	</tr>
	<tr>
		<td>Approved</td>
		<td><?=$this->item->approved()?></td>
	</tr>
	<tr>
		<td>Active</td>
		<td><?=$this->item->active()?></td>
	</tr>
	<tr>
		<td>Moderate</td>
		<td><?=$this->item->moderate()?></td>
	</tr>
	<tr>
		<td>Gallery</td>
		<?php if ($this->item->gallery() instanceof App\Modules\Media\Models\Entities\Gallery): ?>
		<td><?=count($this->item->gallery()->images())?> images</td>
		<?php else: ?>
		<td>No gallery available...</td>
		<?php endif; ?>
	</tr>
</table>
