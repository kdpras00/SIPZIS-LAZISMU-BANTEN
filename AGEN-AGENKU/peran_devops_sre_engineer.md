# DevOps Engineer / Site Reliability Engineer (SRE)

> *"You build it, you run it. If you're woken up at 3AM, you'll write better code tomorrow."*
> — Werner Vogels, CTO Amazon

---

## 🧭 Ringkasan Jabatan

**Jabatan:** DevOps Engineer / Site Reliability Engineer (SRE)  
**Level:** Mid — Senior  
**Laporan Kepada:** Engineering Manager / VP of Infrastructure  
**Berkolaborasi Dengan:** Software Architect, Backend Engineer, Security Engineer, Product Manager  

Seorang **DevOps/SRE Engineer** adalah jembatan antara **tim Development** (yang ingin *ship* fitur secepat mungkin) dan **kebutuhan sistem Operasional** (yang menuntut stabilitas, keamanan, dan keandalan). Mereka bukan sekadar "orang IT" atau *server admin* — mereka adalah *Software Engineer* yang mengkhususkan diri dalam **membangun dan memelihara platform** yang memungkinkan ratusan developer lain untuk bekerja dengan produktif dan percaya diri.

> **SRE adalah apa yang terjadi ketika Software Engineer ditugaskan mengelola Operations.**  
> — Ben Treynor Sloss (Penemu konsep SRE di Google)

---

## 🎯 Tanggung Jawab Utama

### 1. Infrastructure as Code (IaC)
Infrastruktur bukan lagi dikelola secara manual melalui klik-klik di dashboard — semuanya adalah **kode yang di-review, di-version, dan di-deploy**:
- Menulis dan memelihara seluruh infrastruktur (server, jaringan, database, firewall) menggunakan **Terraform** atau **Pulumi**.
- Mengelola konfigurasi server menggunakan **Ansible**, memastikan setiap server identik dan dapat di-*reproduce* kapan saja.
- Memastikan tidak ada *"snowflake server"* — server yang dikonfigurasi secara manual dan tidak terdokumentasikan.

### 2. CI/CD Pipeline Engineering
Pipeline yang kuat adalah jantung dari tim yang bergerak cepat:
- Merancang dan memelihara *pipeline* **Continuous Integration (CI)**: setiap commit kode diuji secara otomatis (unit test, integration test, static analysis, security scan).
- Merancang **Continuous Deployment (CD)**: kode yang lolos CI di-*deploy* otomatis ke *staging* dan *production* tanpa campur tangan manual.
- Menerapkan strategi *deployment* aman: **Blue-Green Deployment**, **Canary Release**, dan **Feature Flags** untuk memungkinkan *rollback* instan jika ada masalah.

### 3. Kubernetes & Container Orchestration
- Mengelola **Kubernetes cluster** (EKS, GKE, atau AKS) termasuk konfigurasi *node pools*, *namespaces*, dan *RBAC* (Role-Based Access Control).
- Menulis dan memelihara **Helm Charts** untuk standarisasi *deployment* semua service.
- Mengkonfigurasi **HPA (Horizontal Pod Autoscaler)** dan **VPA (Vertical Pod Autoscaler)** agar sistem bisa *scale* otomatis saat traffic melonjak.
- Menerapkan **PodDisruptionBudget** dan **ResourceQuotas** agar tidak ada service yang bisa "memakan" resource milik service lain.

### 4. Observability Stack (Monitoring, Logging, Alerting)
- Membangun dan memelihara *stack* observabilitas: **Prometheus + Grafana** untuk metrik, **Elasticsearch + Kibana (ELK)** atau **Grafana Loki** untuk log, dan **Jaeger** atau **Grafana Tempo** untuk *distributed tracing*.
- Mendefinisikan **SLI (Service Level Indicators)** dan menghitung **Error Budget** berdasarkan **SLO** yang telah disepakati.
- Membangun *alerting* yang cerdas menggunakan **Alertmanager** — memberikan notifikasi hanya untuk hal yang benar-benar membutuhkan perhatian manusia, bukan spam notifikasi palsu (*alert fatigue*).

### 5. Reliability Engineering & Incident Management
- Memimpin respons insiden (P0/P1) menggunakan proses **Incident Command Structure** yang terstruktur.
- Memfasilitasi **Post-Mortem Tanpa Menyalahkan (Blameless Post-Mortem)** setelah setiap insiden untuk menemukan akar masalah dan mencegah terulangnya kejadian yang sama.
- Menjalankan **Chaos Engineering** menggunakan tools seperti *Litmus* atau *Gremlin* untuk memvalidasi ketahanan sistem secara proaktif.

### 6. Security Hardening (DevSecOps)
- Mengintegrasikan *security scanning* ke dalam setiap *pipeline* CI (SAST, DAST, dependency scanning menggunakan **Trivy**, **Snyk**, atau **Dependabot**).
- Mengelola **Secret Management** menggunakan HashiCorp Vault atau cloud-native secret manager.
- Memastikan seluruh *network traffic* antar-service menggunakan **mTLS** (mutual TLS) melalui *Service Mesh*.

---

## 🛠️ Tech Stack & Senjata Andalan

| Kategori | Teknologi |
|:---|:---|
| **IaC** | Terraform, Pulumi, Ansible, CloudFormation |
| **Container** | Docker, Podman, Containerd, BuildKit |
| **Orchestration** | Kubernetes (EKS/GKE/AKS), Helm, Kustomize |
| **CI/CD** | GitHub Actions, GitLab CI, Jenkins, ArgoCD, Tekton |
| **Monitoring** | Prometheus, Grafana, Datadog, New Relic |
| **Logging** | ELK Stack, Grafana Loki, Fluentd, Fluent Bit |
| **Tracing** | OpenTelemetry, Jaeger, Grafana Tempo |
| **Cloud** | AWS, GCP, Azure, Cloudflare |
| **Networking** | Nginx, HAProxy, Istio, Envoy, Cilium |
| **Security** | Trivy, Snyk, Vault, CIS Benchmarks |

---

## 📏 Key Performance Indicators (KPI)

| KPI | Target |
|:---|:---|
| **Deployment Frequency** | ≥ 10x/hari (multiple times a day) |
| **Change Failure Rate** | < 5% dari semua *deployment* menyebabkan insiden |
| **Mean Time to Recovery (MTTR)** | < 15 menit untuk insiden P0/P1 |
| **Lead Time for Changes** | < 1 jam dari commit ke production |
| **Infrastructure Uptime** | ≥ 99.95% |
| **Pipeline Success Rate** | ≥ 95% dari semua CI pipeline berhasil |

---

## 🔑 Prinsip Tidak Bisa Dikompromikan

> [!IMPORTANT]
> Seorang DevOps/SRE yang baik berpedoman pada **"You build it, you run it"**.
> Jika Engineer yang membangun sebuah service juga bertanggung jawab saat service tersebut bermasalah di production, mereka akan secara alami menulis kode yang lebih reliable dan sistem monitoring yang lebih baik.

1. **Tidak ada perubahan infrastruktur secara manual** — semua perubahan melalui *pull request* dan *code review*.
2. **Setiap deployment dapat di-rollback dalam < 5 menit** tanpa harus menghubungi siapapun.
3. **Setiap sistem baru WAJIB memiliki dashboard, alert, dan runbook** sebelum dianggap siap *production*.
4. **Error Budget menentukan kecepatan development** — jika Error Budget habis, tidak ada *deployment* baru sampai sistem stabil.

---

*Dokumen ini merepresentasikan standar peran DevOps/SRE pada perusahaan teknologi modern yang menjalankan sistem cloud-native.*
