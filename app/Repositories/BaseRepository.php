<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

abstract class BaseRepository implements RepositoryInterface
{
    /** @var string|null */
    private ?string $modelClass;

    /** @var Model */
    protected Model $model;

    /** @param string|null $modelClass */
    public function __construct(?string $modelClass = null)
    {
        $this->modelClass = $modelClass ?: self::guessModelClass();
        $this->model = app($this->modelClass);
    }

    /**
     * @return string|null
     */
    private static function guessModelClass(): ?string
    {
        return preg_replace('/(.+)\\\\Repositories\\\\(.+)Repository$/m', '$1\Models\\\$2', static::class);
    }

    /**
     * IDに紐づくレコードを取得
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|null
     */
    public function getOneById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Undocumented function
     *
     * @param int[] $ids
     * @return \Illuminate\Database\Eloquent\Collection<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|null
     */
    public function getByIds(array $ids): Collection
    {
        return $this->model->find($ids);
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Collection<\Illuminate\Database\Eloquent\Model>
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Undocumented function
     *
     * @param array<string,string>[] ...$params
     * @return Model|null
     */
    public function getFirstWhere(...$params): ?Model
    {
        return $this->model->firstWhere(...$params);
    }

    /**
     * @return string|null
     */
    public function getModelClass(): ?string
    {
        return $this->modelClass;
    }
}
