<?php

namespace ErpNET\Profiting\Calendar\Models;

use App\Models\Model;
use App\Models\Setting\Category;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Media;
use App\Traits\Recurring;
use Bkwld\Cloner\Cloneable;
use Sofa\Eloquence\Eloquence;
use Date;

class Event extends Model
{
    use Cloneable, 
    //Currencies, 
    DateTime, Eloquence, Media, Recurring;

    protected $table = 'milk_productions';

    protected $dates = ['deleted_at', 'start', 'end'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 
        
        'title',
        'start',
        'end',
        
        'vendor_id', 
        'description', 
        'category_id',  
        'reference', 
        'parent_id',
        
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = [
        'start', 
        'end', 
        'category.name', 
        //'account.name',        
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'title',
        'categories.name',
        'vendors.name' ,
        'description'  ,
    ];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['recurring'];


    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category');
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Expense\Vendor');
    }


    /**
     * Convert amount to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = (double) $value;
    }

    /**
     * Convert currency rate to double.
     *
     * @param  string  $value
     * @return void
     
    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }*/

    public static function scopeLatest($query)
    {
        return $query->orderBy('posted_at', 'desc');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getAttachmentAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->last();
    }
    
    /**
     * Define the filter provider globally.
     *
     * @return ModelFilter
     */
    public function modelFilter()
    {
        return \ErpNET\Profiting\Milk\Filters\Productions::class;
    }
    
}
