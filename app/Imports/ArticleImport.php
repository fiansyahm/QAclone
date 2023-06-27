<?php

namespace App\Imports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ArticleImport implements ToModel, WithStartRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function rules(): array
    {
        return [
            'no' => 'required|unique:articles,no',
        ];
    }

    public function startRow(): int
    {
        return 2;
    }
    
    public function model(array $row)
    {
        return new Article([
            'no' => $row[0],
            'title' => $row[1],
            'publication' => $row[2],
            'index' => $row[3],
            'quartile' => $row[4],
            'year' => $row[5],
            'authors' => $row[6],
            'abstracts' => $row[7],
            'keywords' => $row[8],
            'language' => $row[9],
            'type' => $row[10],
            'publisher' => $row[11],
            'references_ori' => $row[12],
            'references_filter' => $row[13],
            'cited' => $row[14] == '' ? 0 : $row[14],
            'cited_gs' => $row[15] == '' ? 0 : $row[15],
            'citing' => $row[16],
            'citing_new' => $row[17],
            'keyword' => $row[18],
            'edatabase' => $row[19],
            'edatabase_2' => $row[20],
            'nation_first_author' => $row[21],
            'link_articles' => $row[22],
            'project_id' => $this->project_id,
        ]);
    }
}
