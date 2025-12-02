<?php

namespace App\Models;

use DateTime;
use Dom\Text;
use App\Models\User;
use App\Models\Market;
use App\Enums\ModelEnum;
use App\Models\OfferTerm;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModelTraits\GlobalFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
        use HasFactory, GlobalFilterTrait;

/**
 * Class Offer
 *
 * @property int $id
 * @property int $product_id
 * @property int $supplier_id
 * @property int $market_id
 * @property int $delivery_time_from
 * @property int $delivery_time_to
 * @property int $lead_time
 * @property string $quality_certification_file
 * @property DateTime $validity_until_date
 * @property int $status
 * @property string $description
 *
 * Note: price_per_unit, min_amount, max_amount are now stored in offerTerms with key 'min_max_amount_price' as JSON array
 *
 * @package App\Models
 */
   protected $guarded = ['id'];

       public $filters = [
        'status',
        'market_id',
        'user_id',
        'product_id',
    ];
    public $filterWithOperatorAndValue = [
        'lead_time',
        'stock',
    ];
     public function customQuery( $query, $request)
    {
                    return $query
        ->when($request->input('productName'), function ($query, $productName) {
            $query->whereHas('product', fn($q) => $q->where('name', 'like', '%' . $productName . '%'));
        })
        
        ;
    }

    public function getQualityCertificationFileUrlAttribute()
    {
        return $this->quality_certification_file ? url('storage/' . $this->quality_certification_file) : null;
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function supplier() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function market() {
        return $this->belongsTo(Market::class);
    }

    public function terms() {
        return $this->hasMany(OfferTerm::class);
    }

    public function marketTerms() {
        return $this->hasMany(MarketTerm::class);
    }

    public function offerTerms() {
        return $this->hasMany(OfferTerm::class);
    }

    public function matches() {
        return $this->hasMany(BidOfferMatch::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class, 'performanceable_id')
            ->where('performanceable_type', ModelEnum::SUPPLIER_OFFER);
    }

    public function changings()
    {
        return $this->hasMany(Changing::class, 'changable_id')->where('changable_type', ModelEnum::SUPPLIER_OFFER);
    }
}
