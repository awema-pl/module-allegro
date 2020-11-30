<?php

namespace AwemaPL\Allegro\Sections\Settings\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Allegro\Sections\Settings\Http\Requests\StoreSetting;
use AwemaPL\Allegro\Sections\Settings\Http\Requests\UpdateSetting;
use AwemaPL\Allegro\Sections\Settings\Models\Setting;
use AwemaPL\Allegro\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Allegro\Sections\Settings\Repositories\Contracts\UserRepository;
use AwemaPL\Allegro\Sections\Settings\Resources\EloquentSetting;
use AwemaPL\Allegro\Sections\Installations\Http\Requests\StoreInstallation;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;
use AwemaPL\Permission\Resources\EloquentPermission;
use AwemaPL\Allegro\Sections\Settings\Resources\EloquentUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{

    /**
     * Settings repository instance
     *
     * @var SettingRepository
     */
    protected $settings;

    public function __construct(SettingRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Display settings
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('allegro::sections.settings.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentSetting::collection(
            $this->settings->scope($request)
                ->latest()->smartPaginate()
        );
    }

    /**
     * Update setting
     *
     * @param UpdateSetting $request
     * @param $id
     * @return array
     */
    public function update(UpdateSetting $request, $id)
    {
        $this->settings->update($request->all(), $id);
        return notify(_p('allegro::notifies.settings.success_updated_setting', 'Success updated setting.'));
    }
}
