<?php

class ContributorsController extends BaseController {

	public function showContributorsPage()
	{
        return View::make('contributors.home');
	}
}