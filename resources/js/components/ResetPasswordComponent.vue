<template>
  <div class="login-page">
    <div class="login-box">
      <img src="@/assets/logo_moh.png" alt="Logo MOH" class="logo" />

      <h2 class="title">Reset Password</h2>

      <form @submit.prevent="resetPassword">
        <div class="form-group">
          <label>New Password</label>
          <input
            type="password"
            placeholder="Enter new password"
            v-model="password"
            :disabled="isLoading"
          />
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input
            type="password"
            placeholder="Confirm password"
            v-model="confirmPassword"
            :disabled="isLoading"
          />
        </div>

        <button type="submit" class="login-btn" :disabled="isLoading">
          {{ isLoading ? "Processing..." : "Reset Password" }}
        </button>
      </form>
    </div>

    <!-- Loading Modal -->
    <div v-if="isLoading" class="loading-overlay" role="dialog" aria-modal="true">
      <div class="loading-box" aria-label="Resetting password">
        <div class="spinner"></div>
        <div class="loading-text">Please wait, resetting password...</div>
      </div>
    </div>

    <!-- Result Modal -->
    <div
      v-if="showModal && !isLoading"
      class="modal-overlay"
      role="dialog"
      aria-modal="true"
      aria-labelledby="resultModalTitle"
      @click.self="closeModal"
    >
      <div class="modal-box result-modal">
        <div class="modal-header">
          <h3 class="modal-title" id="resultModalTitle">
            {{ modalType === "success" ? "Success" : "Error" }}
          </h3>

          <button class="modal-close" type="button" aria-label="Close" @click="closeModal">
            ✕
          </button>
        </div>

        <div class="result-icon" :class="modalType === 'success' ? 'success' : 'error'">
          {{ modalType === "success" ? "✓" : "!" }}
        </div>

        <p class="modal-text result-text">
          {{ modalMessage }}
        </p>

        <button class="login-btn" @click="closeModal">
          OK
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ResetPassword",
  data() {
    return {
      password: "",
      confirmPassword: "",
      isLoading: false,
      showModal: false,
      modalType: "", // success | error
      modalMessage: ""
    };
  },

  methods: {
    openModal(type, message) {
      this.modalType = type;
      this.modalMessage = message;
      this.showModal = true;
    },

    closeModal() {
      this.showModal = false;

      if (this.modalType === "success") {
        this.$router.push("/");
      }
    },

    async resetPassword() {
      this.showModal = false;
      this.modalMessage = "";
      this.modalType = "";

      if (!this.password || !this.confirmPassword) {
        this.openModal("error", "Please fill all fields.");
        return;
      }

      if (this.password !== this.confirmPassword) {
        this.openModal("error", "Password confirmation does not match.");
        return;
      }

      try {
        const token = this.$route.query.token;
        const email = this.$route.query.email;

        if (!token || !email) {
          this.openModal("error", "Invalid reset link.");
          return;
        }

        this.isLoading = true;

        const res = await axios.post("http://127.0.0.1:8000/api/reset-password", {
          token,
          email,
          password: this.password,
          password_confirmation: this.confirmPassword
        });

        this.password = "";
        this.confirmPassword = "";

        this.openModal(
          "success",
          res.data.message || "Password reset successfully."
        );
      } catch (err) {
        this.openModal(
          "error",
          err?.response?.data?.message || "Failed to reset password."
        );
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  background-color: #f9f9f9;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-box {
  width: 380px;
  padding: 40px;
  border-radius: 16px;
  background: linear-gradient(to bottom, #fbf9df, #fffef7);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  text-align: center;
}

.logo {
  width: 80px;
  margin: 0 auto 20px;
}

.title {
  margin-bottom: 30px;
  font-weight: 600;
  color: #333;
}

.form-group {
  text-align: left;
  margin-bottom: 18px;
}

.form-group input {
  width: 100%;
  padding: 10px 12px;
  margin-top: 6px;
  border-radius: 8px;
  border: 1px solid #ddd;
  box-sizing: border-box;
  outline: none;
}

.form-group input:focus {
  border-color: #facc15;
  box-shadow: 0 0 0 2px rgba(250, 204, 21, 0.2);
}

.login-btn {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  background-color: #facc15;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s ease;
}

.login-btn:hover {
  background-color: #eab308;
  color: white;
}

.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Loading */
.loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.35);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
}

.loading-box {
  background: #fff;
  padding: 22px 26px;
  border-radius: 14px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  min-width: 220px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.spinner {
  width: 46px;
  height: 46px;
  border-radius: 50%;
  border: 5px solid #e6e6e6;
  border-top-color: #eabe0d;
  animation: spin 0.9s linear infinite;
}

.loading-text {
  font-weight: 600;
  font-size: 14px;
  color: #333;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Modal */
.modal-overlay {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 18px;
  background: rgba(15, 15, 15, 0.45);
  backdrop-filter: blur(6px);
  z-index: 9999;
}

.modal-box {
  width: 100%;
  max-width: 440px;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 18px;
  box-shadow: 0 18px 50px rgba(0, 0, 0, 0.25);
  padding: 18px;
  outline: none;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.modal-title {
  font-size: 18px;
  font-weight: 600;
  color: #222;
}

.modal-close {
  width: 36px;
  height: 36px;
  border: 1px solid #e9e9e9;
  background: #fff;
  border-radius: 12px;
  cursor: pointer;
}

.modal-close:hover {
  background-color: #e6e6e6;
  border: 1px solid #ddd;
}

.modal-text {
  margin-bottom: 14px;
  color: #444;
}

/* Result modal */
.result-modal {
  text-align: center;
}

.result-icon {
  width: 70px;
  height: 70px;
  margin: 8px auto 16px;
  border-radius: 50%;
  font-size: 34px;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
}

.result-icon.success {
  background: #ecfdf3;
  color: #067647;
  border: 2px solid #abefc6;
}

.result-icon.error {
  background: #fdecec;
  color: #b42318;
  border: 2px solid #f5c2c7;
}

.result-text {
  text-align: center;
  font-size: 15px;
}
</style>