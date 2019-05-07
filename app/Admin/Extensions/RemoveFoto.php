<?php
/**
 * Created by PhpStorm.
 * User: korak
 * Date: 23.04.2019
 * Time: 21:42
 */

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class RemoveFoto
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

}