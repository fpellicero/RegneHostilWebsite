<?php

namespace RegneHostil\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quote
 *
 * @ORM\Table(name="quote")
 * @ORM\Entity
 */
class Quote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="quote", type="text")
     */
    private $quote;

    /**
     * @var integer
     *
     * @ORM\Column(name="valid", type="integer")
     */
    private $valid;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quote
     *
     * @param string $quote
     * @return Quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    
        return $this;
    }

    /**
     * Get quote
     *
     * @return string 
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Set valid
     *
     * @param integer $valid
     * @return Quote
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    
        return $this;
    }

    /**
     * Get valid
     *
     * @return integer 
     */
    public function getValid()
    {
        return $this->valid;
    }

	/**
	 * Returns a random quote fetched from the database
	 *
	 */
	public function getRandomQuote() 
	{
		$quotes = $this->getDoctrine()
			->getRepository('RegneHostilClubBundle:Quote')
			->findAll();

		$quote = array_rand($quotes,1);
		return $quote;
	}
}