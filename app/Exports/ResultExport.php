<?php

namespace App\Exports;

use App\Models\Article;
use App\Models\Questionaire;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResultExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    private $article;
    private $questionaires;

    public function __construct(int $id)
    {
        $this->article = Article::select('no', 'id', 'title')->with(['article_user' => function ($query) {
            $query->with(['questionaires' => function ($query) {
                $query->with('questionaire');
            }, 'user']);
        }])->where('project_id', $id)->get();

        $this->questionaires = Questionaire::all();
    }

    public function view(): View
    {
        return view('exports.result', [
            'articles' => $this->article,
            'questionaires' => $this->questionaires,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $lastCell = $highestColumn . $highestRow;
        $activeRange = 'A1:' . $lastCell;

        $sheet->getStyle('A1:E1')->getBorders()->getAllBorders()->setBorderStyle('thin')->getColor()->setARGB('000000');
        $sheet->getStyle($activeRange)->getBorders()->getAllBorders()->setBorderStyle('thin')->getColor()->setARGB('000000');
    }
}
