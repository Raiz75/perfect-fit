<?php

namespace App\Services;

use CpChart\Chart\Pie;
use CpChart\Data;
use CpChart\Image;
use Illuminate\Support\Facades\Storage;

class ChartImageService
{

    public function renderPie(array $labels, array $data, array $colors, string $title): string
    {
        $dataSet = $this->buildDataSet($labels, $data, $colors);
        $image = new Image(500, 300, $dataSet);

        $this->drawTitle($image, $title, 500);

        $pie = new Pie($image, $dataSet);
        $pie->draw2DPie(250, 170, [
            'Radius' => 100,
            'DrawLabels' => true,
            'LabelStacked' => true,
            'Border' => true,
        ]);

        return $this->saveImage($image);
    }

    public function renderDoughnut(array $labels, array $data, array $colors, string $title): string
    {
        $dataSet = $this->buildDataSet($labels, $data, $colors);
        $image = new Image(500, 300, $dataSet);

        $this->drawTitle($image, $title, 500);

        $pie = new Pie($image, $dataSet);
        $pie->draw2DRing(250, 170, [
            'Radius' => 100,
            'DrawLabels' => true,
            'LabelStacked' => true,
            'Border' => true,
        ]);

        return $this->saveImage($image);
    }

    public function renderBar(array $labels, array $data, array $colors, string $title): string
    {
        $dataSet = $this->buildDataSet($labels, $data, $colors);
        $image = new Image(600, 350, $dataSet);

        $this->drawTitle($image, $title, 600);

        $image->setGraphArea(60, 50, 560, 310);
        $image->drawScale([
            'Mode' => SCALE_MODE_START0,
            'GridR' => 200, 'GridG' => 200, 'GridB' => 200,
            'DrawSubTicks' => false,
            'LabelRotation' => 45,
        ]);
        $image->drawBarChart();

        return $this->saveImage($image);
    }

    public function cleanup(): void
    {
        Storage::disk('local')->deleteDirectory('report-charts');
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        return [
            'R' => hexdec(substr($hex, 0, 2)),
            'G' => hexdec(substr($hex, 2, 2)),
            'B' => hexdec(substr($hex, 4, 2)),
        ];
    }

    private function buildDataSet(array $labels, array $data, array $colors): Data
    {
        $dataSet = new Data();
        $dataSet->addPoints($data, 'Serie1');
        $dataSet->addPoints($labels, 'Labels');
        $dataSet->setAbscissa('Labels');

        foreach ($colors as $i => $color) {
            $dataSet->setPalette($i, $this->hexToRgb($color));
        }

        return $dataSet;
    }

    private function drawTitle(Image $image, string $title, int $width): void
    {
        $image->setFontProperties([
            'FontName' => 'GeosansLight.ttf',
            'FontSize' => 14,
            'R' => 140,
            'G' => 82,
            'B' => 255,
        ]);
        $image->drawText((int) round($width / 2), 25, $title, [
            'Align' => TEXT_ALIGN_TOPMIDDLE,
        ]);
    }

    private function saveImage(Image $image): string
    {
        Storage::disk('local')->makeDirectory('report-charts');
        $filename = 'chart_'.uniqid().'.png';
        $path = Storage::disk('local')->path('report-charts/'.$filename);
        $image->render($path);

        return 'report-charts/'.$filename;
    }
}