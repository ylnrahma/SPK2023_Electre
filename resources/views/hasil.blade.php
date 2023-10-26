@extends('layouts.main')
@section('title', 'Hasil Perhitungan')
@section('content')
<div class="container">
    <h2>Hasil Normalisasi</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Alternatif</th>
                @foreach (range(1, count($normalizations[0])) as $i)
                <th>Kriteria {{ $i }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($normalizations as $i => $row)
            <tr>
                <td>Alternatif {{ $i + 1 }}</td>
                @foreach ($row as $j => $value)
                <td>{{ number_format($value, 4) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="container">
    <h2>Preference Matrix</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Alternatif</th>
                @foreach (range(1, count($preferenceMatrix[0])) as $i)
                <th>Kriteria {{ $i }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($preferenceMatrix as $i => $row)
            <tr>
                <td>Alternatif {{ $i + 1 }}</td>
                @foreach ($row as $j => $value)
                <td>{{ number_format($value, 4) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="container">
    <h2>Concordance Index</h2>
    <table class="table table-bordered">
        @for($i = 0; $i <= count($concordanceIndex); $i++) @for($j=0; $j <=count($concordanceIndex[0]); $j++) @if(isset($concordanceIndex[$i][$j])) <tr>
            <td>C<sub>{{ $i + 1 . ", " . $j + 1 }}</sub></td>
            @endif

            @if(isset($concordanceIndex[$i][$j]))
            <td>{{ '{' }}

                @for($k = 0; $k <= count($concordanceIndex); $k++) @if(isset($concordanceIndex[$i][$j][$k])) {{ $concordanceIndex[$i][$j][$k] + 1 . ' ' }} @endif @endfor {{ '}' }}</td>
                    @endif
                    @endfor
                    </tr>
                    @endfor
    </table>
</div>

<div class="container">
    <h2>Discordance Index</h2>
    <table class="table table-bordered">
        @for($i = 0; $i <= count($discordanceIndex); $i++) @for($j=0; $j <=count($discordanceIndex[0]); $j++) @if(isset($discordanceIndex[$i][$j])) <tr>
            <td>C<sub>{{ $i + 1 . ", " . $j + 1 }}</sub></td>
            @endif

            @if(isset($discordanceIndex[$i][$j]))
            <td>{{ '{' }}

                @for($k = 0; $k <= count($discordanceIndex); $k++) @if(isset($discordanceIndex[$i][$j][$k])) {{ $discordanceIndex[$i][$j][$k] + 1 . ' ' }} @endif @endfor {{ '}' }}</td>
                    @endif
                    @endfor
                    </tr>
                    @endfor
    </table>
</div>

<div class="container">
    <h2>Concordance Matrix</h2>
    <table class="table table-bordered">
        @foreach($concordanceMatrix as $cm)
        <tr>
            @for($i = 0; $i <= count($cm); $i++) <td>{{ isset($cm[$i]) ? $cm[$i] : "-" }}</td>
                @endfor
        </tr>
        @endforeach
    </table>
    <h2>Discordance Matrix</h2>
    <table class="table table-bordered">
        @foreach($discordanceMatrix as $dm)
        <tr>
            @for($i = 0; $i <= count($dm); $i++) @if(isset($dm[$i])) @if($dm[$i]==1 || $dm[$i]==0) <td>{{ $dm[$i] }}</td>
                @else
                <td>{{ number_format($dm[$i], 4) }}</td>
                @endif
                @else
                <td>-</td>
                @endif
                @endfor
        </tr>
        @endforeach
    </table>
</div>

<div class="container">
    <h4>Concordance Dominant</h4>
    <table class="table table-striped text-center" border="1">
        @foreach($concordanceDominant as $cd)
        <tr>
            @for($i = 0; $i <= count($concordanceDominant[0]); $i++) <td>{{ isset($cd[$i]) ? $cd[$i] : '-' }}</td>
                @endfor
        </tr>
        @endforeach
    </table>
    <h4>Discordance Dominant</h4>
    <table class="table table-striped text-center" border="1">
        @foreach($discordanceDominant as $dd)
        <tr>
            @for($i = 0; $i <= count($discordanceDominant[0]); $i++) <td>{{ isset($dd[$i]) ? $dd[$i] : '-' }}</td>
                @endfor
        </tr>
        @endforeach
    </table>
    <h4>Agregation Dominant</h4>
    <table class="table table-striped text-center" border="1">
        @foreach($agregationDominant as $ad)
        <tr>
            @for($i = 0; $i <= count($agregationDominant[0]); $i++) <td>{{ isset($ad[$i]) ? $ad[$i] : '-' }}</td>
                @endfor
        </tr>
        @endforeach
    </table>
</div>
@endsection