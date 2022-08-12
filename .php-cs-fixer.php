<?php
//if (PHP_VERSION_ID <= 80001 || PHP_VERSION_ID >= 80100) {
//    fwrite(STDERR, '只支持php80');
//    exit(1);
//}

$finder = PhpCsFixer\Finder::create()
    ->exclude('bootstrap') // 忽略这些文件夹
    ->exclude('public')
    ->exclude('bin')
    ->exclude('tmp')
    ->exclude('docs')
    ->exclude('storage')
    ->exclude('vendor')
    ->exclude('tests')
    ->in(__DIR__) //  项目根目录路径
;

// 改成你的php版本
$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PHPUnit75Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']],
        'modernize_strpos' => true, // needs PHP 8+ or polyfill
        'heredoc_indentation' => true,
        'declare_strict_types' => false,
    ])
    ->setFinder($finder)
    ->setUsingCache(false);
;

return $config;
