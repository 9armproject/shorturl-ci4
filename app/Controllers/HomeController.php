<?php

namespace App\Controllers;

class HomeController extends BaseController
{

	public function __construct()
	{
	}

	public function index()
	{
		$data = [
			'page_title' => 'Short URL'
		];
		return view('home', $data);
	}

	public function statistics($path = '')
	{

		$data = [
			'page_title' => 'Statistics',
			'path' => $path
		];
		return view('statistics', $data);
	}
}
