fetch('http://localhost/api-toko/api.php')

.then(response => response.json())

.then(data => {

    let tbody = document.getElementById("dataBarang");

    tbody.innerHTML = "";

    data.forEach(item => {

        tbody.innerHTML += `
        <tr>
            <td>${item.id}</td>
            <td>${item.nama_barang}</td>
            <td>Rp ${parseInt(item.harga).toLocaleString("id-ID")}</td>
            <td>${item.stok}</td>
        </tr>
        `;

    });

})

.catch(error => {

    console.log(error);

    document.getElementById("dataBarang").innerHTML = `
    <tr>
        <td colspan="4">
            Gagal memuat data
        </td>
    </tr>
    `;

});
