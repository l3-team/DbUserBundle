<?php

namespace L3\Bundle\DbUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Table(name:"x_user")]
#[ORM\Entity(repositoryClass:"L3\Bundle\DbUserBundle\Repository\UserRepository")]
class User implements UserInterface, \Serializable {

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(name:"id", type:"integer")]
    private $id;

    #[ORM\Column(name:"uid", type:"string", length:15)]
    private $uid;
    
    #[ORM\ManyToMany(targetEntity:"Xrole")]
    #[ORM\JoinTable(name:"x_user_role")]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"role_id", referencedColumnName:"id")]
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
