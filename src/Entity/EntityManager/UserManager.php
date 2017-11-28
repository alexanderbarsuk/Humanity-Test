<?php
declare(strict_types = 1);

namespace Entity\EntityManager;

use Entity\UserEntity;
use Tools\Db\Db;
use Entity\AbstractEntity;
use Entity\AbstractEntityManager;

class UserManager extends AbstractEntityManager
{
    /**
     * @param int $id
     * @return UserEntity
     */
    public function getById(int $id) : UserEntity
    {
        $list = $this->getListByFilter("AND u.id = {$id}");
        return array_pop($list) ?? new UserEntity();
    }

    /**
     * @param string|null $filter
     * @return array
     */
    public function getListByFilter(?string $filter = null) : array
    {
        $userEntities = [];

        $q = "
            SELECT 
                u.id,
                u.name,
                u.joined_date,
                u.role,
                r.type,
                SUM(
                    CASE 
                    	WHEN YEAR(r.end_date) = YEAR(NOW()) AND YEAR(r.start_date) = YEAR(NOW()) THEN DATEDIFF(r.end_date, r.start_date) + 1
                    	WHEN YEAR(r.end_date) = YEAR(NOW()) AND YEAR(r.start_date) < YEAR(NOW()) THEN DATEDIFF(r.end_date, DATE(CONCAT(YEAR(NOW()), '-01-01'))) + 1
                    	WHEN YEAR(r.end_date) > YEAR(NOW()) AND YEAR(r.start_date) = YEAR(NOW()) THEN DATEDIFF(DATE(CONCAT(YEAR(NOW()), '-12-31')), r.start_date) + 1
                    END
                ) as days
            FROM `user` as u
            LEFT JOIN request as r
                ON r.user_id = u.id
                AND r.status = 'APPROVED'
                AND (YEAR(r.start_date) = YEAR(NOW()) OR YEAR(r.end_date) = YEAR(NOW()))
            WHERE 1
                {$filter}
            GROUP BY 
                u.id,
                r.type
        ";

        $users = Db::getConnection('localhost')->query($q);
        foreach ($users->fetchAll() as $user) {
            $userEntity = new UserEntity($user);
            if (isset($userEntities[$userEntity->getId()])) {
                $userEntity = $userEntities[$userEntity->getId()];
            }

            if ($user['type'] != null) {
                $userEntity->addDaysSpent($user['type'], (int)$user['days']);
            }

            $userEntities[$userEntity->getId()] = $userEntity;
        }

        return $userEntities;
    }

    public function getList() : array
    {
        return $this->getListByFilter();
    }
}
