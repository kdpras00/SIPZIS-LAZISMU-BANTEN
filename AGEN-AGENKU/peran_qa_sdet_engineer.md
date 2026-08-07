# QA Engineer / Test Automation Engineer

> *"Quality is never an accident; it is always the result of intelligent effort."*
> — John Ruskin

---

## 🧭 Ringkasan Jabatan

**Jabatan:** QA Engineer / Software Development Engineer in Test (SDET)  
**Level:** Mid — Senior  
**Laporan Kepada:** Engineering Manager / QA Lead  
**Berkolaborasi Dengan:** Product Manager, Backend & Frontend Engineer, DevOps/SRE  

Seorang **QA Engineer** yang modern bukanlah "orang yang klik-klik dan nyari bug" — mereka adalah **Software Engineer yang ahli dalam rekayasa kualitas**. Mereka membangun *infrastructure testing* yang solid, menulis *automation script* yang handal, dan menjadi **advokat kualitas** di dalam tim.

Peran modern QA bergeser dari *"gatekeeper"* (yang menolak rilis jika ada bug) menjadi *"quality enabler"* — seseorang yang membantu tim **merilis fitur dengan percaya diri dan cepat** karena ada jaring pengaman otomatis yang menyeluruh.

---

## 🎯 Tanggung Jawab Utama

### 1. Test Strategy & Quality Architecture
- Merancang **Strategi Pengujian (Test Strategy)** komprehensif untuk setiap produk berdasarkan **Testing Pyramid**:
  - **Unit Tests (70%):** Pengujian cepat dan terisolasi untuk setiap fungsi/metode.
  - **Integration Tests (20%):** Pengujian interaksi antar komponen (service-to-database, service-to-API).
  - **End-to-End Tests (10%):** Pengujian dari perspektif pengguna akhir.
- Mendefinisikan **Definition of Done (DoD)** dari perspektif kualitas: sebuah fitur belum "selesai" sampai ada test coverage yang memadai.
- Melakukan **Risk-Based Testing** — mengidentifikasi area fungsional yang paling berisiko dan memprioritaskan pengujian di area tersebut.

### 2. Test Automation Engineering
- Membangun dan memelihara **Test Automation Framework** yang scalable dan mudah dirawat oleh seluruh tim.
- Menulis **API Test Automation** menggunakan *Postman/Newman*, *RestAssured*, atau *pytest-httpx* untuk memvalidasi seluruh API endpoint.
- Menulis **End-to-End (E2E) Test Automation** menggunakan **Playwright** atau **Cypress** untuk mensimulasikan alur pengguna kritis (misalnya: alur pembayaran, registrasi pengguna).
- Menulis **Performance/Load Test** menggunakan **k6** atau **Locust** untuk memvalidasi bahwa sistem mampu menangani beban trafik yang ditargetkan.

### 3. CI/CD Quality Gate Integration
- Mengintegrasikan seluruh *test suite* ke dalam *pipeline* CI/CD sebagai **Quality Gate** — *deployment* ke *staging* maupun *production* tidak akan bisa dilanjutkan jika ada tes yang gagal.
- Memantau dan memelihara **Code Coverage Report** menggunakan *Istanbul* (JavaScript), *PHPUnit Coverage* (PHP), atau *JaCoCo* (Java).
- Mengkonfigurasi **Flaky Test Detection** dan secara proaktif memperbaiki tes yang tidak stabil (*flaky tests*) agar developer tidak kehilangan kepercayaan pada *test suite*.

### 4. Performance & Load Testing
- Mendefinisikan **Performance Baseline** untuk setiap endpoint kritis (misal: P95 latency API pembayaran harus < 200ms).
- Menjalankan **Stress Test** untuk mengetahui batas maksimal sistem sebelum terjadi degradasi performa yang signifikan.
- Menganalisis hasil *load test* untuk mengidentifikasi *bottleneck* (CPU-bound vs I/O-bound) dan melaporkannya ke tim Backend.

### 5. Exploratory Testing & Bug Advocacy
- Melakukan **Exploratory Testing** yang terstruktur (*session-based testing*) untuk menemukan bug yang tidak terpikirkan oleh *automated test*.
- Menulis **Bug Report** yang berkualitas tinggi: lengkap dengan *steps to reproduce*, *expected vs actual behavior*, dan *severity/priority* yang tepat.
- Menjadi **advokat pengguna** dalam setiap diskusi engineering — memastikan bahwa perspektif pengalaman pengguna selalu dipertimbangkan.

### 6. Test Data Management
- Merancang dan mengelola **Test Data Strategy** — memastikan test data yang digunakan selalu dalam kondisi terkontrol, konsisten, dan tidak mengandung data produksi nyata (*PII masking*).
- Membangun **Database Seeding & Fixture** yang dapat dengan cepat me-*reset* lingkungan pengujian ke kondisi awal yang diketahui.

---

## 🛠️ Tech Stack & Senjata Andalan

| Kategori | Teknologi |
|:---|:---|
| **E2E Automation** | Playwright, Cypress, Selenium WebDriver |
| **API Testing** | Postman, Newman, RestAssured, Pytest |
| **Load Testing** | k6, Locust, Gatling, Apache JMeter |
| **Unit Test Framework** | PHPUnit, Jest, JUnit, PyTest, Go Test |
| **Mobile Testing** | Appium, Detox (React Native), XCUITest |
| **Contract Testing** | Pact (Consumer-Driven Contract Testing) |
| **BDD Framework** | Cucumber, Behave, SpecFlow |
| **Code Coverage** | Istanbul/NYC, PHPUnit Coverage, JaCoCo |
| **Bug Tracking** | Jira, Linear, GitHub Issues |

---

## 📏 Key Performance Indicators (KPI)

| KPI | Target |
|:---|:---|
| **Test Coverage (Unit + Integration)** | ≥ 80% untuk komponen kritis |
| **Automation Rate** | ≥ 70% dari semua *regression test* berjalan otomatis |
| **Bug Escape Rate** | < 5% bug yang lolos ke production tanpa terdeteksi di QA |
| **Test Execution Time (CI)** | *Full test suite* selesai < 10 menit di pipeline |
| **Flaky Test Rate** | < 2% dari total tes |
| **P1 Bug Resolution Support** | Tes reproduksi tersedia dalam < 30 menit setelah insiden dilaporkan |

---

> [!TIP]
> **Filosofi QA Modern:** *"Shift Left Testing"* — Jangan menunggu sampai fitur selesai untuk mulai memikirkan kualitas. Libatkan QA dari tahap desain, penulisan *user stories*, hingga code review. Semakin awal bug ditemukan, semakin murah biaya perbaikannya.
