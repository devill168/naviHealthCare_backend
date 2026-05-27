<template>
  <div class="login-page">
    <div class="login-wrapper">
      <div class="login-card">

        <div class="login-header">
          <div class="logo-box">
            <img src="@/assets/logo_moh.png" alt="Logo MOH" width="120" height="200" />
          </div>

          <h2 class="login-title">{{ t("login.title") }}</h2>
          <p class="login-subtitle">
            Please sign in to access your account
          </p>
        </div>

        <form class="login-form" @submit.prevent="onLogin">
          <transition name="fade-slide">
            <div v-if="loginErrorMessage" class="alert alert-danger" role="alert">
              <i class="fas fa-circle-exclamation alert-icon"></i>
              <span>{{ loginErrorMessage }}</span>
            </div>
          </transition>

          <div class="form-group">
            <label>{{ t("login.username") }}</label>
            <div class="input-box">
              <span class="input-box__icon">
                <i class="fas fa-user"></i>
              </span>
              <input type="text" :placeholder="t('login.enterUsername')" v-model.trim="username" :disabled="loading"
                autocomplete="username" />
            </div>
          </div>

          <div class="form-group">
            <label>{{ t("login.password") }}</label>
            <div class="input-box">
              <span class="input-box__icon">
                <i class="fas fa-lock"></i>
              </span>
              <input :type="showPassword ? 'text' : 'password'" :placeholder="t('login.enterPassword')"
                v-model="password" :disabled="loading" autocomplete="current-password" />
              <button type="button" class="input-box__toggle" :disabled="loading" @click="showPassword = !showPassword">
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
            </div>
          </div>

          <div class="login-links">
            <a href="#" class="link-btn" @click.prevent="openForgotModal">
              {{ t("login.forgotPassword") }}
            </a>

            <a href="#" class="link-btn link-btn--primary" @click.prevent="showHelpModal = true">
              {{ t("login.needHelp") }}
            </a>
          </div>

          <button type="submit" class="login-btn" :disabled="loading">
            <span class="login-btn__content">
              <i v-if="loading" class="fas fa-spinner fa-spin"></i>
              <i v-else class="fas fa-right-to-bracket"></i>
              <span>{{ loading ? t("login.signingIn") : t("login.signIn") }}</span>
            </span>
          </button>
        </form>
      </div>
    </div>

    <!-- Help Modal -->
    <transition name="modal-fade">
      <div v-if="showHelpModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="helpModalTitle"
        @click.self="closeHelp">
        <div class="modal-box modern-modal" tabindex="-1" ref="helpModal">
          <div class="modal-topbar"></div>

          <div class="modal-header">
            <div class="modal-header__left">
              <div class="modal-badge">
                <i class="fas fa-headset"></i>
              </div>
              <div>
                <h3 class="modal-title" id="helpModalTitle">{{ t("login.needHelp") }}</h3>
                <div class="modal-subtitle">Support information</div>
              </div>
            </div>

            <button class="modal-close" type="button" :aria-label="t('login.close')" @click="closeHelp">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <p class="modal-text">
            {{ t("login.helpMessage1") }}<br />
            <b>{{ t("login.helpMessage2") }}</b>
          </p>

          <div class="contact-list">
            <div class="contact-card">
              <div class="contact-card__icon">
                <i class="fas fa-phone-alt"></i>
              </div>
              <div class="contact-card__body">
                <div class="contact-card__label">{{ t("login.phone") }}</div>
                <a href="tel:+85517940760" class="contact-card__value">+855 17 940 760</a>
              </div>
            </div>

            <div class="contact-card">
              <div class="contact-card__icon contact-card__icon--telegram">
                <i class="fab fa-telegram-plane"></i>
              </div>
              <div class="contact-card__body">
                <div class="contact-card__label">{{ t("login.telegram") }}</div>
                <a href="https://t.me/MOH" target="_blank" class="contact-card__value">
                  @MOH_Cambodia
                </a>
              </div>
            </div>

            <div class="contact-card">
              <div class="contact-card__icon contact-card__icon--gmail">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="contact-card__body">
                <div class="contact-card__label">{{ t("login.gmail") }}</div>
                <a href="mailto:moh_cambodia@gmail.com" class="contact-card__value">
                  moh_cambodia@gmail.com
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Forgot Password Modal -->
    <transition name="modal-fade">
      <div v-if="showForgotModal" class="modal-overlay" role="dialog" aria-modal="true"
        aria-labelledby="forgotModalTitle" @click.self="closeForgot">
        <div class="modal-box modern-modal" tabindex="-1" ref="forgotModal">
          <div class="modal-topbar"></div>

          <div class="modal-header">
            <div class="modal-header__left">
              <div class="modal-badge modal-badge--warning">
                <i class="fas fa-key"></i>
              </div>
              <div>
                <h3 class="modal-title" id="forgotModalTitle">{{ t("login.forgotPasswordTitle") }}</h3>
                <div class="modal-subtitle">Reset your password</div>
              </div>
            </div>

            <button class="modal-close" type="button" :aria-label="t('login.close')" @click="closeForgot">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <p class="modal-text">
            {{ t("login.forgotPasswordDescription") }}
          </p>

          <form @submit.prevent="sendLink">
            <div class="form-group">
              <label>{{ t("login.email") }}</label>
              <div class="input-box">
                <span class="input-box__icon">
                  <i class="fas fa-envelope"></i>
                </span>
                <input type="email" v-model.trim="email" :placeholder="t('login.enterEmail')"
                  :disabled="forgotLoading" />
              </div>
            </div><br>

            <button type="submit" class="login-btn" :disabled="forgotLoading">
              <span class="login-btn__content">
                <i v-if="forgotLoading" class="fas fa-spinner fa-spin"></i>
                <i v-else class="fas fa-paper-plane"></i>
                <span>{{ forgotLoading ? t("login.sending") : t("login.sendResetLink") }}</span>
              </span>
            </button>
          </form>
        </div>
      </div>
    </transition>

    <!-- Forgot Password Result Modal -->
    <transition name="modal-fade">
      <div v-if="showForgotResultModal" class="modal-overlay" role="dialog" aria-modal="true"
        aria-labelledby="forgotResultModalTitle" @click.self="closeForgotResultModal">
        <div class="modal-box modern-modal result-modal" tabindex="-1" ref="forgotResultModal">
          <div class="modal-topbar"></div>

          <div class="modal-header">
            <div class="modal-header__left">
              <div class="modal-badge"
                :class="forgotResultType === 'success' ? 'modal-badge--success' : 'modal-badge--danger'">
                <i :class="forgotResultType === 'success' ? 'fas fa-check-circle' : 'fas fa-circle-exclamation'"></i>
              </div>
              <div>
                <h3 class="modal-title" id="forgotResultModalTitle">
                  {{ forgotResultType === "success" ? t("login.success") : t("login.error") }}
                </h3>
                <div class="modal-subtitle">Password reset result</div>
              </div>
            </div>

            <button class="modal-close" type="button" :aria-label="t('login.close')" @click="closeForgotResultModal">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <div class="result-state" :class="forgotResultType === 'success' ? 'success' : 'error'">
            <i :class="forgotResultType === 'success' ? 'fas fa-check' : 'fas fa-exclamation'"></i>
          </div>

          <p class="modal-text result-text">
            {{ forgotResultMessage }}
          </p>

          <button class="login-btn" @click="closeForgotResultModal">
            <span class="login-btn__content">
              <i class="fas fa-check"></i>
              <span>{{ t("login.ok") }}</span>
            </span>
          </button>
        </div>
      </div>
    </transition>

    <!-- Login Loading Modal -->
    <div v-if="loading" class="loading-overlay" role="dialog" aria-modal="true">
      <div class="loading-box" :aria-label="t('login.loggingIn')">
        <div class="spinner"></div>
        <div class="loading-text">{{ t("login.signingIn") }}</div>
      </div>
    </div>

    <!-- Forgot Password Loading Modal -->
    <div v-if="forgotLoading" class="loading-overlay" role="dialog" aria-modal="true">
      <div class="loading-box" :aria-label="t('login.sendingResetLink')">
        <div class="spinner"></div>
        <div class="loading-text">{{ t("login.sendingResetLink") }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useI18n } from "vue-i18n";

