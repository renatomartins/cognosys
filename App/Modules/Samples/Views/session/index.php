<html>
	<body>
		Session:
		<ul>
			<?php foreach ($this->_session->dump() as $key => $value): ?>
			<li>
				"<?=$key?>":
				<?php var_dump($value); ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</body>
</html>