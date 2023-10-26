<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElectreController extends Controller
{
    public function table(Request $request)
    {
        $x = $request->input('x');
        $y = $request->input('y');

        return view('table')->with(['x' => $x, 'y' => $y]);
    }

    public function hitung(Request $request)
    {
        $bobot = $request->input('bobot');
        $value = $request->input('value');
        $normalisasi = $this->normalisasi($value);
        $preferenceMatrix = $this->preferenceMatrix($normalisasi, $bobot);
        $concordanceIndex = $this->concordanceIndex($preferenceMatrix);
        $discordanceIndex = $this->discordanceIndex($preferenceMatrix);
        $concordanceMatrix = $this->concordanceMatrix($concordanceIndex, $bobot);
        $discordanceMatrix = $this->discordanceMatrix($discordanceIndex, $preferenceMatrix);
        $concordanceThreshold = $this->concordanceThreshold($concordanceMatrix);
        $discordanceThreshold = $this->discordanceThreshold($discordanceMatrix);
        $concordanceDominant = $this->concordanceDominant($concordanceThreshold, $concordanceMatrix);
        $discordanceDominant = $this->discordanceDominant($discordanceThreshold, $discordanceMatrix);
        $agregationDominant = $this->agregationDominant($concordanceDominant, $discordanceDominant);
        return view('hasil', [
            'values' => $value,
            'weights' => $bobot,
            'normalizations' => $normalisasi,
            'preferenceMatrix' => $preferenceMatrix,
            'concordanceIndex' => $concordanceIndex,
            'discordanceIndex' => $discordanceIndex,
            'concordanceMatrix' => $concordanceMatrix,
            'discordanceMatrix' => $discordanceMatrix,
            'concordanceDominant' => $concordanceDominant,
            'discordanceDominant' => $discordanceDominant,
            'agregationDominant' => $agregationDominant,
        ]);
    }

    public function normalisasi($value)
    {
        $normalisasi = $value;
        $sum = array_fill(0, count($value[0]), 0);
        for ($i = 0; $i < count($value); $i++) {
            for ($j = 0; $j < count($value[0]); $j++) {
                $sum[$j] += (pow($value[$i][$j], 2));
            }
        }
        for ($i = 0; $i < count($value); $i++) {
            for ($j = 0; $j < count($value[0]); $j++) {
                $normalisasi[$i][$j] = number_format($value[$i][$j] / sqrt($sum[$j]), 4);
            }
        }
        return $normalisasi;
    }

    public function preferenceMatrix($normalisasi, $bobot)
    {
        $preferenceMatrix = $normalisasi;
        for ($i = 0; $i < count($normalisasi); $i++) {
            for ($j = 0; $j < count($normalisasi[0]); $j++) {
                $preferenceMatrix[$i][$j] *= $bobot[$j];
            }
        }
        return $preferenceMatrix;
    }

    public function concordanceIndex($preferenceMatrix)
    {

        $concordanceIndex = array();
        $index = '';
        for ($i = 0; $i < count($preferenceMatrix); $i++) {
            if ($index != $i) {
                $index = $i;
                $concordanceIndex[$i] = array();
            }

            for ($j = 0; $j < count($preferenceMatrix); $j++) {
                if ($i != $j) {
                    for ($k = 0; $k < count($preferenceMatrix[0]); $k++) {
                        if (!isset($concordanceIndex[$i][$j])) {
                            $concordanceIndex[$i][$j] = array();
                        }
                        if ($preferenceMatrix[$i][$k] >= $preferenceMatrix[$j][$k]) {
                            array_push($concordanceIndex[$i][$j], $k);
                        }
                    }
                }
            }
        }

        return $concordanceIndex;
    }

    public function discordanceIndex($preferenceMatrix)
    {

        $discordanceIndex = array();
        $index = '';
        for ($i = 0; $i < count($preferenceMatrix); $i++) {
            if ($index != $i) {
                $index = $i;
                $discordanceIndex[$i] = array();
            }

            for ($j = 0; $j < count($preferenceMatrix); $j++) {
                if ($i != $j) {
                    for ($k = 0; $k < count($preferenceMatrix[0]); $k++) {
                        if (!isset($discordanceIndex[$i][$j])) {
                            $discordanceIndex[$i][$j] = array();
                        }
                        if ($preferenceMatrix[$i][$k] < $preferenceMatrix[$j][$k]) {
                            array_push($discordanceIndex[$i][$j], $k);
                        }
                    }
                }
            }
        }

        return $discordanceIndex;
    }

    public function concordanceMatrix($concordanceIndex, $bobot)
    {
        $concordanceMatrix = array();
        $index = '';

        for ($i = 0; $i < count($concordanceIndex); $i++) {
            if ($index != $i) {
                $index = $i;
                $concordanceMatrix[$i] = array();
            }

            for ($j = 0; $j < count($concordanceIndex); $j++) {
                if ($i != $j && count($concordanceIndex[$i][$j])) {
                    foreach ($concordanceIndex[$i][$j] as $con) {
                        $concordanceMatrix[$i][$j] = (isset($concordanceMatrix[$i][$j]) ? $concordanceMatrix[$i][$j] : 0) + (int) $bobot[$con];
                    }
                }
            }
        }
        return $concordanceMatrix;
    }

    public function discordanceMatrix($discordanceIndex, $preferenceMatrix)
    {

        $discordanceMatrix = array();
        $index = '';

        for ($i = 0; $i < count($discordanceIndex); $i++) {
            if ($index != $i) {
                $index = $i;
                $discordanceMatrix[$i] = array();
            }

            for ($j = 0; $j < count($discordanceIndex); $j++) {
                if ($i != $j) {
                    $max_d = 0;
                    $max_j = 0;
                    foreach ($discordanceIndex[$i][$j] as $disc) {
                        if ($max_d < abs($preferenceMatrix[$i][$disc] - $preferenceMatrix[$j][$disc])) {
                            $max_d = abs($preferenceMatrix[$i][$disc] - $preferenceMatrix[$j][$disc]);
                        }
                    }
                    for ($k = 0; $k < count($preferenceMatrix[0]); $k++) {
                        if ($max_j < abs($preferenceMatrix[$i][$k] - $preferenceMatrix[$j][$k])) {
                            $max_j = abs($preferenceMatrix[$i][$k] - $preferenceMatrix[$j][$k]);
                        }
                    }
                    $discordanceMatrix[$i][$j] = $max_d / $max_j;
                }
            }
        }
        return $discordanceMatrix;
    }

    public function concordanceThreshold($concordanceMatrix)
    {

        $sigma_c = 0;
        foreach ($concordanceMatrix as $k => $cl) {
            foreach ($cl as $l => $value) {
                $sigma_c += $value;
            }
        }
        $threshold_c = $sigma_c / (count($concordanceMatrix) * (count($concordanceMatrix) - 1));

        return $threshold_c;
    }

    public function discordanceThreshold($discordanceMatrix)
    {
        $sigma_d = 0;
        foreach ($discordanceMatrix as $k => $dl) {
            foreach ($dl as $l => $value) {
                $sigma_d += $value;
            }
        }
        $threshold_d = $sigma_d / (count($discordanceMatrix) * (count($discordanceMatrix) - 1));

        return $threshold_d;
    }

    public function concordanceDominant($concordanceThreshold, $concordanceMatrix)
    {
        $cd = array();
        foreach ($concordanceMatrix as $k => $cl) {
            $cd[$k] = array();
            foreach ($cl as $l => $value) {
                $cd[$k][$l] = ($value >= $concordanceThreshold ? 1 : 0);
            }
        }
        return $cd;
    }

    public function discordanceDominant($discordanceThreshold, $discordanceMatrix)
    {
        $dd = array();
        foreach ($discordanceMatrix as $k => $cl) {
            $dd[$k] = array();
            foreach ($cl as $l => $value) {
                $dd[$k][$l] = ($value >= $discordanceThreshold ? 1 : 0);
            }
        }
        return $dd;
    }

    public function agregationDominant($concordanceDominant, $discordanceDominant)
    {
        $ad = array();
        foreach ($concordanceDominant as $k => $sl) {
            $ad[$k] = array();
            foreach ($sl as $l => $value) {
                $ad[$k][$l] = $concordanceDominant[$k][$l] * $discordanceDominant[$k][$l];
            }
        }
        return $ad;
    }
}
