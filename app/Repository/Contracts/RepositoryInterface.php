<?php declare(strict_types=1);

namespace App\Repository\Contracts;

interface RepositoryInterface
{
    public function all(array $columns = array('*'));

    public function paginate(int $perPage = 15, array $columns = array('*'));

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function find(int $id, array $columns = array('*'));

    public function findBy(string $field, string $value, array $columns = array('*'));
}
