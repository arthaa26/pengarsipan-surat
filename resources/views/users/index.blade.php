@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
<h2>Daftar User</h2>
<a href="{{ url('/users/create') }}">Tambah User</a>
<table border="1" cellpadding="8" style="margin-top:16px;width:100%">
	<thead>
		<tr>
			<th>Nama</th>
			<th>Email</th>
			<th>Aksi</th>
		</tr>
	</thead>
	<tbody>
		{{-- Loop data user di sini --}}
	</tbody>
</table>
@endsection
