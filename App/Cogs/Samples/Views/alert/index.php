<style>
.alert-1{color:red}
.alert-2{color:blue}
.alert-3{color:green}
</style>
<?=count($this->messages, COUNT_RECURSIVE)-count($this->messages)?> Messages
<ul>
<?php foreach ($this->messages as $type => $alerts): ?>
	<?php foreach ($alerts as $message): ?>
	<li class="alert-<?=$type?>"><?=$message?></li>
	<?php endforeach; ?>
<?php endforeach; ?>
</ul>