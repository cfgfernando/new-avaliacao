# 🎨 DESIGN SYSTEM PROMPT — ELITE V8 AESTHETIC

Este guia define o padrão visual, tokens de design e especificações técnicas para o projeto **MDA Church ERP**. Utilize este prompt para garantir que qualquer componente novo em **Laravel (Blade/Vite)** ou **Vue.js** siga rigorosamente a identidade visual estabelecida.

---

## 1. Identidade Visual (Core)
O design é chamado **"Elite V8"**. Suas características principais são: **High-Contrast**, **Glassmorphism**, **Fluidity** e **Premium Tech-Admin**.

### Paleta de Cores (Tailwind Config)
```javascript
colors: {
    primary: {
        DEFAULT: '#0f172a', // Sidebar Background (Navy Profundo)
        dark: '#020617',    // Texto Principal / Títulos (Contraste Máximo)
        light: '#94a3b8',   // Texto Secundário / Inativo
    },
    accent: {
        DEFAULT: '#ea580c', // Laranja Vibrante (Ações Principais)
        hover: '#c2410c',   // Hover Laranja
    },
    background: {
        DEFAULT: '#f8fafc', // Fundo Principal da Aplicação
    }
}
```

### Tipografia
- **Títulos/Display:** `Montserrat` (font-black, uppercase, tracking-tight).
- **Corpo/Interface:** `Inter` (font-medium/bold, antialiased).

---

## 2. Componentes Estruturais (Layout)

### Sidebar (Navegação Lateral)
- **Background:** `bg-primary` (#0f172a).
- **Itens:** `nav-link-neo` (Padding horizontal, margem lateral `mx-3`, bordas `rounded-xl`).
- **Estado Ativo:** Gradiente `bg-gradient-to-r from-accent to-accent-hover`, sombra `shadow-accent/20`.
- **Scroll:** Customizado com `.custom-scrollbar` (fino e translúcido).

### Topbar (Header)
- **Estilo:** `bg-white/80 backdrop-blur-md` (Glassmorphism).
- **Borda:** `border-b border-gray-100`.
- **Altura:** `h-20`.

---

## 3. UI Components (Tokens de Estilo)

### Cards (`.card-neo`)
```html
<div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 p-6 transition-all duration-500 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1">
    <!-- Conteúdo -->
</div>
```

### Formulários (`.input-neo`)
- **Estilo:** Fundo `bg-slate-50/50`, bordas `rounded-xl`, padding `py-3.5`.
- **Foco:** `focus:ring-4 focus:ring-accent/15 focus:border-accent focus:bg-white`.
- **Labels:** `text-[10px] font-black uppercase tracking-[0.2em] mb-2`.

### Botões (`.btn-neo`)
- **Base:** `px-6 py-3 rounded-xl font-bold transition-all active:scale-95`.
- **Primário:** `bg-gradient-to-br from-accent to-accent-hover text-white shadow-lg shadow-accent/30`.

### Tabelas
- **Wrapper:** `bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden`.
- **Header:** `bg-slate-50/50 text-[10px] font-black uppercase tracking-widest text-slate-400`.
- **Rows:** Hover suave `hover:bg-slate-50/50`, divisão `divide-y divide-slate-50`.

---

## 4. Elementos de Interação e Feedback

### Badges
- **Success:** `bg-green-50 text-green-600 border-green-200`.
- **Info:** `bg-blue-50 text-blue-600 border-blue-200`.
- **Estilo Geral:** `font-black text-[10px] px-3 py-1 rounded-lg border uppercase tracking-wider`.

### Toggles (Switches)
- **Track:** `w-10 h-5 bg-slate-200 rounded-full transition-colors`.
- **Thumb:** `w-3 h-3 bg-white rounded-full transition-transform`.
- **Active:** `bg-blue-500` (ou `accent` dependendo do contexto).

### Animações
- Utilizar `animate-reveal-up` para entrada de conteúdo.
- Transições globais de `duration-300` ou `duration-500`.

---

## 5. Diretrizes para Vue.js
Ao criar componentes Vue:
1. Use **Tailwind CSS Utility Classes** diretamente nos templates.
2. Mantenha os estados de **Hover** e **Focus** consistentes com as classes `.neo` acima.
3. Para tabelas de dados dinâmicas, aplique o design de `.card-neo` ao wrapper da tabela.
4. Utilize ícones **FontAwesome 6.4.0+**.

---

## 🎯 Instrução para o Modelo AI:
*"Aja como um Senior Frontend Engineer. Ao gerar código para este projeto, utilize exclusivamente as classes utilitárias e componentes definidos neste DESIGN SYSTEM. Priorize o uso de bordas arredondadas (rounded-2xl/3xl), sombras suaves, tipografia Montserrat para títulos e a paleta de cores Navy/Orange. Não utilize cores padrão do Tailwind (como blue-500 ou red-500) a menos que para alertas rápidos; prefira as definições primary e accent."*
