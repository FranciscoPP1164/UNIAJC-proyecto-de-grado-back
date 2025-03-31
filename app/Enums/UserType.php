<?php
namespace App\Enums;

enum UserType: string {
    case Root = 'root';
    case Nurse = 'nurse';
    case Admin = 'admin';
}
