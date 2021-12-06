<?php

namespace AwemaPL\Allegro\Admin\Sections\Installations\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Allegro\Facades\Allegro;
use AwemaPL\Allegro\Admin\Sections\Installations\Http\Requests\StoreInstallation;
use Illuminate\Routing\Controller;

class InstallationController extends Controller
{

    use RedirectsTo;

    /**
     * Where to redirect after installation.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Display installation form
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('allegro::admin.sections.installations.index');
    }

    /**
     * Store setting installation.
     *
     * @param  StoreInstallation  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreInstallation $request)
    {
        Allegro::install($request->all());
        return $this->ajaxRedirectTo($request);
    }
}
