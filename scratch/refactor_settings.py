import os

file_path = r'c:\laragon\www\new-church\resources\views\admin\finance\settings\index.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Substituições globais de estilo industrial para Elite V8
replacements = {
    'border-slate-800': 'border-slate-100',
    'border-b-2 border-slate-800': 'border-b border-slate-100',
    'border-t-2 border-slate-800': 'border-t border-slate-100',
    'bg-slate-200': 'bg-slate-50/50',
    'bg-slate-100': 'bg-slate-50/30',
    'text-black': 'text-primary-dark',
    'font-black text-black': 'font-bold text-primary-dark',
    'bg-emerald-900': 'bg-emerald-500',
    'bg-rose-900': 'bg-rose-500',
    'border-2 border-slate-800': 'border border-slate-100',
}

for old, new in replacements.items():
    content = content.replace(old, new)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Refatoração global concluída.")
