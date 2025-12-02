<?php
namespace App\Http\Controllers\Api\Supplier\Offer;  
use App\Models\Offer;
use App\Models\Market;
use App\Models\OfferTerm;
use App\Enums\Permissions;
use App\Enums\BasicRoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\SupplierOfferStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\OfferResource;
use App\Services\Share\MarketService;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\OfferExetendRequest;
use App\Services\GlobalService\GlobalService;
use App\Services\Supplier\Offer\OfferService;
use App\Http\Controllers\Global\BaseController;
use App\Services\Supplier\Offer\OfferTermService;
use App\Http\Resources\SupplierStatisticsResource;
use App\Http\Resources\SupplierOfferStatisticsResource;
use App\Http\Requests\CompletecreateOfferStepTowRequest;
use App\Http\Requests\CompletecreateOfferStepfourRequest;
use App\Services\Supplier\Offer\SupplierDashboardservice;
use App\Http\Requests\CompletecreateOfferTermsStepThreeRequest;

class OfferController extends BaseController
{
  protected $globalService;
  protected $OfferService;
  protected $marketservice;

    public function __construct() 
    {
           parent::__construct();
           $this->service                               = new OfferService(new Offer);
           $this->marketservice                         = new MarketService(new Market);
           $this->relations['index']                    = ['performances'];
           $this->relations['show']                     = ['offerTerms', 'performances'];
           $this->relations['update']                   = ['offerTerms', 'performances'];
           $this->formRequest                           =  OfferRequest::class;
           $this->resource                              =  OfferResource::class;
           $this->permissionFunctionName                = 'OfferPermission';
           $this->role                                  = [
                                                            'index' =>[
                                                                BasicRoleEnum::ADMIN->label(), BasicRoleEnum::SUPPLIER->label()
                                                            ],
                                                            'show' =>[
                                                                BasicRoleEnum::ADMIN->label(), BasicRoleEnum::SUPPLIER->label()
                                                            ],
                                                            'store' =>[
                                                                BasicRoleEnum::ADMIN->label(), BasicRoleEnum::SUPPLIER->label()
                                                            ],
                                                            'update' =>[
                                                                BasicRoleEnum::ADMIN->label(), BasicRoleEnum::SUPPLIER->label()
                                                            ],
                                                            'destroy' =>[
                                                                BasicRoleEnum::ADMIN->label(), BasicRoleEnum::SUPPLIER->label()
                                                                ]
                                                          ];
           $this->EnumPermission                        = Permissions::class;
           $this->model                                 = new Offer;
    }
    public function completecreateOfferStepTow(CompletecreateOfferStepTowRequest $request, $id)
    {
                    // Authorization
        $functionName = 'SupplierAll';
        $this->authorizeAction($this->EnumPermission::UPDATE->$functionName(),  [BasicRoleEnum::SUPPLIER->label()]);

        $validatedData = $request->validated();
 
        // $response = $this->service->update($validatedData, $id);
        $response = $this->service->storeOfferTerms($validatedData, $id);

        return $this->apiResponseJson('Data Updated Successfully',$response, Response::HTTP_OK, $this->resource);
    }
    public function completecreateOfferTermsStepThree(CompletecreateOfferTermsStepThreeRequest $request, $id)
    {
                    // Authorization
        $functionName = 'SupplierAll';
        $this->authorizeAction($this->EnumPermission::UPDATE->$functionName(),  [BasicRoleEnum::SUPPLIER->label()]);

        $validatedData = $request->validated();
 
        $response = $this->service->storeOfferTerms($validatedData, $id);

        return $this->apiResponseJson('Data Updated Successfully',$response, Response::HTTP_OK, $this->resource);
    }

