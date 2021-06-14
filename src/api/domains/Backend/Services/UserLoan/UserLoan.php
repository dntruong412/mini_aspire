<?php

namespace Domains\Backend\Services\UserLoan;

class UserLoan {

    public static function execute(Action $action) {
        return $action->execute();
    }

}