export default {
  name: "LoginView",
  setup() {
    const { t, locale } = useI18n();
    return { t, locale };
  },

  data() {
    return {
      showHelpModal: false,
      showForgotModal: false,
      showForgotResultModal: false,
      showPassword: false,

      username: "",
      password: "",

      email: "",
      forgotLoading: false,

      forgotResultType: "",
      forgotResultMessage: "",

      loginErrorMessage: "",
      loading: false,
    };
  },

  mounted() {
    window.addEventListener("keydown", this.onKeydown);
  },

  beforeUnmount() {
    window.removeEventListener("keydown", this.onKeydown);
    document.body.classList.remove("modal-open");
    document.body.style.overflow = "";
  },

  watch: {
    loading(v) {
      if (v) {
        document.body.style.overflow = "hidden";
      } else if (
        !this.showHelpModal &&
        !this.showForgotModal &&
        !this.showForgotResultModal &&
        !this.forgotLoading
      ) {
        document.body.style.overflow = "";
      }
    },

    forgotLoading(v) {
      if (v) {
        document.body.style.overflow = "hidden";
      } else if (
        !this.showHelpModal &&
        !this.showForgotModal &&
        !this.showForgotResultModal &&
        !this.loading
      ) {
        document.body.style.overflow = "";
      }
    },

    showHelpModal(v) {
      document.body.classList.toggle(
        "modal-open",
        v || this.showForgotModal || this.showForgotResultModal
      );

      if (v) {
        this.$nextTick(() => {
          this.$refs.helpModal?.focus?.();
        });
      }

      if (
        !v &&
        !this.showForgotModal &&
        !this.showForgotResultModal &&
        !this.loading &&
        !this.forgotLoading
      ) {
        document.body.style.overflow = "";
      }
    },

    showForgotModal(v) {
      document.body.classList.toggle(
        "modal-open",
        v || this.showHelpModal || this.showForgotResultModal
      );

      if (v) {
        this.$nextTick(() => {
          this.$refs.forgotModal?.focus?.();
        });
      }

      if (
        !v &&
        !this.showHelpModal &&
        !this.showForgotResultModal &&
        !this.loading &&
        !this.forgotLoading
      ) {
        document.body.style.overflow = "";
      }
    },

    showForgotResultModal(v) {
      document.body.classList.toggle(
        "modal-open",
        v || this.showHelpModal || this.showForgotModal
      );

      if (v) {
        this.$nextTick(() => {
          this.$refs.forgotResultModal?.focus?.();
        });
      }

      if (
        !v &&
        !this.showHelpModal &&
        !this.showForgotModal &&
        !this.loading &&
        !this.forgotLoading
      ) {
        document.body.style.overflow = "";
      }
    },
  },

  methods: {
    async onLogin() {
      this.loginErrorMessage = "";
      this.loading = true;

      try {
        const res = await axios.post("/login", {
          username: this.username,
          password: this.password,
        });

        localStorage.setItem("user", JSON.stringify(res.data.user));

        const roleName = res.data.user?.role?.name?.toLowerCase();

        if (roleName === "admin") {
          this.$router.push("/home");
        } else {
          this.$router.push("/health-facilities/find-location");
        }
      } catch (err) {
        this.loginErrorMessage =
          err?.response?.data?.message || this.t("login.loginFailed");
      } finally {
        this.loading = false;
      }
    },

    async sendLink() {
      this.forgotLoading = true;
      this.forgotResultMessage = "";
      this.forgotResultType = "";

      const forgotRequest = axios.post("/forgot-password", {
        email: this.email,
      });

      await new Promise((resolve) => setTimeout(resolve, 1500));

      this.showForgotModal = false;
      this.forgotLoading = false;

      try {
        const res = await forgotRequest;

        this.forgotResultType = "success";
        this.forgotResultMessage =
          res.data.message || this.t("login.resetLinkSent");

        this.showForgotResultModal = true;
        this.email = "";
      } catch (err) {
        this.forgotResultType = "error";
        this.forgotResultMessage =
          err?.response?.data?.message || this.t("login.resetLinkFailed");

        this.showForgotResultModal = true;
      }
    },

    openForgotModal() {
      this.email = "";
      this.showForgotModal = true;
      this.forgotResultMessage = "";
      this.forgotResultType = "";
    },

    closeHelp() {
      this.showHelpModal = false;
    },

    closeForgot() {
      this.showForgotModal = false;
      this.email = "";
    },

    closeForgotResultModal() {
      this.showForgotResultModal = false;
      this.forgotResultMessage = "";
      this.forgotResultType = "";
    },

    onKeydown(e) {
      if (e.key === "Escape") {
        if (this.showHelpModal) this.closeHelp();
        if (this.showForgotModal) this.closeForgot();
        if (this.showForgotResultModal) this.closeForgotResultModal();
      }
    },
  },
};
</script>

