<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

$finder = PhpCsFixer\Finder::create()
    ->ignoreVCSIgnored(true)
    ->in(__DIR__)
    ->append([
        __DIR__ . '/dev-tools/doc.php',
        __FILE__,
    ])
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP83Migration' => true,
        '@PHP82Migration' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        '@Symfony:risky' => true,
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_only',
        ],
        'array_indentation' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        // Anciennement 'braces'
        'single_space_around_construct' => true,
        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,
        'declare_parentheses' => true,
        'no_multiple_statements_per_line' => true,
        'curly_braces_position' => true,
        'statement_indentation' => true,

        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'doctrine_annotation_array_assignment' => [
            'operator' => '=',
        ],
        'doctrine_annotation_spaces' => [
            'after_array_assignments_equals' => false,
            'before_array_assignments_equals' => false,
        ],
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'continue',
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => [
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'php_unit_method_casing' => [
            'case' => 'camel_case',
        ],
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
        'strict_comparison' => true,
        'strict_param' => true,
        'void_return' => true,
        'yoda_style' => true,
        'modernize_strpos' => true, // needs PHP 8+ or polyfill
        'native_constant_invocation' => false,
    ])
    ->setFinder($finder)
;

return $config;
