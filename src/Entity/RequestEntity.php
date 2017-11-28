<?php
declare(strict_types = 1);

namespace Entity;

class RequestEntity extends AbstractEntity
{
    const TYPE = [
        'PAID' => 'PAID',
        'MEDICAL' => 'MEDICAL'
    ];

    const STATUS = [
        'NEW' => 'NEW',
        'APPROVED' => 'APPROVED',
        'REJECTED' => 'REJECTED'
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var UserEntity
     */
    private $userEntity;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $status;

    /**
     * RequestEntity constructor.
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        if ($data !== null){
            $this->id = (int)$data['id'] ?? 0;
            $this->userEntity = new UserEntity();
            $this->startDate = new \DateTime($data['start_date']) ?? null;
            $this->endDate = new \DateTime($data['end_date']) ?? null;
            $this->type = $data['type'] ?? null;
            $this->status = $data['status'] ?? null;
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return RequestEntity
     */
    public function setId(int $id) : RequestEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return UserEntity
     */
    public function getUserEntity(): UserEntity
    {
        return $this->userEntity;
    }

    /**
     * @param UserEntity $userEntity
     * @return RequestEntity
     */
    public function setUserEntity(UserEntity $userEntity) : RequestEntity
    {
        $this->userEntity = $userEntity;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return RequestEntity
     */
    public function setStartDate(\DateTime $startDate) : RequestEntity
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return RequestEntity
     */
    public function setEndDate(\DateTime $endDate) : RequestEntity
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return RequestEntity
     */
    public function setStatus(string $status) : RequestEntity
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return RequestEntity
     */
    public function setType($type) : RequestEntity
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration() : int
    {
        return $this->endDate->diff($this->startDate)->days + 1;
    }
}
