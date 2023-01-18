<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends CrudController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
        $settings = Settings::get();

        $payment_settings = PaymentSetting::get();

        return  view('admin.pages.settings.index',compact('settings','payment_settings'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request ,$type)
    {

        switch ($type) {
            case 'logo':
                $this->storeLogo($request);
            break;
            case 'social':
                $this->storeSocial($request);
                break;
            case 'payment':
                $this->storePayment($request);
                break;
            case 'mail':
                $this->storeMail($request);
                break;
            case 'integration':
                $this->storeIntegration($request);
                break;
            case 'policy':
                $this->storePolicy($request);
                break;
            default:return abort(404);
        }

        return back()->with('success','Parameters added successfully');
    }

    /**
     * @param $request
     */

    public function storeLogo($request)
    {
        foreach($request->except('_token') as $key => $value)
        {

            if ($request->hasFile($key.'_img'))
            {
                $row =  Settings::where('id',$value);

                $this->deleteFile($row->first()->value,$key.'_img');

                $row->update([
                    'value'=>'/storage/'.$request[$key.'_img']->store('/logo','public')
                ]);
            }
        }

    }

    /**
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSocial($request)
    {

        $socials = Settings::where('group','social_media')->get();
            foreach ($socials as $social)
            {

                if($request->has('status_'.$social->key)){

                    if($request['check_type_'.$social->id] == 'image')
                    {
                        $value = $social->value;

                        if($request->has('image_'.$social->key)) {

                        $value = '/storage/'.$request['image_'.$social->key]->store('/social','public');
                        }

                    }
                    else{
                        $value = $request['fa_icon_'.$social->key];
                    }

                    Settings::where('key',$social->key)->where('group',$social->group)->update([
                        'status' => $request['status_'.$social->key],
                        'link' => $request['link_'.$social->key],
                        'value' => $value,
                    ]);
                }
                else {
                    Settings::where('key',$social->key)->where('group',$social->group)->update([
                        'status' => null,

                    ]);
                }
            }

    }

    /**
     * @param $request
     */
    public function  storePayment($request)
    {

        $row = PaymentSetting::where('key',$request->method);

        $row ->update([
            'credentials' => $request->credentials,
            'status' => $request->status,
            'is_test' => $request->mode,
        ]);
        if(!$request->has('status')){
            $row->update(['status'=>0]);
        }
        if(!$request->has('mode')){
            $row->update(['is_test'=>0]);
        }
        if($request->has('logo')) {
            $this->storeFile($row,'logo','/payment');
        }
    }

    /**
     * @param $request
     */
    public function storeIntegration($request) {
        foreach ($request->integration  as $key => $option){
            Settings::where('key',$key)->update(['value'=>$option]);
        }
    }

    /**
     * @param $request
     */
    public function  storeMail($request)
    {
        foreach ($request->mail_options  as $key => $option){
            Settings::where('key',$key)->update(['value'=>$option]);
        }
    }

    /**
     * @param $request
     */
    public function  storePolicy($request)
    {
        foreach ($request->privacy_policy  as $key => $option){
            Settings::where('key',$key)->update(['value'=>$option]);
        }
    }

}
