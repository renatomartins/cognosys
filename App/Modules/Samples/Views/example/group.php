<html>
	<body>
		<?php //TODO: check if an object is an instance of a model
		if ($this->group instanceof App\Modules\User\Models\Entities\UserGroup): ?>
		<h4>Group: <?=$this->group->name?></h4>
		<p>
			Group Info:
			<ul>
				<li>Name: <?=$this->group->name?></li>
				<li>Description: <?=$this->group->description?></li>
				<li>State: <?=$this->group->state?></li>
				<li>Creation Date: <?php var_dump($this->group->creation_date);?></li>
				<li>Privileges: <?=$this->group->privileges?></li>
			</ul>
		</p>
			<?php if ($this->user instanceof App\Modules\User\Models\Entities\User): ?>
				<?php $this->inject('user'); ?>
			<?php else: ?>
		<p>No manager was found...</p>
			<?php endif; ?>
		<?php else: ?>
		<p>No group was found...</p>
		<?php endif; ?>
	</body>
</html>