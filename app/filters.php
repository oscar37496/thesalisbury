<?php

/*
 |--------------------------------------------------------------------------
 | Application & Route Filters
 |--------------------------------------------------------------------------
 |
 | Below you will find the "before" and "after" events for the application
 | which may be used to do any work before or after a request into your
 | application. Here you may also register your custom route filters.
 |
 */

App::before(function($request) {
	if (App::environment('local')) {

		/*
		 That it is an implementation of the function money_format for the
		 platforms that do not it bear.

		 The function accepts to same string of format accepts for the
		 original function of the PHP.

		 (Sorry. my writing in English is very bad)

		 The function is tested using PHP 5.1.4 in Windows XP
		 and Apache WebServer.
		 */

		function money_format($format, $number) {
			$regex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' . '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
			if (setlocale(LC_MONETARY, 0) == 'C') {
				setlocale(LC_MONETARY, '');
			}
			$locale = localeconv();
			preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
			foreach ($matches as $fmatch) {
				$value = floatval($number);
				$flags = array('fillchar' => preg_match('/\=(.)/', $fmatch[1], $match) ? $match[1] : ' ', 'nogroup' => preg_match('/\^/', $fmatch[1]) > 0, 'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0] : '+', 'nosimbol' => preg_match('/\!/', $fmatch[1]) > 0, 'isleft' => preg_match('/\-/', $fmatch[1]) > 0);
				$width = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
				$left = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
				$right = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
				$conversion = $fmatch[5];

				$positive = true;
				if ($value < 0) {
					$positive = false;
					$value *= -1;
				}
				$letter = $positive ? 'p' : 'n';

				$prefix = $suffix = $cprefix = $csuffix = $signal = '';

				$signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
				switch (true) {
					case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+' :
						$prefix = $signal;
						break;
					case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+' :
						$suffix = $signal;
						break;
					case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+' :
						$cprefix = $signal;
						break;
					case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+' :
						$csuffix = $signal;
						break;
					case $flags['usesignal'] == '(' :
					case $locale["{$letter}_sign_posn"] == 0 :
						$prefix = '(';
						$suffix = ')';
						break;
				}
				if (!$flags['nosimbol']) {
					$currency = $cprefix . ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . $csuffix;
				} else {
					$currency = '';
				}
				$space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

				$value = number_format($value, $right, $locale['mon_decimal_point'], $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
				$value = @explode($locale['mon_decimal_point'], $value);

				$n = strlen($prefix) + strlen($currency) + strlen($value[0]);
				if ($left > 0 && $left > $n) {
					$value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
				}
				$value = implode($locale['mon_decimal_point'], $value);
				if ($locale["{$letter}_cs_precedes"]) {
					$value = $prefix . $currency . $space . $value . $suffix;
				} else {
					$value = $prefix . $value . $space . $currency . $suffix;
				}
				if ($width > 0) {
					$value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT);
				}

				$format = str_replace($fmatch[0], $value, $format);
			}
			return $format;
		}

	}

});

App::after(function($request, $response) {
	//
});

Route::filter('force.ssl', function() {
	if (!Request::secure()) {
		return Redirect::secure(Request::path());
	}

});

Route::filter('api', function() {
	if (Input::get('key') !== "8f2OfQbh3w4Muhd") {
		return Response::json(array(), 403);
	}
});

Route::filter('auth', function() {
	if (Session::has('id')) {
		$user = User::where('id', Session::get('id')) -> first();
		if (isset($user)) {
			if (!$user['is_activated'])
				return Redirect::to('/#') -> with('error_message', 'Your account has not been activated, please see one of the syndicate to activate your account');
		} else {
			return Redirect::to('/auth/fb');
		}
	} else {
		return Redirect::to('/auth/fb');
	}
});

Route::filter('auth.admin', function() {
	if (Session::has('id')) {
		$user = User::where('id', Session::get('id')) -> first();
		if (isset($user)) {
			if (!$user['is_admin'])
				return Redirect::to('/account/dashboard');
		} else {
			return Redirect::to('/auth/fb');
		}
	} else {
		return Redirect::to('/auth/fb');
	}
});

Route::filter('auth.sysadmin', function() {
	if (Session::has('id')) {
		$user = User::where('id', Session::get('id')) -> first();
		if (isset($user)) {
			if (!$user['is_sysadmin'])
				return Redirect::to('/account/dashboard');
		} else {
			return Redirect::to('/auth/fb');
		}
	} else {
		return Redirect::to('/auth/fb');
	}
});

Route::filter('auth.charles', function() {
	if (Session::has('id')) {
		$user = User::where('id', Session::get('id')) -> first();
		if (isset($user)) {
			if (!$user['is_charles'])
				return Redirect::to('/account/dashboard');
		} else {
			return Redirect::to('/auth/fb');
		}
	} else {
		return Redirect::to('/auth/fb');
	}
});

/*
 |--------------------------------------------------------------------------
 | Authentication Filters
 |--------------------------------------------------------------------------
 |
 | The following filters are used to verify that the user of the current
 | session is logged into this application. The "basic" filter easily
 | integrates HTTP Basic authentication for quick, simple checking.
 |
 */
/*
 Route::filter('auth', function()
 {
 if (Auth::guest())
 {
 if (Request::ajax())
 {
 return Response::make('Unauthorized', 401);
 }
 else
 {
 return Redirect::guest('login');
 }
 }
 });

 Route::filter('auth.basic', function()
 {
 return Auth::basic();
 });*/

/*
 |--------------------------------------------------------------------------
 | Guest Filter
 |--------------------------------------------------------------------------
 |
 | The "guest" filter is the counterpart of the authentication filters as
 | it simply checks that the current user is not logged in. A redirect
 | response will be issued if they are, which you may freely change.
 |
 */

Route::filter('guest', function() {
	if (Auth::check())
		return Redirect::to('/');
});

/*
 |--------------------------------------------------------------------------
 | CSRF Protection Filter
 |--------------------------------------------------------------------------
 |
 | The CSRF filter is responsible for protecting your application against
 | cross-site request forgery attacks. If this special token in a user
 | session does not match the one given in this request, we'll bail.
 |
 */

Route::filter('csrf', function() {
	if (Session::token() !== Input::get('_token')) {
		throw new Illuminate\Session\TokenMismatchException;
	}
});
