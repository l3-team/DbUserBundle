<?php

namespace L3\Bundle\DbUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Table(name="x_user")
 * @ORM\Entity(repositoryClass="L3\Bundle\DbUserBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable {

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="uid", type="string", length=15)
     */
    private $uid;

    /**
     * @ORM\ManyToMany(targetEntity="Xrole", cascade={"persist"})
     * @ORM\JoinTable(name="x_user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     * @var ArrayCollection $roles
     */
    private $roles;

    
    public function __construct() {
        
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set id
     *
     * @param string $id
     * @return User
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get uid
     *
     * @return string 
     */
    public function getUid() {
        return $this->uid;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return User
     */
    public function setUid($uid) {
        $this->uid = $uid;
        return $this;
    }
    
    //Source : http://www.metod.si/symfony2-error-usernamepasswordtokenserialize-must-return-a-string-or-null/
    public function serialize() {
        return serialize(array(
          $this->id,
          $this->uid
        ));
    }

    public function unserialize($serialize) {
        list(
          $this->id,
          $this->uid
          ) = unserialize($serialize);
    }

    public function eraseCredentials(): void {
        //pas d'implémentation nécessaire
    }

    public function getPassword() {
        //pas d'implémentation nécessaire
    }

    public function getRoles(): array {
        $roles = array();

        foreach ($this->roles as $role) {
            if (!in_array($role->getRole(), $roles)) {
                $roles[] = $role->getRole();
            }
        }
        
        $roles[] = "ROLE_USER";
        
        return $roles;
    }

    public function getSalt() {
        //pas d'implémentation nécessaire
    }
 
    public function getUserIdentifier(): string {
        return $this->getUid();
    }

    public function __toString() {
        return $this->getUid();
    }

}
