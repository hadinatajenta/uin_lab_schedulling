CRITICAL RULES UNTUK CODE GENERATION:
Mulai sekarang, setiap kali Anda memberikan output kode (khususnya Blade template), Anda WAJIB mematuhi aturan berikut agar tidak terjadi ParseError:

1. DILARANG MENINGGALKAN ORPHAN TAGS:
   Pastikan setiap `@foreach` memiliki `@endforeach`, setiap `@if` memiliki `@endif`, dan setiap tag HTML (`<div>`, `<tr>`, `<td>`) tertutup dengan sempurna. Lakukan validasi pasangan tag di dalam "pikiran" Anda sebelum memberikan output.
2. JANGAN MEMOTONG BLOK LOGIKA:
   Jika Anda memodifikasi bagian di dalam loop atau kondisi, JANGAN menyingkatnya dengan komentar seperti `// ... rest of the code`. Tuliskan blok `@foreach` hingga `@endforeach` secara UTUH. Singkatan hanya boleh dilakukan di luar blok yang sedang dikerjakan.

3. FOKUS PADA SCOPE:
   Hati-hati dengan nesting yang dalam (seperti dropdown action di dalam tabel). Pastikan penutupan tag modal/dropdown tidak secara tidak sengaja memotong penutupan baris `</tr>` atau `@endforeach`.
