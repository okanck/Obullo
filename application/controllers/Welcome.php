<?php

use Http\Controller;

class Welcome extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load('views::welcome');
    }
}