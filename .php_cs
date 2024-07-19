<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'align_equals' => false,
        'align_double_arrow' => false,
    ],
    'ordered_imports' => ['sortAlgorithm' => 'alpha'],
    'no_unused_imports' => true,
];

$project_path = getcwd();
$finder = Finder::create()
    ->in([
        $project_path.'/app/Console',
        $project_path.'/app/Enum',
        $project_path.'/app/Http/Controllers/Api',
        $project_path.'/app/Http/Controllers/Liquid',
        $project_path.'/app/Http/Requests/Liquid',
        $project_path.'/app/Models',
        $project_path.'/app/Policies/Liquid',
        $project_path.'/app/Services',
        $project_path.'/resources/views/liquid',
        $project_path.'/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Config::create()
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
