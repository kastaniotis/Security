<?php

namespace Iconic\Security;

use Iconic\Db\DatabaseConnection;
use Iconic\Db\Exception\NoResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

readonly class UserRepository implements PasswordUpgraderInterface
{
    public function __construct(private DatabaseConnection $connection)
    {
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $this->connection->execute(
            'UPDATE `user` SET `password` = :password WHERE `id` = :id', ['id' => $user->getId(), 'password' => $newHashedPassword]);
    }

    /**
     * @throws NoResultException
     */
    public function getById(int $id): IdentifiableUserInterface
    {
        $result = $this->connection->getOne('SELECT * FROM `user` WHERE `id` = ?', [$id]);
        return User::fromArray($result);
    }

    /**
     * @return array<int, mixed>
     */
    public function getAll(): array
    {
        return $this->connection->getMany('SELECT * FROM `user`');
    }

    public function verify(int $id): void
    {
        $this->connection->execute("UPDATE `user` SET `is_verified` = 1, `roles` = '[\"" . Role::USER->value . "\"]' WHERE `id` = ?", [$id]);
    }
}
