<?php

namespace Iconic\Security;

interface OwnerUserInterface
{
    public function isOwnerOf(OwnableInterface $ownable): bool;
}