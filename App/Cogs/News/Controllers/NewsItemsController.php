<?php
namespace App\Cogs\News\Controllers;
use Cognosys\Controller,
	Cognosys\Alert,
	Cognosys\Exceptions\UserError,
	App\Cogs\News\Models\Entities\Item,
	App\Cogs\News\Models\Entities\Category;

//TODO: constraints defined in the class, global to all actions
class NewsItemsController extends Controller
{
	/**
	 * @Group(name="ALL")
	 */
	public function indexAction()
	{
		$this->news = $this->repo(Item::classname())->findAll();
	}
	
	/**
	 * @Role(name="NEWS_EDITOR")
	 */
	public function createAction()
	{
		if ($this->isPost()) {
			//FIXME: put a namespace on the form to get params like $this->params['news']
			$this->item = Item::create($this->params);
			$this->item->author($this->_user);
			
			if ($this->valid($this->item)) {
				$this->persist($this->item, true);
				$this->redirect("show/{$this->item->id()}");
			} else {
				$this->alert(Alert::WARNING, 'There are some errors in your form');
				$this->redirect('create');
			}
		}
		
		//FIXME: how to fill the model with previous data? all data? e.g. no passwords, for sure
		$this->item = Item::create();
		
		// categories to select from
		$this->categories = $this->repo(Category::classname())->findAll();
		//TODO: test if this inserts a category
		$this->categories[] = Category::create(array(
			'id'			=> 4,
			'title'			=> 'Whatever',
			'description'	=> 'Something...'
		));
	}
	
	/**
	 * @Group(name="ALL")
	 */
	public function showAction($id)
	{
		$this->item = $this->repo(Item::classname())->find($id);
		
		if ( ! $this->item instanceof Item) {
			$this->alert(Alert::ERROR, 'No such item');
			$this->redirect();
		}
	}
	
	/**
	 * @Role(name="NEWS_EDITOR")
	 */
	public function editAction($id)
	{
		$this->item = $this->repo(Item::classname())->find($id);
		$this->categories = $this->repo(Category::classname())->findAll();
	}
	
	/**
	 * @Role(name="NEWS_EDITOR")
	 */
	public function deleteAction($id)
	{
		$item = $this->repo(Item::classname())->find($id);
		$this->remove($item, true);
		$this->redirect();
	}
}
