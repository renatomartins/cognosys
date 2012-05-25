<?php
namespace App\Cogs\Samples\Controllers;
use Cognosys\Controller,
	Cognosys\Alert;

/**
 * @deprecated possibly
 */
class AlertController extends Controller
{
	public function indexAction()
	{
		$this->alert(Alert::INFO, 'Testing alert messages');
		$this->alert(Alert::INFO, 'Another information message');
		$this->alert(Alert::ERROR, 'Test this or you will get an error :P');
		
		var_dump($this->_session->dump());
		echo 'informations: ';
		$this->messages = $this->alert(Alert::INFO);
	}
	
	public function jumpAction()
	{
		$this->alert(Alert::SUCCESS, 'Great success!');
		$this->alert(Alert::INFO, 'Just so you know...');
		$this->alert(Alert::INFO, 'Big stuff going on');
		$this->alert(Alert::ERROR, 'Red alert');
		
		$this->redirect('show');
	}
	
	public function showAction()
	{
		$this->messages = $this->alert();
		
		$this->render('index');
	}
}
