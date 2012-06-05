<?php
namespace App\Cogs\Base\Controllers;
use Cognosys\Controller,
	Cognosys\Alert;

class HomeController extends Controller
{
	public function indexAction()
	{
		$this->alert(Alert::SUCCESS, 'Cool, at least this works :)');
		$this->show = true;
	}
}