<style scoped>
* {
  box-sizing: border-box;
}

:global(body.modal-open) {
  overflow: hidden;
}

.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  font-family: "Khmer OS Battambang", sans-serif;
  background:
    radial-gradient(circle at top left, rgba(59, 130, 246, 0.12), transparent 30%),
    radial-gradient(circle at bottom right, rgba(99, 102, 241, 0.1), transparent 28%),
    linear-gradient(135deg, #f8fbff 0%, #eef4ff 45%, #f8fafc 100%);
}

.login-wrapper {
  width: 100%;
  max-width: 460px;
}

.login-card {
  position: relative;
  overflow: hidden;
  padding: 34px 30px 30px;
  border-radius: 28px;
  background: rgba(255, 255, 255, 0.86);
  border: 1px solid rgba(226, 232, 240, 0.8);
}

.login-card__glow {
  position: absolute;
  border-radius: 999px;
  filter: blur(8px);
  pointer-events: none;
}

.login-card__glow--one {
  top: -55px;
  right: -40px;
  width: 160px;
  height: 160px;
  background: rgba(59, 130, 246, 0.12);
}

.login-card__glow--two {
  bottom: -65px;
  left: -50px;
  width: 180px;
  height: 180px;
  background: rgba(99, 102, 241, 0.08);
}

