<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Queues
 *
 * @ORM\Table(name="queues")
 * @ORM\Entity
 */
class Queues
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $name;



    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return Extensions
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    
}
