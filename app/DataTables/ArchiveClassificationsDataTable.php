<?php

namespace App\DataTables;

use App\Models\ArchiveClassification;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ArchiveClassificationsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (ArchiveClassification $archiveClassification) {
                return $archiveClassification->created_at?->format('d M Y H:i:s');
            })
            ->editColumn('updated_at', function (ArchiveClassification $archiveClassification) {
                return $archiveClassification->updated_at?->format('d M Y H:i:s');
            })
            ->addColumn('action', function (ArchiveClassification $archiveClassification) {
                $buttons = [
                    'view' => true,
                    'edit' => true,
                    'delete' => true,
                ];

                return view('pages.reference.archive-classification.datatables-action', compact('buttons', 'archiveClassification'));
            })
            ->addIndexColumn()
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ArchiveClassification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ArchiveClassification $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderBy('id', 'asc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('archiveclassifications-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('full_code')->title('Kode Klasifikasi'),
            Column::make('name'),
            Column::computed('action')
                ->title(__('menu.general.action'))
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ArchiveClassifications_' . date('YmdHis');
    }
}
