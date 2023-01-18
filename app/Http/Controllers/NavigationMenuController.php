<?php
/*
 * DEVELOPED BY DINEURON.COM
 * NeuronCart
 *  */
namespace App\Http\Controllers;


use App\Models\NavigationMenu;


class NavigationMenuController extends CrudController
{

//   private $model;

   private $view_path;

   private $variable = 'nav_menu';


    public function __construct()
    {
        parent::__construct();
        if(request()->route())
        {
            $this->view_path = 'admin.pages.'.request()->route()->getName();
        }
    }


    /**
     * @var array
     */

    private function parameters ()
    {
        $parameters = $this->allColumns(str_replace('Controller','','NavigationMenuController')) ;

        /* For validation rules write here
         For Example:
                 $parameters['title'] = 'required';
        */

        $parameters['title.am'] = 'required';
        $parameters['position'] = 'required';

        return $parameters ;

    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
        return view($this->view_path,['all'=>NavigationMenu::get()]);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view($this->view_path,[$this->variable => new NavigationMenu(),'pageType' => 'create']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {

        NavigationMenu::create($this->validateRequest($this->parameters()));

        return  redirect()
                    ->route($this->variable.'.index')
                        ->with('success','Parameters added successfully')->withInput();

    }

    /**
     * @param NavigationMenu $navigationMenu
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(NavigationMenu $navigationMenu)
    {
        return view($this->view_path,[$this->variable=> $navigationMenu]);
    }

    /**
     * @param NavigationMenu $navigationMenu
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(NavigationMenu $navigationMenu)
    {
        return view($this->view_path,[$this->variable => $navigationMenu]);
    }

    /**
     * @param NavigationMenu $navigationMenu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NavigationMenu $navigationMenu)
    {


        $navigationMenu->update($this->validateRequest($this->parameters()));
        if(request()->has('category_id')){
            $navigationMenu->update(['link' => null]);
        }
        if(request()->has('link')){
            $navigationMenu->update(['category_id' => null]);
        }

        return redirect()
                ->route($this->variable.'.index')
                    ->with('success','Parameters Successful Changes');
    }

    /**
     * @param NavigationMenu $navigationMenu
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(NavigationMenu $navigationMenu)
    {

        if($navigationMenu->parent_id == 'General'){
            NavigationMenu::where('parent_id',$navigationMenu->id)->delete();
        }
        $navigationMenu->delete();


        return  redirect()
                ->route($this->variable.'.index')
                    ->with('massage','Successfully Removed');
    }

}
