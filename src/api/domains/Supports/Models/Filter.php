<?php

namespace Domains\Supports\Models;
use Domains\Supports\Exceptions\DBException;

trait Filter {
    public $paginateParams = ['search', 'sort', 'type', 'no-paging'];

    public function filter($query, $searchColumns = [], $filterCallback = null, $sortCallback = null) {
        $request = app()->make('request');

        // Has search key
        if (!empty($request->input('search'))) {
            $key = $request->input('search');
            $query = $query->where(function($q) use($key, $searchColumns) {
                if (!empty($searchColumns)) {
                    foreach ($searchColumns as $index => $col) {
                        if ($index == 0) {
                            $q = $q->where($col, 'like', "%{$key}%");
                        } else {
                            $q = $q->orWhere($col, 'like', "%{$key}%");
                        }
                    }
                }
                return $q;
            }); 
        }

        // Has custom filter
        if ($filterCallback != null) {
            $query = $query->where($filterCallback);
        }

        // Has order
        if($request->has('sort')) {
            if (in_array($request->input('sort'), $searchColumns) && in_array(strtolower($request->input('type')), ['asc', 'desc'])) {
                $query = $query->orderBy($request->input('sort'), $request->input('type', 'asc'));
            }
        }

        // Has custom order
        if ($sortCallback != null) {
            $query = $sortCallback($query);
        }

        if ($request->has('is-csv')) {
            return $query->get();
        }

        if ($request->has('no-paging')) {
            return new \Illuminate\Pagination\LengthAwarePaginator($query->get(), 1, 1);
        }

        $this->paginateParams = $request->only($this->paginateParams);

        return $query->paginate($this->records_per_page ?? 10)->appends($this->paginateParams);
    }
}
