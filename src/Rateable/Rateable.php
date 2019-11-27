<?php namespace willvincent\Rateable;

trait Rateable
{
    /**
     * This model has many ratings.
     *
     * @return Rating
     */
    public function ratings()
    {
        return $this->morphMany('willvincent\Rateable\Rating', 'rateable');
    }

    public function userRatings()
    {
        return $this->ratings()->fromCurrentUser();
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function sumRating()
    {
        return $this->ratings()->sum('rating');
    }

    public function userAverageRating()
    {
        return $this->ratings()->fromCurrentUser()->avg('rating');
    }

    public function userSumRating()
    {
        return $this->ratings()->fromCurrentUser()->sum('rating');
    }

    public function userHasRated()
    {
        return $this->ratings()->fromCurrentUser()->count() > 0;
    }

    public function userDeleteRatings()
    {
        return $this->ratings()->fromCurrentUser()->delete();
    }

    public function ratingPercent($max = 5)
    {
        $quantity = $this->ratings()->count();
        $total = $this->sumRating();

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    public function getAverageRatingAttribute()
    {
        return $this->averageRating();
    }

    public function getSumRatingAttribute()
    {
        return $this->sumRating();
    }

    public function getUserAverageRatingAttribute()
    {
        return $this->userAverageRating();
    }

    public function getUserSumRatingAttribute()
    {
        return $this->userSumRating();
    }

    public function addRating($rating)
    {
        $rating = new willvincent\Rateable\Rating;
        $rating->rating = (int) $rating;
        $rating->user_id = \Auth::id();
        $this->ratings()->save($rating);
    }

    public function updateRating($rating)
    {
        if ($this->userHasRated()) {
            $this->userRatings()->first()->update(["rating" => $rating]);
        } else {
            $this->addRating($rating);
        }


    }

}
