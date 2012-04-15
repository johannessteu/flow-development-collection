<?php
namespace TYPO3\Eel;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Eel".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * An evaluator that compiles expressions down to PHP code
 *
 * This simple implementation will lazily parse and evaluate the generated PHP
 * code into a function with a name built from the hashed expression.
 */
class CompilingEvaluator implements EelEvaluatorInterface {

	/**
	 * Evaluate an expression under a given context
	 *
	 * @param string $expression
	 * @param Context
	 * @return mixed
	 */
	public function evaluate($expression, Context $context) {
		$identifier = md5($expression);
		$functionName = 'expression_' . $identifier;

		if (!function_exists($functionName)) {
			$code = $this->generateEvaluatorCode($expression);
			$functionDeclaration = 'function ' . $functionName . '($context){return ' . $code . ';}';
			eval($functionDeclaration);
		}

		$result = $functionName($context);
		if ($result instanceof Context) {
			return $result->unwrap();
		} else {
			return $result;
		}
	}

	/**
	 * Internal generator method
	 *
	 * Used by unit tests to debug generated PHP code.
	 *
	 * @param string $expression
	 * @return string
	 * @throws \Exception
	 */
	protected function generateEvaluatorCode($expression) {
		$parser = new CompilingEelParser($expression);
		$res = $parser->match_Expression();

		if ($parser->pos !== strlen($expression)) {
			throw new Exception(sprintf('Expression "%s" could not be parsed. Error at character %d.', $expression, $parser->pos + 1), 1327682383);
		}

		if (!array_key_exists('code', $res)) {
			throw new Exception(sprintf('Parser error, no code in result %s ', json_encode($res)), 1334491498);
		}
		return $res['code'];
	}

}
?>