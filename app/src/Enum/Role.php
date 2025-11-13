<?php

namespace App\Enum;

enum Role: string
{
    case ETUDIANT = 'ROLE_ETUDIANT';
    case PROFESSEUR = 'ROLE_PROFESSEUR';
    case ADMIN = 'ROLE_ADMIN';
}
