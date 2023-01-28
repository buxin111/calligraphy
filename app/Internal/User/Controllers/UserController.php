<?php

namespace App\Internal\User\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Internal\User\Resources\UserResource;


/**
 * Class UserController
 * @author buxin
 * @date 2022-04-03
 * @package App\Internal\User\Controllers
 */
class UserController extends ApiController
{

    /**
     * @author buxin
     * @date 2022-04-03
     * @return UserResource
     */
    public function info()
    {
        return new UserResource($this->auth());
    }
}
