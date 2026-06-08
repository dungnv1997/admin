<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
        $this->userRepository = $userRepository;
    }

    // Thêm các phương thức đặc biệt cho UserService nếu cần

    /**
     * Search list.
     *
     * @param array $data
     *
     * @return any
     */
    public function lists($data)
    {
        return $this->userRepository->lists($data);
    }
}
