<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SummaryController extends Controller
{
    // LIMA SERANGKAI DATA
    public function summary()
    {
        return view('pages.summary');
    }

    public function summaryData(Request $request)
    {
        $dataSet = $request->input('dataSet');

        // Validate input data (you can add more validation rules)
        $request->validate([
            'dataSet' => 'required',
        ]);

        // Parse and calculate data
        $dataSet = explode(',', $dataSet);
        $dataSet = array_map('trim', $dataSet);

        $count = count($dataSet);
        $sum = array_sum($dataSet);
        $median = $this->calculateMedian($dataSet);
        $mean = $sum / $count;
        $min = min($dataSet);
        $max = max($dataSet);
        $mode = $this->calculateMode($dataSet);
        $q1 = $this->calculateQuartile($dataSet, 25);
        $q3 = $this->calculateQuartile($dataSet, 75);
        $standardDeviation = $this->calculateStandardDeviation($dataSet);
        $variance = $this->calculateVariance($dataSet);

        // Check if there's only one data point
        if ($count === 1) {
            // Return a custom message
            return response()->json(['message' => 'Add data']);
        }

        // Return results as JSON
        return response()->json([
            'count' => $count,
            'sum' => $sum,
            'median' => $median,
            'mean' => $mean,
            'min' => $min,
            'max' => $max,
            'mode' => $mode,
            'q1' => $q1,
            'q3' => $q3,
            'standardDeviation' => $standardDeviation,
            'variance' => $variance,
        ]);
    }

    private function calculateMedian($dataSet)
    {
        sort($dataSet);
        $count = count($dataSet);
        $middle = floor($count / 2);
    
        if ($count % 2 == 0) {
            // For an even count, the median is the average of the two middle values
            $median = ($dataSet[$middle - 1] + $dataSet[$middle]) / 2;
        } else {
            // For an odd count, the median is the middle value
            $median = $dataSet[$middle];
        }
    
        return $median;
    }
    

    private function calculateMode($dataSet)
    {
        $counts = array_count_values($dataSet);
        arsort($counts);
        $mode = key($counts);
        return $mode;
    }

    private function calculateQuartile($dataSet, $percentile)
    {
        sort($dataSet);
        $n = count($dataSet);
        $position = ($n - 1) * $percentile / 100;

        if (is_int($position)) {
            return $dataSet[$position];
        } else {
            $k = floor($position);
            $fraction = $position - $k;
            return $dataSet[$k] + ($fraction * ($dataSet[$k + 1] - $dataSet[$k]));
        }
    }

    private function calculateStandardDeviation($dataSet)
    {
        $mean = array_sum($dataSet) / count($dataSet);
        $squaredDifferences = array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $dataSet);
        $variance = array_sum($squaredDifferences) / (count($dataSet) - 1);
        return sqrt($variance);
    }

    private function calculateVariance($dataSet)
    {
        $mean = array_sum($dataSet) / count($dataSet);
        $squaredDifferences = array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $dataSet);
        return array_sum($squaredDifferences) / (count($dataSet) - 1);
    }
}
