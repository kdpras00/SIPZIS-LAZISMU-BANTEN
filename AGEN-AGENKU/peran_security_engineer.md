# Security Engineer / AppSec Engineer

> *"Security is not a product, but a process. The goal is not to build a system that cannot be attacked — but one that fails gracefully."*
> — Bruce Schneier

---

## 🧭 Ringkasan Jabatan

**Jabatan:** Security Engineer / Application Security (AppSec) Engineer  
**Level:** Mid — Senior  
**Laporan Kepada:** CISO / Engineering Manager  
**Berkolaborasi Dengan:** Software Architect, Backend & Frontend Engineer, DevOps/SRE, Legal & Compliance  

Seorang **Security Engineer** adalah *guardian* dari seluruh lapisan sistem — mulai dari baris kode aplikasi, konfigurasi server, hingga jalur jaringan yang menghubungkan data pengguna ke database. Mereka bukan sekadar pelaksana kebijakan *password complexity* atau yang mengonfigurasi *firewall* satu kali kemudian selesai.

Seorang *Security Engineer* yang sejati mengadopsi **mentalitas penyerang (adversarial mindset)** — mereka berpikir seperti hacker untuk membangun pertahanan yang tahan uji, bukan hanya yang terlihat aman di permukaan.

---

## 🎯 Tanggung Jawab Utama

### 1. Threat Modeling & Secure Design Review
- Melakukan **Threat Modeling** menggunakan metodologi **STRIDE** (Spoofing, Tampering, Repudiation, Information Disclosure, Denial of Service, Elevation of Privilege) pada setiap fitur atau sistem baru sebelum implementasi dimulai.
- Berpartisipasi dalam *architecture review* untuk memastikan bahwa setiap keputusan desain mempertimbangkan implikasi keamanannya dari awal (*Security by Design*).
- Mendefinisikan **Attack Surface** aplikasi dan memastikan area-area kritis dilindungi dengan lapisan pertahanan yang memadai.

### 2. Application Security Testing (SAST, DAST, Penetration Testing)
- Mengintegrasikan **Static Application Security Testing (SAST)** ke dalam pipeline CI menggunakan tools seperti *SonarQube*, *Semgrep*, atau *CodeQL* untuk mendeteksi kerentanan pada level kode sebelum di-*merge*.
- Menjalankan **Dynamic Application Security Testing (DAST)** pada lingkungan *staging* menggunakan *OWASP ZAP* atau *Burp Suite* untuk menemukan kerentanan runtime yang tidak tertangkap oleh analisis statis.
- Melakukan atau mengkoordinasikan **Penetration Testing (Pentest)** secara berkala — baik oleh tim internal maupun *third-party security firm* — untuk menemukan celah yang belum diketahui.

### 3. OWASP Top 10 Mitigation (Application Layer)
Memastikan seluruh aplikasi bebas dari 10 kerentanan paling kritis menurut OWASP:

| # | Kerentanan | Mitigasi |
|:---|:---|:---|
| A01 | Broken Access Control | Implementasi RBAC + ABAC, validasi otorisasi di setiap layer |
| A02 | Cryptographic Failures | Enkripsi data at-rest (AES-256) & in-transit (TLS 1.3) |
| A03 | SQL/NoSQL Injection | Parameterized queries / ORM, input validation ketat |
| A04 | Insecure Design | Threat modeling di setiap sprint |
| A05 | Security Misconfiguration | Automated compliance scanning, hardened defaults |
| A06 | Vulnerable Components | Dependency scanning (Snyk, Dependabot), SBOM management |
| A07 | Auth & Session Failures | MFA, secure session management, JWT best practices |
| A08 | Software & Data Integrity | CI/CD signing, SLSA framework, provenance verification |
| A09 | Logging & Monitoring Failures | Centralized SIEM, anomaly detection, alerting |
| A10 | Server-Side Request Forgery | Allowlist validasi URL, network segmentation |

