import "./bootstrap";

// Import Bootstrap JavaScript
import "bootstrap/dist/js/bootstrap.bundle.min.js";

// Import SweetAlert2
import Swal from "sweetalert2";
window.Swal = Swal;

// Import DataTables (Responsive +/- expand on all admin tables)
import "./datatables";

// Zakat System JavaScript
document.addEventListener("DOMContentLoaded", function () {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]'),
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]'),
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Sidebar toggle for mobile
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("show");
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener("click", function (e) {
      if (window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
          sidebar.classList.remove("show");
        }
      }
    });
  }

  // Sidebar submenu collapse functionality
  const reportCollapseElement = document.getElementById("reportsSubmenu");
  if (reportCollapseElement) {
    // Keep the reports submenu always open
    const bsCollapse = new bootstrap.Collapse(reportCollapseElement, {
      toggle: false, // Don't toggle automatically
    });

    // Show the submenu immediately
    bsCollapse.show();

    const reportToggle = document.querySelector('[href="#reportsSubmenu"]');
    if (reportToggle) {
      // Remove click event to prevent collapsing
      reportToggle.removeEventListener("click", null);
      reportToggle.setAttribute("aria-expanded", "true");
    }

    // Ensure submenu links are clickable
    const submenuLinks =
      reportCollapseElement.querySelectorAll(".submenu-link");
    submenuLinks.forEach(function (link) {
      link.addEventListener("click", function (e) {
        e.stopPropagation();
        // Ensure the link navigation works
        if (
          this.href &&
          this.href !== "#" &&
          this.href !== window.location.href + "#"
        ) {
          window.location.href = this.href;
        }
      });

      // Add additional hover events to ensure responsiveness
      link.addEventListener("mouseenter", function () {
        this.style.pointerEvents = "auto";
      });
    });
  }

  // Zakat calculator functionality
  const zakatCalculatorForm = document.getElementById("zakatCalculatorForm");
  if (zakatCalculatorForm) {
    zakatCalculatorForm.addEventListener("submit", function (e) {
      e.preventDefault();
      calculateZakat();
    });
  }

  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert:not(.alert-permanent)");
  alerts.forEach(function (alert) {
    setTimeout(function () {
      if (alert && alert.parentNode) {
        alert.style.transition = "opacity 0.5s ease";
        alert.style.opacity = "0";
        setTimeout(function () {
          if (alert && alert.parentNode) {
            alert.remove();
          }
        }, 500);
      }
    }, 5000);
  });

  // Format currency inputs
  const currencyInputs = document.querySelectorAll(".currency-input");
  currencyInputs.forEach(function (input) {
    input.addEventListener("input", function (e) {
      formatCurrencyInput(e.target);
    });
  });

  // Confirm delete actions
  const deleteButtons = document.querySelectorAll(".btn-delete");
  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const message =
        this.dataset.message || "Apakah Anda yakin ingin menghapus data ini?";
      if (confirm(message)) {
        const form = this.closest("form");
        if (form) {
          form.submit();
        }
      }
    });
  });
});

// Zakat calculation function
function calculateZakat() {
  const formData = new FormData(document.getElementById("zakatCalculatorForm"));
  const resultDiv = document.getElementById("calculationResult");

  // Show loading
  if (resultDiv) {
    resultDiv.innerHTML =
      '<div class="text-center"><div class="spinner-zakat mx-auto"></div><p>Menghitung...</p></div>';
  }

  fetch("/calculator/calculate", {
    method: "POST",
    body: formData,
    headers: {
      "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      displayZakatResult(data);
    })
    .catch((error) => {
      console.error("Error:", error);
      if (resultDiv) {
        resultDiv.innerHTML =
          '<div class="alert alert-danger">Terjadi kesalahan dalam perhitungan.</div>';
      }
    });
}

// Display zakat calculation result
function displayZakatResult(data) {
  const resultDiv = document.getElementById("calculationResult");
  if (!resultDiv) return;

  let html = '<div class="card mt-4">';
  html += '<div class="card-header bg-primary text-white">';
  html +=
    '<h5 class="mb-0"><i class="bi bi-calculator"></i> Hasil Perhitungan Zakat</h5>';
  html += "</div>";
  html += '<div class="card-body">';

  html += `<div class="row mb-3">`;
  html += `<div class="col-md-6"><strong>Jenis Zakat:</strong> ${data.zakat_type}</div>`;
  html += `<div class="col-md-6"><strong>Jumlah Harta:</strong> ${formatCurrency(
    data.wealth_amount,
  )}</div>`;
  html += `</div>`;

  if (data.is_eligible) {
    html += '<div class="alert alert-success">';
    html += '<i class="bi bi-check-circle"></i> <strong>Wajib Zakat</strong>';
    html += `<br>Jumlah zakat yang harus dibayar: <strong>${formatCurrency(
      data.zakat_amount,
    )}</strong>`;
    html += "</div>";
  } else {
    html += '<div class="alert alert-warning">';
    html +=
      '<i class="bi bi-exclamation-circle"></i> <strong>Belum Wajib Zakat</strong>';
    html += `<br>Nisab: ${formatCurrency(data.nisab_amount)}`;
    html += "</div>";
  }

  if (data.notes && data.notes.length > 0) {
    html += '<div class="mt-3"><h6>Catatan:</h6><ul class="mb-0">';
    data.notes.forEach((note) => {
      html += `<li>${note}</li>`;
    });
    html += "</ul></div>";
  }

  html += "</div></div>";
  resultDiv.innerHTML = html;
}

// Format currency input
function formatCurrencyInput(input) {
  let value = input.value.replace(/[^\d]/g, "");
  input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Format currency display
function formatCurrency(amount) {
  return "Rp " + Number(amount).toLocaleString("id-ID");
}

// Load mustahik by category (for distribution form)
function loadMustahikByCategory(category, targetSelect) {
  const select = document.getElementById(targetSelect);
  if (!select) return;

  // Show loading
  select.innerHTML = "<option>Loading...</option>";

  fetch(`/api/distributions/mustahik-by-category?category=${category}`)
    .then((response) => response.json())
    .then((data) => {
      select.innerHTML = '<option value="">Pilih Mustahik</option>';
      data.forEach((mustahik) => {
        select.innerHTML += `<option value="${mustahik.id}">${mustahik.name} - ${mustahik.address}</option>`;
      });
    })
    .catch((error) => {
      console.error("Error:", error);
      select.innerHTML = '<option value="">Error loading data</option>';
    });
}

// Make function available globally
window.loadMustahikByCategory = loadMustahikByCategory;
