<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'discount_info' => 'array',
        'safety_items' => 'array',
        'nearest_places' => 'array',
        'rules' => 'array',
    ];

    public function seoable()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            $property->affiliate_slug = Str::random(15);
        });
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'property_id');
    }
    public function futureBookings()
    {
        return $this->hasMany(Booking::class, 'property_id')
            ->where(function ($query) {
                $query->whereIn('status', [1,4])
                ->orWhere('is_external', true);
            });
    }
    public function category()
    {
        return $this->belongsTo(PropertyCategory::class, 'category_id');
    }
    public function pricing()
    {
        return $this->hasOne(Pricing::class, 'property_id');
    }
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
    public function features()
    {
        return $this->hasOne(PropertyFeature::class, 'property_id');
    }
    public function photos()
    {
        return $this->hasOne(PropertyPhotos::class, 'property_id');
    }
    public function availability()
    {
        return $this->hasOne(Availability::class, 'property_id');
    }
    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'type_id');
    }
    public function style()
    {
        return $this->belongsTo(PropertyStyle::class, 'style_id');
    }
    public function allAmenity()
    {
        return $this->hasOne(PropertyAmenity::class, 'property_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'name', 'country');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'name', 'state');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'name', 'city');
    }
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id', 'id');
    }

    public function activites()
    {
        return $this->hasMany(ActivityLog::class, 'property_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'property_id');
    }

    public function getStatusMessageAttribute()
    {
        if ($this->status == 1) {
            return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Approved') . '
                  </span>';
        } elseif ($this->status == 0) {
            return '<span class="badge bg-soft-warning text-warning">
                    <span class="legend-indicator bg-warning"></span>' . trans('In Progress') . '
                  </span>';
        } elseif ($this->status == 2) {
            return '<span class="badge bg-soft-secondary text-secondary">
                    <span class="legend-indicator bg-secondary"></span>' . trans('Re Submission') . '
                  </span>';
        } elseif ($this->status == 3) {
            return '<span class="badge bg-soft-warning text-warning">
                    <span class="legend-indicator bg-warning"></span>' . trans('Hold') . '
                  </span>';
        } elseif ($this->status == 4) {
            return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Soft Rejected') . '
                  </span>';
        } elseif ($this->status == 5) {
            return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Hard Rejected') . '
                  </span>';
        }elseif ($this->status == 6) {
            return '<span class="badge bg-soft-warning text-warning">
                    <span class="legend-indicator bg-warning"></span>' . trans('Pending') . '
                  </span>';
        }
    }

    public function getActivityTitleAttribute()
    {
        $oldActivity = $this->activites->count();
        if ($this->status == 0) {
            return "New Post Submission";
        } elseif (0 < $oldActivity && $this->status == 1) {
            return "Resubmission Trusted Approval";
        } elseif ($this->status == 1) {
            return "Trusted Approval";
        } elseif ($this->status == 2) {
            return "Resubmission";
        } elseif ($this->status == 3) {
            return "Post Hold";
        } elseif (0 < $oldActivity && $this->status == 4) {
            return "Resubmission Soft Rejected";
        } elseif ($this->status == 4) {
            return "Soft Rejected";
        } elseif ($this->status == 5) {
            return 'Hard Rejected';
        }
        return 'Unknown';
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'property_id');
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'property_id')->where('status', 1)->where('review_id', null)->latest();
    }
    public function reviewSummary()
    {
        return $this->hasOne(Review::class, 'property_id')
            ->whereNull('review_id')
            ->selectRaw('property_id, AVG(avg_rating) as average_rating, COUNT(*) as review_count, MAX(id) as id')
            ->groupBy('property_id')
            ->withDefault([
                'average_rating' => 0,
                'review_count' => 0,
            ]);
    }


    public function affiliateClick()
    {
        return $this->hasMany(AffiliateClick::class, 'property_id');
    }

    public function isReviewable(User $user)
    {
        $hasCompletedBooking = $this->bookings()
            ->where('guest_id', $user->id)
            ->whereIn('status', [1, 3])
            ->where('check_in_date', '<=', now())
            ->exists();

        if (!$hasCompletedBooking) {
            return false;
        }

        return true;
    }

    public function chats(){
        return $this->hasMany(Chat::class, 'property_id');
    }

    public function icalSources()
    {
        return $this->hasMany(PropertyIcalSource::class, 'property_id');
    }
}
