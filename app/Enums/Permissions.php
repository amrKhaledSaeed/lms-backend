<?php
namespace App\Enums;


enum Permissions: string
{
    /**
     * admin permission must be start with admin_
     * supplier permission must be start with supplier_
     * buyer permission must be start with buyer_
     */
    // Permission actions
    case INDEX          = 'index';
    case SHOW           = 'show';
    case STORE          = 'store';
    case UPDATE         = 'update';
    case DESTROY        = 'destroy';

    // Permission keys
    private const ROLE_PERMISSON                                                = 'role';
    private const COMPANY_PROFILE_PERMISSON                                     = 'company_profile';
    private const CATEGORY_PERMISSON                                            = 'category';
    private const SUB_CATEGORY_PERMISSON                                        = 'sub_category';
    private const PRODUCT_PERMISSON                                             = 'Product';
    private const MARKET_PERMISSON                                              = 'Market';
    private const MARKET_TERM_PERMISSON                                         = 'MarketTerm';
    private const USER_PERMISSON                                                = 'user';
    private const OFFER_PERMISSON                                               = 'offer';
    private const BID_PERMISSON                                                 = 'bid';
    private const PACKAGING_PERMISSON                                           = 'packaging';
    private const QUALITY_CERTIFICATION_PERMISSON                               = 'quality_certification';
    private const ORDER_PERMISSON                                               = 'order';
    private const ESCROW_PERMISSON                                               = 'escrow';
    private const BUSENESS_TYPE_PERMISSON                                        = 'buseness_type';

    //custom
    private const ASSIGN_PERMISSION_TO_ROLE                                     = 'assign_permission_to_role';
    private const ASSIGN_ROLE_TO_USER                                           = 'assign_role_to_user';
    private const ASSIGN_PERMISSON_TO_USER                                      = 'assign_permission_to_user';
                            
    private const GET_USER_PERMISSONS                                           = 'all_get_user_permissions';
    private const GET_ALL_PERMISSONS                                            = 'get_all_permissions';
    private const COMPANY_PROFILE_CHANGE_KYC_KYB_STATUS_PERMISSONS              = 'company_profile_change_kyc_kyb_status';
    private const AUTH_USER_PERMISSONS                                          = 'all_auth_user'; //for all
    private const SUPPLIER_ALL_PERMISSONS                                       = 'supplier_all'; //for all
    private const BUYER_ALL_PERMISSONS                                          = 'buyer_all'; //for all

    private const FUNCTIONSCONROLLERNAME = [
        'RolePermission',
        'CompanyProfilePermission',
        'CategoryPermission',
        'SubCategoryPermission',
        'ProductPermission',
        'MarketPermission',
        'MarketTermPermission',
        'UserPermission',
        'OfferPermission',
        'BidPermission',
        'PackagingPermission',
        'QualityCertificationPermission',
        'OrderPermission',
        'EscrowPermission',
        //custom
        'assignPermissionsToRole',
        'assignRoleToUser',
        'assignPermissionsToUser',
        'getUserPermissions',
        'getAllPermissions',
        'companyProfileChangeKycKybStatus',
        'showAuthUser',
        'SupplierAll',
        'BuyerAll',
    ];

    // General permission handler
    private function getPermissionKey(string $key): string
    {
        return match ($this) {
            self::INDEX,
            self::SHOW,
            self::STORE,
            self::UPDATE,
            self::DESTROY
            => $this->value . '_' . $key,
        };
    }

    public static function getAllFunctionsControllerName(): array
    {
        return self::FUNCTIONSCONROLLERNAME;
    }

    public function RolePermission(): string
    {
        return $this->getPermissionKey(self::ROLE_PERMISSON);
    }
    public function CompanyProfilePermission(): string
    {
        return $this->getPermissionKey(self::COMPANY_PROFILE_PERMISSON);
    }

    public function CategoryPermission(): string
    {
        return $this->getPermissionKey(self::CATEGORY_PERMISSON);
    }

    public function SubCategoryPermission(): string
    {
        return $this->getPermissionKey(self::SUB_CATEGORY_PERMISSON);
    }

    public function ProductPermission(): string
    {
        return $this->getPermissionKey(self::PRODUCT_PERMISSON);
    }
    public function MarketPermission(): string
    {
        return $this->getPermissionKey(self::MARKET_PERMISSON);
    }

    public function MarketTermPermission(): string
    {
        return $this->getPermissionKey(self::MARKET_TERM_PERMISSON);
    }
    
    public function UserPermission(): string
    {
        return $this->getPermissionKey(self::USER_PERMISSON);
    }
    public function OfferPermission(): string
    {
        return $this->getPermissionKey(self::OFFER_PERMISSON);
    }
    
    public function BidPermission(): string
    {
        return $this->getPermissionKey(self::BID_PERMISSON);
    }
    
    public function PackagingPermission(): string
    {
        return $this->getPermissionKey(self::PACKAGING_PERMISSON);
    }
    
    public function QualityCertificationPermission(): string
    {
        return $this->getPermissionKey(self::QUALITY_CERTIFICATION_PERMISSON);
    }

    public function OrderPermission(): string
    {
        return $this->getPermissionKey(self::ORDER_PERMISSON);
    }
    public function EscrowPermission(): string
    {
        return $this->getPermissionKey(self::ESCROW_PERMISSON);
    }
    public function BusenessTypePermission(): string
    {
        return $this->getPermissionKey(self::BUSENESS_TYPE_PERMISSON);
    }





    //custom function
    public function assignPermissionsToRole()
    {
        return self::ASSIGN_PERMISSION_TO_ROLE;
    }

    public function assignRoleToUser()
    {
        return self::ASSIGN_ROLE_TO_USER;
    }

    public function assignPermissionsToUser()
    {
        return self::ASSIGN_PERMISSON_TO_USER;
    }

    public function getUserPermissions()
    {
        return self::GET_USER_PERMISSONS;
    }

    public function getAllPermissions()
    {
        return self::GET_ALL_PERMISSONS;
    }

    public function companyProfileChangeKycKybStatus()
    {
        return self::COMPANY_PROFILE_CHANGE_KYC_KYB_STATUS_PERMISSONS;
    }
    public function showAuthUser()
    {
        return self::AUTH_USER_PERMISSONS;
    }
    public function updateAuthUser()
    {
        return self::AUTH_USER_PERMISSONS;
    }
    public function updateAuthprofile()
    {
        return self::AUTH_USER_PERMISSONS;
    }
    public function SupplierAll()
    {
        return self::SUPPLIER_ALL_PERMISSONS;
    }
    
    public function BuyerAll()
    {
        return self::BUYER_ALL_PERMISSONS;
    }

}

