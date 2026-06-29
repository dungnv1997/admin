<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param mixed $query
     * @param mixed $column
     * @param mixed $data
     *
     * @return Query
     */
    public function search($query, $column, $data)
    {

        switch ($column) {
            case 'id':
            case 'agency_id':
            case 'email':
            case 'name':
                return $query->where($column, $data);
                break;

            default:
                return $query;
                break;
        }
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function updateToken(User $user)
    {
        // dd($user);
        $user->api_token = $user->createToken($user->email)->accessToken;
    }
}
