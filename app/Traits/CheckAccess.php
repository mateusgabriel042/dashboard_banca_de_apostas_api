<?php

namespace App\Traits;

use Gate;

trait CheckAccess {
	public function checkAccess($permission){
        if(Gate::denies($permission))
            throw new \Exception('Acesso não autorizado!', 403);
    }
}