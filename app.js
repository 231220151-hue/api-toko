const API_BASE = 'http://localhost/api-toko';
const myToken = localStorage.getItem('token_toko');

let halamanSaatIni = 1;
let totalHalaman = 1;
let keywordCari = "";

if(!myToken){
    alert('Anda harus login terlebih dahulu!');
    window.location.href = 'login.html';
}

function logout(){
    localStorage.removeItem('token_toko');
    window.location.href = 'login.html';
}

function escapeHTML(value){
    return String(value ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function escapeJS(value){
    return String(value ?? "").replaceAll("\\", "\\\\").replaceAll("'", "\\'");
}

function loadData(page = 1){

    keywordCari = document.getElementById("inputCari") 
        ? document.getElementById("inputCari").value 
        : "";

    const url = API_BASE + 
        '/get_barang.php?cari=' + encodeURIComponent(keywordCari) + 
        '&page=' + page;

    fetch(url)
    .then(response => response.json())
    .then(hasil => {

        let tbody = document.getElementById("dataBarang");
        tbody.innerHTML = "";

        halamanSaatIni = hasil.halaman_saat_ini;
        totalHalaman = hasil.total_halaman;

        if(hasil.data.length === 0){
            tbody.innerHTML = `
            <tr>
                <td colspan="6">Data tidak ditemukan</td>
            </tr>
            `;
        }

        hasil.data.forEach(item => {

            let namaBarang = escapeHTML(item.nama_barang);
            let namaBarangJS = escapeJS(item.nama_barang);

            let gambarBarang = item.gambar
                ? `<img class="thumbnail" src="${API_BASE}/${item.gambar}" alt="${namaBarang}">`
                : `<span class="gambar-kosong">Tidak ada</span>`;

            tbody.innerHTML += `
            <tr>
                <td>${item.id}</td>
                <td>${gambarBarang}</td>
                <td>${namaBarang}</td>
                <td>Rp ${parseInt(item.harga).toLocaleString("id-ID")}</td>
                <td>${item.stok}</td>
                <td>
                    <button
                        class="btn-edit"
                        onclick="editBarang(
                            ${item.id},
                            '${namaBarangJS}',
                            ${item.harga},
                            ${item.stok}
                        )">
                        Edit
                    </button>

                    <button
                        class="btn-hapus"
                        onclick="hapusBarang(${item.id})">
                        Hapus
                    </button>
                </td>
            </tr>
            `;

        });

        aturPagination();

    })
    .catch(error => {
        console.log(error);

        document.getElementById("dataBarang").innerHTML = `
        <tr>
            <td colspan="6">Gagal memuat data</td>
        </tr>
        `;
    });
}

function aturPagination(){

    document.getElementById("infoHalaman").innerHTML =
        "Halaman " + halamanSaatIni + " dari " + totalHalaman;

    document.getElementById("btnPrev").disabled = halamanSaatIni <= 1;
    document.getElementById("btnNext").disabled = halamanSaatIni >= totalHalaman;

}

function pindahHalaman(arah){

    if(arah === "prev" && halamanSaatIni > 1){
        loadData(halamanSaatIni - 1);
    }

    if(arah === "next" && halamanSaatIni < totalHalaman){
        loadData(halamanSaatIni + 1);
    }

}

function cariBarang(){
    loadData(1);
}

loadData();

document
.getElementById("formBarang")
.addEventListener("submit", function(e){

    e.preventDefault();

    const tokenSekarang = localStorage.getItem('token_toko');

    if(!tokenSekarang){
        alert("Token tidak ditemukan. Silakan login ulang.");
        window.location.href = "login.html";
        return;
    }

    let id = document.getElementById("id_barang").value;

    let formData = new FormData();

    formData.append("id", id);
    formData.append("nama_barang", document.getElementById("nama_barang").value);
    formData.append("harga", document.getElementById("harga").value);
    formData.append("stok", document.getElementById("stok").value);

    let gambar = document.getElementById("gambar").files[0];

    if(gambar){
        formData.append("gambar", gambar);
    }

    let url = id == ""
        ? API_BASE + "/tambah_barang.php"
        : API_BASE + "/edit_barang.php";

    fetch(url,{
        method:'POST',
        headers:{
            'Authorization': tokenSekarang,
            'X-Authorization': tokenSekarang
        },
        body:formData
    })
    .then(response => response.json())
    .then(result => {

        alert(result.message);

        if(result.status === 'error'){
            return;
        }

        document.getElementById("formBarang").reset();
        document.getElementById("id_barang").value = "";
        document.getElementById("btnSubmit").innerHTML = "Simpan Barang";

        loadData(halamanSaatIni);

    })
    .catch(error => {
        console.log(error);
        alert("Terjadi kesalahan saat menyimpan data");
    });

});

function editBarang(id,nama,harga,stok){

    document.getElementById("id_barang").value = id;
    document.getElementById("nama_barang").value = nama;
    document.getElementById("harga").value = harga;
    document.getElementById("stok").value = stok;
    document.getElementById("btnSubmit").innerHTML = "Update Barang";

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

}

function hapusBarang(id){

    const tokenSekarang = localStorage.getItem('token_toko');

    if(!tokenSekarang){
        alert("Token tidak ditemukan. Silakan login ulang.");
        window.location.href = "login.html";
        return;
    }

    let konfirmasi = confirm("Yakin ingin menghapus barang ini?");

    if(!konfirmasi){
        return;
    }

    let formData = new FormData();
    formData.append("id", id);

    fetch(API_BASE + '/hapus_barang.php',{
        method:'POST',
        headers:{
            'Authorization': tokenSekarang,
            'X-Authorization': tokenSekarang
        },
        body:formData
    })
    .then(response => response.json())
    .then(result => {
        alert(result.message);
        loadData(halamanSaatIni);
    })
    .catch(error => {
        console.log(error);
    });

}   