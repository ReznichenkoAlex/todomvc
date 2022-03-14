<?php

namespace App\Base\View;

interface ViewInterface
{
	const TEMPLATE_PATH = PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . 'app/View';

	public function render(string $tpl): string;
}
