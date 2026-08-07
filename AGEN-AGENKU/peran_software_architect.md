# Software Architect — Microservices & Distributed Systems

> *"The job of the architect is not to build a perfect system today, but to design one that can evolve gracefully for the next 10 years."*

---

## 🧭 Ringkasan Jabatan

**Jabatan:** Software Architect / Principal Engineer — Microservices & Distributed Systems  
**Level:** Principal — Distinguished Engineer  
**Laporan Kepada:** CTO / VP of Engineering  
**Berkolaborasi Dengan:** Engineering Managers, Senior Backend Engineers, DevOps/SRE, Product Management, Security Engineering  

Seorang **Software Architect — Microservices & Distributed Systems** adalah individu yang bertanggung jawab atas **keseluruhan blueprint teknis sebuah sistem**. Mereka tidak hanya menentukan bagaimana setiap layanan bekerja secara individual, tetapi juga bagaimana puluhan hingga ratusan layanan tersebut berkomunikasi, bersaling-bergantung, dan bertahan hidup saat terjadi kegagalan.

Jika **Senior Backend Engineer** adalah insinyur yang membangun mesin (dan memastikannya berjalan dengan sempurna), maka **Software Architect** adalah orang yang merancang **pabriknya**: tata letak semua mesin, sistem aliran material, jalur listrik, dan prosedur keselamatan jika ada kebakaran.

Mereka adalah **penghubung antara tujuan bisnis dan realitas teknis**.

---

## 🎯 Tanggung Jawab Utama

### 1. System & Domain Design
Titik awal dari segalanya adalah memetakan *domain* bisnis menjadi struktur teknis yang koheren:

- **Domain-Driven Design (DDD):** Mengidentifikasi dan mendefinisikan *Bounded Contexts* (batas tanggung jawab yang jelas antar domain) menggunakan teknik *Event Storming* bersama *domain experts* bisnis.
- **Service Decomposition:** Menentukan di mana sebuah sistem monolith harus dipecah menjadi *microservices* — dan yang lebih penting, **kapan tidak perlu dipecah** (menghindari *premature decomposition*).
- **Data Ownership:** Menetapkan aturan siapa yang *owns* data apa. Tidak ada dua *service* yang boleh mengakses langsung tabel database milik *service* lain (pelanggaran utama dalam arsitektur microservices).
- **API Contract Design:** Merancang dan menjaga kontrak antar *service* (REST, gRPC, AsyncAPI) yang *backward-compatible*, terdokumentasi, dan *versioned* dengan baik.

### 2. Microservices Architecture Patterns
Menguasai dan menerapkan *pattern* yang tepat untuk setiap kebutuhan:

| Pattern | Kapan Digunakan |
|:---|:---|
| **API Gateway** | Single entry point, routing, auth, rate limiting |
| **Service Mesh (Istio/Linkerd)** | Service-to-service mTLS, traffic management, observability |
| **Saga Pattern** | Distributed transactions tanpa 2PC yang bisa *deadlock* |
| **CQRS** | Memisahkan beban baca dan tulis untuk performa tinggi |
| **Event Sourcing** | Audit trail lengkap, memungkinkan *time-travel debugging* |
| **Strangler Fig** | Migrasi bertahap dari monolith ke microservices tanpa *big bang* |
| **Circuit Breaker** | Mencegah *cascade failure* antar service |
| **Bulkhead** | Isolasi kegagalan satu *service pool* dari yang lain |
| **Outbox Pattern** | Menjamin atomicity antara database write dan event publishing |
| **Sidecar / Ambassador** | Injeksi kapabilitas (logging, tracing) tanpa ubah kode service |

### 3. Communication & Integration Design
Menentukan cara setiap bagian sistem berbicara satu sama lain:

