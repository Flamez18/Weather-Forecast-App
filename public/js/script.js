// ================================================
// NEXUS Weather - Main JavaScript
// ================================================

// --- UNIT CONVERTER ---
// defaultUnit diinjeksi dari Blade sebelum file ini dimuat
let currentUnit = typeof defaultUnit !== 'undefined' ? defaultUnit : 'celsius';

function toF(c) {
    return Math.round((c * 9 / 5) + 32);
}

function setUnit(unit) {
    currentUnit = unit;

    const btnC = document.getElementById('btn-celsius');
    const btnF = document.getElementById('btn-fahrenheit');
    if (btnC) btnC.classList.toggle('active', unit === 'celsius');
    if (btnF) btnF.classList.toggle('active', unit === 'fahrenheit');

    // Update main temp
    const mainTemp = document.getElementById('main-temp');
    if (mainTemp) {
        const c = parseFloat(mainTemp.dataset.celsius);
        mainTemp.textContent = unit === 'celsius' ? `${c}°C` : `${toF(c)}°F`;
    }

    // Update semua .temp-val
    document.querySelectorAll('.temp-val').forEach(el => {
        const c = parseFloat(el.dataset.celsius);
        const isForecast = el.classList.contains('forecast-max') || el.classList.contains('forecast-min');
        if (isForecast) {
            el.textContent = unit === 'celsius' ? `${c}°` : `${toF(c)}°`;
        } else {
            el.textContent = unit === 'celsius' ? `${c}°C` : `${toF(c)}°F`;
        }
    });
}

// --- SHARE WEATHER ---
function shareWeather() {
    const shareEl = document.getElementById('share-text');
    if (!shareEl) return;
    const text = shareEl.textContent.trim();

    if (navigator.share) {
        navigator.share({ title: 'NEXUS Weather', text: text });
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Teks cuaca berhasil disalin ke clipboard!', 'success');
        });
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
    }
}

// --- TOAST NOTIFICATION ---
function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toastWrap');
    if (!wrap) return;
    const id   = 'toast-' + Date.now();
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    wrap.insertAdjacentHTML('beforeend', `
        <div class="toast toast-${type}" id="${id}">
            <span style="font-size:18px;flex-shrink:0;"><i class="fas ${icon}"></i></span>
            <span style="flex:1;line-height:1.4;">${msg}</span>
            <button class="toast-close" onclick="dismissToast('${id}')"><i class="fas fa-times"></i></button>
            <div class="toast-bar"></div>
        </div>
    `);
    setTimeout(() => dismissToast(id), 4000);
}

function dismissToast(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.animation = 'slideOut 0.3s ease-in forwards';
    setTimeout(() => el.remove(), 300);
}

// --- INIT ON DOM READY ---
document.addEventListener('DOMContentLoaded', () => {
    // Apply default unit dari settings
    if (currentUnit === 'fahrenheit') {
        setUnit('fahrenheit');
    }

    // Auto dismiss existing toasts
    document.querySelectorAll('.toast').forEach(toast => {
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-in forwards';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    });
});
