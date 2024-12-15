<?php
namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class PolicyAuthorizationException extends AuthorizationException
{
    public $policy;
    public $action;

    public function __construct(string $policy, string $action, $message = "This action is unauthorized.")
    {
        parent::__construct($message);
        $this->policy = $policy;
        $this->action = $action;
    }
}
