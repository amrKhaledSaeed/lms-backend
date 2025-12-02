<?php
namespace App\Http\Controllers\Global;

use App\Enums\BasicRoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\AuthorizeTypeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Trait\ResponseHandellerTrait;
use App\Services\GlobalService\ImageUploadService;
use Knuckles\Scribe\Attributes\Group;
class BaseController extends Controller
{
    use ResponseHandellerTrait;
    public $relations;
    public $service;
    public $formRequest;
    public $resource;

    protected $permissionFunctionName;
    protected $role;
    protected $EnumPermission;
    protected $imageUploadService;
    protected $model;
    protected $files = []; // Array of file fields in db to handle

    public function __construct(){
        $this->imageUploadService = new ImageUploadService; 
    }

    /**
     * Get List of Resources
     * examble of filter pattern
     * filter:1; 
     * preferred_price[operator]:>; 
     *preferred_price[value]:160; 
     *quantity[operator]:>=; 
     *quantity[value]:300; 
     *is_expiring_soon:0; 
     *perPage:3;  : to generate pagination 3collection in one page
     *
     */
    public function index(Request $request)
    {
        // Authorization
        $functionName = $this->permissionFunctionName;
        $this->authorizeAction($this->EnumPermission::INDEX->$functionName(), $this->role['index']);

        $formRequest = app($this->formRequest);

        $perPage = $request->input('perPage');
        $response = $this->service->listData($request, $this->relations['index'], $perPage, $request->input('orderColumn'), $request->input('orderDirection'), null);

        return $this->apiResponseJson('Data Get Successfully',$response,  Response::HTTP_OK,  $this->resource);

    }

    /**
     * Create Resource
     * 
     * 
     */
    public function store(Request $request)
    {
                // Authorization
        $functionName = $this->permissionFunctionName;
        $this->authorizeAction($this->EnumPermission::STORE->$functionName(), $this->role['store']);

        $formRequest = app($this->formRequest);

        $validatedData = $formRequest->validated();
        foreach($this->files as $file){
            if ($request->hasFile( $file['file'])) {
                $filecolumnName =$file['file'];
                $folderName =$file['folder'];
                    $validatedData[$filecolumnName] = $this->imageUploadService
                            ->upload(
                                $request->$filecolumnName, $folderName
                                    );
            }
        }
        $response = $this->service->create($validatedData);

        return $this->apiResponseJson('Data Created Successfully',$response, Response::HTTP_CREATED, $this->resource);
    }

    /**
     * Get Resource Details
     * 
     * Retrieve detailed information about a specific resource by ID.
     * This is a generic endpoint that should be documented in child controllers
     * with specific details about the resource being retrieved.
     */
    public function show(int $id)
    {
                              // Authorization
        $functionName = $this->permissionFunctionName;
        $this->authorizeAction($this->EnumPermission::SHOW->$functionName(), $this->role['show']);

        $response = $this->service->show($id, $this->relations['show']);

        return $this->apiResponseJson('Data Show Successfully',$response, Response::HTTP_OK,  $this->resource);
    }

    /**
     * Update Resource
     * 
     * Update an existing resource. This is a generic endpoint that should be documented
     * in child controllers with specific details about the resource being updated.
     */
    public function update(Request $request, int $id)
    {
            // Authorization
        $functionName = $this->permissionFunctionName;
        $this->authorizeAction($this->EnumPermission::UPDATE->$functionName(), $this->role['update']);

        $formRequest = app($this->formRequest);
        $validatedData = $formRequest->validated();
        foreach($this->files as $file){
            $filecolumnName =$file['file'];
            $folderName     =$file['folder'];
            if ($request->hasFile( $filecolumnName)) {
                    $validatedData[$filecolumnName] = $this->imageUploadService
                        ->updateFile(
                    $request->$filecolumnName, $folderName, $this->model, $id,  $filecolumnName
                        );
            }
        }
 
        $response = $this->service->update($validatedData, $id, $this->relations['update']);

        return $this->apiResponseJson('Data Updated Successfully',$response, Response::HTTP_OK, $this->resource);
    }

    /**
     * Delete Resource
     * 
     * Delete a resource by ID. This is a generic endpoint that should be documented
     * in child controllers with specific details about the resource being deleted.
     * This action is permanent and cannot be undone.
     */
    public function destroy(int $id)
    {
                            // Authorization
        $functionName = $this->permissionFunctionName;
        $this->authorizeAction($this->EnumPermission::DESTROY->$functionName(), $this->role['destroy']);

        $response = $this->service->delete($id);

        return $this->apiResponseJson('Data Deleted Successfully',$response, Response::HTTP_OK);
    }

public function authorizeAction(string $ability, array $roles = ['admin']): void
{
    try {
        if (authorizeWayCheck() == AuthorizeTypeEnum::BY_PERMISSION->value) {
            Gate::authorize($ability);
        } elseif (authorizeWayCheck() == AuthorizeTypeEnum::BY_ROLE->value) {
            if (!auth()->user()->hasAnyRole($roles)) {
                throw new \Exception('You do not have the required role.');
            }
        }
    } catch (\Throwable $e) {
       $this->apiResponseJson('Unauthorized',[], Response::HTTP_FORBIDDEN)->send();
        exit; 
    }
}

}
