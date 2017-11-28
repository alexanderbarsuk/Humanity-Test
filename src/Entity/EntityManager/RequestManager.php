<?php
declare(strict_types = 1);

namespace Entity\EntityManager;

use App\Exception\UserException;
use Entity\AbstractEntity;
use Entity\AbstractEntityManager;
use Entity\RequestEntity;
use Entity\UserEntity;
use Tools\Db\Db;

class RequestManager extends AbstractEntityManager
{
    /**
     * @var \PDO
     */
    private $conn;

    /**
     * RequestManager constructor.
     */
    public function __construct()
    {
        $this->conn = Db::getConnection('localhost');
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param string|null $filter
     * @return array
     */
    private function getListByFilter(string $filter = null) : array
    {
        $requestEntities = [];

        $q = "
            SELECT 
              r.id,
              r.user_id,
              r.start_date,
              r.end_date,
              r.status,
              r.type,
              u.name as 'user_name',
              u.role as 'user_role',
              u.joined_date as 'user_joined_date'
            FROM request as r
            INNER JOIN user as u
              ON u.id = r.user_id
            WHERE 1
              AND r.deleted_at IS NULL
              AND r.deleted_by IS NULL 
              {$filter}
            ORDER BY 
              IF(r.status = '" . RequestEntity::STATUS['NEW'] . "', 1, 10) ASC,
              r.created_at DESC
        ";

        $requests = $this->conn->query($q);
        foreach ($requests->fetchAll() as $request) {

            $requestEntity = new RequestEntity($request);
            if (isset($requestEntities[$requestEntity->getId()])) {
                $requestEntity = $requestEntities[$requestEntity->getId()];
            } else {
                $requestEntity->getUserEntity()->setId((int)$request['user_id']);
                $requestEntity->getUserEntity()->setName($request['user_name']);
                $requestEntity->getUserEntity()->setRole($request['user_role']);
                $requestEntity->getUserEntity()->setJoinedDate(new \DateTime($request['user_joined_date']));
            }

            $requestEntities[$requestEntity->getId()] = $requestEntity;
        }

        return $requestEntities;
    }

    /**
     * @return array
     */
    public function getList() : array
    {
        return $this->getListByFilter();
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getListByUserId(int $userId) : array
    {
        return $this->getListByFilter("AND r.user_id = {$userId}");
    }

    /**
     * @param int $requestId
     * @return RequestEntity
     */
    public function getById(int $requestId) : RequestEntity
    {
        $list = $this->getListByFilter("AND r.id = {$requestId}");
        return array_pop($list) ?? new RequestEntity();
    }

    /**
     * @param RequestEntity $entity
     * @return bool
     * @throws UserException
     */
    public function add(RequestEntity $entity)
    {
        try {
            $this->conn->beginTransaction();

            $q = "
                INSERT INTO request 
                  (id, user_id, start_date, end_date, status, type, created_at)
                VALUES 
                  (NULL, :user_id, :start_date, :end_date, :status, :type, NOW())
            ";
            $prepared = $this->conn->prepare($q);
            $prepared->execute([
                ':user_id' => $entity->getUserEntity()->getId(),
                ':start_date' => $entity->getStartDate()->format('Y-m-d 00:00:00'),
                ':end_date' => $entity->getEndDate()->format('Y-m-d 23:59:59'),
                ':status' => $entity->getStatus(),
                ':type' => $entity->getType()
            ]);

            $entity->setId((int)$this->conn->lastInsertId());

            $this->conn->commit();

            return true;

        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw new UserException($e->getMessage());
        }
    }

    /**
     * @param RequestEntity $requestEntity
     * @return bool
     * @throws UserException
     */
    public function update(RequestEntity $requestEntity)
    {
        try {
            $this->conn->beginTransaction();

            $q = "
                UPDATE request 
                SET 
                  start_date = :start_date,
                  end_date = :end_date,
                  type = :type,
                  modified_at = NOW()
                WHERE id = :request_id
            ";
            $prepared = $this->conn->prepare($q);
            $prepared->execute([
                ':start_date' => $requestEntity->getStartDate()->format('Y-m-d 00:00:00'),
                ':end_date' => $requestEntity->getEndDate()->format('Y-m-d 23:59:59'),
                ':type' => $requestEntity->getType(),
                ':request_id' => $requestEntity->getId()
            ]);

            if ($prepared->rowCount() != 1) {
                throw new \Exception("Request does not exist");
            }

            $this->conn->commit();

            return true;

        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw new UserException($e->getMessage());
        }
    }

    /**
     * @param RequestEntity $requestEntity
     * @param UserEntity $deletedBy
     * @return bool
     * @throws UserException
     */
    public function delete(RequestEntity $requestEntity, UserEntity $deletedBy)
    {
        try {
            $this->conn->beginTransaction();

            $q = "
                UPDATE request 
                SET 
                  deleted_by = :user_id,
                  deleted_at = NOW()
                WHERE id = :request_id
            ";
            $prepared = $this->conn->prepare($q);
            $prepared->execute([
                ':user_id' => $deletedBy->getId(),
                ':request_id' => $requestEntity->getId()
            ]);

            if ($prepared->rowCount() != 1) {
                throw new \Exception("Request does not exist");
            }

            $this->conn->commit();

            return true;

        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw new UserException($e->getMessage());
        }
    }

    /**
     * @param RequestEntity $requestEntity
     * @param UserEntity $changedBy
     * @return bool
     * @throws UserException
     */
    public function changeStatusRequest(RequestEntity $requestEntity, UserEntity $changedBy)
    {
        try {
            $this->conn->beginTransaction();

            $q = "
                UPDATE request 
                SET 
                  status = :status,
                  processed_by = :user_id,
                  processed_at = NOW()
                WHERE id = :request_id
            ";
            $prepared = $this->conn->prepare($q);
            $prepared->execute([
                ':status' => $requestEntity->getStatus(),
                ':user_id' => $changedBy->getId(),
                ':request_id' => $requestEntity->getId()
            ]);

            if ($prepared->rowCount() != 1) {
                throw new \Exception("Request does not exist");
            }

            $this->conn->commit();

            return true;

        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw new UserException($e->getMessage());
        }
    }
}
