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

use Arikaim\Extensions\Actions\Rules\RuleEngine;
use nicoSWD\Rule\TokenStream\Token\TokenType;


class Rule 
{
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


    public static function compile(string $rule)
    {
        $parse = RuleEngine::createParser([])->parse($rule);
        return $parse;
    }
}
