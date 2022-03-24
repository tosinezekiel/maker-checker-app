<?php 
namespace App\Constants;

class Type {
    const CREATE = 'Create';
    const UPDATE = 'Update';
    const DELETE = 'Delete';
    
    const ALL = [self::CREATE, self::UPDATE, self::DELETE];
}