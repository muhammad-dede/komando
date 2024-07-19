<?php

namespace App\Console\Commands;

use App\Services\StubGenerator;
use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;

class GenerateTest extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:generate-test';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate test scripts automatically based on route lists';

    protected $routes;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();
        $this->routes = $router->getRoutes();
        $this->stub = resource_path('stubs/tests/ControllerTest.php.stub');
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $routesGroupByController = collect($this->routes)
            ->groupBy(function (Route $item) {
                $controller = Arr::first(explode('@', $item->getActionName()));

                return $controller;
            })
            ->except('Closure');

        $choices = $routesGroupByController->keys()->toArray();
        $choice = $this->choice('Pilih controller', $choices);

        foreach ($routesGroupByController->only($choice) as $controller => $routes) {
            $controllerName = Arr::last(explode('\\', $controller));
            $controllerPath = trim(str_replace('\\', '/', Arr::last(explode('App\\Http\\Controllers', $controller))), '/');
            $methodStubs = $this->generateMethodStubs($routes);
            $target = base_path("tests/{$controllerPath}Test.php");
            $generator = new StubGenerator($this->stub);
            $generator->compile([
                ':CLASS_NAME:' => "{$controllerName}Test",
                ':METHODS:' => $methodStubs,
            ])->save($target, true);

            $this->info($target);
        }
    }

    protected function generateMethodStubs($routes)
    {
        $stubs = '';
        $bags = [];
        foreach ($routes as $route) {
            /** @var $route Route */
            $method = Arr::last(explode('@', Arr::last(explode('\\', $route->getActionName()))));
            $verb = collect($route->getMethods());
            $verb = $verb->combine($verb)->only(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])->first();

            switch ($method) {
                case 'index':
                    $template = resource_path('stubs/tests/index.stub');
                    break;
                case 'show':
                    $template = resource_path('stubs/tests/show.stub');
                    break;
                case 'create':
                    $template = resource_path('stubs/tests/create.stub');
                    break;
                case 'store':
                    $template = resource_path('stubs/tests/store.stub');
                    break;
                case 'edit':
                    $template = resource_path('stubs/tests/edit.stub');
                    break;
                case 'update':
                    $template = resource_path('stubs/tests/update.stub');
                    break;
                case 'destroy':
                    $template = resource_path('stubs/tests/destroy.stub');
                    break;
                default:
                    $template = resource_path('stubs/tests/unknown.stub');
                    break;
            }

            if (! in_array($method, $bags)) {
                $generator = new StubGenerator($template);

                $stubs .= $generator->compile([
                    ':url:' => $route->getUri(),
                    ':method:' => ucfirst($method),
                    ':verb:' => strtolower($verb),
                ])->getContents();
                $bags[] = $method;
            }
        }

        return $stubs;
    }
}
