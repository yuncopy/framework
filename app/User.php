<?php

namespace App;

use core\database\model\Model;

class User extends Model
{
	public function php()
	{
		echo "hello php";
	}

    public function sayPhp()
    {
        return "id={$this->id} 名字:$this->nickname 说了:php是世界最好的语言";
    }
}