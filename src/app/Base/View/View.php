<?php

namespace App\Base\View;

class View implements ViewInterface
{
	public function render($tpl) : string
	{
		ob_start();

		include self::TEMPLATE_PATH . DIRECTORY_SEPARATOR . $tpl;
		return ob_get_clean();
	}
}
