<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @template T
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|null
     */
    public function getOneById(int $id);

    /**
     * @param integer[] $ids
     * @return \Illuminate\Database\Eloquent\Collection<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|null
     */
    public function getByIds(array $ids): Collection;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\Illuminate\Database\Eloquent\Model>
     */
    public function getAll(): Collection;

    /**
     * @param array<string,string>[] ...$params
     * @return Model|null
     */
    public function getFirstWhere(...$params): ?Model;
}
