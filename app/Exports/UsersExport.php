<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, Responsable,ShouldAutoSize,WithStyles,WithDefaultStyles
{
    use Exportable;
    private $fileName;
    /* private $headers = [
        'Content-Type' => 'text/csv',
    ]; */
    public  function __construct($name = 'user.xlsx') {
       $this->fileName = $name;
    }
    public function headings():array
    {
        return ['id', 'name', 'email','verified at','create at', 'update at'];
    }
    public function defaultStyles(Style $defaultStyle)
    {
        // Configure the default styles
        return [
            'borders'=>[
                'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['argb' => '000000'],
                ],
            ],
            'cell'=>[
                'alignment'=>[
                    'horizontal'=>'center',
                    'vertical'=>'center',
                ],
                'height'    => 50
            ]
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '#fff'],
                ],
                'fill'=>[
                    'fillType'=>Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_RED],
                ],
            ],
        ];
    }
    public function collection()
    {
        return User::all();
    }
}