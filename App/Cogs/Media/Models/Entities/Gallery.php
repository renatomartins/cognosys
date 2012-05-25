<?php
namespace App\Cogs\Media\Models\Entities;
use Cognosys\Model,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="App\Cogs\Media\Models\Repositories\Gallery")
 * @Table(name="galleries")
 */
class Gallery extends Model
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="integer")
	 */
	protected $width;
	
	/**
	 * @Column(type="integer")
	 */
	protected $height;
	
	/**
	 * @OneToMany(targetEntity="Image", mappedBy="gallery")
	 */
	protected $images;
	
	public function id()
	{
		return $this->id;
	}
	
	public function width()
	{
		return $this->width;
	}
	
	public function height()
	{
		return $this->height;
	}
	
	public function images()
	{
		return $this->images;
	}
	
	public function __construct()
	{
		$this->images = new ArrayCollection();
	}
	
	public function validate()
	{
		//TODO: implement method 'validate'
	}
}
