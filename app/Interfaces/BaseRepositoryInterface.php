<?php 

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    /**
     * @param  array $columns
     * @return mixed
     */
    public function all(Array $columns = array('*'));

    /**
     * @param  array $data
     * @param  bool $force
     * @return mixed
     */
    public function create(array $data, bool $force = true);

    /**
     * @param  array $data
     * @param  int $id
     * @return mixed
     */
    public function update(array $data, int $id);

    /**
     * @param  array $data
     * @param  array $ids
     * @return mixed
     */
    public function updateMultiple(Array $data, Array $ids);

    /**
     * @param  int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param  int $id
     * @param  array $columns
     * @return mixed
     */
    public function find(int $id, Array $columns = array('*'));

    /**
     * @param  string $field
     * @param  string $value
     * @param  array $columns
     * @return mixed
     */
    public function findBy(string $field, string $value, Array $columns = array('*'));
}
