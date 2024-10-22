<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Rules;

use nicoSWD\Rule\Grammar\JavaScript\JavaScript;
use nicoSWD\Rule\TokenStream\AST;
use nicoSWD\Rule\Compiler\CompilerFactory;
use nicoSWD\Rule\Evaluator\Evaluator;
use nicoSWD\Rule\Expression\ExpressionFactory;
use nicoSWD\Rule\Tokenizer\Tokenizer;
use nicoSWD\Rule\TokenStream\Token\TokenFactory;
use nicoSWD\Rule\TokenStream\TokenStreamFactory;
use nicoSWD\Rule\TokenStream\CallableUserMethodFactory;
use nicoSWD\Rule\Parser\Parser;

/**
 *  Rule engine factory class
 */
class RuleEngine
{
    /**
     * Ref to token stream factory
     * @var 
     */
    private static $tokenStreamFactory;

    /**
     * Ref tor token factory
     * 
     * @var object|null
     */
    private static $tokenFactory;

    /**
     * Ref to compiler
     * 
     * @var object|null
     */
    private static $compiler;
    private static $javaScript;
    private static $expressionFactory;
    private static $userMethodFactory;
    private static $tokenizer;
    private static $evaluator;

    public static function createParser(array $variables = []): object
    {
        return new Parser(
            self::ast($variables),
            self::expressionFactory(),
            self::compiler()
        );
    }

    public static function evaluator(): object
    {
        if (isset(self::$evaluator) == false) {
            self::$evaluator = new Evaluator();
        }

        return self::$evaluator;
    }

    public static function tokenFactory(): object
    {
        if (isset(self::$tokenFactory) == false) {
            self::$tokenFactory = new TokenFactory();
        }

        return self::$tokenFactory;
    }

    public static function compiler(): object
    {
        if (isset(self::$compiler) == false) {
            self::$compiler = new CompilerFactory();
        }

        return self::$compiler;
    }

    public static function createAst(): object
    {
        return new AST(
            self::createTokenizer(), 
            self::tokenFactory(), 
            self::tokenStreamFactory(), 
            self::userMethodFactory()
        );
    }

    public static function ast(array $variables): object
    {
        $ast = self::createAst();
        $ast->setVariables($variables);

        return $ast;
    }

    public static function createTokenizer(): object
    {
        if (isset(self::$tokenizer) == false) {
            self::$tokenizer = new Tokenizer(self::javascript(), self::tokenFactory());
        }

        return self::$tokenizer;
    }

    public static function javascript(): object
    {
        if (isset(self::$javaScript) == false) {
            self::$javaScript = new JavaScript();
        }

        return self::$javaScript;
    }

    public static function tokenStreamFactory(): object
    {
        if (isset(self::$tokenStreamFactory) == false) {
            self::$tokenStreamFactory = new TokenStreamFactory();
        }

        return self::$tokenStreamFactory;
    }

    public static function expressionFactory(): object
    {
        if (isset(self::$expressionFactory) == false) {
            self::$expressionFactory = new ExpressionFactory();
        }

        return self::$expressionFactory;
    }

    public static function userMethodFactory(): object
    {
        if (isset(self::$userMethodFactory) == false) {
            self::$userMethodFactory = new CallableUserMethodFactory();
        }

        return self::$userMethodFactory;
    }
}
