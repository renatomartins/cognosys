<?php
namespace App\Cogs\Media\Models\Entities;
use Cognosys\Model;

/**
 * @Entity(repositoryClass="App\Cogs\Media\Models\Repositories\Image")
 * @Table(name="images")
 */
class Image extends Model
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @ManyToOne(targetEntity="Gallery", inversedBy="images")
	 * @JoinColumn(name="gallery_id")
	 */
	protected $gallery;
	
	/**
	 * @Column(type="string", length=128)
	 */
	protected $name;
	
	/**
	 * @Column(type="string", length=128)
	 */
	protected $url;
	
	/**
	 * @Column(type="string", length=64)
	 */
	protected $type;
	
	/**
	 * @Column(type="integer")
	 */
	protected $size;
	
	public function id()
	{
		return $this->id;
	}
	
	public function gallery()
	{
		return $this->gallery;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function url()
	{
		return $this->url;
	}
	
	public function type()
	{
		return $this->type;
	}
	
	public function size()
	{
		return $this->size;
	}
	
	public function validate()
	{
		//TODO: implement method 'validate'
	}
}
