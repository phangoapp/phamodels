<?php

/**
* BigIntegerfield is a field for big integers values.
*  
*/

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;

class BigIntegerField extends IntegerField {

	function get_type_sql()
	{

		return 'BIGINT('.$this->size.') NOT NULL DEFAULT "0"';

	}

}

?>
