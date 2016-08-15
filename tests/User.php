<?php

namespace Jeroenherczeg\Dog\Test;

use Illuminate\Database\Eloquent\Model;
use Jeroenherczeg\Dog\Followable;

class User extends Model
{
    use Followable;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];
}