<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdate;
use App\Repositories\Contracts\UserRepositoryContract;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private  UserRepositoryContract $repository;

    public function __construct(UserRepositoryContract $repository)
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
        $nameSearch = $request->get("name", "") ?? "";
        $users = $this->repository->paginateWithSearch(5, "name", $nameSearch);

        return response()->json(compact("users"));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->repository->findOrFail($id);
        $url = env("APP_URL");

        return response()->json(compact("user", "url"));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdate $request, $id)
    {
        try {
            $user = $this->repository->findOrFail($id);

            if (!$update = $this->repository->update($id, $request->getUserData($user))) {
                throw new Exception($update);
            }
            return response()->json(compact("user"));
        } catch (\Throwable $th) {
            return $this->redirectWithErrors($th, __("user.error.update"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (!$destroy = $this->repository->delete($id)) {
                throw new Exception($destroy);
            }
            return response('', 204)->send();
        } catch (\Throwable $th) {
            return $this->redirectWithErrors($th, __("user.error.destroy"));
        }
    }
}