### 4. Identity & Access Management (IAM)
- Merancang dan menerapkan sistem **autentikasi terpusat** menggunakan standar **OAuth 2.0 + OpenID Connect (OIDC)**.
- Menerapkan prinsip **Least Privilege Access** — setiap pengguna dan service hanya memiliki akses minimum yang diperlukan untuk melakukan tugasnya.
- Mengelola **Secret Rotation** secara otomatis agar *API keys*, *password database*, dan *certificates* diperbarui secara berkala tanpa intervensi manual.
- Memastikan implementasi **Multi-Factor Authentication (MFA)** pada semua akun administrator dan akses sensitif.

### 5. Supply Chain Security & Dependency Management
- Mengaudit seluruh *third-party dependencies* (libraries, packages) untuk kerentanan yang diketahui menggunakan **Snyk** atau **Dependabot**.
- Mengelola **Software Bill of Materials (SBOM)** — inventaris lengkap seluruh komponen perangkat lunak yang digunakan dalam produksi.
- Menerapkan **Signed Commits** dan **Container Image Signing** (menggunakan *Cosign* / *Sigstore*) untuk memastikan integritas seluruh artefak yang di-*deploy*.

### 6. Security Incident Response
- Memimpin **Security Incident Response** saat terjadi pelanggaran keamanan (data breach, compromised credentials, dll).
- Melakukan **Digital Forensics** pasca-insiden untuk mengidentifikasi *entry point*, *blast radius*, dan data yang terdampak.
- Menyusun **Incident Response Playbooks** — prosedur langkah-demi-langkah yang jelas untuk berbagai skenario insiden keamanan.

### 7. Compliance & Regulatory Requirements
- Memastikan sistem memenuhi standar regulasi yang berlaku: **ISO 27001**, **SOC 2 Type II**, **GDPR**, **PCI-DSS** (untuk sistem pembayaran), dan **UU PDP** (Indonesia).
- Mengkoordinasikan **Security Audit** eksternal dan mengelola temuan audit hingga *remediation* selesai.

---

## 🛠️ Tech Stack & Senjata Andalan

| Kategori | Teknologi |
|:---|:---|
| **SAST** | SonarQube, Semgrep, CodeQL, Bandit (Python) |
| **DAST** | OWASP ZAP, Burp Suite, Nuclei |
| **Dependency Scanning** | Snyk, Dependabot, OWASP Dependency-Check |
| **Container Security** | Trivy, Clair, Grype, Falco (runtime) |
| **Secret Management** | HashiCorp Vault, AWS Secrets Manager, Doppler |
| **Identity & Auth** | Keycloak, Auth0, Okta, AWS Cognito |
| **SIEM** | Splunk, Elastic SIEM, Microsoft Sentinel |
| **Network Security** | Cloudflare WAF, AWS Shield, Cilium NetworkPolicy |
| **Pentest Tools** | Metasploit, Nmap, Wireshark, Burp Suite Pro |

---

## 📏 Key Performance Indicators (KPI)

| KPI | Target |
|:---|:---|
| **Critical/High Vulnerability Resolution Time** | < 24 jam untuk Critical, < 7 hari untuk High |
| **Mean Time to Detect (MTTD)** | < 1 jam untuk insiden keamanan |
| **Security Debt Ratio** | < 10% dari semua dependency memiliki kerentanan diketahui |
| **Penetration Test Coverage** | ≥ 100% *critical endpoints* diuji setiap quarter |
| **MFA Adoption Rate** | 100% untuk akun admin & akses infrastruktur |
| **Security Training Completion** | 100% tim engineering menyelesaikan *security awareness training* tahunan |

---

> [!CAUTION]
> **"Security adalah tanggung jawab semua orang, bukan hanya Security Engineer."**
> Peran Security Engineer yang efektif bukan yang memblokir setiap PR karena alasan keamanan, melainkan yang **membangun tools, proses, dan budaya** sehingga seluruh tim engineering secara alami menulis kode yang aman.
