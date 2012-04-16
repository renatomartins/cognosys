<?php
namespace Core\Exceptions;
use Core\Layout,
	Core\Error;

class UserError extends Error
{
	public function handle($request, $response)
	{
		include Layout::get();
	}
	
	protected function view()
	{
		return <<<EOT
<h3>Oops, an error occured</h3>
<p>
{$this->message}
</p>
EOT;
	}
}