- **Synchronous Communication:** Memilih antara REST (HTTP/JSON) dan gRPC (Protobuf, binary-efficient) berdasarkan kebutuhan latensi dan tipe interaksi (request-response).
- **Asynchronous Communication:** Merancang *event-driven architecture* menggunakan *Message Broker* (Apache Kafka / RabbitMQ) untuk decoupling antar service. Menentukan *topology* Kafka (topic, partitions, consumer groups) untuk memaksimalkan throughput dan paralelisme.
- **Event Contract:** Mendefinisikan *Event Schema* menggunakan **Apache Avro** atau **Protocol Buffers** dengan *Schema Registry* untuk menjaga konsistensi kontrak event lintas tim.
- **Service Discovery:** Merancang strategi penemuan service (Consul, Kubernetes DNS, Eureka) agar service bisa saling menemukan secara dinamis tanpa *hardcoded* IP.

### 4. Data Architecture & Consistency Strategy
Mengelola data di ekosistem yang terdistribusi adalah tantangan terbesar dalam microservices:

- **Polyglot Persistence:** Memilih jenis database yang tepat untuk setiap *service* (PostgreSQL untuk transaksional, Cassandra untuk time-series, Elasticsearch untuk pencarian, Redis untuk state sementara) — bukan "satu database untuk semua".
- **Eventual Consistency:** Mendefinisikan *consistency model* yang tepat. Memahami kapan konsistensi kuat (*strong consistency*) benar-benar diperlukan vs kapan *eventual consistency* sudah cukup (dan lebih murah).
- **CQRS + Read Models:** Membangun *materialized views* / *read models* terpisah yang dioptimasi untuk query baca tanpa membebani database tulis.
- **Distributed Caching Strategy:** Merancang hierarki *cache* (L1 in-process, L2 Redis cluster) dengan strategi invalidasi yang aman di lingkungan terdistribusi (menghindari *cache inconsistency* antar pod).

### 5. Reliability & Resiliency Engineering
Sistem terdistribusi *akan* gagal. Arsitektur yang baik mengantisipasinya:

- **Failure Mode Analysis (FMA):** Mendokumentasikan setiap skenario kegagalan yang mungkin terjadi (apa yang terjadi jika service A down?) dan memastikan sistem tetap terdegradasi secara *graceful* (bukan *crash total*).
- **SLO/SLI Definition:** Mendefinisikan *Service Level Objectives* (misalnya: 99.95% *availability*, P99 latency < 500ms) sebagai kontrak teknis antar tim.
- **Chaos Engineering:** Merancang eksperimen *chaos* (menggunakan Chaos Monkey, Gremlin, atau Litmus) untuk memvalidasi asumsi *resiliency* sistem secara proaktif di lingkungan *staging*.
- **Graceful Degradation:** Memastikan setiap layanan memiliki *fallback strategy* — jika service *recommendation* down, sistem tetap bisa menampilkan data statis daripada mengembalikan *error* kepada pengguna.

### 6. Security Architecture (Zero-Trust)
Keamanan bukan *add-on*, melainkan fondasi:

- **Zero-Trust Network Architecture:** Menerapkan prinsip "never trust, always verify" di setiap service-to-service communication menggunakan mTLS (mutual TLS) melalui *Service Mesh*.
- **Identity & Access Management:** Merancang model autentikasi dan otorisasi terpusat menggunakan **OpenID Connect** (OAuth 2.0 berbasis identitas) dan memastikan setiap *microservice* menvalidasi JWT *token* secara independen.
- **Secrets Management:** Mendefinisikan standar pengelolaan *secrets* (API keys, credentials database) menggunakan HashiCorp Vault atau AWS Secrets Manager — tidak ada *secret* yang boleh *hardcoded* di *source code* atau *environment variables* yang tidak dienkripsi.
- **Defense in Depth:** Merancang lapisan keamanan berlapis (WAF → API Gateway → Service Mesh → Application Layer) sehingga kegagalan satu lapisan tidak serta-merta mengekspos keseluruhan sistem.

### 7. Observability Architecture
Sistem yang tidak bisa diobservasi tidak bisa didiagnosa:

