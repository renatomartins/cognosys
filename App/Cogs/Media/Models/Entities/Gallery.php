<?php
namespace App\Modules\Media\Models\Entities;
use Core\Model,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="App\Modules\Media\Models\Repositories\Gallery")
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
