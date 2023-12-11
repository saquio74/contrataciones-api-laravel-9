<?php

namespace App\Http\Controllers\BaseControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;

/**
 * @template T of Entity
 * @template-implements Collection<T>
 */
abstract class BaseController extends Controller
{
    /**
     * @var T of Model
     */
    public $entity;

    /**
     * @param T $entity of Model
     * @return T of Model
     */
    public abstract function validateData(Request $request, $entity);

    public abstract function addIncludes();


    public function getAll()
    {

        $this->addIncludes();
        $className = class_basename($this->entity);
        return $this->entity->where($className . '.deleted_at', '=', null);
    }

    public function GetFiltered(Request $request)
    {
        return $this->validateData($request, $this->getall());
    }

    public function GetPaginateResponse(Request $request)
    {
        return $this->GetFiltered($request)->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }
}