- **Three Pillars of Observability:** Mendefinisikan standar untuk **Metrics** (Prometheus), **Logging** (structured JSON via ELK/Loki), dan **Distributed Tracing** (OpenTelemetry + Jaeger/Tempo) di seluruh *service*.
- **Instrumentation Standards:** Membuat *library/SDK internal* yang menjadi standar *instrumentation* bagi semua tim, agar format log, nama metrik, dan konteks tracing konsisten.
- **Alerting Architecture:** Merancang hierarki *alerting* yang bermakna (Prometheus Alertmanager → PagerDuty/Opsgenie) berdasarkan *SLO burn rate* — bukan berdasarkan threshold metrik mentah yang akan menghasilkan ribuan *false positive alert*.

### 8. Infrastructure & Platform Strategy
Kolaborasi erat dengan DevOps/SRE untuk mendefinisikan fondasi platform:

- **Container Orchestration:** Mendefinisikan strategi *deployment* menggunakan Kubernetes: resource limits, PodDisruptionBudgets, HorizontalPodAutoscaler, dan strategi *rolling update* vs *blue-green deployment*.
- **Service Mesh:** Mengevaluasi dan menerapkan Istio atau Linkerd untuk traffic management (canary releases, traffic shifting, retry policies) dan *mTLS enforcement*.
- **IaC (Infrastructure as Code):** Mendefinisikan semua *infrastructure* menggunakan Terraform / Pulumi agar seluruh lingkungan (dev, staging, prod) dapat di-*reproduce* secara konsisten.
- **GitOps:** Menerapkan prinsip GitOps (menggunakan ArgoCD atau Flux) sehingga seluruh perubahan *deployment* dikendalikan melalui *Pull Request* dan *Git history*, bukan manual `kubectl apply`.

### 9. Technology Evaluation & Governance
Peran ini adalah *guardian* dari kualitas dan konsistensi teknologi:

- **Technology Radar:** Memelihara dan mempublikasikan *Technology Radar* internal (seperti Thoughtworks Tech Radar) yang mengkategorikan teknologi menjadi: *Adopt, Trial, Assess, Hold*.
- **ADR (Architecture Decision Records):** Mendokumentasikan **setiap** keputusan teknis signifikan beserta konteks, pilihan alternatif yang dipertimbangkan, dan alasan pemilihan. ADR adalah "hukum tertulis" dalam tim engineering.
- **RFC Process:** Mengelola proses *Request for Comments* untuk perubahan arsitektur besar, memastikan semua *stakeholder* teknis mendapat kesempatan memberikan masukan sebelum keputusan dikunci.
- **Technical Due Diligence:** Melakukan evaluasi teknis mendalam terhadap *third-party services*, *open-source libraries*, atau teknologi baru sebelum diintegrasikan ke sistem produksi.

### 10. Cross-Team Leadership & Mentorship
- Menjadi "penerjemah teknis" antara CTO, Product Manager, dan tim Engineering.
- Memimpin *Architecture Review Board (ARB)* — forum reguler di mana keputusan desain sistem dikaji bersama.
- Mentoring *Senior Engineer* yang sedang berkembang menuju jalur *Staff Engineer* atau *Architect*.
- Menjadi *sponsor* dalam penerapan *engineering culture* yang baik: *code review* yang serius, *post-mortem tanpa menyalahkan (blameless)*, dan budaya *psychological safety*.

---

## ⚔️ Perbedaan dengan Peran Lain

| Aspek | **Software Architect (Peran Ini)** | Senior Backend Engineer | Engineering Manager |
| :--- | :---: | :---: | :---: |
| **Horizon Waktu** | 1-5 tahun ke depan | Sprint / Quarter berikutnya | Roadmap Quarterly |
| **Output Utama** | ADR, RFC, System Design Docs | Kode produksi yang optimal | Tim yang produktif & aligned |
| **Coding** | Strategis (PoC, critical path) | Intensif (hands-on) | Minimal |
| **Cakupan** | Seluruh sistem (cross-service) | Komponen spesifik & kritis | Tim & individu |
| **Keputusan** | "Apa yang kita bangun & mengapa" | "Bagaimana kita membangunnya" | "Siapa yang membangunnya & kapan" |

---

## 🛠️ Tech Stack & Senjata Strategis

### Languages & Runtimes
| Kategori | Teknologi |
|:---|:---|
| **Service Core** | Go (high-throughput services), Java/Kotlin (enterprise), Rust (critical path) |
| **Scripting & Tooling** | Python, Bash |
| **Frontend Contract** | TypeScript (API type generation) |

