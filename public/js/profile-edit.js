document.addEventListener("DOMContentLoaded", function () {
  // Access configuration from window object
  const config = window.profileConfig || {};

  // Initialize intl-tel-input
  const phoneInput = document.querySelector("#phone");

  // Set phone value in international format before initialization
  const existingPhone = phoneInput.value.trim();
  let phoneToSet = existingPhone;

  if (existingPhone) {
    // If phone doesn't start with +, try to detect country code or default to Indonesia
    if (!existingPhone.startsWith("+")) {
      // Remove leading 0 and non-digits, then add +62 as default (Indonesia)
      phoneToSet = "+62" + existingPhone.replace(/^0+/, "").replace(/\D/g, "");
    } else {
      // Already in international format, keep it
      phoneToSet = existingPhone;
    }
  }

  const iti = window.intlTelInput(phoneInput, {
    initialCountry: "id",
    separateDialCode: true,
    nationalMode: false, // Use international format, not national format
    formatAsYouType: false, // Don't auto-format as user types
    utilsScript:
      "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
  });

  // After initialization, set the number in international format
  if (phoneToSet && phoneToSet.startsWith("+")) {
    // Use setNumber to properly format the number based on country code
    iti.setNumber(phoneToSet);
  } else if (existingPhone) {
    // Fallback: if no + found, treat as Indonesia number
    const cleanPhone = existingPhone
      .replace(/^\+62|^62|^0/, "")
      .replace(/\D/g, "");
    if (cleanPhone) {
      iti.setNumber("+62" + cleanPhone);
    }
  }

  // Set initial width for progress bar
  const completionPercent = config.profileCompleteness || 0;
  const progressBar = document.getElementById("progressBar");
  const profileCompletion = document.getElementById("profileCompletion");

  if (progressBar) {
    progressBar.style.width = completionPercent + "%";
    progressBar.style.transition = "width 0.6s ease-in-out";

    // Set initial progress bar color based on completion
    // Reset base classes
    progressBar.className =
      "h-2.5 rounded-full transition-all duration-500 ease-out";

    if (completionPercent < 30) {
      progressBar.classList.add("bg-red-500");
    } else if (completionPercent < 70) {
      progressBar.classList.add("bg-yellow-500");
    } else {
      progressBar.classList.add("bg-green-600");
    }
  }

  if (profileCompletion) {
    profileCompletion.textContent = completionPercent + "%";
  }

  // Initialize KTP preview if exists
  const ktpPhotoUrl = config.ktpPhotoUrl;
  if (ktpPhotoUrl) {
    const ktpPreview = document.getElementById("ktpPreview");
    const ktpPlaceholder = document.getElementById("ktpPlaceholder");
    if (ktpPreview) {
      ktpPreview.src = ktpPhotoUrl;
      ktpPreview.style.display = "block";
    }
    if (ktpPlaceholder) {
      ktpPlaceholder.style.display = "none";
    }
  }

  // Phone input handler - prevent national format (0 prefix) for Indonesia only
  // Other countries will use their standard format
  let isUpdating = false; // Flag to prevent infinite loop
  phoneInput.addEventListener("input", function () {
    if (isUpdating) return; // Prevent infinite loop

    const selectedCountry = iti.getSelectedCountryData();

    // Special handling only for Indonesia (which uses 0 prefix in national format)
    if (selectedCountry.iso2 === "id") {
      // Get the current number from intl-tel-input
      const currentNumber = iti.getNumber();
      // Check if it has leading 0 after +62 (national format)
      if (currentNumber.match(/^\+620/)) {
        isUpdating = true;
        // Remove the leading 0 and re-set the number
        const cleanNumber = currentNumber
          .replace(/^\+620/, "+62")
          .replace(/\D/g, "")
          .replace(/^62/, "");
        if (cleanNumber) {
          iti.setNumber("+62" + cleanNumber);
        }
        setTimeout(() => {
          isUpdating = false;
        }, 100);
      }
    }
    // For other countries, let intl-tel-input handle the formatting naturally
  });

  // Handle country change - ensure proper format for each country
  phoneInput.addEventListener("countrychange", function () {
    const selectedCountry = iti.getSelectedCountryData();

    // Special handling only for Indonesia
    if (selectedCountry.iso2 === "id") {
      // Get current number without dial code
      const currentNumber = iti.getNumber();
      const cleanNumber = currentNumber.replace(/^\+62/, "").replace(/^0+/, "");
      if (cleanNumber && cleanNumber !== currentNumber.replace(/^\+62/, "")) {
        // Re-set the number without leading 0
        iti.setNumber("+62" + cleanNumber);
      }
    }
    // For other countries, intl-tel-input will handle formatting automatically
  });

  // Verify phone button handler
  const verifyPhoneBtn = document.getElementById("verifyPhoneBtn");
  let otpModal;

  // Function to clean up any existing backdrops
  function cleanupBackdrops() {
    // First remove all backdrops
    const existingBackdrops = document.querySelectorAll(".modal-backdrop");
    existingBackdrops.forEach((backdrop) => backdrop.remove());

    // Then specifically target our modal's backdrop if it exists
    const modalBackdrop = document.querySelector("#otpModal + .modal-backdrop");
    if (modalBackdrop) {
      modalBackdrop.remove();
    }
  }

  // Periodic cleanup to handle any stray backdrops
  setInterval(() => {
    const visibleModal = document.querySelector("#otpModal.show");
    const backdrops = document.querySelectorAll(".modal-backdrop");

    // If modal is not visible but backdrops exist, remove them
    if (!visibleModal && backdrops.length > 0) {
      cleanupBackdrops();
    }
  }, 1000);

  // Improved verify phone button with better UX
  if (verifyPhoneBtn) {
    verifyPhoneBtn.addEventListener("click", function () {
      const phoneValue = phoneInput.value.trim();

      if (!phoneValue) {
        showToast("Mohon masukkan nomor telepon", "warning");
        phoneInput.focus();
        return;
      }

      // Show loading state
      const originalText = verifyPhoneBtn.innerHTML;
      verifyPhoneBtn.disabled = true;
      verifyPhoneBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

      // Use intl-tel-input to get the full number with international format for display
      const fullPhone = iti.getNumber(); // Get the full international format number
      document.getElementById("displayPhone").textContent = fullPhone;

      // Send OTP to backend (ensure phone is correctly formatted)
      let phoneForAPI = fullPhone;
      // Remove + prefix if present
      if (phoneForAPI.startsWith("+")) {
        phoneForAPI = phoneForAPI.substring(1);
      }
      // Ensure it starts with 62
      if (!phoneForAPI.startsWith("62")) {
        showToast(
          "Format nomor telepon tidak valid. Harus dimulai dengan 62.",
          "warning",
        );
        verifyPhoneBtn.disabled = false;
        verifyPhoneBtn.innerHTML = originalText;
        return;
      }

      const csrfToken = config.csrfToken;

      fetch("/send-otp", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
          phone: phoneForAPI,
        }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          verifyPhoneBtn.disabled = false;
          verifyPhoneBtn.innerHTML = originalText;

          if (data.success) {
            // Initialize modal only when needed to avoid backdrop issues
            if (!otpModal) {
              otpModal = new bootstrap.Modal(
                document.getElementById("otpModal"),
                {
                  backdrop: false, // We'll handle backdrop manually to avoid conflicts
                  keyboard: false,
                },
              );
            }

            // Add a small delay to ensure proper initialization
            setTimeout(() => {
              // Remove any existing backdrop to prevent conflicts
              cleanupBackdrops();

              // Show modal without alert
              otpModal.show();

              // Add backdrop manually to ensure proper styling
              const modalElement = document.getElementById("otpModal");
              if (modalElement.classList.contains("show")) {
                // Remove any existing backdrops first
                cleanupBackdrops();

                // Create a single backdrop
                const backdrop = document.createElement("div");
                backdrop.className = "modal-backdrop fade show";
                // Insert backdrop before the modal element for proper z-index stacking
                if (modalElement.parentNode) {
                  modalElement.parentNode.insertBefore(backdrop, modalElement);
                } else {
                  document.body.appendChild(backdrop);
                }
              }

              // Start smart countdown
              startSmartCountdown();

              // Show success toast
              showToast("Kode OTP telah dikirim ke WhatsApp Anda", "success");
            }, 100);
          } else {
            showToast(data.message || "Gagal mengirim OTP", "warning");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          verifyPhoneBtn.disabled = false;
          verifyPhoneBtn.innerHTML = originalText;
          showToast("Terjadi kesalahan. Silakan coba lagi.", "warning");
        });
    });
  }

  // OTP Input handling with smart behavior
  const otpInputs = document.querySelectorAll(".otp-input");
  const verifyOtpBtn = document.getElementById("verifyOtpBtn");

  // Initialize smart OTP input behavior
  initializeSmartOTPInput();

  // Improved verify OTP function
  improvedVerifyOTP();

  // Smart countdown timer
  function startSmartCountdown() {
    let seconds = 60;
    const countdownElement = document.getElementById("countdown");
    const resendLink = document.getElementById("resendOtp");

    resendLink.style.pointerEvents = "none";
    resendLink.style.opacity = "0.6";

    clearInterval(window.countdownInterval);

    window.countdownInterval = setInterval(function () {
      seconds--;
      countdownElement.textContent = seconds;

      if (seconds <= 0) {
        clearInterval(window.countdownInterval);
        resendLink.style.pointerEvents = "auto";
        resendLink.style.opacity = "1";
        resendLink.innerHTML =
          '<i class="bi bi-arrow-clockwise me-1"></i>Kirim ulang kode';

        // Show expiration notification
        showToast(
          "Kode OTP telah kedaluwarsa. Silakan kirim ulang.",
          "warning",
        );
      }
    }, 1000);
  }

  // Resend OTP with improved UX
  const resendOtpBtn = document.getElementById("resendOtp");
  if (resendOtpBtn) {
    resendOtpBtn.addEventListener("click", function (e) {
      e.preventDefault();

      const fullPhone = iti.getNumber(); // Get the full international format number

      // Show loading state on button
      const originalText = this.innerHTML;
      this.innerHTML =
        '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
      this.style.pointerEvents = "none";

      // Resend OTP to backend (ensure phone is correctly formatted)
      let phoneForAPI = fullPhone;
      // Remove + prefix if present
      if (phoneForAPI.startsWith("+")) {
        phoneForAPI = phoneForAPI.substring(1);
      }
      // Ensure it starts with 62
      if (!phoneForAPI.startsWith("62")) {
        showToast(
          "Format nomor telepon tidak valid. Harus dimulai dengan 62.",
          "warning",
        );
        this.innerHTML = originalText;
        this.style.pointerEvents = "auto";
        return;
      }

      const csrfToken = config.csrfToken;

      fetch("/send-otp", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
          phone: phoneForAPI,
        }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          document.getElementById("resendOtp").innerHTML = originalText;
          document.getElementById("resendOtp").style.pointerEvents = "auto";

          if (data.success) {
            // Clear OTP inputs
            otpInputs.forEach((input) => (input.value = ""));
            otpInputs[0].focus();
            verifyOtpBtn.disabled = true;

            // Remove any existing backdrop to prevent conflicts
            cleanupBackdrops();

            // Restart countdown
            startSmartCountdown();

            // Show success toast
            showToast("Kode OTP baru telah dikirim", "success");
          } else {
            showToast(data.message || "Gagal mengirim ulang OTP", "warning");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          document.getElementById("resendOtp").innerHTML = originalText;
          document.getElementById("resendOtp").style.pointerEvents = "auto";
          showToast("Terjadi kesalahan saat mengirim ulang OTP", "warning");
        });
    });
  }

  // Profile photo preview
  const profilePhotoInput = document.getElementById("profilePhotoInput");
  if (profilePhotoInput) {
    profilePhotoInput.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const preview = document.getElementById("profilePhotoPreview");
          const defaultAvatar = document.getElementById("defaultAvatarIcon");
          if (preview) {
            preview.src = e.target.result;
            preview.style.display = "block";
          }
          if (defaultAvatar) {
            defaultAvatar.style.display = "none";
          }
          const profilePhotoText = document.getElementById("profilePhotoText");
          if (profilePhotoText) {
            profilePhotoText.textContent = "Preview foto terpilih";
          }
          calculateCompletion();
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // KTP photo preview
  const ktpInput = document.getElementById("ktpInput");
  if (ktpInput) {
    ktpInput.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById("ktpPreview").src = e.target.result;
          document.getElementById("ktpPreview").style.display = "block";
          document.getElementById("ktpPlaceholder").style.display = "none";
          // Update progress bar
          calculateCompletion();
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Bio editor sync
  const bioEditor = document.getElementById("bio_editor");
  const bioInput = document.getElementById("bio");

  if (bioEditor && bioInput) {
    bioEditor.addEventListener("input", function () {
      bioInput.value = this.innerHTML;
      calculateCompletion(); // Add this to update progress when bio changes
    });
  }

  // Set phone number format before form submission
  const form = document.getElementById("muzakkiEditForm");
  if (form) {
    form.addEventListener("submit", function (e) {
      // Combine birth date fields into a single date_of_birth field
      const birthDay = document.querySelector('[name="birth_day"]').value;
      const birthMonth = document.querySelector('[name="birth_month"]').value;
      const birthYear = document.querySelector('[name="birth_year"]').value;

      if (birthDay && birthMonth && birthYear) {
        // Create hidden input for date_of_birth
        let dateOfBirthInput = document.getElementById("date_of_birth");
        if (!dateOfBirthInput) {
          dateOfBirthInput = document.createElement("input");
          dateOfBirthInput.type = "hidden";
          dateOfBirthInput.id = "date_of_birth";
          dateOfBirthInput.name = "date_of_birth";
          form.appendChild(dateOfBirthInput);
        }
        // Format as YYYY-MM-DD for database storage
        dateOfBirthInput.value = `${birthYear}-${birthMonth.padStart(2, "0")}-${birthDay.padStart(2, "0")}`;
      }

      // Use intl-tel-input to get the full number with international format
      const fullNumber = iti.getNumber(); // Get the full international format number

      // Special handling for Indonesia: remove leading 0 if present
      const selectedCountry = iti.getSelectedCountryData();
      if (selectedCountry.iso2 === "id") {
        // Remove 0 after +62 for Indonesia
        const cleanNumber = fullNumber.replace(/^\+620/, "+62");
        phoneInput.value = cleanNumber;
      } else {
        // For other countries, use the number as-is (already in international format)
        phoneInput.value = fullNumber;
      }

      // Set country_name in a hidden field
      const countrySelect = document.getElementById("country");
      if (countrySelect && countrySelect.value) {
        let countryNameInput = document.getElementById("country_name");
        if (!countryNameInput) {
          countryNameInput = document.createElement("input");
          countryNameInput.type = "hidden";
          countryNameInput.id = "country_name";
          countryNameInput.name = "country_name";
          form.appendChild(countryNameInput);
        }
        countryNameInput.value = countrySelect.value;
      }

      // Update progress bar after form submission
      setTimeout(calculateCompletion, 1000);
    });
  }

  // Postal code formatting
  const postalInput = document.getElementById("postal_code");
  if (postalInput) {
    postalInput.addEventListener("input", function () {
      // Remove any non-digit characters
      this.value = this.value.replace(/\D/g, "");

      // Limit to 5 characters
      if (this.value.length > 5) {
        this.value = this.value.slice(0, 5);
      }
    });
  }

  // Calculate profile completion
  // Removed automatic calculation on load to prevent overriding server-side value
  // setTimeout(calculateCompletion, 100);

  // Add event listeners to update progress bar when fields change
  const formFields = [
    "name",
    "email",
    "phone",
    "gender",
    "occupation",
    "province",
    "city",
    "district",
    "village",
    "postal_code",
    "address",
    "bio",
  ];
  formFields.forEach((fieldId) => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.addEventListener("input", calculateCompletion);
      field.addEventListener("change", calculateCompletion);
    }
  });

  // Add event listeners for select fields
  const selectFields = [
    "gender",
    "occupation",
    "province",
    "city",
    "district",
    "village",
    "country",
  ];
  selectFields.forEach((fieldId) => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.addEventListener("change", function (e) {
        // Only calculate if the event was triggered by the user
        if (e.isTrusted) {
          calculateCompletion();
        }
      });
    }
  });

  // Add event listeners for date of birth fields
  const dateFields = ["birth_day", "birth_month", "birth_year"];
  dateFields.forEach((fieldName) => {
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (field) {
      field.addEventListener("change", calculateCompletion);
    }
  });

  // Clear OTP inputs when modal is closed
  const otpModalEl = document.getElementById("otpModal");
  if (otpModalEl) {
    otpModalEl.addEventListener("hidden.bs.modal", function () {
      otpInputs.forEach((input) => (input.value = ""));
      verifyOtpBtn.disabled = true;
      clearInterval(window.countdownInterval);

      // Ensure backdrop is properly removed
      cleanupBackdrops();
    });

    // Also handle manual close button click
    const closeBtn = otpModalEl.querySelector(".btn-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", function () {
        setTimeout(cleanupBackdrops, 100);
      });
    }
  }

  // Region dropdown functions
  const countrySelect = document.querySelector("#country");
  const provinceSelect = document.querySelector("#province");
  const citySelect = document.querySelector("#city");
  const districtSelect = document.querySelector("#district");
  const villageSelect = document.querySelector("#village");

  function initTomSelect(el, options = {}) {
    if (typeof TomSelect !== "undefined" && el) {
      const defaultOptions = {
        create: false,
        placeholder: el.querySelector('option[value=""]') ? el.querySelector('option[value=""]').textContent : "Pilih...",
        allowEmptyOption: true,
        plugins: [], // completely remove clear_button plugin to hide the 'x'
        controlInput: null,
        render: {
          no_results: function(data, escape) {
            return '<div class="no-results text-xs text-[#8b7e74] p-2">Tidak ditemukan "' + escape(data.input) + '"</div>';
          }
        }
      };
      
      const mergedOptions = Object.assign({}, defaultOptions, options);
      if (el.tomselect) {
        el.tomselect.destroy();
      }
      
      const ts = new TomSelect(el, mergedOptions);
      
      // If search is disabled (controlInput: null), add no-search class to wrapper
      if (mergedOptions.controlInput === null && ts.wrapper) {
        ts.wrapper.classList.add("no-search");
      }
      
      return ts;
    }
    return null;
  }

  function resetDropdown(select, placeholder) {
    if (select.tomselect) {
      select.tomselect.clear(true);
      select.tomselect.clearOptions();
      select.tomselect.addOption({ value: "", text: placeholder });
      select.tomselect.setValue("");
    } else {
      select.innerHTML = `<option value="">${placeholder}</option>`;
    }
  }

  function addSelectOption(select, value, text) {
    if (select.tomselect) {
      select.tomselect.addOption({ value: value, text: text });
    } else {
      const option = document.createElement("option");
      option.value = value;
      option.textContent = text;
      select.appendChild(option);
    }
  }

  function selectOptionByText(select, savedText, triggerChange = true) {
    if (!savedText) return;
    
    if (select.tomselect) {
      const tsOptions = select.tomselect.options;
      let matchValue = "";
      for (const val in tsOptions) {
        if (tsOptions[val].text.trim().toLowerCase() === savedText.trim().toLowerCase()) {
          matchValue = val;
          break;
        }
      }
      if (matchValue) {
        select.tomselect.setValue(matchValue, !triggerChange);
        if (triggerChange) {
          const event = new Event("change");
          select.dispatchEvent(event);
        }
      }
    } else {
      const options = select.options;
      for (let i = 0; i < options.length; i++) {
        if (options[i].text.trim().toLowerCase() === savedText.trim().toLowerCase()) {
          select.selectedIndex = i;
          if (triggerChange) {
            const event = new Event("change");
            select.dispatchEvent(event);
          }
          break;
        }
      }
    }
  }

  // Initialize Tom Select on page load
  if (typeof TomSelect !== "undefined") {
    const genderSelect = document.querySelector("#gender");
    if (genderSelect) initTomSelect(genderSelect, { controlInput: null });

    const occupationSelect = document.querySelector("#occupation");
    if (occupationSelect) initTomSelect(occupationSelect, { controlInput: null });

    const birthDaySelect = document.querySelector('select[name="birth_day"]');
    if (birthDaySelect) initTomSelect(birthDaySelect, { controlInput: null });

    const birthMonthSelect = document.querySelector('select[name="birth_month"]');
    if (birthMonthSelect) initTomSelect(birthMonthSelect, { controlInput: null });

    const birthYearSelect = document.querySelector('select[name="birth_year"]');
    if (birthYearSelect) initTomSelect(birthYearSelect, { controlInput: null });

    if (countrySelect) initTomSelect(countrySelect, { controlInput: null });
    if (provinceSelect) initTomSelect(provinceSelect, { controlInput: null });
    if (citySelect) initTomSelect(citySelect, { controlInput: null });
    if (districtSelect) initTomSelect(districtSelect, { controlInput: null });
    if (villageSelect) initTomSelect(villageSelect, { controlInput: null });
  }

  function fetchCountries() {
    fetch("/regions/countries")
      .then((res) => res.json())
      .then((data) => {
        resetDropdown(countrySelect, "Pilih Negara");
        data.forEach((country) => {
          addSelectOption(countrySelect, country.name, country.name);
        });

        // Set value if existing data (default to Indonesia)
        const savedCountry = config.savedCountry || "Indonesia";
        if (countrySelect.tomselect) {
          countrySelect.tomselect.setValue(savedCountry);
        } else {
          countrySelect.value = savedCountry;
        }

        if (!config.savedCountry || savedCountry.toLowerCase() === "indonesia") {
          showIndonesiaFields();
          fetchProvinces();
        } else {
          hideIndonesiaFields();
        }
      })
      .catch((err) => {
        console.error("Gagal memuat negara:", err);
        showIndonesiaFields();
        fetchProvinces();
      });
  }

  // Helper functions to show/hide Indonesia-specific fields
  function showIndonesiaFields() {
    const fields = ["province", "city", "district", "village", "postal_code"];
    fields.forEach((fieldId) => {
      const field = document.getElementById(fieldId);
      if (field) {
        const wrapper = field.closest("div");
        if (wrapper) wrapper.style.display = "";
        if (field.tomselect) {
          field.tomselect.enable();
        }
      }
    });
  }

  // Helper functions to show/hide Indonesia-specific fields
  function hideIndonesiaFields() {
    const fields = ["province", "city", "district", "village", "postal_code"];
    fields.forEach((fieldId) => {
      const field = document.getElementById(fieldId);
      if (field) {
        const wrapper = field.closest("div");
        if (wrapper) wrapper.style.display = "none";
        if (field.tomselect) {
          field.tomselect.disable();
        }
      }
    });
  }

  function fetchProvinces() {
    fetch("/regions/provinces/indonesia")
      .then((res) => res.json())
      .then((data) => {
        resetDropdown(provinceSelect, "Pilih Provinsi");
        data.forEach((prov) => {
          addSelectOption(provinceSelect, prov.id, prov.name);
        });

        // Set value if existing data
        const savedProvince = config.savedProvince;
        if (savedProvince) {
          selectOptionByText(provinceSelect, savedProvince, true);
        }
      })
      .catch((err) => console.error("Gagal memuat provinsi:", err));
  }

  function fetchCities(provinceId) {
    if (!provinceId) return;

    fetch(`/regions/cities/${provinceId}`)
      .then((res) => res.json())
      .then((data) => {
        resetDropdown(citySelect, "Pilih Kota/Kabupaten");
        data.forEach((city) => {
          addSelectOption(citySelect, city.id, city.name);
        });

        // Set value if existing data
        const savedCity = config.savedCity;
        if (savedCity) {
          selectOptionByText(citySelect, savedCity, true);
        }
      })
      .catch((err) => console.error("Gagal memuat kota:", err));
  }

  function fetchDistricts(cityId) {
    if (!cityId) return;

    fetch(`/regions/districts/${cityId}`)
      .then((res) => res.json())
      .then((data) => {
        resetDropdown(districtSelect, "Pilih Kecamatan");
        data.forEach((dist) => {
          addSelectOption(districtSelect, dist.id, dist.name);
        });

        // Set value if existing data
        const savedDistrict = config.savedDistrict;
        if (savedDistrict) {
          selectOptionByText(districtSelect, savedDistrict, true);
        }
      })
      .catch((err) => console.error("Gagal memuat kecamatan:", err));
  }

  function fetchVillages(districtId) {
    if (!districtId) return;

    fetch(`/regions/villages/${districtId}`)
      .then((res) => res.json())
      .then((data) => {
        resetDropdown(villageSelect, "Pilih Kelurahan");
        data.forEach((village) => {
          addSelectOption(villageSelect, village.id, village.name);
        });

        // Set value if existing data
        const savedVillage = config.savedVillage;
        if (savedVillage) {
          if (villageSelect.tomselect) {
            if (villageSelect.tomselect.options[savedVillage]) {
              villageSelect.tomselect.setValue(savedVillage);
            } else {
              selectOptionByText(villageSelect, savedVillage, false);
            }
          } else {
            const options = villageSelect.options;
            let matched = false;

            for (let i = 0; i < options.length; i++) {
              if (options[i].value === savedVillage) {
                villageSelect.selectedIndex = i;
                matched = true;
                break;
              }
            }

            if (!matched) {
              for (let i = 0; i < options.length; i++) {
                if (
                  options[i].text.trim().toLowerCase() ===
                  savedVillage.trim().toLowerCase()
                ) {
                  villageSelect.selectedIndex = i;
                  break;
                }
              }
            }
          }
        }
      })
      .catch((err) => console.error("Gagal memuat kelurahan:", err));
  }

  // Event listeners for cascading dropdowns
  // When country is changed
  if (countrySelect) {
    countrySelect.addEventListener("change", function () {
      const val = this.value.toLowerCase();

      // Save the country name in a hidden field
      let countryNameInput = document.getElementById("country_name");
      if (!countryNameInput) {
        countryNameInput = document.createElement("input");
        countryNameInput.type = "hidden";
        countryNameInput.id = "country_name";
        countryNameInput.name = "country_name";
        form.appendChild(countryNameInput);
      }
      countryNameInput.value = this.value; // Save the actual country name

      if (val === "indonesia") {
        // Show Indonesia-specific fields
        showIndonesiaFields();
        fetchProvinces();
      } else {
        // Hide Indonesia-specific fields
        hideIndonesiaFields();

        // Reset dropdowns
        resetDropdown(provinceSelect, "Pilih Provinsi");
        resetDropdown(citySelect, "Pilih Kota/Kabupaten");
        resetDropdown(districtSelect, "Pilih Kecamatan");
        resetDropdown(villageSelect, "Pilih Kelurahan");
      }
    });
  }

  // When province is changed
  if (provinceSelect) {
    provinceSelect.addEventListener("change", function () {
      if (this.value) {
        // Save the province name in a hidden field
        const provinceName = this.options[this.selectedIndex].textContent;
        let provinceNameInput = document.getElementById("province_name");
        if (!provinceNameInput) {
          provinceNameInput = document.createElement("input");
          provinceNameInput.type = "hidden";
          provinceNameInput.id = "province_name";
          provinceNameInput.name = "province_name";
          form.appendChild(provinceNameInput);
        }
        provinceNameInput.value = provinceName;

        fetchCities(this.value);
      } else {
        resetDropdown(citySelect, "Pilih Kota/Kabupaten");
        resetDropdown(districtSelect, "Pilih Kecamatan");
        resetDropdown(villageSelect, "Pilih Kelurahan");
      }
    });
  }

  // When city is changed
  if (citySelect) {
    citySelect.addEventListener("change", function () {
      if (this.value) {
        // Save the city name in a hidden field
        const cityName = this.options[this.selectedIndex].textContent;
        let cityNameInput = document.getElementById("city_name");
        if (!cityNameInput) {
          cityNameInput = document.createElement("input");
          cityNameInput.type = "hidden";
          cityNameInput.id = "city_name";
          cityNameInput.name = "city_name";
          form.appendChild(cityNameInput);
        }
        cityNameInput.value = cityName;

        fetchDistricts(this.value);
      } else {
        resetDropdown(districtSelect, "Pilih Kecamatan");
        resetDropdown(villageSelect, "Pilih Kelurahan");
      }
    });
  }

  // When district is changed
  if (districtSelect) {
    districtSelect.addEventListener("change", function () {
      if (this.value) {
        // Save the district name in a hidden field
        const districtName = this.options[this.selectedIndex].textContent;
        let districtNameInput = document.getElementById("district_name");
        if (!districtNameInput) {
          districtNameInput = document.createElement("input");
          districtNameInput.type = "hidden";
          districtNameInput.id = "district_name";
          districtNameInput.name = "district_name";
          form.appendChild(districtNameInput);
        }
        districtNameInput.value = districtName;

        fetchVillages(this.value);

        // Get the selected district name
        const selectedOption = this.options[this.selectedIndex];
        const districtNameForPostal = selectedOption.textContent;

        // Validate and suggest postal code based on district only
        validatePostalCodeByDistrict(districtNameForPostal);
      } else {
        resetDropdown(villageSelect, "Pilih Kelurahan");
      }
    });
  }

  // When village is changed
  if (villageSelect) {
    villageSelect.addEventListener("change", function () {
      if (this.value) {
        // Save the village name in a hidden field
        const villageName = this.options[this.selectedIndex].textContent;
        let villageNameInput = document.getElementById("village_name");
        if (!villageNameInput) {
          villageNameInput = document.createElement("input");
          villageNameInput.type = "hidden";
          villageNameInput.id = "village_name";
          villageNameInput.name = "village_name";
          form.appendChild(villageNameInput);
        }
        villageNameInput.value = villageName;

        // Get the selected district and village names
        const districtSelect = document.querySelector("#district");
        const districtOption =
          districtSelect.options[districtSelect.selectedIndex];
        const districtName = districtOption.textContent;

        const villageOption = this.options[this.selectedIndex];
        const villageNameForPostal = villageOption.textContent;

        // Validate postal code based on both district and village
        validatePostalCodeByDistrictAndVillage(
          districtName,
          villageNameForPostal,
        );
      }
    });
  }

  // Validate postal code based on district name only
  function validatePostalCodeByDistrict(districtName) {
    fetch("/regions/validate-postal-code", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": config.csrfToken,
      },
      body: JSON.stringify({
        district: districtName,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success && data.suggestion) {
          // Auto-populate the postal code field with the suggestion only if field is empty
          const postalInput = document.getElementById("postal_code");
          const oldValue = postalInput.value;

          if (!oldValue || oldValue.length === 0) {
            postalInput.value = data.suggestion;
            showPostalCodeFeedback(
              "Kode pos " +
                data.suggestion +
                " diisi secara otomatis berdasarkan kecamatan " +
                districtName,
              "success",
            );
          }
        }
      })
      .catch((err) => {
        console.error("Error validating postal code:", err);
      });
  }

  // Validate postal code based on both district and village
  function validatePostalCodeByDistrictAndVillage(districtName, villageName) {
    fetch("/regions/validate-postal-code", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": config.csrfToken,
      },
      body: JSON.stringify({
        district: districtName,
        village: villageName,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success && data.postal_code) {
          // Auto-populate the postal code field with the specific village code
          const postalInput = document.getElementById("postal_code");
          postalInput.value = data.postal_code;
          showPostalCodeFeedback(data.message, "success");
        } else if (data.success && data.suggestion) {
          // Fallback to district-level suggestion
          const postalInput = document.getElementById("postal_code");
          const oldValue = postalInput.value;

          if (!oldValue || oldValue.length === 0) {
            postalInput.value = data.suggestion;
            showPostalCodeFeedback(
              "Kode pos " +
                data.suggestion +
                " diisi secara otomatis berdasarkan kecamatan " +
                districtName,
              "success",
            );
          }
        }
      })
      .catch((err) => {
        console.error("Error validating postal code:", err);
      });
  }

  // Show feedback for postal code validation
  function showPostalCodeFeedback(message, type) {
    // Remove any existing feedback
    const existingFeedback = document
      .querySelector("#postal_code")
      .closest(".mb-3")
      .querySelector(".postal-feedback");
    if (existingFeedback) {
      existingFeedback.remove();
    }

    // Create feedback element
    const feedback = document.createElement("div");
    feedback.className =
      "postal-feedback small mt-1 " +
      (type === "success" ? "text-success" : "text-warning");
    feedback.textContent = message;

    // Insert after the postal code input
    document
      .getElementById("postal_code")
      .closest(".mb-3")
      .appendChild(feedback);

    // Auto-hide after 5 seconds
    setTimeout(() => {
      if (feedback.parentNode) {
        feedback.remove();
      }
    }, 5000);
  }

  // Validate postal code input in real-time
  if (postalInput) {
    postalInput.addEventListener("blur", function () {
      const postalCode = this.value.trim();
      if (postalCode.length > 0 && postalCode.length !== 5) {
        showPostalCodeFeedback(
          "Kode pos harus terdiri dari 5 digit angka",
          "warning",
        );
        return;
      }

      if (postalCode.length === 5 && /^\d+$/.test(postalCode)) {
        // Get the selected district and village names for validation context
        const districtSelect = document.querySelector("#district");
        const villageSelect = document.querySelector("#village");

        if (districtSelect && districtSelect.value) {
          const districtOption =
            districtSelect.options[districtSelect.selectedIndex];
          const districtName = districtOption.textContent;

          let validationData = {
            district: districtName,
          };

          // Include village if selected
          if (villageSelect && villageSelect.value) {
            const villageOption =
              villageSelect.options[villageSelect.selectedIndex];
            const villageName = villageOption.textContent;
            validationData.village = villageName;
          }

          // Validate the entered postal code
          fetch("/regions/validate-postal-code", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": config.csrfToken,
            },
            body: JSON.stringify(validationData),
          })
            .then((res) => res.json())
            .then((data) => {
              if (data.success) {
                // If we have a specific postal code for village, check against it
                if (data.postal_code) {
                  if (parseInt(postalCode) !== data.postal_code) {
                    showPostalCodeFeedback(
                      "Kode pos " +
                        postalCode +
                        " mungkin tidak sesuai dengan kelurahan " +
                        validationData.village +
                        ". Seharusnya: " +
                        data.postal_code,
                      "warning",
                    );
                  } else {
                    // Valid code, remove any previous warnings
                    const existingFeedback = document
                      .querySelector("#postal_code")
                      .closest(".mb-3")
                      .querySelector(".postal-feedback");
                    if (
                      existingFeedback &&
                      existingFeedback.classList.contains("text-warning")
                    ) {
                      existingFeedback.remove();
                    }
                  }
                }
                // If we have specific postal codes list, check against it
                else if (
                  data.postal_codes &&
                  Array.isArray(data.postal_codes)
                ) {
                  // Convert postal code to integer for comparison
                  const postalCodeInt = parseInt(postalCode);
                  const isValid = data.postal_codes.some(
                    (code) => parseInt(code) === postalCodeInt,
                  );

                  if (!isValid) {
                    showPostalCodeFeedback(
                      "Kode pos " +
                        postalCode +
                        " mungkin tidak sesuai dengan kecamatan " +
                        districtName +
                        ". Valid: " +
                        data.postal_codes.join(", "),
                      "warning",
                    );
                  } else {
                    // Valid code, remove any previous warnings
                    const existingFeedback = document
                      .querySelector("#postal_code")
                      .closest(".mb-3")
                      .querySelector(".postal-feedback");
                    if (
                      existingFeedback &&
                      existingFeedback.classList.contains("text-warning")
                    ) {
                      existingFeedback.remove();
                    }
                  }
                }
              }
            })
            .catch((err) => {
              console.error("Error validating postal code:", err);
            });
        }
      } else if (postalCode.length > 0) {
        showPostalCodeFeedback(
          "Kode pos harus terdiri dari 5 digit angka",
          "warning",
        );
      }
    });
  }

  // Initial load - hide province, city, district, village, and postal code if country is not Indonesia
  fetchCountries();

  // Add a small delay to ensure country is set properly
  setTimeout(function () {
    const savedCountry = config.savedCountry;
    if (savedCountry && savedCountry.toLowerCase() !== "indonesia") {
      const provinceSelect = document.querySelector("#province");
      if (provinceSelect) provinceSelect.parentElement.style.display = "none";

      const citySelect = document.querySelector("#city");
      if (citySelect) citySelect.parentElement.style.display = "none";

      const districtSelect = document.querySelector("#district");
      if (districtSelect) districtSelect.parentElement.style.display = "none";

      const villageSelect = document.querySelector("#village");
      if (villageSelect) villageSelect.parentElement.style.display = "none";

      const postalCodeInput = document.querySelector("#postal_code");
      if (postalCodeInput)
        postalCodeInput.closest(".mb-5").style.display = "none";
    } else if (savedCountry && savedCountry.toLowerCase() === "indonesia") {
      // Make sure province dropdown is visible for Indonesia
      // Make sure province dropdown is visible for Indonesia
      const provinceSelect = document.querySelector("#province");
      if (provinceSelect) provinceSelect.parentElement.style.display = "block";

      const citySelect = document.querySelector("#city");
      if (citySelect) citySelect.parentElement.style.display = "block";

      const districtSelect = document.querySelector("#district");
      if (districtSelect) districtSelect.parentElement.style.display = "block";

      const villageSelect = document.querySelector("#village");
      if (villageSelect) villageSelect.parentElement.style.display = "block";

      const postalCodeInput = document.querySelector("#postal_code");
      if (postalCodeInput)
        postalCodeInput.closest(".mb-5").style.display = "block";

      // Load provinces and set the selected value
      fetchProvinces();
    }

    // Ensure country value is set correctly
    if (savedCountry && countrySelect) {
      countrySelect.value = savedCountry;
    }
  }, 500);

  // Add phone_verified input if user is already verified
  if (config.isPhoneVerified) {
    const phoneVerifiedInput = document.createElement("input");
    phoneVerifiedInput.type = "hidden";
    phoneVerifiedInput.name = "phone_verified";
    phoneVerifiedInput.value = "1";
    phoneVerifiedInput.id = "phone_verified_input";
    form.appendChild(phoneVerifiedInput);
  }

  // Ensure all required hidden inputs exist before form load
  // Create phone_verified input if phone is already verified
  const isPhoneVerified = config.isPhoneVerified;
  if (isPhoneVerified) {
    let phoneVerifiedInput = document.getElementById("phone_verified_input");
    if (!phoneVerifiedInput) {
      phoneVerifiedInput = document.createElement("input");
      phoneVerifiedInput.type = "hidden";
      phoneVerifiedInput.name = "phone_verified";
      phoneVerifiedInput.value = "1";
      phoneVerifiedInput.id = "phone_verified_input";
      form.appendChild(phoneVerifiedInput);
    }
  }

  // Create country_name input with existing value
  const existingCountry = config.savedCountry || "Indonesia";
  if (existingCountry) {
    let countryNameInput = document.getElementById("country_name");
    if (!countryNameInput) {
      countryNameInput = document.createElement("input");
      countryNameInput.type = "hidden";
      countryNameInput.id = "country_name";
      countryNameInput.name = "country_name";
      countryNameInput.value = existingCountry;
      form.appendChild(countryNameInput);
    }
  }

  // ===================================
  // SMART OTP INPUT BEHAVIOR
  // ===================================
  function initializeSmartOTPInput() {
    const otpInputs = document.querySelectorAll(".otp-input");

    otpInputs.forEach((input, index) => {
      // Auto-advance on input
      input.addEventListener("input", function (e) {
        const value = e.target.value;

        // Only allow numbers
        if (!/^\d*$/.test(value)) {
          e.target.value = "";
          return;
        }

        // Auto-advance to next input
        if (value && index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }

        checkOtpComplete();
      });

      // Smart backspace behavior
      input.addEventListener("keydown", function (e) {
        if (e.key === "Backspace") {
          if (!e.target.value && index > 0) {
            // Move to previous input if current is empty
            otpInputs[index - 1].focus();
            otpInputs[index - 1].value = "";
          }
        } else if (e.key === "ArrowLeft" && index > 0) {
          otpInputs[index - 1].focus();
        } else if (e.key === "ArrowRight" && index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }
      });

      // Enhanced paste behavior
      input.addEventListener("paste", function (e) {
        e.preventDefault();
        const pastedData = e.clipboardData.getData("text").replace(/\D/g, "");

        for (
          let i = 0;
          i < pastedData.length && index + i < otpInputs.length;
          i++
        ) {
          otpInputs[index + i].value = pastedData[i];
        }

        const lastIndex = Math.min(
          index + pastedData.length - 1,
          otpInputs.length - 1,
        );
        otpInputs[lastIndex].focus();

        checkOtpComplete();
      });

      // Select all on focus for easier editing
      input.addEventListener("focus", function () {
        this.select();
      });
    });
  }

  // ===================================
  // AUTO-VERIFY WHEN COMPLETE
  // ===================================
  function checkOtpComplete() {
    const otpInputs = document.querySelectorAll(".otp-input");
    const verifyOtpBtn = document.getElementById("verifyOtpBtn");
    const allFilled = Array.from(otpInputs).every(
      (input) => input.value.length === 1,
    );

    verifyOtpBtn.disabled = !allFilled;

    // Auto-submit if all filled
    if (allFilled) {
      // Add small delay for more natural UX
      setTimeout(() => {
        verifyOtpBtn.click();
      }, 300);
    }
  }

  // ===================================
  // IMPROVED VERIFY OTP
  // ===================================
  function improvedVerifyOTP() {
    const verifyOtpBtn = document.getElementById("verifyOtpBtn");
    const otpInputs = document.querySelectorAll(".otp-input");

    if (verifyOtpBtn) {
      verifyOtpBtn.addEventListener("click", function () {
        const otp = Array.from(otpInputs)
          .map((input) => input.value)
          .join("");

        // Show loading state
        const originalText = verifyOtpBtn.innerHTML;
        verifyOtpBtn.disabled = true;
        verifyOtpBtn.innerHTML =
          '<span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...';

        const csrfToken = config.csrfToken;

        fetch("/verify-otp", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
          },
          body: JSON.stringify({
            otp: otp,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Success animation
              verifyOtpBtn.innerHTML =
                '<i class="bi bi-check-circle-fill me-2"></i>Berhasil!';
              verifyOtpBtn.classList.add("btn-success");

              setTimeout(() => {
                // Use our modal instance if available, otherwise get it from Bootstrap
                if (otpModal) {
                  otpModal.hide();
                } else {
                  const modalInstance = bootstrap.Modal.getInstance(
                    document.getElementById("otpModal"),
                  );
                  if (modalInstance) {
                    modalInstance.hide();
                  }
                }

                // Clean up any remaining backdrops
                setTimeout(cleanupBackdrops, 100);

                // Update UI
                updateVerificationStatus();

                showToast("Nomor WhatsApp berhasil diverifikasi!", "success");
              }, 1000);
            } else {
              // Error state
              verifyOtpBtn.innerHTML = originalText;
              verifyOtpBtn.disabled = false;

              // Shake animation for error
              otpInputs.forEach((input) => {
                input.classList.add("shake");
                input.value = "";
              });

              setTimeout(() => {
                otpInputs.forEach((input) => input.classList.remove("shake"));
                otpInputs[0].focus();
              }, 500);

              showToast("Kode OTP salah. Silakan coba lagi.", "warning");
            }
          })
          .catch((error) => {
            verifyOtpBtn.innerHTML = originalText;
            verifyOtpBtn.disabled = false;
            showToast("Terjadi kesalahan. Silakan coba lagi.", "warning");
          });
      });
    }
  }

  // ===================================
  // UPDATE VERIFICATION STATUS
  // ===================================
  function updateVerificationStatus() {
    const verifyBtn = document.getElementById("verifyPhoneBtn");
    const verifyText = document.getElementById("verifyButtonText");
    const verifyCheckmark = document.getElementById("verifyCheckmark");

    verifyText.style.display = "none";
    verifyCheckmark.style.display = "inline-block";
    verifyBtn.style.backgroundColor = "#198754"; // Bootstrap success color
    verifyBtn.disabled = true;

    // Show the verified alert and hide the warning alert
    const warningAlert = document.querySelector(".alert-warning");
    if (warningAlert) {
      warningAlert.style.display = "none";
    }

    const verifiedAlert = document.getElementById("verifiedAlert");
    if (verifiedAlert) {
      verifiedAlert.style.display = "block";
    } else {
      // Create the verified alert if it doesn't exist
      const alertDiv = document.createElement("div");
      alertDiv.className = "alert alert-success border-0 mt-2 py-2 px-3 small";
      alertDiv.id = "verifiedAlert";
      alertDiv.innerHTML =
        '<i class="bi bi-check-circle-fill me-1"></i> Nomor telepon sudah diverifikasi';
      document.querySelector('[for="phone"]').parentNode.appendChild(alertDiv);
    }

    // Create the not verified alert if it doesn't exist (for future use)
    const notVerifiedAlert = document.getElementById("notVerifiedAlert");
    if (!notVerifiedAlert) {
      const alertDiv = document.createElement("div");
      alertDiv.className = "alert alert-warning border-0 mt-2 py-2 px-3 small";
      alertDiv.id = "notVerifiedAlert";
      alertDiv.innerHTML =
        '<i class="bi bi-exclamation-triangle me-1"></i> Mohon verifikasi nomor telepon Anda';
      document.querySelector('[for="phone"]').parentNode.appendChild(alertDiv);
      alertDiv.style.display = "none"; // Hide it initially since we're verified
    }

    // Update the phone_verified field in the form
    let phoneVerifiedInput = document.getElementById("phone_verified_input");
    if (!phoneVerifiedInput) {
      phoneVerifiedInput = document.createElement("input");
      phoneVerifiedInput.type = "hidden";
      phoneVerifiedInput.name = "phone_verified";
      phoneVerifiedInput.id = "phone_verified_input";
      document
        .getElementById("muzakkiEditForm")
        .appendChild(phoneVerifiedInput);
    }
    phoneVerifiedInput.value = "1";
  }

  // ===================================
  // NON-INTRUSIVE NOTIFICATIONS
  // ===================================
  function showToast(message, type = "info") {
    // Remove existing toast if any
    const existingToast = document.querySelector(".otp-toast");
    if (existingToast) {
      existingToast.remove();
    }

    const toast = document.createElement("div");
    toast.className = `otp-toast otp-toast-${type}`;
    toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${type === "success" ? "check-circle-fill" : type === "warning" ? "exclamation-triangle-fill" : "info-circle-fill"} me-2"></i>
                <span>${message}</span>
            </div>
        `;

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => toast.classList.add("show"), 10);

    // Auto-hide after 3 seconds
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }
});

function formatText(command) {
  document.execCommand(command, false, null);
  document.getElementById("bio_editor").focus();
}

function calculateCompletion() {
  const fields = [
    "name",
    "email",
    "phone",
    "gender",
    "address",
    "city",
    "province",
    "district",
    "village",
    "postal_code",
    "country",
    "campaign_url",
    "profile_photo",
    "ktp_photo",
    "bio",
    "occupation",
    "date_of_birth",
  ];

  let filled = 0;
  let total = fields.length;

  fields.forEach((field) => {
    // Special handling for date_of_birth - check all three fields
    if (field === "date_of_birth") {
      const birthDay = document.querySelector('[name="birth_day"]');
      const birthMonth = document.querySelector('[name="birth_month"]');
      const birthYear = document.querySelector('[name="birth_year"]');

      if (
        birthDay &&
        birthMonth &&
        birthYear &&
        birthDay.value &&
        birthDay.value.trim() !== "" &&
        birthMonth.value &&
        birthMonth.value.trim() !== "" &&
        birthYear.value &&
        birthYear.value.trim() !== ""
      ) {
        filled++;
      }
    }
    // Special handling for profile_photo - check if file is selected or preview exists
    else if (field === "profile_photo") {
      const profilePhotoInput = document.getElementById("profilePhotoInput");
      const profilePhotoPreview = document.getElementById(
        "profilePhotoPreview",
      );

      // Check if file is selected or preview image exists (not default placeholder)
      if (
        profilePhotoInput &&
        profilePhotoInput.files &&
        profilePhotoInput.files.length > 0
      ) {
        filled++;
      } else if (profilePhotoPreview && profilePhotoPreview.src) {
        // Check if preview is not the default placeholder image
        const defaultImage = profilePhotoPreview.src.includes(
          "profile-default.jpg",
        );
        if (!defaultImage) {
          filled++;
        }
      }
    }
    // Special handling for ktp_photo - check if file is selected or preview exists
    else if (field === "ktp_photo") {
      const ktpInput = document.getElementById("ktpInput");
      const ktpPreview = document.getElementById("ktpPreview");

      // Check if file is selected or preview image exists
      if (ktpInput && ktpInput.files && ktpInput.files.length > 0) {
        filled++;
      } else if (
        ktpPreview &&
        ktpPreview.src &&
        ktpPreview.style.display !== "none"
      ) {
        filled++;
      }
    } else {
      const element =
        document.getElementById(field) ||
        document.querySelector(`[name="${field}"]`);

      // Special handling for select fields - check if a valid option is selected
      if (element) {
        if (element.tagName === "SELECT") {
          // For village select, make sure a valid option is selected (not the placeholder)
          if (field === "village") {
            if (
              element.value &&
              element.value.trim() !== "" &&
              element.selectedIndex > 0 &&
              element.value !== "Pilih Kelurahan"
            ) {
              filled++;
            }
          } else {
            // For other select fields, make sure a valid option is selected (not the placeholder)
            if (
              element.value &&
              element.value.trim() !== "" &&
              element.selectedIndex > 0
            ) {
              filled++;
            }
          }
        } else {
          // For text fields, check if they have a value
          if (element.value && element.value.trim() !== "") {
            filled++;
          }
        }
      }
    }
  });

  const percentage = Math.round((filled / total) * 100);

  // Update progress bar
  const progressBar = document.getElementById("progressBar");
  const profileCompletion = document.getElementById("profileCompletion");

  if (progressBar) {
    progressBar.style.width = percentage + "%";
    progressBar.style.transition = "width 0.6s ease-in-out";
  }

  if (profileCompletion) {
    profileCompletion.textContent = percentage + "%";
  }

  // Update progress bar color based on completion percentage
  if (progressBar) {
    // Reset base classes
    progressBar.className =
      "h-2.5 rounded-full transition-all duration-500 ease-out";

    if (percentage < 30) {
      progressBar.classList.add("bg-red-500");
    } else if (percentage < 70) {
      progressBar.classList.add("bg-yellow-500");
    } else {
      progressBar.classList.add("bg-green-600");
    }
  }

  return percentage;
}
