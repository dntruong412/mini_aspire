<?php

namespace Domains\Backend\Services\UserLoan;

class UserLoan {

    private $action;

    public function __construct(Action $action) {
        $this->action = $action;
    }

    public function execute() {
        return $this->action->execute();
    }

}