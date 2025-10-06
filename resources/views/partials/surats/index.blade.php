@extends('layouts.app')

@section('title', 'Daftar Surat')

@section('content')
<h2>Daftar Surat</h2>
<a href="{{ url('/surats/create') }}">Tambah Surat</a>
<table border="1" cellpadding="8" style="margin-top:16px;width:100%">
	<thead>
		<tr>
			<th>Kode Surat</th>
			<th>Judul</th>
			<th>Aksi</th>
		</tr>
	</thead>
	<tbody>
		{{-- Loop data surat di sini --}}
	</tbody>
</table>
@endsection