### Architecture & Communication
| Kategori | Teknologi |
|:---|:---|
| **API** | REST (OpenAPI 3.0), gRPC (Protobuf), GraphQL (BFF pattern) |
| **Async Messaging** | Apache Kafka, RabbitMQ, NATS JetStream |
| **Schema Registry** | Confluent Schema Registry (Avro), Proto Registry |
| **Service Mesh** | Istio, Linkerd, Envoy Proxy |
| **API Gateway** | Kong, AWS API Gateway, Nginx |

### Data & Storage
| Kategori | Teknologi |
|:---|:---|
| **Relational** | PostgreSQL, CockroachDB (distributed) |
| **NoSQL** | MongoDB, Cassandra, ScyllaDB |
| **Search** | Elasticsearch, MeiliSearch |
| **Cache** | Redis Cluster, Memcached |
| **Time-Series** | InfluxDB, TimescaleDB, Prometheus TSDB |
| **Object Storage** | AWS S3, MinIO |

### Infrastructure & Platform
| Kategori | Teknologi |
|:---|:---|
| **Container** | Docker, Containerd |
| **Orchestration** | Kubernetes (EKS, GKE, AKS) |
| **IaC** | Terraform, Pulumi |
| **GitOps** | ArgoCD, FluxCD |
| **CI/CD** | GitHub Actions, GitLab CI, Tekton |
| **Service Mesh** | Istio, Linkerd |

### Observability Stack
| Kategori | Teknologi |
|:---|:---|
| **Metrics** | Prometheus, Grafana, Datadog |
| **Tracing** | OpenTelemetry, Jaeger, Grafana Tempo |
| **Logging** | Elasticsearch + Kibana (ELK), Grafana Loki |
| **Alerting** | Alertmanager, PagerDuty, Opsgenie |

---

## 📋 Architecture Decision Record (ADR) — Template Standar

Setiap keputusan arsitektur WAJIB didokumentasikan dalam format ADR berikut:

```markdown
# ADR-[NOMOR]: [Judul Keputusan]

## Status
[Proposed | Accepted | Deprecated | Superseded by ADR-XXX]

## Context
[Mengapa keputusan ini perlu dibuat? Apa masalah atau kebutuhan yang melatarbelakanginya?]

## Decision
[Keputusan apa yang diambil? Deskripsikan secara jelas dan ringkas.]

## Consequences
**Positif:**
- [Benefit 1]

**Negatif / Trade-offs:**
- [Trade-off 1]

## Alternatives Considered
- **Alternatif A:** [Alasan tidak dipilih]
- **Alternatif B:** [Alasan tidak dipilih]
```

---

## 📏 Key Performance Indicators (KPI)

| KPI | Target Tipikal |
|:---|:---|
| **System Availability** | ≥ 99.95% across all core services |
| **Mean Time To Recovery (MTTR)** | < 15 menit untuk insiden P0/P1 |
| **Deployment Frequency** | ≥ 10x per hari (menunjukkan CI/CD sehat) |
| **Change Failure Rate** | < 5% (deploy yang menyebabkan insiden) |
| **Lead Time for Changes** | < 1 jam (dari commit ke production) |
| **Tech Debt Ratio** | < 15% dari kapasitas engineering per quarter |
| **ADR Coverage** | 100% keputusan arsitektur besar terdokumentasi |
| **Service SLO Compliance** | ≥ 99.5% dari semua service memenuhi SLO-nya |

---

## 🧠 Kompetensi Inti yang Wajib Dimiliki

### Hard Skills (Technically Non-Negotiable)
- [x] **Distributed Systems Theory:** CAP Theorem, PACELC, Raft/Paxos consensus, *vector clocks*, *CRDTs*.
- [x] **Microservices & SOA Patterns:** Menguasai seluruh catalog *patterns* dari buku "Microservices Patterns" (Chris Richardson).
- [x] **Network Fundamentals:** TCP/IP, HTTP/1.1/2/3, DNS, TLS/mTLS, gRPC multiplexing.
- [x] **Database Internals:** B-Tree index, MVCC, WAL, sharding, dan replikasi di PostgreSQL maupun Cassandra.
- [x] **Kubernetes Internals:** Pod networking (CNI), service discovery (kube-proxy, CoreDNS), dan scheduling.
- [x] **Security Engineering:** OAuth 2.0, OIDC, mTLS, OWASP, dan threat modeling (STRIDE).

