fetch('http://localhost/app-toko/get_barang.php')
.then(res => res.json())
.then(data => {
    let tbody = document.getElementById("data-barang");
    tbody.innerHTML = "";

    data.forEach(item => {
        tbody.innerHTML += `
            <tr>
                <td>${item.id}</td>
                <td>${item.nama_barang}</td>
                <td>${item.harga}</td>
                <td>${item.stok}</td>
            </tr>
        `;
    });
})
.catch(err => console.log(err));