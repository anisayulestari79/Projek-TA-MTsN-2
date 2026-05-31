<section id="view-dashboard" class="view">
    <div class="card" style="margin-top:20px;">
        <div class="card-head">
            <strong>Tabel Poin Keseluruhan</strong>
        </div>
        <div class="card-body">
            <div class="data-filter" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
                <input type="text" id="filter-nama" placeholder="Cari nama siswa..." oninput="filterData()">
                <select id="filter-tingkat-dashboard">
                    <option value="">Semua Tingkat</option>
                </select>
                <select id="filter-kelas-dashboard" onchange="filterData()">
                    <option value="">Semua Kelas</option>
                </select>
            </div>
            <table id="tbl-poin-keseluruhan">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th class="sortable-header" data-sort-by="poin">Poin <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>

