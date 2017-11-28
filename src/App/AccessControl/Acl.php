<?php
declare(strict_types = 1);

namespace App\AccessControl;

class Acl
{
    /**
     * @var Role[]
     */
    private $roles = [];

    /**
     * @var array
     */
    private $privileges = [];

    /**
     * @param string $id
     * @param string|null $parent
     * @return Acl
     * @throws \Exception
     */
    public function addRole(string $id, ?string $parent = null) : Acl
    {
        if ($id === $parent) {
            throw new \Exception("Roles should be different");
        }
        if (!isset($this->roles[$id])) {
            $this->roles[$id] = new Role($id);
        }
        if (null !== $parent) {
            if (!isset($this->roles[$parent])) {
                $this->roles[$parent] = new Role($parent);
            }
            $this->roles[$parent]->setChildren($this->roles[$id]);
        }

        return $this;
    }

    /**
     * @param string $id
     * @return Role|null
     */
    public function getRole(string $id) : ?Role
    {
        return $this->roles[$id] ?? null;
    }

    /**
     * @param string $role
     * @param string $controller
     * @param array $action
     * @return Acl
     * @throws \Exception
     */
    public function allow(string $role, string $controller, array $action = []) : Acl
    {
        if (!isset($this->roles[$role])) {
            throw new \Exception("Role does not found");
        }
        if (!isset($this->privileges[$role])) {
            $this->privileges[$role] = [];
        }
        if (!isset($this->privileges[$role][$controller])) {
            $this->privileges[$role][$controller] = $action;
        } else {
            if ($action == []) {
                $this->privileges[$role][$controller] = [];
            } else {
                $this->privileges[$role][$controller] = array_merge($this->privileges[$role][$controller], $action);
            }
        }

        if ($this->getRole($role)->getChildren() !== null) {
            $this->allow($this->getRole($role)->getChildren()->getId(), $controller, $action);
        }

        return $this;
    }

    /**
     * @param string $role
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function isAllowed(string $role, string $controller, string $action) : bool
    {

        if (!isset($this->roles[$role])) {
            return false;
        }

        if (!isset($this->privileges[$role])) {
            return false;
        }

        if (!isset($this->privileges[$role][$controller])) {
            return false;
        }

        if ($this->privileges[$role][$controller] == []) {
            return true;
        }

        if (!in_array($action, $this->privileges[$role][$controller])) {
            return false;
        }

        return true;
    }
}