<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Chat\Models\MessageKeywords;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class LineReplyTable.
 */
class LineReplyTable extends DataTableComponent
{
    /**
     * @var string
     */
    public $status;

    /**
     * @param string $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        if ($this->status === 'deleted') {
            $query = MessageKeywords::onlyTrashed();
        } else {
            $query = new MessageKeywords();
        }

        return $query
            ->when($this->getFilter('search'), fn ($query, $term) => $query->search($term));
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('ID'))
                ->sortable(),
            Column::make(__('Keywords'), 'keywords')
                ->sortable(),
            Column::make(__('Response'), 'response')
                ->sortable(),
            Column::make(__('Created at'), 'created_at')
                ->sortable(),
            Column::make(__('Updated at'), 'updated_at')
                ->sortable(),
            Column::make(__('Actions')),
        ];
    }

    /**
     * @return string
     */
    public function rowView(): string
    {
        return 'backend.line.reply.includes.row';
    }
}
