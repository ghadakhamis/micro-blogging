<?php
namespace App\Filters;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TweetFilters extends QueryFilters
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->filters = ['search', 'iFollowing'];
        $this->keys    = ['created_at'];
        $this->request->merge(['iFollowing' => true]);
        parent::__construct($request);
    }
  
    public function search($value) {
        return $this->builder->where(function (Builder $query) use ($value) {
            return $query->where('text', 'LIKE', '%'.$value.'%')
                ->orWhereHas('user', function (Builder $query) use ($value) {
                    $query->where('username', 'LIKE', '%'.$value.'%');
                });
        });
    }

    public function iFollowing()
    {
        $this->builder->whereHas(
            'user', function (Builder $query) {
                $query->whereHas('followers', function (Builder $query) {
                    $query->where('user_id', Auth::id());
                });
            }
        );
    }
}