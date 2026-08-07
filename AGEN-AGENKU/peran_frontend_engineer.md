# Frontend Engineer / UI Engineer

> *"Good design is actually a lot harder to notice than poor design, in part because good designs fit our needs so well that the design is invisible."*
> — Don Norman

---

## 🧭 Ringkasan Jabatan

**Jabatan:** Frontend Engineer / UI Engineer  
**Level:** Junior — Senior — Staff  
**Laporan Kepada:** Engineering Manager / Tech Lead  
**Berkolaborasi Dengan:** Backend Engineer, UX Designer, Product Manager, QA Engineer  

Seorang **Frontend Engineer** adalah pembangun **wajah** dari sebuah produk digital. Mereka bukan sekadar mengubah desain Figma menjadi HTML/CSS — mereka adalah **craftsman antarmuka** yang memastikan pengguna mendapatkan pengalaman yang cepat, intuitif, indah, dan dapat diakses oleh semua orang (*accessible*).

Seorang Frontend Engineer yang baik memiliki pemahaman mendalam tentang **browser internals**, **performance optimization**, **accessibility (a11y)**, dan bagaimana **jaringan** memengaruhi pengalaman pengguna di berbagai kondisi.

---

## 🎯 Tanggung Jawab Utama

### 1. UI Component Development & Design System
- Membangun **Design System** — koleksi komponen UI yang dapat digunakan kembali (*reusable*), konsisten, dan terdokumentasi menggunakan tools seperti **Storybook**.
- Memastikan setiap komponen mengikuti prinsip **atomic design**: dari atom (Button, Input) → molekul (Card, Form) → organisme (Header, Modal) → template → halaman.
- Menjaga konsistensi visual dan fungsional di seluruh produk dengan memastikan developer menggunakan komponen dari *design system*, bukan membuat ulang dari nol.

### 2. Performance Engineering (Core Web Vitals)
Pengguna tidak akan menunggu halaman yang lambat — performa adalah fitur:
- Mengoptimasi **Core Web Vitals**: LCP (Largest Contentful Paint), FID/INP (Interaction to Next Paint), dan CLS (Cumulative Layout Shift).
- Menerapkan teknik **Code Splitting** dan **Lazy Loading** agar *bundle* JavaScript yang dikirim ke browser minimal.
- Mengimplementasikan **Critical CSS Inlining** dan strategi **Font Loading** yang optimal.
- Mengoptimasi media: **WebP/AVIF** untuk gambar, **video compression**, dan implementasi **responsive images** (`srcset`).
- Menerapkan **Service Worker** dan **PWA (Progressive Web App)** capabilities untuk mendukung *offline-first* experience.

### 3. State Management Architecture
- Merancang arsitektur *state management* yang tepat dan tidak berlebihan: kadang **local component state** sudah cukup, kadang perlu **Zustand**, **Redux**, atau **Pinia**.
- Menghindari ***prop drilling*** dan *anti-pattern* lain yang membuat kode sulit dipelihara.
- Menerapkan pola **optimistic updates** untuk memberikan *feedback* instan kepada pengguna saat menunggu respons API.

### 4. Accessibility (a11y)
- Memastikan seluruh antarmuka memenuhi standar **WCAG 2.1 Level AA** minimum.
- Mengimplementasikan **semantic HTML** (`<button>`, `<nav>`, `<main>`) agar *screen reader* dapat bernavigasi dengan benar.
- Memastikan semua elemen interaktif dapat dioperasikan **hanya menggunakan keyboard** (tanpa mouse).
- Mengelola **ARIA roles dan attributes** dengan benar untuk komponen kustom yang tidak memiliki padanan HTML semantik.

### 5. API Integration & Data Fetching Strategy
- Merancang strategi *data fetching* yang efisien: memilih antara **REST**, **GraphQL**, atau **tRPC** sesuai kebutuhan.
- Menerapkan **caching strategy** di sisi klien menggunakan **TanStack Query (React Query)** atau **SWR** untuk mengurangi request berulang ke API.
- Menangani **loading states**, **error states**, dan **empty states** dengan baik — tidak ada layar yang hanya menampilkan `undefined` atau *blank screen*.

### 6. Cross-Browser & Cross-Device Compatibility
- Memastikan antarmuka berfungsi dengan benar di seluruh *target browser* (Chrome, Firefox, Safari, Edge) dan perangkat (desktop, tablet, mobile).
- Mengelola **CSS specificity** dan *cascade* dengan disiplin menggunakan metodologi seperti **BEM**, **CSS Modules**, atau **Tailwind CSS utility classes**.
- Mengimplementasikan **responsive design** yang sesungguhnya — bukan hanya "mengecilkan layar", tetapi menyesuaikan seluruh pengalaman pengguna untuk setiap ukuran layar.

---

## 🛠️ Tech Stack & Senjata Andalan

| Kategori | Teknologi |
|:---|:---|
| **Framework** | React, Vue.js, Angular, Svelte, Next.js, Nuxt |
| **Language** | TypeScript, JavaScript (ES2024+) |
| **Styling** | Tailwind CSS, CSS Modules, Styled Components |
| **State Management** | Zustand, Redux Toolkit, Pinia, Jotai |
| **Data Fetching** | TanStack Query, SWR, Apollo Client |
| **Build Tools** | Vite, Webpack, Turbopack, esbuild |
| **Testing** | Vitest, Jest, Testing Library, Playwright, Storybook |
| **Design** | Figma, Storybook, Chromatic |
| **Performance** | Lighthouse, WebPageTest, Chrome DevTools |

---

## 📏 Key Performance Indicators (KPI)

| KPI | Target |
|:---|:---|
| **Largest Contentful Paint (LCP)** | < 2.5 detik |
| **Interaction to Next Paint (INP)** | < 200ms |
| **Cumulative Layout Shift (CLS)** | < 0.1 |
| **JavaScript Bundle Size (gzipped)** | < 150KB untuk initial bundle |
| **Accessibility Score (Lighthouse)** | ≥ 95/100 |
| **Component Test Coverage** | ≥ 80% komponen kritis |

---

> [!NOTE]
> **Filosofi Frontend Terbaik:** *"The best interface is no interface."* — Setiap elemen UI yang ada harus memiliki alasan yang jelas. Kurangi noise, tingkatkan *signal*. Pengguna yang hebat adalah pengguna yang tidak menyadari mereka sedang menggunakan UI — mereka hanya menyelesaikan tujuan mereka.