    public function completecreateOfferStepfour(CompletecreateOfferStepfourRequest $request, $id)
    {
       return DB::transaction(function () use ($request, $id) {
             // Authorization
             $functionName = $this->permissionFunctionName;
             $this->authorizeAction($this->EnumPermission::UPDATE->$functionName(),  [BasicRoleEnum::SUPPLIER->label()]);
     
             $validatedData = $request->validated();
             $file = $validatedData['quality_certification_file'] ?? null;
             if ($file) {
                 $validatedData['quality_certification_file'] = $this->imageUploadService
                     ->updateFile(
                         $file, 
                         'offer_certified/', 
                         $this->model, 
                         $id,  
                         'quality_certification_file'
                     );
             }
             $response = $this->service->updateLastStep($validatedData, $id);
             
             return $this->apiResponseJson('Data Updated Successfully',$response, Response::HTTP_OK, $this->resource);
        });
       
    }

    public function getProductAndSubCategoryMarkets()
    {
                    // Authorization
        $functionName = $this->permissionFunctionName;
        $this->authorizeAction($functionName,  [BasicRoleEnum::SUPPLIER->label()]);

        $response = $this->marketservice->getProductAndSubCategoryMarkets();

        return $this->apiResponseJson('Data Updated Successfully',[
            'products' =>  ProductResource::collection($response['products']),
            'categories' =>  CategoryResource::collection($response['categories'])
        ], Response::HTTP_OK);
    }

    public function getAuthOffers(Request $request)
    {
        // Authorization
        $functionName = 'SupplierAll';
        $this->authorizeAction($functionName, [BasicRoleEnum::SUPPLIER->label()]);

        $formRequest = app($this->formRequest);

        $perPage = $request->input('perPage');
        $response = $this->service->listOffers($request, ['performances'], $perPage, $request->input('orderColumn'), $request->input('orderDirection'), null);
        return $this->apiResponseJson('Data Get Successfully',$response,  Response::HTTP_OK,  $this->resource);

    }

    public function duplicateOffer($offerId)
    {

        // Authorization
        $functionName = 'SupplierAll';
        $this->authorizeAction($functionName, [BasicRoleEnum::SUPPLIER->label()]);

        $response = DB::transaction(function () use ($offerId) {
            $response = $this->service->duplicateOffer($offerId);

            return $response;
        });
        // $response = $this->service->duplicateOffer($offerId);
        return $this->apiResponseJson('Data Get Successfully',$response,  Response::HTTP_OK,  $this->resource);

    }

    public function puseOffer($offerId)
    {
                    // Authorization
        $functionName = 'SupplierAll';
        $this->authorizeAction($this->EnumPermission::UPDATE->$functionName(),  [BasicRoleEnum::SUPPLIER->label()]);
 
        try {
            $response = $this->service->update(['status' => SupplierOfferStatus::PAUSED->value],  $offerId);
            return $this->apiResponseJson('Data Updated Successfully',$response, Response::HTTP_OK, $this->resource);
        } catch (\Exception $e) {
            return $this->apiResponseJson($e->getMessage(), null, Response::HTTP_BAD_REQUEST);
        }
    }
    public function extend(OfferExetendRequest $request,$offerId)
    {
                    // Authorization
        $functionName = 'SupplierAll';
        $this->authorizeAction($this->EnumPermission::UPDATE->$functionName(),  [BasicRoleEnum::SUPPLIER->label()]);
 
        $validatedData = $request->validated();
        $response = $this->service->update(['validity_until_date' => $validatedData['validity_until_date']],  $offerId);

        return $this->apiResponseJson('Data Updated Successfully',$response, Response::HTTP_OK, $this->resource);
    }

    public function offerStatistics()
    {
                    // Authorization
        $functionName = 'SupplierAll';
        $this->authorizeAction($this->EnumPermission::INDEX->$functionName(),  [BasicRoleEnum::SUPPLIER->label()]);

        $supplierStatistecs = $this->service->getOfferTableStatistics();

        return $this->apiResponseJson('Data Get Successfully',new SupplierOfferStatisticsResource($supplierStatistecs),  Response::HTTP_OK);
    }   

}
