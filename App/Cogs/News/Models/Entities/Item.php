<?php
namespace App\Cogs\News\Models\Entities;
use Cognosys\Model,
	Cognosys\AlertManager,
	Cognosys\Validators\Validator,
	Cognosys\Validators\String,
	Cognosys\Validators\Boolean,
	Cognosys\Validators\Integer,
	Doctrine\Common\Collections\ArrayCollection,
	\DateTime;

/**
 * @Entity(repositoryClass="App\Cogs\News\Models\Repositories\Item")
 * @Table(name="news_items")
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Item extends Model
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string", length=128, nullable=false)
	 */
	protected $title;
	
	/**
	 * @Column(type="text")
	 */
	protected $summary;
	
	/**
	 * @Column(type="text", nullable=false)
	 */
	protected $body;
	
	/**
	 * @OneToOne(targetEntity="App\Cogs\Media\Models\Entities\Gallery", cascade={"persist", "remove"})
	 * @JoinColumn(name="gallery_id")
	 */
	protected $gallery;
	
	/**
	 * @ManyToOne(targetEntity="App\Cogs\User\Models\Entities\User", cascade={"persist"})
	 * @JoinColumn(name="author_id")
	 */
	protected $author;
	
	/**
	 * @ManyToOne(targetEntity="App\Cogs\User\Models\Entities\User", cascade={"persist"})
	 * @JoinColumn(name="approver_id")
	 */
	protected $approver;
	
	/**
	 * @Column(type="boolean")
	 */
	protected $active;
	
	/**
	 * @ManyToOne(targetEntity="Category", inversedBy="news_items", cascade={"persist"})
	 * @JoinColumn(name="category_id")
	 */
	protected $category;
	
	/**
	 * @OneToMany(targetEntity="Comment", mappedBy="news_item", cascade={"persist", "remove"})
	 */
	protected $comments;
	
	/**
	 * @Column(type="boolean")
	 */
	protected $moderate;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $creation_date;
	
	public function __construct()
	{
		$this->creation_date = new DateTime();
		$this->comments = new ArrayCollection();
	}
	
	public function id()
	{
		return $this->id;
	}
	
	public function comments()
	{
		return $this->comments;
	}
	
	public function creation_date()
	{
		return $this->creation_date;
	}
	
	public function title($title = null)
	{
		if (is_null($title)) {
			return $this->title;
		}
		$this->title = $title;
	}
	
	public function summary($summary = null)
	{
		if (is_null($summary)) {
			return $this->summary;
		}
		$this->summary = $summary;
	}
	
	public function body($body = null)
	{
		if (is_null($body)) {
			return $this->body;
		}
		$this->body = $body;
	}
	
	public function gallery($gallery = null)
	{
		if (is_null($gallery)) {
			return $this->gallery;
		}
		$this->gallery = $gallery;
	}
	
	public function author($author = null)
	{
		if (is_null($author)) {
			return $this->author;
		}
		$this->author = $author;
	}
	
	public function approver($approver = null)
	{
		if (is_null($approver)) {
			return $this->approver;
		}
		$this->approver = $approver;
	}
	
	public function approved()
	{
		return is_null($this->approver) === false;
	}
	
	public function active($active = null)
	{
		if (is_null($active)) {
			return $this->active;
		}
		$this->active = $active;
	}
	
	public function category($category = null)
	{
		if (is_null($category)) {
			return $this->category;
		}
		$this->category = $category;
	}
	
	public function moderate($moderate = null)
	{
		if (is_null($moderate)) {
			return $this->moderate;
		}
		$this->moderate = $moderate;
	}
	
	public function validate()
	{
		String::required($this->title, 'title');
		String::max($this->title, 28, false, 'title');
		String::required($this->summary, 'summary');
		String::required($this->body, 'body');
		Boolean::cast($this->active);
		Boolean::cast($this->moderate);
		Integer::natural($this->category, 'category');
	}
}
