<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="User API",
 *      description="User Management API",
 *      @OA\Contact(
 *          email="jayantadas.dev@gmail.com",
 *          name="Jayanta Das",
 *          url="https://www.linkedin.com/in/jayanta-das-88771b17a/"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 * 
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 */
class SwaggerConfig
{
}
