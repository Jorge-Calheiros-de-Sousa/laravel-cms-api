<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreRequest;
use App\Repositories\Contracts\CategoryRepositoryContract;
use Exception;
use Illuminate\Http\Request;
use Str;

class CategoryController extends Controller
{
    private $repository;

    public function __construct(CategoryRepositoryContract $respository)
    {
        $this->repository = $respository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository->list(false);

        return view("pages.category.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $config = [
            "onlyEdit" => false,
            "title" => __("category.text.title.category"),
            "method" => "POST",
            "route" => route("category.store")
        ];

        return view("pages.category.form", compact("config"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->except("token");
            $data["slug"] = Str::slug($data["title"]);

            if (!$this->repository->create($data)) {
                throw new Exception($data);
            }

            return redirect(route("category.index"))->with([
                "success-message" => __("category.success.store")
            ]);
        } catch (\Throwable $th) {
            return $this->redirectWithErrors($th, __("category.error.store"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
