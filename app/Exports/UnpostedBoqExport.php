<?php

namespace App\Exports;

use App\Models\BOQActual;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class UnpostedBoqExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $q = BOQActual::query()->with(['material', 'project', 'subProject', 'user']);

        if (!empty($this->filters['project_id'])) {
            $q->where('project_id', $this->filters['project_id']);
        }

        if (!empty($this->filters['sub_project_id'])) {
            $q->where('sub_project_id', $this->filters['sub_project_id']);
        }

        $q->whereRaw('actual_quantity - COALESCE(posted_quantity, 0) > 0');

        return $q->orderBy('created_at', 'desc');
    }

    public function map($row): array
    {
        $posted = (float) ($row->posted_quantity ?? 0);
        $remaining = max(0, (float)$row->actual_quantity - $posted);

        return [
            $row->id,
            $row->project->name ?? '',
            $row->subProject->name ?? '',
            $row->cluster,
            $row->material->name ?? '',
            (float)$row->actual_quantity,
            (float)$posted,
            (float)$remaining,
            $row->dn_number ?? '',
            $row->usage_date ? $row->usage_date->format('Y-m-d') : '',
            $row->notes ?? '',
            $row->user->name ?? ''
        ];
    }

    public function headings(): array
    {
        return ['ID','Project','Sub Project','Cluster','Material','BOQ Qty','Posted','Remaining','DN Number','Usage Date','Notes','Input By'];
    }
}
