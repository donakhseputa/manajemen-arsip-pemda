<?php

namespace App\DataTables;

use App\Models\ArchiveClassification;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
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
            ->orderBy('full_code', 'asc');
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
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'dom' => 'rt<"archive-classification-footer d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-2 px-3 py-3"<"archive-classification-info"i><"archive-classification-pagination d-flex justify-content-md-end justify-content-start"p>>',
                'pageLength' => 10,
                'autoWidth' => false,
                'language' => [
                    'emptyTable' => 'Belum ada data klasifikasi arsip',
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 data',
                    'infoFiltered' => '(difilter dari _MAX_ total data)',
                    'loadingRecords' => 'Memuat data...',
                    'processing' => 'Memproses...',
                    'zeroRecords' => 'Data tidak ditemukan',
                    'paginate' => [
                        'previous' => '‹',
                        'next' => '›',
                    ],
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('full_code')
                ->title('KODE KLASIFIKASI')
                ->width('220px')
                ->addClass('fw-semibold'),
            Column::make('name')
                ->title('NAMA KLASIFIKASI'),
            Column::computed('action')
                ->title(__('menu.general.action'))
                ->exportable(false)
                ->printable(false)
                ->width('160px')
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
