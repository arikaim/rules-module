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

use nicoSWD\Rule\TokenStream\Token\TokenType;
use Arikaim\Modules\Rules\RuleEngine;

/**
 *  Rule class
 */
class Rule 
{
    /**
     * Tailwind css classes
     * @var array
     */
    private static $cssClasses = [
        TokenType::COMMENT        => 'text-gray-400',
        TokenType::LOGICAL        => 'text-blue-500 font-bold',
        TokenType::OPERATOR       => 'text-green-500 font-bold',
        TokenType::PARENTHESIS    => 'text-green-400',
        TokenType::SPACE          => '',
        TokenType::UNKNOWN        => '',
        TokenType::VALUE          => 'text-blue-500',
        TokenType::VARIABLE       => 'text-gray-900 font-bold',
        TokenType::METHOD         => 'text-red-500',
        TokenType::SQUARE_BRACKET => '',
        TokenType::COMMA          => '',
        TokenType::FUNCTION       => '',
        TokenType::INT_VALUE      => 'text-gray-400 font-bold',
    ];

    /**
     * Highlight rule code
     * 
     * @param string $ruleCode
     * @return string
     */
    public static function highlight(string $ruleCode): string
    {       
        $ruleCode = \html_entity_decode($ruleCode);
        $tokenizer = RuleEngine::createTokenizer();
        $tokens = $tokenizer->tokenize($ruleCode);
        $code = '';

        foreach ($tokens as $token) {
            $cssClass = Self::$cssClasses[$token->getType()];
            $value = \htmlentities($token->getOriginalValue(),ENT_QUOTES,'utf-8');
          
            if (empty($style) == false) {
                $code .= '<span class="' . $cssClass . '">' . $value . '</span>';
            } else {
                $code .= $value;
            }
        }

        return $code;
    }

    /**
     * Get vars from rule text
     * 
     * @param string $rule
     * @return array
     */
    public static function getVariables(string $rule): array 
    {
        $tokenizer = RuleEngine::createTokenizer();
        $tokens = $tokenizer->tokenize($rule);
        
        $variables = [];
        foreach ($tokens as $token) {
            if (TokenType::VARIABLE == $token->getType() ) {
                $variables[] = $token->getValue();
            };
        }

       return $variables;
    }

    /**
     * Merge rule vars
     * 
     * @param string $rule
     * @param array $variables
     * @return array
     */
    public static function mergeRuleVariables(string $rule, array $variables = []): array
    {
        $vars = \array_values(Self::getVariables($rule));
        $vars = \array_combine($vars,\array_fill(0,count($vars),null));

        return \array_merge($vars,$variables);
    }

    /**
     * Check if rule is true
     * 
     * @param string $rule
     * @param array $variables
     * @return bool
     */
    public static function isTrue(string $rule, array $variables = []): bool
    {
        $variables = Self::mergeRuleVariables($rule,$variables);

        return RuleEngine::evaluator()->evaluate(Self::parse($rule,$variables));
    }

    /**
     * Parse rule
     * 
     * @param string $rule
     * @param array $variables
     * @return string
     */
    public static function parse(string $rule, array $variables = []): string
    {
        return RuleEngine::createParser($variables)->parse($rule);
    }
}
