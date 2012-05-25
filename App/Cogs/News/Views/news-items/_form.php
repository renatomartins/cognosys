<?php
use App\Cogs\News\Models\Entities\Category;
$that = $this;
?>
<?=form(function ($f) use($that) { ?>
	<table>
		<tr>
			<td><?=$f->label('title')?></td>
			<td><?=$f->text('title', array('value' => $that->item->title()))?></td>
		</tr>
		<tr>
			<td><?=$f->label('summary')?></td>
			<td><?=$f->textarea('summary', $that->item->summary(), array(
				'rows' => 2,
				'cols' => 25
				
			))?></td>
		</tr>
		<tr>
			<td><?=$f->label('body')?></td>
			<td><?=$f->textarea('body', $that->item->body(), array(
				'rows' => 4,
				'cols' => 25
			))?></td>
		</tr>
		<tr>
			<td><?=$f->label('category')?></td>
			<td><?=$f->select('category', $that->categories, array(
				'text'		=> 'title',
				'selected'	=> $that->item->category() instanceof Category
					? $that->item->category()->id()
					: 0
			))?></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<?=$f->checkbox('active', $that->item->active())?>
				<?=$f->label('active', 'Active?')?>
			</td>
		</tr>
	</table>
	<?=$f->submit()?>
<?php }); ?>
