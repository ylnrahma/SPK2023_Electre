@extends('layouts.main')
@section('title', 'Input')
@section('content')
<div class="container">
    <h2>Tentukan jumlah Alternatif dan Kriteria</h2>
    <form method="post" action="{{ route('table') }}">
        @csrf

        <div class="form-group">
            <label for="x">Jumlah Alternatif:</label>
            <input type="number" name="x" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="y">Jumlah Kriteria:</label>
            <input type="number" name="y" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Generate Tabel</button>
    </form>
</div>
@endsection