<?php

namespace Domains\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Domains\Supports\Exceptions\DBException;
use DB;
use Exception;

class UserModel extends Model
{
    use \Domains\Supports\Models\Filter;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    public const STATUS_ACTIVE   = 1;
    public const STATUS_INACTIVE = 0;

    /**
     * get users
     * 
     * @param  Array $selectedColumns
     * @return Object
     */
    public function getUsers($selectedColumns = []) {
        if (empty($selectedColumns)) {
            $selectedColumns = ['id', 'name', 'status'];
        }
        $query = DB::table($this->table)
            ->whereNull('deleted_at')
            ->select($selectedColumns);

        return $this->filter($query, ['name', 'status']);
    }

    /**
     * get user info
     * 
     * @param  Integer $userId
     * @return Object
     */
    public function getUserById($userId, $selectedColumns = []) {
        if (empty($selectedColumns)) {
            $selectedColumns = ['id', 'name', 'status', 'created_at', 'updated_at'];
        }
        return DB::table($this->table)
            ->where('id', $userId)
            ->whereNull('deleted_at')
            ->select($selectedColumns)
            ->first();
    }

    /**
     * create user
     * 
     * @param  Array $user
     * @return Array
     */
    public function createUser($user) {
        try {
            DB::beginTransaction();
            $user['id'] = DB::table($this->table)->insertGetId($user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new DBException($e);
        }

        return $user;
    }

    /**
     * update user
     * 
     * @param  Integer $userId
     * @param  Object $user
     * @return Boolean
     */
    public function updateUser($userId, $user) {
        try {
            DB::beginTransaction();
            DB::table($this->table)->where('id', $userId)->update($user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new DBException($e);
        }

        return true;
    }

    /**
     * delete user
     * 
     * @param  Integer $userId
     * @return Boolean
     */
    public function deleteUser($userId) {
        $date = date('Y-m-d H:i:s');

        try {
            DB::beginTransaction();
            DB::table($this->table)->where('id', $userId)->update([
                'status'     => self::STATUS_INACTIVE,
                'deleted_at' => $date
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new DBException($e);
        }

        return true;
    }
}
