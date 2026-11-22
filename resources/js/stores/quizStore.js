import { defineStore } from 'pinia';
import echo from '../echo';

export const useQuizStore = defineStore('quiz', {
    state: () => ({
        sessionId: null,
        reguId: null,
        round: 1,
        serverTime: null,
        endTime: null,
        remainingSeconds: 0,
        isLocked: false,
        warningCount: 0,
        wsConnected: false,
        saveStatus: 'idle', // 'idle' | 'saving' | 'saved' | 'error'
        answers: {}, // { [questionId]: answerText }
        answeredCount: 0,
        totalQuestions: 0,
    }),

    actions: {
        initSession(payload) {
            this.sessionId = payload.sessionId;
            this.reguId = payload.reguId;
            this.round = payload.round;
            this.remainingSeconds = payload.remainingSeconds || 0;
            this.endTime = payload.endTime || null;
            this.isLocked = payload.isLocked || false;
            this.warningCount = payload.warningCount || 0;
            this.totalQuestions = payload.totalQuestions || 0;

            this.restoreLocalAnswers();
            this.initEchoListeners();
        },

        restoreLocalAnswers() {
            const prefix = `ans_r${this.round}_s${this.sessionId}_`;
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key && key.startsWith(prefix)) {
                    const qId = key.replace(prefix, '').replace('answers[', '').replace(']', '');
                    this.answers[qId] = localStorage.getItem(key);
                }
            }
            this.updateAnsweredCount();
        },

        setAnswer(questionId, value) {
            this.answers[questionId] = value;
            const storageKey = `ans_r${this.round}_s${this.sessionId}_answers[${questionId}]`;
            if (value !== null && value !== undefined && value !== '') {
                localStorage.setItem(storageKey, value);
            } else {
                localStorage.removeItem(storageKey);
            }
            this.updateAnsweredCount();
        },

        updateAnsweredCount() {
            this.answeredCount = Object.values(this.answers).filter(v => v !== null && v !== undefined && v !== '').length;
        },

        async autosaveAnswer(questionId, value, csrfToken) {
            this.setAnswer(questionId, value);
            this.saveStatus = 'saving';

            try {
                const response = await fetch('/peserta/cerdas-cermat/autosave', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        answer_text: value,
                        round: this.round,
                    })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    this.saveStatus = 'saved';
                    this.answeredCount = data.answered_count ?? this.answeredCount;
                    setTimeout(() => {
                        if (this.saveStatus === 'saved') this.saveStatus = 'idle';
                    }, 2000);
                } else if (response.status === 423 || data.locked) {
                    this.isLocked = true;
                    this.saveStatus = 'error';
                } else {
                    this.saveStatus = 'error';
                }
            } catch (err) {
                console.error('Autosave failed', err);
                this.saveStatus = 'error';
            }
        },

        initEchoListeners() {
            if (!this.sessionId) return;

            // Connect to private session channel
            echo.private(`quiz-session.${this.sessionId}`)
                .listen('.SessionUnlocked', (e) => {
                    this.isLocked = false;
                    this.warningCount = 0;
                    if (window.onSessionUnlocked) {
                        window.onSessionUnlocked(e);
                    }
                })
                .listen('.ExamViolationLogged', (e) => {
                    this.warningCount = e.violationCount;
                    this.isLocked = e.isFrozen;
                    if (window.onViolationLogged) {
                        window.onViolationLogged(e);
                    }
                });

            // Connect to presence quiz arena
            echo.join('quiz-arena')
                .here((users) => {
                    this.wsConnected = true;
                })
                .joining((user) => {})
                .leaving((user) => {})
                .listen('.RoundStatusChanged', (e) => {
                    if (window.onRoundStatusChanged) {
                        window.onRoundStatusChanged(e);
                    }
                });
        }
    }
});
