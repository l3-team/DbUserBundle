<?php

namespace L3\Bundle\DbUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;

#[ORM\Table(name:"x_role")]
#[ORM\Entity(repositoryClass:"L3\Bundle\DbUserBundle\Repository\RoleRepository")]
class Xrole extends Role {

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(name:"id", type:"integer")]
    private $id;

    #[ORM\Column(name:"role", type:"string", length:255)]
    private $role;


    public function __toString() {
        return $this->getRole();
    }

    /**
     * Get the id.
     *
     * @return integer The id.
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set id
     *
     * @param string $id
     * @return Role
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }


    /**
     * Implementation of getRole for the RoleInterface.
     *
     * @return string The role.
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

}
