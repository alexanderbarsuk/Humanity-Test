<?php
declare(strict_types = 1);

namespace Entity;

use App\Main;

class UserEntity extends AbstractEntity
{
    const USER_ROLES = [
        'GUEST' => 'GUEST',
        'USER' => 'USER',
        'MANAGER' => 'MANAGER'
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $joinedDate;

    /**
     * @var string
     */
    private $role;

    /**
     * @var array
     */
    private $daysSpent = [];

    /**
     * UserEntity constructor.
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        if ($data !== null){
            $this->id = (int)$data['id'] ?? 0;
            $this->name = $data['name'] ?? '';
            $this->joinedDate = $data['joined_date'] ?? '';
            $this->role = $data['role'] ?? self::USER_ROLES['USER'];
            $this->daysSpent = [];
        }

        foreach (RequestEntity::TYPE as $type) {
            if (!isset($this->daysSpent[$type])){
                $this->daysSpent[$type] = 0;
            }
        }
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role ?? self::USER_ROLES['GUEST'];
    }

    /**
     * @param string $role
     * @return UserEntity
     */
    public function setRole(string $role) : UserEntity
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     * @return UserEntity
     */
    public function setId(int $id) : UserEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     * @return UserEntity
     */
    public function setName(string $name) : UserEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getJoinedDate(): \DateTime
    {
        return $this->joinedDate;
    }

    /**
     * @param \DateTime $joinedDate
     * @return UserEntity
     */
    public function setJoinedDate(\DateTime $joinedDate) : UserEntity
    {
        $this->joinedDate = $joinedDate;
        return $this;
    }

    /**
     * @param string $type
     * @return int
     */
    public function getDaysSpent(string $type = null): int
    {
        if ($type == null){
            return array_sum($this->daysSpent);
        }
        return $this->daysSpent[$type] ?? 0;
    }

    /**
     * @param array $daysSpent
     * @return UserEntity
     */
    public function setDaysSpent(array $daysSpent) : UserEntity
    {
        $this->daysSpent = $daysSpent;
        return $this;
    }

    /**
     * @param string $dayType
     * @param int $spent
     */
    public function addDaysSpent(string $dayType, int $spent)
    {
        $this->daysSpent[$dayType] = $spent;
    }

    /**
     * @param string|null $type
     * @return int
     */
    public function getDaysLeft(string $type = null) : int
    {
        $result = 0;

        try {
            $total = Main::$appConfig->leaves_per_year;
            if ($type == null) {
                $result = array_sum($total) - array_sum($this->daysSpent);
            } else {
                $result = $total[$type] - $this->daysSpent[$type];
            }
        } catch (\Exception $e){

        }

        return $result < 0 ? 0 : $result;
    }

}