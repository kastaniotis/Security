<?php

namespace Iconic\Security;

enum Role: string {
    case USER = 'ROLE_USER';
    case ADMINISTRATOR = 'ROLE_ADMIN';
}