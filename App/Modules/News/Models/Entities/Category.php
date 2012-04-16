<?php
namespace App\Modules\News\Models\Entities;
use Core\Model,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="App\Modules\News\Models\Repositories\Category")
 * @Table(name="news_categories")
 * @author Renato S. Martins
 */
class Category extends Model
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string", length=128)
	 */
	protected $title;
	
	/**
	 * @Column(type="text")
	 */
	protected $description;
	
	/**
	 * @OneToMany(targetEntity="Item", mappedBy="category")
	 */
	protected $news_items;
	
	public function __construct()
	{
		$this->news_items = new ArrayCollection;
	}
	
	public function id()
	{
		return $this->id;
	}
	
	public function news_items()
	{
		return $this->news_items;
	}
	
	public function title($title = null)
	{
		if (is_null($title)) {
			return $this->title;
		}
		$this->title = $title;
	}
	
	public function description($description = null)
	{
		if (is_null($description)) {
			return $this->description;
		}
		$this->description = $description;
	}
	
	public function validate()
	{
		//TODO: implement method 'validate'
	}
}
