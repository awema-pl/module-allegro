<?php

namespace AwemaPL\Allegro\Sections\Applications\Http\Controllers;

use AwemaPL\Allegro\Sections\Applications\Models\Application;
use AwemaPL\Allegro\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Allegro\Sections\Applications\Http\Requests\StoreApplication;
use AwemaPL\Allegro\Sections\Applications\Http\Requests\UpdateApplication;
use AwemaPL\Allegro\Sections\Applications\Repositories\Contracts\ApplicationRepository;
use AwemaPL\Allegro\Sections\Applications\Resources\EloquentApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApplicationController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /**
     * Applications repository instance
     *
     * @var ApplicationRepository
     */
    protected $applications;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(ApplicationRepository $applications, SettingRepository $settings)
    {
        $this->applications = $applications;
        $this->settings = $settings;
    }

    /**
     * Display applications
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('allegro::sections.applications.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentApplication::collection(
            $this->applications->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create application
     *
     * @param StoreApplication $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreApplication $request)
    {
          $this->applications->create($request->all());
        return notify(_p('allegro::notifies.application.success_created_application', 'Success created application.'));
    }

    /**
     * Update application
     *
     * @param UpdateApplication $request
     * @param $id
     * @return array
     */
    public function update(UpdateApplication $request, $id)
    {
        $this->authorize('isOwner', Application::find($id));
        $this->applications->update($request->all(), $id);
        return notify(_p('allegro::notifies.application.success_updated_application', 'Success updated application.'));
    }

    /**
     * Delete application
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Application::find($id));
        $this->applications->delete($id);
        return notify(_p('allegro::notifies.application.success_deleted_application', 'Success deleted application.'));
    }

}
