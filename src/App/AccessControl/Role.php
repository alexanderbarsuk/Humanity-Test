<?php
declare(strict_types = 1);

namespace App\AccessControl;

class Role
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Role|null
     */
    private $children = null;

    /**
     * Role constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Role
     */
    public function setId(string $id) : Role
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Role
     */
    public function getChildren(): ?Role
    {
        return $this->children;
    }

    /**
     * @param Role $children
     * @return Role
     */
    public function setChildren(Role $children) : Role
    {
        $this->children = $children;
        return $this;
    }

}