<?php
/**
 * Created by PhpStorm.
 * User: nedron
 * Date: 24.07.15
 * Time: 14:51
 */

namespace Database;


class QueryBuilder
{
	const OP_EQUAL = '=';
	const OP_LESS  = '<';
	const OP_LESS_EQUAL = '<=';
	const OP_GREATER = '>';
	const OP_GREATER_EQUAL = '>=';

	const OP_BOOL_AND = 'AND';
	const OP_BOOL_NOT = 'NOT';
	const OP_BOOL_OR  = 'OR';

	const OP_ACTION_JOIN = 'JOIN';


	public static function build_query($a_query_fragments) {



	}
}