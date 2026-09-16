<?php

namespace App\Support;

use DateTime;
use DateTimeInterface;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

/**
 * Helper export & import file Excel menggunakan PhpSpreadsheet.
 */
class ExcelHelper
{
    /**
     * Buat spreadsheet dari baris judul dan baris data.
     *
     * @param  array<int, string>  $headings
     * @param  array<int, array<int, mixed>>  $rows
     */
    public static function spreadsheet(array $headings, array $rows, string $title = 'Data'): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(mb_substr($title, 0, 31));

        $sheet->fromArray($headings, null, 'A1');

        if ($rows !== []) {
            $sheet->fromArray($rows, null, 'A2');
        }

        $lastColumn = Coordinate::stringFromColumnIndex(max(count($headings), 1));
        $headerRange = 'A1:'.$lastColumn.'1';

        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('2C3E50');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        for ($index = 1; $index <= count($headings); $index++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($index))->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        return $spreadsheet;
    }

    /**
     * Kirim spreadsheet sebagai file unduhan (.xlsx).
     */
    public static function download(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet) {
            (new XlsxWriter($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Baca seluruh baris file Excel/CSV yang diupload (index kolom dimulai dari 0).
     *
     * @return array<int, array<int, mixed>>
     */
    public static function readRows(UploadedFile $file): array
    {
        $reader = self::readerFor($file);
        $spreadsheet = $reader->load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        $spreadsheet->disconnectWorksheets();

        return $rows;
    }

    /**
     * Format nilai tanggal agar rapi saat ditulis ke Excel.
     */
    public static function formatDate(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('d/m/Y');
        }

        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}/', $value) === 1) {
            return date('d/m/Y', strtotime($value));
        }

        return $value;
    }

    /**
     * Normalisasi tanggal dari file import menjadi format Y-m-d.
     */
    public static function normalizeDate(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '' || $value === '-' || $value === '0000-00-00') {
            return null;
        }

        if (is_numeric($value)) {
            if ((float) $value < 25569) {
                return null;
            }

            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (Throwable) {
                return null;
            }
        }

        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d'] as $format) {
            $date = DateTime::createFromFormat('!'.$format, $value);

            if ($date instanceof DateTime) {
                return $date->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);

        return $timestamp === false ? null : date('Y-m-d', $timestamp);
    }

    /**
     * Normalisasi nominal (mis. "3.000.000" / "Rp 3.000.000") menjadi angka.
     */
    public static function normalizeMoney(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '' || $value === '-') {
            return null;
        }

        if (is_numeric($value)) {
            return (string) (int) round((float) $value);
        }

        $digits = preg_replace('/\D/', '', $value);

        return $digits === '' ? null : (string) (int) $digits;
    }

    /**
     * Tentukan reader PhpSpreadsheet sesuai format file yang diupload.
     */
    private static function readerFor(UploadedFile $file): XlsxReader|Xls|Csv
    {
        $handle = fopen($file->getRealPath(), 'rb');
        $bytes = $handle ? (string) fread($handle, 8) : '';

        if ($handle) {
            fclose($handle);
        }

        if (str_starts_with($bytes, 'PK')) {
            return new XlsxReader();
        }

        if (str_starts_with($bytes, "\xD0\xCF\x11\xE0")) {
            return new Xls();
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());

        if ($extension === 'xlsx') {
            return new XlsxReader();
        }

        if ($extension === 'xls') {
            return new Xls();
        }

        $reader = new Csv();
        $reader->setInputEncoding('UTF-8');

        return $reader;
    }
}