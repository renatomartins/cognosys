<?php
namespace App\Cogs\News\Models\Entities;
use Cognosys\Model,
	\DateTime;

/**
 * @Entity(repositoryClass="App\Cogs\News\Models\Repositories\Comment")
 * @Table(name="news_comments")
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Comment extends Model
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @ManyToOne(targetEntity="App\Cogs\User\Models\Entities\User")
	 * @JoinColumn(name="author_id")
	 */
	protected $author;
	
	/**
	 * @Column(type="string", length=128)
	 */
	protected $title;
	
	/**
	 * @Column(type="text")
	 */
	protected $body;
	
	/**
	 * @ManyToOne(targetEntity="Item", inversedBy="comments")
	 * @JoinColumn(name="news_item_id")
	 */
	protected $news_item;
	
	/**
	 * @ManyToOne(targetEntity="App\Cogs\User\Models\Entities\User")
	 * @JoinColumn(name="approver_id")
	 */
	protected $approver;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $approved_date;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $creation_date;
	
	public function __construct()
	{
		$this->creation_date = new DateTime();
	}
	
	public function id()
	{
		return $this->id;
	}
	
	public function creation_date()
	{
		return $this->creation_date;
	}
	
	public function author($author = null)
	{
		if (is_null($author)) {
			return $this->author;
		}
		$this->author = $author;
	}
	
	public function title($title = null)
	{
		if (is_null($title)) {
			return $this->title;
		}
		$this->title = $title;
	}
	
	public function body($body = null)
	{
		if (is_null($body)) {
			return $this->body;
		}
		$this->body = $body;
	}
	
	public function news_item($news_item = null)
	{
		if (is_null($news_item)) {
			return $this->news_item;
		}
		$this->news_item = $news_item;
	}
	
	public function approver($approver = null)
	{
		if (is_null($approver)) {
			return $this->approver;
		}
		$this->approver = $approver;
	}
	
	public function approved_date($approved_date = null)
	{
		if (is_null($approved_date)) {
			return $this->approved_date;
		}
		$this->approved_date = $approved_date;
	}
	
	public function validate()
	{
		//TODO: implement method 'validate'
	}
}
