<?php 
namespace App\Constants;

class Status {
    const PENDING = '1';
    const APPROVED = '2';

    const ALL = [self::PENDING, self::APPROVED];
}