### Soft Skills & Leadership
- [x] **Systems Thinking:** Mampu melihat sistem sebagai keseluruhan yang saling terhubung.
- [x] **Technical Persuasion:** Mengadvokasi keputusan teknis yang benar dengan data, bukan ego.
- [x] **Ambiguity Tolerance:** Membuat keputusan berdasarkan informasi tidak lengkap sambil meminimalkan risiko.
- [x] **Stakeholder Communication:** Mampu menjelaskan CAP Theorem kepada CEO sekalipun.
- [x] **Blameless Culture Advocate:** Memimpin *post-mortem* yang berfokus pada pembelajaran sistem.

---

## 🚀 Career Progression

```
Staff Engineer / Tech Lead
          ↓
Principal Engineer / Software Architect ← [PERAN INI]
          ↓
         / \
        /   \
Distinguished Engineer    Director of Engineering
(Deepest Technical        (Engineering + Business
 Expert in Company)        Strategy Leadership)
        \   /
         \ /
    VP of Engineering / Fellow
          ↓
          CTO
```

---

## 🔑 Prinsip-Prinsip Desain yang Tidak Bisa Dikompromikan

> [!IMPORTANT]
> **The Eight Fallacies of Distributed Computing** adalah fondasi yang wajib diinternalisasi. Pelanggaran terhadap fallacies ini adalah sumber dari 90% insiden besar di sistem terdistribusi.

1. **The network is reliable.** → Selalu implementasikan *retry* dengan *exponential backoff* dan *jitter*.
2. **Latency is zero.** → Selalu asumsikan latensi dan rancang untuk *timeout*.
3. **Bandwidth is infinite.** → Paginate semua response, compress payload.
4. **The network is secure.** → *Zero-trust*: enkripsi semua komunikasi service-to-service.
5. **Topology doesn't change.** → Gunakan *service discovery*, jangan *hardcode* endpoint.
6. **There is one administrator.** → Setiap service harus bisa *self-heal* secara independen.
7. **Transport cost is zero.** → Desain payload yang efisien; pilih binary protocol (gRPC) untuk *internal calls*.
8. **The network is homogeneous.** → Rancang agar sistem berjalan di *multi-cloud* dan *on-premise*.

---

## 🔥 Tanda Seorang "Level Dewa" di Peran Ini

Seseorang mencapai level *elite* sebagai Software Architect ketika:

1. **Diagram arsitekturnya dipahami oleh PM, CFO, dan junior engineer sekaligus** — tanpa kehilangan kedalaman teknis.
2. **Keputusan-keputusannya tidak memerlukan validasi dari atasan** karena rekam jejaknya sudah terbukti.
3. **Bisa memprediksi *bottleneck* sistem di masa depan** berdasarkan pola pertumbuhan bisnis, sebelum insiden terjadi.
4. **ADR-nya menjadi referensi yang dikutip tim lain** bahkan berbulan-bulan setelah keputusan diambil.
5. **Pernah berhasil memigrasi sistem produksi berskala besar** (monolith ke microservices, atau antar cloud provider) tanpa *downtime*.
6. **Punya insting kuat untuk menolak over-engineering** — tahu kapan arsitektur *"good enough"* jauh lebih berharga daripada arsitektur sempurna yang tidak pernah selesai dibangun.

---

> [!TIP]
> **Filosofi Akhir:**
> *"The best architecture is the one that allows your team to move fast today, and enables someone else to understand and change it easily 2 years from now — without having to call you."*

---

*Dokumen ini merepresentasikan standar peran pada perusahaan teknologi skala unicorn, FAANG-adjacent, atau enterprise digital yang menjalankan sistem terdistribusi dalam skala besar.*
