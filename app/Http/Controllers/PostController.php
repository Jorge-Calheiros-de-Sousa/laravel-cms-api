<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostStore;
use App\Http\Requests\Post\PostUpdate;
use App\Repositories\Contracts\CategoryRepositoryContract;
use App\Repositories\Contracts\PostRepositoryContract;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    private PostRepositoryContract $repository;

    public function __construct(PostRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $titleSearch = $request->get("s", "") ?? "";
        $page = $request->get("page", 1) ?? 1;
        $cat = $request->get("cat", "") ?? "";

        $categoryRepository = app(CategoryRepositoryContract::class);
        $categories = $categoryRepository->list(true);

        $posts = $this->repository->postPaginateWithSearch(5, $page, "title", $titleSearch, $cat);

        return response()->json(compact("posts"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStore $request)
    {
        try {
            $data = $request->except(["_token", "category"]);
            $data["user_id"] = auth()->user()->id;

            if (!$post = $this->repository->create($data)) {
                throw new Exception($post);
            }


            return response()->json(compact("post"), 202);
        } catch (\Throwable $th) {
            return $this->redirectWithErrors($th, __("post.error.store"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$post = Cache::tags("post.show")->get("post.show:$id")) {
            $post = $this->repository->findOrFail($id);
            Cache::tags("post.show")->put("post.show:$id", $post);
        }
        $url = env("APP_URL");

        return response()->json(compact("post", "url"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdate $request, $id)
    {
        try {
            $data = $request->except(["_token", "_method"]);
            $data["user_id"] = auth()->user()->id;

            if (!$post = $this->repository->update($id, $data)) {
                throw new Exception($post);
            }

            //Cache::tags(["posts"])->flush();

            return response()->json(compact("post"));
        } catch (\Throwable $th) {
            return $this->redirectWithErrors($th, __("post.error.update"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (!$destroy = $this->repository->delete($id)) {
                throw new Exception($destroy);
            }

            //Cache::tags(["posts"])->flush();
            return response('', 204)->send();
        } catch (\Throwable $th) {
            return $this->redirectWithErrors($th, __("post.error.destroy"));
        }
    }
}
