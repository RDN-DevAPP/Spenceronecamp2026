import './bootstrap';
import { createApp } from 'vue';

import CipherCharacter from './components/CipherCharacter.vue';
import CipherTranslator from './components/CipherTranslator.vue';
import AdminQuizPanel from './components/cyber-quiz/AdminQuizPanel.vue';
import JuriQuizPanel from './components/cyber-quiz/JuriQuizPanel.vue';
import PesertaRoadmapView from './components/cyber-quiz/PesertaRoadmapView.vue';
import PesertaQrHunt from './components/cyber-quiz/PesertaQrHunt.vue';
import PesertaCipherSearch from './components/cyber-quiz/PesertaCipherSearch.vue';
import PesertaCoinRoyale from './components/cyber-quiz/PesertaCoinRoyale.vue';
import LiveCoinRoyaleBoard from './components/cyber-quiz/LiveCoinRoyaleBoard.vue';

const components = {
    AdminQuizPanel,
    JuriQuizPanel,
    PesertaRoadmapView,
    PesertaQrHunt,
    PesertaCipherSearch,
    PesertaCoinRoyale,
    LiveCoinRoyaleBoard,
    CipherTranslator,
};

function decodeBase64Utf8(b64) {
    const binary = atob(b64);
    const bytes = new Uint8Array(binary.length);
    for (let i = 0; i < binary.length; i++) {
        bytes[i] = binary.charCodeAt(i);
    }
    return new TextDecoder().decode(bytes);
}

function mountTarget(elId, payloadId) {
    const el = document.getElementById(elId);
    if (!el) return;

    const componentName = el.dataset.component;
    const Component = components[componentName];
    if (!Component) {
        console.error(`[CyberQuiz] Unknown component: ${componentName}`);
        return;
    }

    let initialData = {};
    if (el.dataset.payloadB64) {
        try {
            initialData = JSON.parse(decodeBase64Utf8(el.dataset.payloadB64));
        } catch (err) {
            console.error('[CyberQuiz] Failed decoding base64 payload:', err);
        }
    } else {
        const payloadEl = document.getElementById(payloadId);
        if (payloadEl) {
            try {
                initialData = JSON.parse(payloadEl.textContent || '{}');
            } catch (err) {
                console.error('[CyberQuiz] Failed parsing JSON payload:', err);
            }
        }
    }

    const app = createApp(Component, { initialData });
    app.component('CipherCharacter', CipherCharacter);
    app.component('CipherTranslator', CipherTranslator);
    app.mount(el);
}

mountTarget('cyber-quiz-root', 'cyber-quiz-payload');
mountTarget('live-coin-royale-root', 'live-coin-royale-payload');
