<?php

namespace App\Http\Controllers;

use App\Helpers\DataHelper;
use App\Http\Requests\CustomRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;

class CrudController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $model = null;

    protected $modelResource = null;

    protected $updateDataRequest = null;

    protected $request;

    public function __construct($model = null, $modelResource = null, $updateDataRequest = null)
    {
        $this->model = $model;
        $this->modelResource = $modelResource;
        $this->updateDataRequest = $updateDataRequest;
    }

    public function index()
    {
        $data = $this->model->all();
        return call_user_func([$this->modelResource, 'collection'], $data);
    }

    public function store(CustomRequest $request)
    {
        return self::createOrUpdate($request, $this->model, $this->modelResource, $this->updateDataRequest);
    }

    public function update(CustomRequest $request)
    {
        return self::createOrUpdate($request, $this->model, $this->modelResource, $this->updateDataRequest);
    }


    public function show($detailId)
    {
        $primaryKey = $this->model->getKeyName();
        $queryData = $this->model->where($primaryKey, $detailId)->first();
        if (!$queryData) return [];
        return new $this->modelResource($queryData);
    }

    public function destroy($detailId)
    {
        $primaryKey = $this->model->getKeyName();
        $queryData = $this->model->where($primaryKey, $detailId)->first();
        if (!$queryData) return false;
        $queryData->delete();
        return new $this->modelResource($queryData);
    }

    public static function createOrUpdate($request, $model, $modelResource, $updateDataRequest)
    {
        $request = $request->convertRequest($updateDataRequest);
        $validator = $request['validator'];
        $request = $request['request'];

        $errors = $validator->errors()->all();
        if (is_array($errors) && count($errors) > 0) {
            $res = [];
            $res['error']  = $validator->errors()->all();
            throw new HttpResponseException(new Response(json_encode($res)), 422);
        }

        $postValidated = $request->validated();
        $primaryKey = $model->getKeyName();
        $primaryKeyValue = DataHelper::safeGet($postValidated, $primaryKey, null);

        $savedData = $model->updateOrCreate([
            $primaryKey => $primaryKeyValue
        ], $postValidated);

        return new $modelResource($savedData);
    }
}
