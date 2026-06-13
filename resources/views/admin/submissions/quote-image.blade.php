<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quote Image — {{ $submission->getDisplayName() }}</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
* { font-family: 'Space Grotesk', sans-serif; box-sizing: border-box; }
body { background: #FDF6E3; margin: 0; padding: 20px; }
.btn { display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 20px;font-weight:700;font-size:14px;border:2px solid #1A1A1A;border-radius:12px;box-shadow:3px 3px 0px 0px #1A1A1A;cursor:pointer;transition:all 0.1s;text-decoration:none;background:#FFFDF7;color:#1A1A1A; }
.btn:hover { transform:translate(-1px,-1px);box-shadow:4px 4px 0px 0px #1A1A1A; }
.btn-primary { background:#1A1A1A;color:#FDF6E3; }
.btn-green { background:#25D366;color:#fff; }
</style>
</head>
<body>

<div class="max-w-lg mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button onclick="window.close()" class="btn" style="padding:8px 16px;font-size:13px;">← Tutup</button>
        <h1 class="text-xl font-black">Generator Quote Image</h1>
    </div>

    <!-- Controls -->
    <div class="p-4 rounded-2xl border-2 border-gray-800 mb-4" style="background:#FFFDF7;box-shadow:4px 4px 0 #1A1A1A;">
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block text-xs font-bold mb-1">Background</label>
                <div class="flex gap-1 flex-wrap">
                    @foreach(['#FDF6E3' => 'Cream', '#1A1A1A' => 'Hitam', '#FF7A6B' => 'Coral', '#FFE066' => 'Kuning', '#A8D0F0' => 'Biru', '#FFB5D8' => 'Pink'] as $color => $name)
                    <button onclick="setBg('{{ $color }}')" class="w-7 h-7 rounded-full border-2 border-gray-800 hover:scale-110 transition" style="background:{{ $color }};" title="{{ $name }}"></button>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">Ukuran Font</label>
                <input type="range" id="fontSizeRange" min="24" max="72" value="36" class="w-full" oninput="renderCanvas()">
                <span class="text-xs font-bold" id="fontSizeLabel">36px</span>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="renderCanvas()" class="btn btn-primary" style="padding:8px 16px;font-size:13px;">🔄 Refresh</button>
            <button onclick="downloadCanvas()" class="btn btn-green" style="padding:8px 16px;font-size:13px;">⬇ Download PNG</button>
        </div>
    </div>

    <!-- Canvas Preview -->
    <div class="overflow-hidden rounded-2xl border-2 border-gray-800" style="box-shadow:6px 6px 0 #1A1A1A;">
        <canvas id="quoteCanvas" width="540" height="960" style="width:100%;display:block;"></canvas>
    </div>
</div>

<script>
const submission = {
    content: @json($submission->content),
    name: @json($submission->getDisplayName()),
    roomTitle: @json($submission->room->title),
};

let bgColor = '#FDF6E3';

function setBg(color) { bgColor = color; renderCanvas(); }

function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
    const words = text.split(' ');
    let line = '';
    let lines = [];

    for (let i = 0; i < words.length; i++) {
        const testLine = line + words[i] + ' ';
        const metrics = ctx.measureText(testLine);
        if (metrics.width > maxWidth && i > 0) {
            lines.push(line.trim());
            line = words[i] + ' ';
        } else {
            line = testLine;
        }
    }
    lines.push(line.trim());

    lines.forEach((l, i) => {
        ctx.fillText(l, x, y + (i * lineHeight));
    });
    return lines.length;
}

function renderCanvas() {
    const canvas = document.getElementById('quoteCanvas');
    const ctx = canvas.getContext('2d');
    const W = canvas.width;
    const H = canvas.height;

    // Determine text color based on bg brightness
    const isDark = bgColor === '#1A1A1A';
    const textColor = isDark ? '#FDF6E3' : '#1A1A1A';
    const accentColor = isDark ? '#FFE066' : '#FF7A6B';
    const mutedColor = isDark ? 'rgba(253,246,227,0.6)' : 'rgba(26,26,26,0.5)';

    // Background
    ctx.fillStyle = bgColor;
    ctx.fillRect(0, 0, W, H);

    // Border decoration
    ctx.strokeStyle = isDark ? '#FFE066' : '#1A1A1A';
    ctx.lineWidth = 8;
    ctx.strokeRect(24, 24, W - 48, H - 48);

    // Corner decorations
    ctx.fillStyle = accentColor;
    ctx.fillRect(16, 16, 40, 40);
    ctx.fillRect(W - 56, H - 56, 40, 40);

    // Quote marks
    ctx.font = 'bold 160px Space Grotesk';
    ctx.fillStyle = isDark ? 'rgba(255,224,102,0.15)' : 'rgba(255,122,107,0.12)';
    ctx.fillText('"', 44, 260);

    // Font size from slider
    const fontSize = parseInt(document.getElementById('fontSizeRange').value);
    document.getElementById('fontSizeLabel').textContent = fontSize + 'px';

    // Main text
    ctx.font = `700 ${fontSize}px Space Grotesk`;
    ctx.fillStyle = textColor;
    ctx.textAlign = 'left';

    const padding = 64;
    const maxWidth = W - (padding * 2);
    const lineHeight = fontSize * 1.4;
    const startY = H * 0.3;
    const linesCount = wrapText(ctx, submission.content, padding, startY, maxWidth, lineHeight);

    const textEndY = startY + (linesCount * lineHeight) + 40;

    // Author
    ctx.font = 'bold 26px Space Grotesk';
    ctx.fillStyle = accentColor;
    ctx.fillText('— ' + submission.name, padding, textEndY);

    // Divider line
    ctx.strokeStyle = isDark ? 'rgba(255,224,102,0.3)' : 'rgba(26,26,26,0.15)';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(padding, H - 200);
    ctx.lineTo(W - padding, H - 200);
    ctx.stroke();

    // Room title
    ctx.font = '600 22px Space Grotesk';
    ctx.fillStyle = mutedColor;
    ctx.fillText(submission.roomTitle, padding, H - 168);

    // Brand
    ctx.font = 'bold 32px Space Grotesk';
    ctx.fillStyle = textColor;
    ctx.fillText('TitipKata', padding, H - 120);

    ctx.font = '18px Space Grotesk';
    ctx.fillStyle = mutedColor;
    ctx.fillText('titipkata.my.id', padding, H - 90);

    // Sparkle decoration
    ctx.font = '36px Space Grotesk';
    ctx.fillStyle = accentColor;
    ctx.fillText('✦', W - padding - 50, H - 100);
}

function downloadCanvas() {
    renderCanvas();
    const canvas = document.getElementById('quoteCanvas');
    const a = document.createElement('a');
    a.download = 'titipkata-quote.png';
    a.href = canvas.toDataURL('image/png');
    a.click();
}

// Wait for font to load
document.fonts.ready.then(() => renderCanvas());
</script>
</body>
</html>
