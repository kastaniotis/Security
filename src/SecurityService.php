<?php

namespace Iconic\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser(): ?IdentifiableUserInterface
    {
        /** @var IdentifiableUserInterface|null $user */
        $user = $this->security->getUser();

        return $user;
    }

    public function ensureUser(): IdentifiableUserInterface
    {
        /** @var IdentifiableUserInterface|null $user */
        $user = $this->getUser();
        if (null == $user) {
            throw new AccessDeniedException('access.denied');
        }

        return $user;
    }

    public function isUser(): bool
    {
        return $this->getUser() !== null;
    }
}
