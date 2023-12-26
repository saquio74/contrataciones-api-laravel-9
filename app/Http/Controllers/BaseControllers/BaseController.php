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
    protected $entity;

    /**
     * @param T $entity of Model
     * @return T of Model
     */
    public abstract function validateData(Request $request, $entity);

    public abstract function addIncludes();


    public function getAll()
    {
        $className = class_basename($this->entity);
        return $this->addIncludes($this->entity)->where($className . '.deleted_at', '=', null);
    }

    public function GetFiltered(Request $request)
    {
        return $this->validateData($request, $this->getall());
    }

    public function GetPaginateResponse(Request $request)
    {
        return $this->GetFiltered($request)->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }

    /**
     * @param int $id
     * @return T of Model
     */
    public function GetById(int $id)
    {
        return $this->getAll()->where(class_basename($this->entity) . '.id', '=', $id)->first();
    }


    /**
     * @return T of Model
     */
    public function Create(Request $request)
    {
        $entity = $this->toEntity($request);
        $entity = $this->setBase('created', $entity);
        $entity->save();
        return $entity;
    }
    /**
     * @return T of Model
     */
    public function Update(Request $request)
    {
        $entity = $this->toEntity($request);
        $entity = $this->setBase('updated', $entity);
        $entity->save();
        return $entity;
    }

    public function Destroy(int $id): void
    {
        $entity = $this->GetById($id);
        $entity = $this->setBase('deleted', $entity);
        $entity->save();
    }

    /**
     * @return T of Model
     */
    public abstract function toEntity(Request $request);
}
