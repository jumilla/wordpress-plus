<?php

if (! function_exists('wordpress_path')) {
	function wordpress_path($path) {
		return base_path('wordpress/' . $path);
	}
}

if (! function_exists('logger')) {
	function logger() {
		return app('Psr\Log\LoggerInterface');
	}
}

if (! function_exists('debug_log')) {
	/**
	 * @signature debug_log(mixed $var)
	 * @signature debug_log(string label, mixed $var, array $context = [])
	 */
	function debug_log() {
		if (func_num_args() < 1) {
			throw new InvalidArgumentException('Missing argument');
		}
		else if (func_num_args() == 1) {
			$label = null;
			$var = func_get_arg(0);
			$context = [];
		}
		else if (func_num_args() == 2) {
			$label = func_get_arg(0);
			$var = func_get_arg(1);
			$context = [];
		}
		else if (func_num_args() >= 3) {
			$label = func_get_arg(0);
			$var = func_get_arg(1);
			$context = func_get_arg(2);
		}

		logger()->debug(($label ? "$label: " : '') . print_r($var, true), $context);
	}
}

if (! function_exists('var_log')) {
	/**
	 * @signature var_log(array $vars)
	 * @signature var_log(string $name, mixed $var)
	 */
	function var_log() {
		if (func_num_args() < 1) {
			throw new InvalidArgumentException('Missing argument');
		}
		else if (func_num_args() == 1) {
			$vars = func_get_arg(0);
		}
		else if (func_num_args() == 2) {
			$vars = [func_get_arg(0) => func_get_arg(1)];
		}

		foreach ($vars as $name => $var) {
			logger()->debug("[VAR] $name: " . print_r($var, true), []);
		}
	}
}