.login-header {
  position: relative;
  text-align: center;
  margin-bottom: 28px;
}

.logo-box {
  margin: 0 auto 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo {
  width: 62px;
  height: 62px;
  object-fit: contain;
}

.login-title {
  margin: 0 0 10px;
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
}

.login-subtitle {
  margin: 0;
  font-size: 14px;
  line-height: 1.7;
  color: #64748b;
}

.login-form {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.form-group {
  text-align: left;
}

.form-group label {
  display: inline-block;
  margin-bottom: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #334155;
}

.input-box {
  position: relative;
}

.input-box__icon {
  position: absolute;
  top: 50%;
  left: 15px;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 15px;
  pointer-events: none;
}

.form-group input {
  width: 100%;
  height: 54px;
  padding: 0 46px 0 44px;
  border-radius: 16px;
  border: 1px solid #dbe4ee;
  background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
  outline: none;
  font-size: 14px;
  color: #0f172a;
  transition: all 0.25s ease;
  box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02);
}

.form-group input::placeholder {
  color: #9aa6b2;
}

.form-group input:focus {
  border-color: #2563eb;
  background: #ffffff;
  box-shadow:
    0 0 0 4px rgba(37, 99, 235, 0.12),
    0 8px 18px rgba(37, 99, 235, 0.08);
}

.input-box__toggle {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  width: 34px;
  height: 34px;
  border: none;
  border-radius: 10px;
  background: transparent;
  color: #64748b;
  cursor: pointer;
  transition: all 0.2s ease;
}

.input-box__toggle:hover {
  background: #eff6ff;
  color: #2563eb;
}

.login-links {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-top: -2px;
}

.link-btn {
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
  color: #64748b;
  transition: all 0.2s ease;
}

.link-btn:hover {
  color: #1d4ed8;
}

.link-btn--primary {
  color: #2563eb;
}

.login-btn {
  width: 100%;
  height: 54px;
  border: none;
  border-radius: 16px;
  background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
  color: #ffffff;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.25s ease;
  box-shadow: 0 14px 28px rgba(37, 99, 235, 0.2);
}

.login-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 18px 34px rgba(37, 99, 235, 0.25);
}

.login-btn:disabled {
  opacity: 0.72;
  cursor: not-allowed;
  transform: none;
}

