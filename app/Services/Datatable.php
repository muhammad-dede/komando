<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class Datatable
{
    protected $query;

    protected $collection = [];

    protected $rowView;

    protected $rowPayload = [];

    protected $keyword;

    protected $orderByDirection;

    protected $orderByColumn;

    protected $recordsTotal = 0;

    protected $recordsFiltered = 0;

    protected $draw = 1;

    protected $columns;

    protected $arrayData;

    public static function make($query)
    {
        $datatable = new static();
        $datatable->query = $query;

        $datatable->recordsTotal = $query->count();
        $orderByIndex = request('order.0.column');
        $datatable->orderByColumn = request("columns.$orderByIndex.data");
        $datatable->orderByDirection = request('order.0.dir');
        $datatable->keyword = request('search.value');
        $datatable->draw = intval(request()->input('draw'));

        return $datatable;
    }

    public function rowView($view, $payload = [])
    {
        $this->rowView = $view;
        $this->rowPayload = $payload;

        return $this;
    }

    public function columns($columns)
    {
        $this->columns = collect($columns);

        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function search(\Closure $callback)
    {
        call_user_func($callback, $this->query, $this->keyword);

        return $this;
    }

    public function toJson()
    {
        $data = $this->buildQuery()->buildView();
        if ($this->orderByDirection === 'asc') {
            $data = collect($data)->sortBy(function ($row, $key) {
                return strtolower(strip_tags($row[$this->orderByColumn]));
            })->values()->toArray();
        } else {
            $data = collect($data)->sortByDesc(function ($row, $key) {
                return strtolower(strip_tags($row[$this->orderByColumn]));
            })->values()->toArray();
        }

        return new JsonResponse([
            'draw' => $this->draw,
            'recordsTotal' => $this->recordsTotal,
            'recordsFiltered' => $this->recordsFiltered,
            'data' => $data
        ]);
    }

    private function buildQuery()
    {
        $this->buildSearchQuery();

        $this->recordsFiltered = $this->query->count();

        $this->query
            ->offset(request('start'), 0)
            ->limit(request('length'));

        if ($this->orderByDirection === 'asc') {
            $this->collection = collect($this->query->get())->sortBy($this->orderByColumn);
        } else {
            $this->collection = collect($this->query->get())->sortByDesc($this->orderByColumn);
        }

        return $this;
    }

    private function buildView()
    {
        $result = [];
        if (! empty($this->arrayData)) {
            $datas = $this->arrayData;
        } else {
            $datas = $this->collection;
        }

        $ids = $this->columns->pluck('data');
        foreach ($datas as $data) {
            $html = view()->make($this->rowView, array_merge(compact('data'), $this->rowPayload))->render();
            $html = str_replace("\n", " ", $html);
            $dom = new \DOMDocument();
            $dom->loadHTML($html);
            $temp = [];
            foreach ($ids as $id) {
                $node = $dom->getElementById($id);
                if ($node !== null) {
                    $temp[$id] = innerHTML($dom->getElementById($id));
                }
            }
            $result[] = $temp;
        }

        return $result;
    }

    private function searchableColumns()
    {
        return $this->columns->filter(function ($col) {
            return array_get($col, 'searchable', false);
        });
    }

    private function buildSearchQuery()
    {
        if ($this->keyword && !empty($this->columns)) {
            $this->query->where(function ($q) {
                $keyword = strtolower($this->keyword);
                foreach ($this->searchableColumns() as $col) {
                    $col = $col['data'];
                    $q->orWhere("lower({$col})", 'like', "%{$keyword}%");
                }
            });
        }
    }

    public static function fromArray($array)
    {
        $datatable = new static();
        $datatable->arrayData = $array;

        $datatable->recordsTotal = count($array);
        $orderByIndex = request('order.0.column');
        $datatable->orderByColumn = request("columns.$orderByIndex.data");
        $datatable->orderByDirection = request('order.0.dir');
        $datatable->keyword = request('search.value');
        $datatable->draw = intval(request()->input('draw'));

        return $datatable;
    }

    public function searchFromArray(\Closure $callback)
    {
        if (!empty($this->keyword)) {
            $this->arrayData = call_user_func($callback, $this->arrayData, $this->keyword);
        }
        $this->recordsFiltered = $this->arrayData->count();

        return $this;
    }

    public function fromArrayToJson()
    {
        $data = $this->buildView();

        return new JsonResponse([
            'draw' => $this->draw,
            'recordsTotal' => $this->recordsTotal,
            'recordsFiltered' => $this->recordsFiltered,
            'data' => $data
        ]);
    }

    public function sortFromArray()
    {
        if ($this->orderByDirection === 'asc') {
            $this->arrayData = collect($this->arrayData)->sortBy($this->orderByColumn);
        } else {
            $this->arrayData = collect($this->arrayData)->sortByDesc($this->orderByColumn);
        }

        return $this;
    }

    public function paginateArray()
    {
        $this->arrayData = $this->arrayData->slice(intval(request('start')), intval(request('length')));

        return $this;
    }

    public function customRowStyle(\Closure $callback)
    {
        $this->arrayData = call_user_func($callback, $this->arrayData);

        return $this;
    }

    public function appendColumn($arr)
    {
        $this->columns = $this->columns->push($arr);

        return $this;
    }
}
