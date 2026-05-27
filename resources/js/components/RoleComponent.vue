<template>
  <div class="container mt-4">
    <div class="card shadow-sm rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <h4 class="mb-0">{{ t('role.title') }}</h4>

          <div class="d-flex gap-2">
            <!-- Language Switch -->
            <button class="btn btn-success" @click="openCreateModal">
              <i class="fa-solid fa-circle-plus"></i> {{ t('role.createButton') }}
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-bg">
              <tr>
                <th width="80">{{ t('role.tableId') }}</th>
                <th>{{ t('role.tableRole') }}</th>
                <th>{{ t('role.tableDescription') }}</th>
                <th width="180" class="text-end">{{ t('role.tableAction') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(r, index) in roles" :key="r.id || index">
                <td>{{ index + 1 }}</td>
                <td>{{ r.name }}</td>
                <td>{{ r.description || '-' }}</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-primary me-2" @click="openEditModal(r)">
                    <i class="fa-solid fa-pen-to-square"></i> {{ t('user.edit') }}
                  </button>
                  <button class="btn btn-sm btn-danger" @click="askDelete(r)">
                    <i class="fa-solid fa-trash"></i> {{ t('user.delete') }}
                  </button>
                </td>
              </tr>

              <tr v-if="roles.length === 0">
                <td colspan="4" class="text-center text-muted py-4">
                  {{ t('role.noData') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create / Edit Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true" ref="roleModalEl">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow modal-gradient">
          <div class="modal-header border-0 flex-column text-center position-relative">
            <button
              type="button"
              class="btn-close position-absolute end-0 top-0 m-3"
              data-bs-dismiss="modal"
            ></button>

            <div v-if="!editMode" class="w-100">
              <img src="@/assets/logo_moh.png" alt="Logo MOH" class="logo mt-3" />
              <h5 class="modal-title title-border pb-2">{{ t('role.createTitle') }}</h5>
            </div>

            <div v-else class="w-100">
              <h5 class="modal-title title-border pb-2 mt-3">{{ t('role.editTitle') }}</h5>
            </div>
          </div>

          <div class="modal-body pt-0">
            <form @submit.prevent="editMode ? updateRole() : createRole()">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">{{ t('role.roleLabel') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model.trim="form.role"
                    required
                  />
                </div>

                <div class="col-12">
                  <label class="form-label">{{ t('role.descriptionLabel') }}</label>
                  <textarea
                    class="form-control"
                    rows="3"
                    v-model.trim="form.description"
                  ></textarea>
                </div>
              </div>

              <div class="modal-footer border-0 px-0 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  {{ t('role.cancel') }}
                </button>

                <button v-if="!editMode" type="submit" class="btn btn-success">
                  <i class="fa-solid fa-check me-1"></i> {{ t('role.save') }}
                </button>

                <button v-else type="submit" class="btn btn-primary">
                  <i class="fa-solid fa-check me-1"></i> {{ t('role.update') }}
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
              <button
                type="button"
                class="btn"
                :class="alertState.btnClass"
                data-bs-dismiss="modal"
              >
                OK
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div
      class="modal fade"
      id="confirmDeleteModal"
      tabindex="-1"
      aria-hidden="true"
      ref="confirmDeleteModalEl"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-body p-4 text-center">
            <div class="mb-3 text-danger" style="font-size: 40px">
              <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <h5 class="mb-2">{{ t('role.confirmDeleteTitle') }}</h5>
            <p class="text-muted mb-0">{{ t('role.confirmDeleteMessage') }}</p>

            <div class="d-flex justify-content-center gap-2 mt-4">
              <button
                type="button"
                class="btn btn-outline-secondary"
                data-bs-dismiss="modal"
                :disabled="deleting"
              >
                {{ t('role.cancel') }}
              </button>

              <button
                type="button"
                class="btn btn-danger"
                @click="confirmDelete"
                :disabled="deleting"
              >
                <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                {{ t('role.confirmDeleteButton') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { Modal } from 'bootstrap'
import { useI18n } from 'vue-i18n'

export default {
  setup() {
    const { t, locale } = useI18n()
    return { t, locale }
  },

  data() {
    return {
      roles: [],
      editMode: false,
      editId: null,
      deleting: false,
      deleteTarget: null,
      roleModal: null,
      alertModal: null,
      confirmDeleteModal: null,
      currentLocale: localStorage.getItem('app_locale') || 'en',

      form: {
        role: '',
        description: '',
      },

      alertState: {
        title: '',
        message: '',
        icon: 'fa-circle-check',
        iconClass: 'success',
        btnClass: 'btn-success',
      },
    }
  },

  async mounted() {
    this.locale = this.currentLocale

    this.roleModal = new Modal(this.$refs.roleModalEl, {
      backdrop: 'static',
      keyboard: false,
    })

    this.alertModal = new Modal(this.$refs.alertModalEl, {
      backdrop: 'static',
      keyboard: true,
    })

    this.confirmDeleteModal = new Modal(this.$refs.confirmDeleteModalEl, {
      backdrop: 'static',
      keyboard: false,
    })

    await this.loadRoles()
  },

  methods: {
    changeLanguage() {
      this.locale = this.currentLocale
      localStorage.setItem('app_locale', this.currentLocale)
    },

    showAlert(type, title, message) {
      const map = {
        success: {
          icon: 'fa-circle-check',
          iconClass: 'success',
          btnClass: 'btn-success',
        },
        error: {
          icon: 'fa-triangle-exclamation',
          iconClass: 'error',
          btnClass: 'btn-warning',
        },
        fail: {
          icon: 'fa-circle-xmark',
          iconClass: 'fail',
          btnClass: 'btn-danger',
        },
        warning: {
          icon: 'fa-triangle-exclamation',
          iconClass: 'warning',
          btnClass: 'btn-warning',
        },
        info: {
          icon: 'fa-circle-info',
          iconClass: 'info',
          btnClass: 'btn-primary',
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

    resetForm() {
      this.form = {
        role: '',
        description: '',
      }
    },

    async loadRoles() {
      try {
        const res = await axios.get('/roles')
        this.roles = res.data.data || []
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert(
          'error',
          this.t('role.loadFailed'),
          this.t('role.loadFailedMessage')
        )
      }
    },

    openCreateModal() {
      this.editMode = false
      this.editId = null
      this.resetForm()
      this.roleModal?.show()
    },

    openEditModal(role) {
      this.editMode = true
      this.editId = role.id
      this.form.role = role.name || ''
      this.form.description = role.description || ''
      this.roleModal?.show()
    },

    async createRole() {
      if (!this.form.role) {
        this.showAlert(
          'warning',
          this.t('role.validationError'),
          this.t('role.roleRequired')
        )
        return
      }

      try {
        await axios.post('/roles', {
          name: this.form.role,
          description: this.form.description,
        })

        await this.loadRoles()
        this.roleModal?.hide()
        this.resetForm()

        this.showAlert(
          'success',
          this.t('role.success'),
          this.t('role.createdSuccess')
        )
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg =
            Object.values(errors)?.[0]?.[0] || this.t('role.validationMessage')

          this.showAlert('warning', this.t('role.validationError'), firstMsg)
          return
        }

        console.log(err.response?.data || err.message)
        this.showAlert(
          'error',
          this.t('role.createFailed'),
          err.response?.data?.message || err.message || this.t('role.createFailedMessage')
        )
      }
    },

    async updateRole() {
      if (!this.editId) return

      if (!this.form.role) {
        this.showAlert(
          'warning',
          this.t('role.validationError'),
          this.t('role.roleRequired')
        )
        return
      }

      try {
        await axios.put(`/roles/${this.editId}`, {
          name: this.form.role,
          description: this.form.description,
        })

        await this.loadRoles()
        this.roleModal?.hide()
        this.resetForm()

        this.showAlert(
          'success',
          this.t('role.success'),
          this.t('role.updatedSuccess')
        )
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg =
            Object.values(errors)?.[0]?.[0] || this.t('role.validationMessage')

          this.showAlert('warning', this.t('role.validationError'), firstMsg)
          return
        }

        console.log(err.response?.data || err.message)
        this.showAlert(
          'error',
          this.t('role.updateFailed'),
          err.response?.data?.message || err.message || this.t('role.updateFailedMessage')
        )
      }
    },

    askDelete(role) {
      this.deleteTarget = role
      this.confirmDeleteModal?.show()
    },

    async confirmDelete() {
      if (!this.deleteTarget?.id) return

      this.deleting = true
      try {
        await axios.delete(`/roles/${this.deleteTarget.id}`)
        this.roles = this.roles.filter((r) => r.id !== this.deleteTarget.id)

        this.confirmDeleteModal?.hide()
        this.showAlert(
          'success',
          this.t('role.deletedTitle'),
          this.t('role.deletedSuccess')
        )
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert(
          'error',
          this.t('role.deleteFailed'),
          err.response?.data?.message || err.message || this.t('role.deleteFailedMessage')
        )
      } finally {
        this.deleting = false
        this.deleteTarget = null
      }
    },
  },
}
</script>

<style scoped>

.table-bg th {
  background: linear-gradient(to bottom, #f7f1b5, #fffef7);
}

.modal-gradient {
  background: linear-gradient(to bottom, #fbf9df, #fffef7);
}

.container {
  font-family: "Khmer OS Battambang", sans-serif;
}

.logo {
  width: 60px;
  margin: 0 auto 15px auto;
  display: block;
}

.title-border {
  display: inline-block;
  border-bottom: 2px solid #d6c76d;
}

.alert-icon i {
  font-size: 60px;
}

.alert-icon.success i {
  color: #198754;
}

.alert-icon.error i {
  color: #f2b604;
}

.alert-icon.fail i {
  color: #dc3545;
}

.alert-icon.warning i {
  color: #ffc107;
}

.alert-icon.info i {
  color: #0d6efd;
}

.lang-select {
  width: 130px;
}
</style>