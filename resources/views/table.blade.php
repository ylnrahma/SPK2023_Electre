@extends('layouts.main')
@section('title', 'Tabel')
@section('content')
<div class="container">
    <h2>Form Tabel dengan {{ $x }} Alternatif dan {{ $y }} Kriteria</h2>
    <form method="post" action="{{ route('hasil') }}">
        @csrf

        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    @for ($i = 0; $i < $y; $i++) <th>Kriteria {{ $i + 1 }}</th>
                        @endfor
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Bobot</th>
                    @for ($i = 0; $i < $y; $i++) <td><input type="number" name="bobot[]" class="form-control" required></td>
                        @endfor
                </tr>
                @for ($row = 0; $row < $x; $row++) <tr>
                    <th>Alternatif {{ $row + 1}}</th>
                    @for ($col = 0; $col < $y; $col++) <td><input type="number" name="value[{{ $row }}][{{ $col }}]" class="form-control" required></td>
                        @endfor
                        </tr>
                        @endfor
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">hitung</button>
    </form>
</div>
@endsection