<?php

namespace Iconic\Security;

use Symfony\Component\Security\Core\User\UserInterface;

interface IdentifiableUserInterface extends UserInterface
{
    public function getId(): int;

    public function isAdmin(): bool;

    public function getEmail(): string;
}
