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

function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");
  if (!input || !table) return;

  const box = input.closest(".search-box") || input.parentElement;

  const select = document.createElement("select");
  select.id = "search-column";
  select.setAttribute("aria-label", "Cari di kolom");
  const semua = document.createElement("option");
  semua.value = "all";
  semua.textContent = "Semua kolom";
  select.appendChild(semua);

  table.querySelectorAll("thead th").forEach(function (th, i) {
    if (th.textContent.trim().toLowerCase() === "aksi") return;
    const opt = document.createElement("option");
    opt.value = i;
    opt.textContent = th.textContent.trim();
    select.appendChild(opt);
  });
  input.insertAdjacentElement("afterend", select);

  const counter = document.createElement("p");
  counter.id = "row-counter";
  counter.className = "row-counter";
  box.insertAdjacentElement("afterend", counter);

  function terapkanFilter() {
    const keyword = input.value.toLowerCase();
    const kolom = select.value;

    table.querySelectorAll("tbody tr").forEach(function (row) {
      let teks;
      if (kolom === "all") {
        teks = row.textContent.toLowerCase();
      } else {
        const sel = row.querySelectorAll("td")[Number(kolom)];
        teks = sel ? sel.textContent.toLowerCase() : "";
      }
      row.style.display = teks.includes(keyword) ? "" : "none";
    });

    perbaruiCounter();
  }

  input.addEventListener("keyup", terapkanFilter);
  select.addEventListener("change", terapkanFilter);
  perbaruiCounter();
}

function initHapusConfirm() {
  document.querySelectorAll(".btn-hapus").forEach(function (btn) {
    btn.addEventListener("click", function () {
      const row = btn.closest("tr");
      const nama = row ? row.querySelector("td")?.textContent : "data ini";
      const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
      if (yakin && row) {
        row.remove();
        perbaruiCounter(); // Latihan 4
      }
    });
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

document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initTableFilter();
  initHapusConfirm();
  initValidasiForm();
});