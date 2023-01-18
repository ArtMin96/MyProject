<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Role\UserRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected function validator(array $data,$id): array
    {
        $array = [
            'name' => ['required', 'string', 'max:255'],
        ];

        isset($data['password']) ? $array['password'] = ['required', 'string', 'min:8','confirmed']  : null;
        key_exists('email',$data) ? $array['email'] = 'required|max:255|unique:users,email,'.$id  : null;
        key_exists('phone',$data) and $data['phone']  ? $array['phone'] = 'required|max:255|unique:users,phone,'.$id  : null;
        $messages = [
            'email.unique' =>__('messages.wrong_email'),
            'phone.unique' =>__('messages.wrong_phone'),
            'confirmed' =>__('messages.password_confirm'),
            'min' =>__('messages.password_min'),
        ];
        return Validator::make($data,$array,$messages)->validate();
    }



    private function update ($id,$type = false): \Illuminate\Http\RedirectResponse
    {

        $this->validator(request()->all(),$id);

        $user = User::findOrFail($id);

        DB::beginTransaction();

        try {

            $data = [
                'name' => request('name'),
                'last_name' => request('last_name'),
                'accept_notification' => key_exists('accept_notification', request()->all()) ? request('accept_notification') : null,
                'additional' => key_exists('additional', request()->all()) ? request('additional') : null,
            ];


            if($type){
                if(auth()->user()->hasRole('ROLE_SUPER_ADMIN') and count(array_diff( request('roles'),(array)UserRole::getAllRoles(['super_admin'])))==0 ){
                    $data['roles'] = request('roles');
                }
                elseif(auth()->user()->hasRole('ROLE_ADMIN') and count(array_diff( request('roles'),(array)UserRole::getAllRoles(['super_admin','admin'])))==0 ){
                    $data['roles'] = request('roles');

                }else{
                    return redirect()->back()->with('message',"Իզուր մի բզբզա քեզ վնաս կտաս");
                }

            }
            if (request()->has('email'))  $data['email'] = request('email');

            if (request()->has('phone')) $data['phone'] =str_replace(['-',' ','+(374)'],'',request('phone'));

           $user->update($data);

           if(!$type or ($type and auth()->user()->hasRole('ROLE_SUPER_ADMIN') )){
               if (request()->has('password') and request('password') != '') {
                   if (request('password') == request('password_confirmation')) {
                       $user->update([
                           'password' => Hash::make(request('password')),
                       ]);
                   } else {
                       return redirect()->back()->with('message', __('messages.password_confirm'));
                   }
               }
           }

            DB::commit();
            return redirect()->back()->with('success', __('messages.user_update_success'));

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('massage', 'Please check all parameters');
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser (): \Illuminate\Http\RedirectResponse
    {
        $id = auth()->user()->id;

        return $this->update($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function changeUser($id): \Illuminate\Http\RedirectResponse
    {
        return $this->update($id,true);
    }





}
