function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");
  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

function perbaruiCounter() {
  const counter = document.getElementById("row-counter");
  const table = document.querySelector(".table-responsive table");
  if (!counter || !table) return;

  const rows = table.querySelectorAll("tbody tr");
  const tampil = Array.from(rows).filter(function (row) {
    return row.style.display !== "none";
  }).length;
  counter.textContent = "Menampilkan " + tampil + " dari " + rows.length + " data";
}

function initHapusConfirm() {
  document.addEventListener("submit", function (e) {
    const form = e.target;
    if(!form.classList.contains("form-hapus")) return;

    const row = form.closest("tr");
    const nama = row ? row.querySelector("td")?.textContent : "semua data ini";
    const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
    if (!yakin) {
      e.preventDefault();
    }
  });
}

function initEditConfirm() {
    document.addEventListener("submit", function (e) {
        const form = e.target;
        if (!form.classList.contains("form-edit")) return;

        if (e.defaultPrevented) return;

        const yakin = confirm("Simpan perubahan data ini?");
        if (!yakin) {
            e.preventDefault();
        }
    });
}

function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

const ATURAN_FIELD = [
  {
    name: "judul",
    cek: function (v) { return v.trim() !== ""; },
    pesan: "Judul wajib diisi."
  },
  {
    name: "pengarang",
    cek: function (v) { return v.trim() !== ""; },
    pesan: "Pengarang wajib diisi."
  },
  {
    name: "tahun",
    cek: function (v) {
      const n = parseInt(v, 10);
      return !isNaN(n) && n >= 1900 && n <= 2026;
    },
    pesan: "Tahun harus di antara 1900-2026."
  },
  {
    name: "stok",
    cek: function (v) {
      const n = parseInt(v, 10);
      return !isNaN(n) && n >= 0;
    },
    pesan: "Stok tidak boleh negatif."
  },
  {
    name: "isbn",
    cek: function (v) {
      return v.trim() === "" || /^[0-9-]+$/.test(v.trim());
    },
    pesan: "ISBN hanya boleh berisi angka dan tanda hubung (-)."
  },
  { name: "nama",
    cek: v => v.trim() !== "",
    pesan: "Nama wajib diisi."
  },
  { name: "email",
    cek: v => /^\S+@\S+\.\S+$/.test(v),
    pesan: "Format email tidak valid."
  },
  { name: "no_anggota",
    cek: v => v.trim() !== "",
    pesan: "No. Anggota wajib diisi."
  },
  { name: "tanggal_lahir",
    cek: v => v !== "",
    pesan: "Tanggal lahir wajib diisi."
  },
  { name: "alamat",
    cek: v => v.trim() !== "",
    pesan: "Alamat wajib diisi."
  }
];

function periksaField(field, cek, pesan) {
  if (cek(field.value)) {
    hapusError(field);
    return true;
  }
  tampilkanError(field, pesan);
  return false;
}

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.noValidate = true;

  form.addEventListener("submit", function (e) {
    let valid = true;
    const namaTerdaftar = ATURAN_FIELD.map(function (a) { return a.name; });

    form.querySelectorAll("input[required]").forEach(function (field) {
      if (namaTerdaftar.includes(field.name)) return;
      const ok = periksaField(
        field,
        function (v) { return v.trim() !== ""; },
        "Field ini wajib diisi."
      );
      if (!ok) valid = false;
    });

    ATURAN_FIELD.forEach(function (aturan) {
      const field = form.querySelector("[name='" + aturan.name + "']");
      if (!field) return;
      if (!periksaField(field, aturan.cek, aturan.pesan)) valid = false;
    });

    if (!valid) {
      e.preventDefault();
    }
  });
}

async function muatTabel(url, kolom) {
  const tbody = document.querySelector(".table-responsive table tbody");
  const loading = document.getElementById("loading-indicator");
  if (!tbody) return;

  loading.style.display = "block";
  tbody.innerHTML = "";

  try {
      await new Promise((resolve) => setTimeout(resolve, 3000));

      const res = await fetch(url);
      if (!res.ok) {
          throw new Error("Gagal mengambil data (status " + res.status + ")");
      }
      const data = await res.json();

      data.forEach(function (item) {
          const tr = document.createElement("tr");
          let selData = "";
          kolom.forEach(function (namaKunci) {selData += "<td>" + item[namaKunci] + "</td>";
          });
          tr.innerHTML =
              selData +
              "<td>" +
              "<button type=\"button\" class=\"btn-edit\">Edit</button> " +
              "<button type=\"button\" class=\"btn-detail\">Detail</button> " +
              "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
              "</td>";
          tbody.appendChild(tr);
      });
  } catch (err) {
    const jumlahKolom = kolom.length + 1;
    tbody.innerHTML = "<tr><td colspan=\"" + jumlahKolom + "\">Gagal memuat data: " + err.message + "</td></tr>";
  } finally { 
    loading.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initEditConfirm();
  initValidasiForm();
});