import re

p = 'resources/views/dashboard.blade.php'
lines = open(p, encoding='utf-8').read().splitlines(keepends=True)

repl = {
    107: '<i data-lucide="warehouse" class="w-5 h-5"></i>',
    151: '<i data-lucide="command" class="w-4 h-4"></i>',
    157: '<i data-lucide="bell" class="w-5 h-5"></i>',
    176: '<i data-lucide="calendar" class="w-5 h-5"></i>',
    186: '<i data-lucide="coffee" class="w-5 h-5"></i>',
    196: '<i data-lucide="package" class="w-5 h-5"></i>',
    206: '<i data-lucide="bar-chart-2" class="w-5 h-5"></i>',
    216: '<i data-lucide="trending-up" class="w-5 h-5"></i>',
    284: '<i data-lucide="coffee" class="w-5 h-5"></i>',
    299: '<i data-lucide="coffee" class="w-5 h-5"></i>',
    314: '<i data-lucide="coffee" class="w-5 h-5"></i>',
    343: '<i data-lucide="package-plus" class="w-5 h-5"></i>',
    358: '<i data-lucide="package-minus" class="w-5 h-5"></i>',
    373: '<i data-lucide="package-plus" class="w-5 h-5"></i>',
}

for i, line in enumerate(lines, 1):
    if i in repl:
        lines[i-1] = re.sub(r'[^\x00-\x7F]+', repl[i], line, count=1)

text = ''.join(lines)
text = text.replace(
    "@vite(['resources/css/app.css', 'resources/js/app.js'])\n",
    "@vite(['resources/css/app.css', 'resources/js/app.js'])\n    <script src=\"https://unpkg.com/lucide@latest\"></script>\n",
    1
)
text = text.replace("</body>", "    <script>lucide.createIcons();</script>\n</body>", 1)

open(p, 'w', encoding='utf-8').write(text)
print("done")