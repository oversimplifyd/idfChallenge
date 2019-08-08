<?php declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Repository\Contracts\RepositoryInterface;
use App\Repository\Exceptions\RepositoryException;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var
     */
    private $app;

    /**
     * @var
     */
    protected $model;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /***
     * Return Model name of child class
     * @return mixed
     */
    abstract protected function model();

    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = array('*'))
    {
        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, int $id, string $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find(int $id, array $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(string $attribute, string $value, array $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findAllBy(string $attribute, string $value, array $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->get($columns);
    }

    /**
     * Queries a model with another relationship given a criteria.
     *
     * @param $with
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findWith(string $with, string $attribute, string $value, array $columns = array('*'))
    {
        return $this->model->with($with)->where($attribute, '=', $value)->get($columns);
    }

    /**
     * Queries a model with another relationship given a criteria.
     *
     * @param $with
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findFirstWith(string $with, string $attribute, string $value, array $columns = array('*'))
    {
        return $this->model->with($with)->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function firstOrCreate(array $data)
    {
        return $this->model->firstOrCreate($data);
    }

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of
            Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Eager Loading using with.
     *
     * @param $relationship
     * @return mixed
     */
    public function with(string $relationship)
    {
        $model = $this->model();
        return $model::with($relationship)->get();
    }

    /**
     * Eager Load using with for first result.
     *
     * @param $relationship
     * @return mixed
     */
    public function firstWith(string $relationship)
    {
        $model = $this->model();
        return $model::with($relationship)->first();
    }

    /**
     * Inserts into table
     * @param $data
     * @return mixed
     */
    public function insert(array $data)
    {
        $model = $this->model();
        return $model::insert($data);
    }

    /**
     * Does bulk insertion into a table.
     *
     * @param $tableName
     * @param array $bulkDatasssss
     * @return mixed
     */
    public function insertBulk(string $tableName, array $bulkData)
    {
        return DB::table($tableName)->insert($bulkData);
    }

    /**
     * Delete Multiple roes where key in keys.
     *
     * @param $tableName
     * @param string $whereInKey
     * @param array $keys
     * @return mixed
     */
    public function deleteWhereIn(string $tableName, array $keys, string $whereInKey = 'id')
    {
        return DB::table($tableName)
            ->whereIn($whereInKey, $keys)
            ->delete();
    }
}
