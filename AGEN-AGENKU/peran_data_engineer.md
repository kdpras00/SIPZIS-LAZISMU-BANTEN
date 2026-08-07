# Data Engineer

> *"Data is the new oil. But like oil, it's only valuable after it's been refined."*

---

## 🧭 Ringkasan Jabatan

**Jabatan:** Data Engineer  
**Level:** Mid — Senior  
**Laporan Kepada:** Head of Data / CTO  
**Berkolaborasi Dengan:** Data Analyst, Data Scientist, Backend Engineer, Software Architect  

Seorang **Data Engineer** adalah *foundational builder* dari seluruh ekosistem data sebuah perusahaan. Jika **Data Analyst** adalah yang *menggunakan* data untuk menghasilkan *insight*, dan **Data Scientist** yang *membangun model* dari data, maka **Data Engineer** adalah yang memastikan **data yang benar, bersih, dan dapat dipercaya tersedia tepat waktu** untuk semua pihak tersebut.

Mereka membangun **jalan tol data** — *pipeline* yang mengalirkan data dari berbagai sumber (database aplikasi, API eksternal, event stream) ke tempat di mana data tersebut bisa dianalisis dan dimanfaatkan.

---

## 🎯 Tanggung Jawab Utama

### 1. Data Pipeline Engineering (ETL/ELT)
- Merancang dan membangun **pipeline ETL/ELT** yang handal untuk mengumpulkan data dari berbagai sumber (database relasional, API, file CSV, stream Kafka) ke dalam *Data Warehouse* atau *Data Lake*.
- Memilih pendekatan yang tepat:
  - **ETL (Extract-Transform-Load):** Data ditransformasi sebelum masuk ke *warehouse* — lebih cocok untuk data yang sudah diketahui strukturnya.
  - **ELT (Extract-Load-Transform):** Data mentah disimpan dulu, baru ditransformasi sesuai kebutuhan — lebih fleksibel untuk kebutuhan analitik yang berkembang.
- Menerapkan **idempotency** pada setiap pipeline — menjalankan ulang pipeline yang gagal tidak boleh menghasilkan data duplikat.

### 2. Data Warehouse & Data Lake Architecture
- Merancang **skema Data Warehouse** menggunakan pendekatan **Star Schema** atau **Snowflake Schema** agar kueri analitik berjalan efisien.
- Membangun **Data Lakehouse** menggunakan format seperti **Apache Iceberg** atau **Delta Lake** yang mendukung transaksi ACID pada *Data Lake*.
- Mendefinisikan strategi **partitioning** dan **clustering** tabel di BigQuery, Snowflake, atau Redshift agar kueri skala besar selesai dalam detik.

### 3. Real-Time Streaming Data
- Membangun pipeline *real-time* menggunakan **Apache Kafka** sebagai *event bus* dan **Apache Flink** atau **Spark Streaming** sebagai *stream processing engine*.
- Menerapkan pola **Lambda Architecture** (batch + stream) atau **Kappa Architecture** (stream-only) sesuai kebutuhan bisnis.
- Memastikan *exactly-once semantics* dalam pemrosesan stream agar tidak ada data yang hilang atau dihitung dua kali.

### 4. Data Quality & Observability
- Membangun **Data Quality Framework** menggunakan tools seperti **Great Expectations** atau **dbt tests** untuk memvalidasi bahwa data yang masuk ke *warehouse* memenuhi standar kualitas yang telah ditetapkan.
- Menerapkan **Data Observability** — memantau *freshness*, *volume*, *schema changes*, dan *distribution anomalies* pada setiap dataset kritis.
- Membuat **Data Lineage** yang jelas: setiap metrik bisnis dapat ditelusuri kembali ke *source of truth* awalnya.

### 5. Data Modeling & Transformation
- Menulis dan memelihara **data model transformasi** menggunakan **dbt (data build tool)** — memungkinkan transformasi SQL yang terversi, terdokumentasi, dan teruji.
- Mendefinisikan **semantic layer**: *metrics*, *dimensions*, dan *business logic* yang konsisten agar seluruh tim berbicara dalam "bahasa data" yang sama.

---

## 🛠️ Tech Stack & Senjata Andalan

| Kategori | Teknologi |
|:---|:---|
| **Pipeline Orchestration** | Apache Airflow, Prefect, Dagster |
| **Stream Processing** | Apache Kafka, Apache Flink, Spark Streaming |
| **Batch Processing** | Apache Spark, dbt |
| **Data Warehouse** | BigQuery, Snowflake, Amazon Redshift, ClickHouse |
| **Data Lake** | AWS S3, GCS, Azure Data Lake |
| **Data Formats** | Apache Parquet, ORC, Apache Avro, Apache Iceberg |
| **Data Quality** | Great Expectations, dbt tests, Monte Carlo |
| **Query Engine** | Presto/Trino, Apache Hive, Athena |
| **Languages** | Python, SQL, Scala, Java |

---

## 📏 Key Performance Indicators (KPI)

| KPI | Target |
|:---|:---|
| **Data Pipeline SLA** | ≥ 99.5% pipeline selesai on-time |
| **Data Freshness** | Data kritis tersedia dalam < 1 jam setelah terbentuk di sumber |
| **Data Quality Score** | ≥ 99% dari semua *data quality checks* lolos |
| **Pipeline MTTR** | < 30 menit untuk pipeline yang gagal |
| **Query Performance** | Kueri analitik kritis selesai < 30 detik |

---

> [!TIP]
> **Perbedaan kunci: Data Engineer vs Data Analyst vs Data Scientist**
> - **Data Engineer:** "Bagaimana kita memastikan data tersedia, bersih, dan dapat diandalkan?"
> - **Data Analyst:** "Apa yang dikatakan data tentang performa bisnis kita?"
> - **Data Scientist:** "Bisakah kita memprediksi perilaku di masa depan berdasarkan data historis?"
