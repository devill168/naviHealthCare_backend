<template>
  <div class="container-fluid mt-4">
    <!-- Top Bar -->
    <div class="card shadow-sm rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <h4>{{ t('user.title') }}</h4>
          <button class="btn btn-success" @click="openCreateModal">
            <i class="fa-solid fa-circle-plus"></i> {{ t('user.createButton') }}
          </button>
        </div>

        <!-- Search -->
       <div class="d-flex mb-3">
          <div class="w-25 ms-auto position-relative">
            <i
              class="fa-solid fa-magnifying-glass position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
            <input type="text" class="form-control pe-5" :placeholder="t('user.search')" v-model="search" />
          </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-bg">
              <tr>
                <th>{{ t('user.no') }}</th>
                <th>{{ t('user.image') }}</th>
                <th>{{ t('user.username') }}</th>
                <th>{{ t('user.gender') }}</th>
                <th>{{ t('user.email') }}</th>
                <th>{{ t('user.phone') }}</th>
                <th>{{ t('user.role') }}</th>
                <th>{{ t('user.status') }}</th>
                <th width="180">{{ t('user.action') }}</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(user, index) in filteredUsers" :key="user.id">
                <td>{{ index + 1 }}</td>
                <td>
                  <img :src="user.image || defaultAvatar" class="img-in-table rounded-circle" />
                </td>
                <td>{{ user.username }}</td>
                <td>{{ user.gender }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.phone || '-' }}</td>
                <td>
                  <span class="badge bg-primary">
                    {{ user.role_name || user.role?.name || '-' }}
                  </span>
                </td>
                <td>
                  <span class="badge" :class="statusClass(user.status)">
                    {{ t(`user.${user.status}`) || user.status }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-primary me-1" @click="editUser(user)">
                    <i class="fa-solid fa-edit"></i> {{ t('user.edit') }}
                  </button>
                  <button class="btn btn-sm btn-danger" @click="askDelete(user)">
                    <i class="fa-solid fa-trash"></i> {{ t('user.delete') }}
                  </button>
                </td>
              </tr>

              <tr v-if="filteredUsers.length === 0">
                <td colspan="9" class="text-center text-muted py-4">{{ t('user.noData') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true" ref="createModalEl">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-gradient rounded-4 border-0">
          <div class="modal-header border-0 flex-column text-center">
            <img src="@/assets/logo_moh.png" alt="Logo MOH" class="logo mb-3" />

            <h5 class="modal-title title-border pb-2">
              {{ t('user.titleForm') }}
            </h5>

            <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body pt-0">
            <form @submit.prevent="submitCreateUser" enctype="multipart/form-data">
              <div class="row g-3">
                <!-- Image upload -->
                <div class="col-12">
                  <label class="form-label">{{ t('user.image') }}</label>

                  <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="preview-avatar">
                      <img v-if="createForm.imagePreview" :src="createForm.imagePreview"
                        class="rounded-circle preview-img" alt="preview" />
                      <img v-else :src="defaultAvatar" class="rounded-circle preview-img" alt="default" />
                    </div>

                    <div class="flex-grow-1">
                      <input type="file" class="form-control" accept="image/*" @change="onCreateImageChange" />
                      <small class="text-muted">{{ t('user.imageFormat') }}</small>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.username') }}</label>
                  <input v-model.trim="createForm.username" type="text" class="form-control" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.gender') }}</label>
                  <div class="d-flex gap-3 mt-1">
                    <div class="form-check">
                      <input type="radio" class="form-check-input" value="male" v-model="createForm.gender" required />
                      <label class="form-check-label">{{ t('user.male') }}</label>
                    </div>

                    <div class="form-check">
                      <input type="radio" class="form-check-input" value="female" v-model="createForm.gender" />
                      <label class="form-check-label">{{ t('user.female') }}</label>
                    </div>

                    <div class="form-check">
                      <input type="radio" class="form-check-input" value="other" v-model="createForm.gender" />
                      <label class="form-check-label">{{ t('user.other') }}</label>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.email') }}</label>
                  <input v-model.trim="createForm.email" type="email" class="form-control" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.phone') }}</label>
                  <input v-model.trim="createForm.phone" type="text" class="form-control" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.password') }}</label>
                  <div class="input-group">
                    <input v-model.trim="createForm.password" :type="showPassword ? 'text' : 'password'"
                      class="form-control" required minlength="6" autocomplete="new-password" />
                    <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                      <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                  </div>
                  <small class="text-muted">{{ t('user.minPassword') }}</small>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.confirmPassword') }}</label>
                  <div class="input-group">
                    <input v-model.trim="createForm.confirmPassword" :type="showConfirmPassword ? 'text' : 'password'"
                      class="form-control" required minlength="6" autocomplete="new-password"
                      :class="{ 'is-invalid': passwordMismatch }" />
                    <button class="btn btn-outline-secondary" type="button"
                      @click="showConfirmPassword = !showConfirmPassword">
                      <i class="fa-solid" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                  </div>

                  <div v-if="passwordMismatch" class="invalid-feedback d-block">
                    {{ t('user.passwordMismatch') }}
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.role') }}</label>
                  <select v-model="createForm.role_id" class="form-select" required>
                    <option value="" disabled>{{ t('user.selectRole') }}</option>
                    <option v-for="role in roles" :key="role.id" :value="String(role.id)">
                      {{ role.name }}
                    </option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.status') }}</label>
                  <select v-model="createForm.status" class="form-select" required>
                    <option value="active">{{ t('user.active') }}</option>
                    <option value="inactive">{{ t('user.inactive') }}</option>
                    <option value="suspended">{{ t('user.suspended') }}</option>
                  </select>
                </div>
              </div>

              <div class="modal-footer border-0 px-0 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  {{ t('user.cancel') }}
                </button>

                <button type="submit" class="btn btn-success" :disabled="passwordMismatch">
                  <i class="fa-solid fa-check me-1"></i> {{ t('user.save') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true" ref="alertModalEl">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-body p-4 text-center">
            <div class="alert-icon mb-3" :class="alertState.iconClass">
              <i class="fa-solid" :class="alertState.icon"></i>
            </div>

            <h5 class="mb-2">{{ alertState.title }}</h5>
            <p class="text-muted mb-0">{{ alertState.message }}</p>

            <div class="mt-4">
              <button type="button" class="btn" :class="alertState.btnClass" data-bs-dismiss="modal">
                {{ t('user.ok') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true" ref="editModalEl">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-gradient rounded-4 border-0">
          <div class="modal-header border-0 flex-column text-center">
            <h5 class="modal-title title-border pb-2">{{ t('user.editTitle') }}</h5>
            <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body pt-0">
            <form @submit.prevent="submitUpdateUser" enctype="multipart/form-data">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">{{ t('user.image') }}</label>
                  <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="preview-avatar">
                      <img v-if="editForm.imagePreview" :src="editForm.imagePreview"
                        class="rounded-circle preview-img" />
                      <img v-else :src="defaultAvatar" class="rounded-circle preview-img" />
                    </div>

                    <div class="flex-grow-1">
                      <input type="file" class="form-control" accept="image/*" @change="onEditImageChange" />
                      <small class="text-muted">{{ t('user.imageFormat') }}</small>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.username') }}</label>
                  <input v-model.trim="editForm.username" type="text" class="form-control" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.gender') }}</label>
                  <div class="d-flex gap-3 mt-1">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" value="male" v-model="editForm.gender" />
                      <label class="form-check-label">{{ t('user.male') }}</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" value="female" v-model="editForm.gender" />
                      <label class="form-check-label">{{ t('user.female') }}</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" value="other" v-model="editForm.gender" />
                      <label class="form-check-label">{{ t('user.other') }}</label>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.email') }}</label>
                  <input v-model.trim="editForm.email" type="email" class="form-control" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.phone') }}</label>
                  <input v-model.trim="editForm.phone" type="text" class="form-control" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.role') }}</label>
                  <select v-model="editForm.role_id" class="form-select" required>
                    <option value="" disabled>{{ t('user.selectRole') }}</option>
                    <option v-for="role in roles" :key="role.id" :value="String(role.id)">
                      {{ role.name }}
                    </option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.status') }}</label>
                  <select v-model="editForm.status" class="form-select" required>
                    <option value="active">{{ t('user.active') }}</option>
                    <option value="inactive">{{ t('user.inactive') }}</option>
                    <option value="suspended">{{ t('user.suspended') }}</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.newPassword') }}</label>
                  <input v-model.trim="editForm.password" type="password" class="form-control" minlength="6"
                    :disabled="!editForm.changePassword" :required="editForm.changePassword" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ t('user.confirmPassword') }}</label>
                  <input v-model.trim="editForm.confirmPassword" type="password" class="form-control" minlength="6"
                    :disabled="!editForm.changePassword" :required="editForm.changePassword" />
                </div>

                <div class="col-12">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="changePassword"
                      v-model="editForm.changePassword" />
                    <label class="form-check-label" for="changePassword">
                      {{ t('user.changePassword') }}
                    </label>
                  </div>
                </div>
              </div>

              <div class="modal-footer border-0 px-0 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  {{ t('user.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                  <i class="fa-solid fa-check me-1"></i> {{ t('user.update') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" ref="confirmDeleteModalEl">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-body p-4 text-center">
            <div class="mb-3 text-danger" style="font-size: 40px;">
              <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <h5 class="mb-2">{{ t('user.confirmDeleteTitle') }}</h5>
            <p class="text-muted mb-0">{{ t('user.confirmDeleteMessage') }}</p>

            <div class="d-flex justify-content-center gap-2 mt-4">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" :disabled="deleting">
                {{ t('user.cancel') }}
              </button>

              <button type="button" class="btn btn-danger" @click="confirmDelete" :disabled="deleting">
                <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                {{ t('user.confirmDeleteButton') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { useI18n } from "vue-i18n"
import { Modal } from "bootstrap"
import axios from "axios"
import defaultAvatar from "@/assets/user.png"

export default {
  setup() {
    const { t } = useI18n()
    return { t }
  },

  data() {
    return {
      showPassword: false,
      showConfirmPassword: false,
      deleting: false,
      defaultAvatar,
      search: "",
      users: [],
      roles: [],

      createModal: null,
      editModal: null,
      alertModal: null,
      confirmDeleteModal: null,
      deleteTarget: null,

      alertState: {
        title: "",
        message: "",
        icon: "fa-circle-check",
        iconClass: "success",
        btnClass: "btn-success",
      },

      createForm: {
        username: "",
        gender: "",
        email: "",
        phone: "",
        role_id: "",
        status: "active",
        imageFile: null,
        imagePreview: "",
        password: "",
        confirmPassword: "",
      },

      editForm: {
        id: null,
        username: "",
        gender: "",
        email: "",
        phone: "",
        role_id: "",
        status: "active",
        imageFile: null,
        imagePreview: "",
        changePassword: false,
        password: "",
        confirmPassword: "",
      },
    }
  },

  computed: {
    filteredUsers() {
      const s = this.search.toLowerCase().trim()

      return this.users.filter((u) => {
        const username = (u.username || "").toLowerCase()
        const email = (u.email || "").toLowerCase()
        const roleName = (u.role_name || u.role?.name || "").toLowerCase()

        return username.includes(s) || email.includes(s) || roleName.includes(s)
      })
    },

    passwordMismatch() {
      if (!this.createForm.password || !this.createForm.confirmPassword) return false
      return this.createForm.password !== this.createForm.confirmPassword
    },
  },

  mounted() {
    this.createModal = new Modal(this.$refs.createModalEl, {
      backdrop: "static",
      keyboard: false,
    })

    this.alertModal = new Modal(this.$refs.alertModalEl, {
      backdrop: "static",
      keyboard: true,
    })

    this.editModal = new Modal(this.$refs.editModalEl, {
      backdrop: "static",
      keyboard: false,
    })

    this.confirmDeleteModal = new Modal(this.$refs.confirmDeleteModalEl, {
      backdrop: "static",
      keyboard: false,
    })

    this.fetchUsers()
    this.fetchRoles()
  },

  methods: {
    openCreateModal() {
      this.resetCreateForm()
      this.createModal?.show()
    },

    onCreateImageChange(e) {
      const file = e.target.files?.[0]
      if (!file) return

      this.createForm.imageFile = file

      const reader = new FileReader()
      reader.onload = () => {
        this.createForm.imagePreview = reader.result
      }
      reader.readAsDataURL(file)
    },

    onEditImageChange(e) {
      const file = e.target.files?.[0]
      if (!file) return

      this.editForm.imageFile = file

      const reader = new FileReader()
      reader.onload = () => {
        this.editForm.imagePreview = reader.result
      }
      reader.readAsDataURL(file)
    },

    askDelete(user) {
      this.deleteTarget = user
      this.confirmDeleteModal?.show()
    },

    showAlert(type, title, message) {
      const map = {
        success: {
          icon: "fa-circle-check",
          iconClass: "success",
          btnClass: "btn-success",
        },
        error: {
          icon: "fa-triangle-exclamation",
          iconClass: "error",
          btnClass: "btn-warning",
        },
        fail: {
          icon: "fa-circle-xmark",
          iconClass: "fail",
          btnClass: "btn-danger",
        },
        warning: {
          icon: "fa-triangle-exclamation",
          iconClass: "warning",
          btnClass: "btn-warning",
        },
        info: {
          icon: "fa-circle-info",
          iconClass: "info",
          btnClass: "btn-primary",
        },
      }

      const cfg = map[type] || map.info

      this.alertState.title = title
      this.alertState.message = message
      this.alertState.icon = cfg.icon
      this.alertState.iconClass = cfg.iconClass
      this.alertState.btnClass = cfg.btnClass

      this.alertModal?.show()
    },

    async fetchUsers() {
      try {
        const res = await axios.get("/registers")
        this.users = (res.data.data || []).map((u) => ({
          ...u,
          role_id: String(u.role_id || u.role?.id || ""),
          role_name: u.role_name || u.role?.name || "",
          image: u.profile_image_url || this.defaultAvatar,
        }))
      } catch (err) {
        console.log(err)
        this.showAlert("error", "Load Failed", "Failed to load users")
      }
    },

    async fetchRoles() {
      try {
        const res = await axios.get("/roles")
        this.roles = res.data.data || []
      } catch (err) {
        console.log(err)
        this.showAlert("error", "Load Failed", "Failed to load roles")
      }
    },

    async submitCreateUser() {
      if (this.passwordMismatch) return

      const fd = new FormData()
      fd.append("username", this.createForm.username)
      fd.append("gender", String(this.createForm.gender || ""))
      fd.append("email", this.createForm.email)
      fd.append("phone", this.createForm.phone || "")
      fd.append("role_id", this.createForm.role_id)
      fd.append("status", this.createForm.status)
      fd.append("password", this.createForm.password)
      fd.append("password_confirmation", this.createForm.confirmPassword)

      if (this.createForm.imageFile) {
        fd.append("profile_image", this.createForm.imageFile)
      }

      try {
        const res = await axios.post("/register", fd)
        const created = res.data.data

        this.users.unshift({
          ...created,
          role_id: created.role_id || created.role?.id || this.createForm.role_id,
          role_name:
            created.role_name ||
            created.role?.name ||
            this.roles.find((r) => String(r.id) === String(this.createForm.role_id))?.name ||
            "-",
          image: created.profile_image_url || this.defaultAvatar,
        })

        this.createModal?.hide()
        this.resetCreateForm()
        this.showAlert("success", "Success", "Created successfully!")
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg = Object.values(errors)?.[0]?.[0] || "Validation error"
          this.showAlert("warning", "Validation Error", firstMsg)
          return
        }

        this.showAlert("error", "Create Failed", err.response?.data?.message || err.message)
        console.log(err)
      }
    },

    async submitUpdateUser() {
      if (!this.editForm.id) return

      if (this.editForm.changePassword) {
        if (!this.editForm.password || !this.editForm.confirmPassword) {
          this.showAlert("warning", "Validation Error", "Password and Confirm Password are required")
          return
        }

        if (this.editForm.password !== this.editForm.confirmPassword) {
          this.showAlert("warning", "Validation Error", "Password confirmation does not match")
          return
        }
      }

      const fd = new FormData()
      fd.append("username", this.editForm.username)
      fd.append("email", this.editForm.email)
      fd.append("phone", this.editForm.phone || "")
      fd.append("role_id", this.editForm.role_id)
      fd.append("status", this.editForm.status)

      if (this.editForm.gender) {
        fd.append("gender", this.editForm.gender)
      }

      if (this.editForm.changePassword && this.editForm.password) {
        fd.append("password", this.editForm.password)
        fd.append("password_confirmation", this.editForm.confirmPassword)
      }

      if (this.editForm.imageFile) {
        fd.append("profile_image", this.editForm.imageFile)
      }

      fd.append("_method", "PUT")

      try {
        const res = await axios.post(`/registers/${this.editForm.id}`, fd)
        const updated = res.data.data

        const idx = this.users.findIndex((u) => u.id === updated.id)
        if (idx !== -1) {
          this.users[idx] = {
            ...this.users[idx],
            ...updated,
            role_id: updated.role_id || updated.role?.id || this.editForm.role_id,
            role_name:
              updated.role_name ||
              updated.role?.name ||
              this.roles.find((r) => String(r.id) === String(this.editForm.role_id))?.name ||
              this.users[idx].role_name,
            image: updated.profile_image_url || this.defaultAvatar,
          }
        }

        this.editModal?.hide()
        this.showAlert("success", "Success", "Updated successfully!")
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg = Object.values(errors)?.[0]?.[0] || "Validation error"
          this.showAlert("warning", "Validation Error", firstMsg)
          return
        }

        this.showAlert("error", "Update Failed", err.response?.data?.message || err.message)
      }
    },

    async confirmDelete() {
      if (!this.deleteTarget?.id) return

      this.deleting = true
      try {
        await axios.delete(`/registers/${this.deleteTarget.id}`)
        this.users = this.users.filter((u) => u.id !== this.deleteTarget.id)

        this.confirmDeleteModal?.hide()
        this.showAlert("success", "Deleted", "Deleted successfully!")
      } catch (err) {
        this.showAlert("error", "Delete Failed", err.response?.data?.message || err.message)
      } finally {
        this.deleting = false
        this.deleteTarget = null
      }
    },

    resetCreateForm() {
      this.showPassword = false
      this.showConfirmPassword = false

      this.createForm = {
        username: "",
        gender: "",
        email: "",
        phone: "",
        role_id: "",
        status: "active",
        imageFile: null,
        imagePreview: "",
        password: "",
        confirmPassword: "",
      }
    },

    editUser(user) {
      this.editForm = {
        id: user.id,
        username: user.username || "",
        gender: user.gender || "",
        email: user.email || "",
        phone: user.phone || "",
        role_id: String(user.role_id || user.role?.id || ""),
        status: user.status || "active",
        imageFile: null,
        imagePreview: user.image || this.defaultAvatar,
        changePassword: false,
        password: "",
        confirmPassword: "",
      }

      this.editModal?.show()
    },

    statusClass(status) {
      if (status === "active") return "bg-success"
      if (status === "inactive") return "bg-secondary"
      if (status === "suspended") return "bg-danger"
      return "bg-secondary"
    },
  },
}
</script>


<style scoped>
.table-bg th {
  background: linear-gradient(to bottom, #f7f1b5, #fffef7);
}

.container-fluid {
  font-family: "Khmer OS Battambang", sans-serif;
}

.img-in-table {
  width: 72px;
  height: 72px;
  object-fit: cover;
}

/* modal background */
.modal-gradient {
  background: linear-gradient(to bottom, #fbf9df, #fffef7);
}

/* Alert Icon Size */
.alert-icon i {
  font-size: 60px;
  /* make icon large */
}

/* Alert Message Icon Colors */
.alert-icon.success i {
  color: #198754;
  /* green */
}

.alert-icon.error i {
  color: #f2b604;
  /* orange */
}

.alert-icon.fail i {
  color: #dc3545;
  /* red */
}

.alert-icon.warning i {
  color: #ffc107;
  /* yellow */
}

.alert-icon.info i {
  color: #0d6efd;
  /* blue */
}

/* image preview */
.preview-avatar .placeholder {
  width: 72px;
  height: 72px;
  border: 1px dashed rgba(0, 0, 0, .2);
  background: rgba(255, 255, 255, .6);
  color: rgba(0, 0, 0, .5);
}

.preview-img {
  width: 72px;
  height: 72px;
  object-fit: cover;
}

/* Logo */
.logo {
  width: 80px;
  margin-bottom: 15px;
}

.gender-buttons {
  display: flex;
  gap: 10px;
  margin-top: 5px;
}

.gender-btn {
  padding: 6px 16px;
  border: 1px solid #ced4da;
  border-radius: 8px;
  cursor: pointer;
  user-select: none;
  background: white;
  transition: 0.2s;
}

.gender-btn input {
  display: none;
}

.gender-btn:hover {
  background: #f8f9fa;
}

.gender-btn.active {
  background: #198754;
  color: white;
  border-color: #198754;
}

.badge {
  font-size: 15px;
}
</style>