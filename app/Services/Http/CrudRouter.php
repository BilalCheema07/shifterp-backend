<?php
namespace App\Services\Http;

use Laravel\Lumen\Routing\Router;

class CrudRouter extends Router
{
    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function resource($resource, $controller)
    {
        $this->get($resource, "$controller@index");
        $this->get("$resource/{id}", "$controller@show");
        $this->put("$resource/{id}", "$controller@update");
        $this->post($resource, "$controller@store");
        $this->delete("$resource/{id}", "$controller@destroy");
        $this->patch("$resource/{id}", "$controller@update");
        $this->head($resource, "$controller@head");
    }
}
