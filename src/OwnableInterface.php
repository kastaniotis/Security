<?php

namespace Iconic\Security;

interface OwnableInterface
{
    public function getOwner(): IdentifiableUserInterface;
}