<form method="POST" action="">
	@csrf
	<label>Kode Surat</label><br>
	<input type="text" name="kode_surat"><br><br>
	<label>Judul</label><br>
	<input type="text" name="tittle"><br><br>
	<label>Isi Surat</label><br>
	<textarea name="isi_surat"></textarea><br><br>
	<button type="submit">Simpan</button>
</form>
