<?php

namespace OC\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Entity\AdvertRepository")
 */
class Advert
{
  /**
   * @ORM\ManyToMany(targetEntity="OC\PlatformBundle\Entity\Category", cascade={"persist"})
   */
  private $categories;

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(name="date", type="date")
   */
  protected $date;

  /**
   * @ORM\Column(name="title", type="string", length=255)
   */
  protected $title;

  /**
   * @ORM\Column(name="author", type="string", length=255)
   */
  protected $author;

  /**
   * @ORM\Column(name="content", type="text")
   */
  protected $content;
  
  public function __construct()
  {
    $this->date = new \Datetime();
    $this->categories = new ArrayCollection();
  }

  public function addCategory(Category $category)
  {
   $this->categories[] = $category;
  }

  public function removeCategory(Category $category)
  {
   $this->categories->removeElement($category);
  }

  public function getCategories()
  {
   return $this->categories;
  }

  // Et bien sûr les getters/setters :

  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }

  public function setDate($date)
  {
   $this->date = $date;
  }
  public function getDate()
  {
   return $this->date;
  }
  
  public function setTitle($title)
  {
   $this->title = $title;
  }
  public function getTitle()
  {
   return $this->title;
  }
 
  public function setAuthor($author)
  {
   $this->author = $author;
  }
  public function getAuthor()
  {
   return $this->author;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }
  public function getContent()
  {
    return $this->content;
  }
    /**
     * @ORM\OneToOne(targetEntity="OC\PlatformBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    public function setImage(Image $image = null)
    {
      $this->image = $image;
    }

    public function getImage()
    {
      return $this->image;
    }
     /**
      * @ORM\Column(name="published", type="boolean")
      */
    private $published = true;

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
}