.login-btn__content {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.alert {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 14px 15px;
  border-radius: 16px;
  font-size: 14px;
  line-height: 1.6;
  text-align: left;
}

.alert-icon {
  margin-top: 2px;
}

.alert-danger {
  background: linear-gradient(180deg, #fff5f5 0%, #fef2f2 100%);
  color: #b42318;
  border: 1px solid #fecaca;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 18px;
  background: rgba(15, 23, 42, 0.45);
  backdrop-filter: blur(8px);
  z-index: 9999;
}

.modern-modal {
  position: relative;
  overflow: hidden;
}

.modal-box {
  width: 100%;
  max-width: 470px;
  background: rgba(255, 255, 255, 0.96);
  border-radius: 24px;
  border: 1px solid rgba(226, 232, 240, 0.9);
  box-shadow: 0 30px 70px rgba(15, 23, 42, 0.2);
  padding: 22px;
  outline: none;
}

.modal-topbar {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5px;
  background: linear-gradient(90deg, #2563eb, #4f46e5);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 14px;
  margin-bottom: 16px;
}

.modal-header__left {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.modal-badge {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  background: #eaf2ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 17px;
  flex-shrink: 0;
}

.modal-badge--warning {
  background: #fff4e8;
  color: #d97706;
}

.modal-badge--success {
  background: #ecfdf3;
  color: #067647;
}

.modal-badge--danger {
  background: #fef2f2;
  color: #b42318;
}

.modal-title {
  margin: 0;
  font-size: 21px;
  font-weight: 700;
  color: #0f172a;
}

.modal-subtitle {
  margin-top: 4px;
  font-size: 13px;
  color: #64748b;
}

.modal-close {
  width: 38px;
  height: 38px;
  border: 1px solid #e2e8f0;
  background: #ffffff;
  border-radius: 12px;
  cursor: pointer;
  color: #334155;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.modal-close:hover {
  background: #f8fafc;
  border-color: #cbd5e1;
}

.modal-text {
  margin-bottom: 16px;
  color: #475569;
  line-height: 1.7;
  font-size: 14px;
}

.contact-list {
  display: grid;
  gap: 12px;
}

.contact-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px;
  border-radius: 18px;
  background: #f8fbff;
  border: 1px solid #e2e8f0;
  transition: all 0.2s ease;
}

.contact-card:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 18px rgba(15, 23, 42, 0.05);
}

.contact-card__icon {
  width: 44px;
  height: 44px;
  border-radius: 14px;
  background: #dbeafe;
  color: #1d4ed8;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.contact-card__icon--telegram {
  background: #e0f2fe;
  color: #0284c7;
}

.contact-card__icon--gmail {
  background: #fee2e2;
  color: #dc2626;
}

.contact-card__label {
  font-size: 12px;
  font-weight: 700;
  color: #64748b;
  margin-bottom: 3px;
}

.contact-card__value {
  font-size: 14px;
  text-decoration: none;
  color: #0f172a;
  font-weight: 600;
}

.contact-card__value:hover {
  color: #2563eb;
}

.result-modal {
  text-align: center;
}

.result-state {
  width: 82px;
  height: 82px;
  margin: 6px auto 18px;
  border-radius: 50%;
  font-size: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.result-state.success {
  background: #ecfdf3;
  color: #067647;
  border: 2px solid #abefc6;
}

.result-state.error {
  background: #fef2f2;
  color: #b42318;
  border: 2px solid #fecaca;
}

.result-text {
  text-align: center;
  font-size: 15px;
}

.loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.34);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
  backdrop-filter: blur(4px);
}

.loading-box {
  background: rgba(255, 255, 255, 0.98);
  padding: 24px 28px;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
  min-width: 220px;
  box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18);
}

.spinner {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  border: 5px solid #e5e7eb;
  border-top-color: #2563eb;
  animation: spin 0.9s linear infinite;
}

.loading-text {
  font-weight: 600;
  font-size: 14px;
  color: #334155;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.25s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: all 0.22s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
  transform: scale(0.96);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 576px) {
  .login-page {
    padding: 16px;
  }

  .login-card {
    padding: 26px 20px 22px;
    border-radius: 22px;
  }

  .login-title {
    font-size: 24px;
  }

  .logo-box {
    width: 84px;
    height: 84px;
    border-radius: 22px;
  }

  .logo {
    width: 56px;
    height: 56px;
  }

  .login-links {
    flex-direction: column;
    align-items: flex-start;
  }

  .modal-box {
    padding: 18px;
    border-radius: 20px;
  }

  .modal-title {
    font-size: 18px;
  }

  .contact-card {
    padding: 12px;
  }
}
</style>
