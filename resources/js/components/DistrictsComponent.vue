<template>
  <div class="container mt-4">
    <div class="card shadow-sm rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
          <h4 class="mb-0">{{ t("distric.title") }}</h4>

          <div class="d-flex align-items-center gap-2 flex-wrap">
            <select
              class="form-select form-select-sm per-page-select"
              v-model.number="pagination.per_page"
              @change="changePerPage"
              :disabled="pageLoading || modalLoading || submitLoading"
            >
              <option :value="5">5</option>
              <option :value="10">10</option>
              <option :value="15">15</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
            </select>

            <button class="btn btn-success" @click="openCreateModal" :disabled="pageLoading || modalLoading || submitLoading">
              <i class="fa-solid fa-circle-plus"></i>
              {{ t("distric.createButton") }}
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-bg">
              <tr>
                <th width="80">{{ t("distric.tableId") }}</th>
                <th>{{ t("distric.province") }}</th>
                <th>{{ t("distric.districtNameEn") }}</th>
                <th>{{ t("distric.districtNameKh") }}</th>
                <th>{{ t("distric.districtCode") }}</th>
                <th>{{ t("distric.latitude") }}</th>
                <th>{{ t("distric.longitude") }}</th>
                <th width="180" class="text-end">{{ t("distric.action") }}</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(d, index) in districts" :key="d.id || index">
                <td>{{ rowNumber(index) }}</td>
                <td>{{ d.province?.province_name_en || "-" }}</td>
                <td>{{ d.district_name_en }}</td>
                <td>{{ d.district_name_kh }}</td>
                <td>{{ d.district_code }}</td>
                <td>{{ d.latitude || "-" }}</td>
                <td>{{ d.longitude || "-" }}</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-primary me-2" @click="openEditModal(d)">
                    <i class="fa-solid fa-pen-to-square"></i>
                    {{ t("distric.edit") }}
                  </button>

                  <button class="btn btn-sm btn-danger" @click="askDelete(d)">
                    <i class="fa-solid fa-trash"></i>
                    {{ t("distric.delete") }}
                  </button>
                </td>
              </tr>

              <tr v-if="!pageLoading && districts.length === 0">
                <td colspan="8" class="text-center text-muted py-4">
                  {{ t("distric.noData") }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div
          v-if="pagination.total > 0"
          class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3"
        >
          <div class="text-muted small">
            Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }}
            of {{ pagination.total || 0 }} entries
          </div>

          <div class="d-flex align-items-center flex-wrap gap-2">
            <button
              class="btn btn-sm btn-outline-secondary"
              @click="goToFirstPage"
              :disabled="pageLoading || pagination.current_page <= 1"
            >
              First
            </button>

            <button
              class="btn btn-sm btn-outline-secondary"
              @click="goToPrevPage"
              :disabled="pageLoading || pagination.current_page <= 1"
            >
              Prev
            </button>

            <button
              v-for="page in visiblePages"
              :key="page"
              class="btn btn-sm"
              :class="page === pagination.current_page ? 'btn-primary' : 'btn-outline-primary'"
              @click="goToPage(page)"
              :disabled="pageLoading"
            >
              {{ page }}
            </button>

            <button
              class="btn btn-sm btn-outline-secondary"
              @click="goToNextPage"
              :disabled="pageLoading || pagination.current_page >= pagination.last_page"
            >
              Next
            </button>

            <button
              class="btn btn-sm btn-outline-secondary"
              @click="goToLastPage"
              :disabled="pageLoading || pagination.current_page >= pagination.last_page"
            >
              Last
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create / Edit Modal -->
    <div class="modal fade" id="districtModal" tabindex="-1" aria-hidden="true" ref="districtModalEl">
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0 shadow modal-gradient">
          <div class="modal-header border-0 flex-column text-center position-relative modal-top">
            <button
              type="button"
              class="btn-close position-absolute end-0 top-0 m-3"
              data-bs-dismiss="modal"
            ></button>

            <div v-if="!editMode" class="w-100">
              <img src="@/assets/logo_moh.png" alt="Logo MOH" class="logo mt-3" />
              <h5 class="modal-title title-border pb-2 mb-4">
                {{ t("distric.createTitle") }}
              </h5>
            </div>

            <div v-else class="w-100">
              <h5 class="modal-title title-border pb-2 mt-3">
                {{ t("distric.editTitle") }}
              </h5>
            </div>
          </div>

          <div class="modal-body pt-0 px-4 px-lg-5 pb-4">
            <form @submit.prevent="editMode ? updateDistrict() : createDistrict()">
              <div class="row g-4">
                <!-- Left Form -->
                <div class="col-lg-6">
                  <div class="form-panel p-4 rounded-4 shadow-sm">
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label class="form-label custom-label">
                          {{ t("distric.selectProvince") }}
                        </label>
                        <select
                          class="form-select custom-input"
                          v-model="form.province_code"
                          @change="generateDistrictCode"
                          required
                        >
                          <option value="">{{ t("distric.selectProvinceOption") }}</option>
                          <option
                            v-for="p in provinces"
                            :key="p.province_code"
                            :value="p.province_code"
                          >
                            {{ p.province_name_en }} - {{ p.province_name_kh }}
                          </option>
                        </select>
                      </div>

                      <div class="col-md-12">
                        <label class="form-label custom-label">
                          {{ t("distric.districtNameEnLabel") }}
                        </label>
                        <input
                          type="text"
                          class="form-control custom-input"
                          v-model.trim="form.district_name_en"
                          :placeholder="t('distric.districtNameEnPlaceholder')"
                          required
                        />
                      </div>

                      <div class="col-md-12">
                        <label class="form-label custom-label">
                          {{ t("distric.districtNameKhLabel") }}
                        </label>
                        <input
                          type="text"
                          class="form-control custom-input"
                          v-model.trim="form.district_name_kh"
                          :placeholder="t('distric.districtNameKhPlaceholder')"
                          required
                        />
                      </div>

                      <div class="col-md-12">
                        <label class="form-label custom-label">
                          {{ t("distric.districtCodeLabel") }}
                        </label>
                        <input
                          type="text"
                          class="form-control custom-input bg-secondary-subtle"
                          v-model.trim="form.district_code"
                          :placeholder="t('distric.districtCodePlaceholder')"
                          readonly
                        />
                      </div>

                      <div class="col-md-6">
                        <label class="form-label custom-label">
                          {{ t("distric.latitudeLabel") }}
                        </label>
                        <input
                          type="number"
                          step="any"
                          class="form-control custom-input"
                          v-model="form.latitude"
                          :placeholder="t('distric.latitudePlaceholder')"
                          @input="updateMarkerFromInputs"
                        />
                      </div>

                      <div class="col-md-6">
                        <label class="form-label custom-label">
                          {{ t("distric.longitudeLabel") }}
                        </label>
                        <input
                          type="number"
                          step="any"
                          class="form-control custom-input"
                          v-model="form.longitude"
                          :placeholder="t('distric.longitudePlaceholder')"
                          @input="updateMarkerFromInputs"
                        />
                      </div>

                      <div class="col-md-12">
                        <button
                          type="button"
                          class="btn btn-outline-secondary rounded-4"
                          @click="getCurrentLocation"
                        >
                          <i class="fa-solid fa-location-crosshairs me-1"></i>
                          {{ t("distric.getCurrentLocation") }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Right Map -->
                <div class="col-lg-6">
                  <div class="map-panel p-4 rounded-4 shadow-sm">
                    <div class="section-title">
                      <i class="fa-solid fa-location-dot me-2"></i>
                      <label class="form-label custom-label mb-1">
                        {{ t("distric.mapTitle") }}
                      </label>
                    </div>
                    <div id="district-map" class="map-box rounded-4"></div>
                    <small class="text-muted d-block mt-3 map-note">
                      {{ t("distric.mapNote") }}
                    </small>
                  </div>
                </div>
              </div>

              <div class="modal-footer border-0 px-0 mt-4 justify-content-end gap-2">
                <button type="button" class="btn btn-light btn-action" data-bs-dismiss="modal">
                  {{ t("distric.cancel") }}
                </button>

                <button
                  v-if="!editMode"
                  type="submit"
                  class="btn btn-success btn-action px-4"
                  :disabled="submitLoading"
                >
                  <i class="fa-solid fa-check me-1"></i>
                  {{ submitLoading ? t("distric.saving") : t("distric.save") }}
                </button>

                <button
                  v-else
                  type="submit"
                  class="btn btn-primary btn-action px-4"
                  :disabled="submitLoading"
                >
                  <i class="fa-solid fa-check me-1"></i>
                  {{ submitLoading ? t("distric.updating") : t("distric.update") }}
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
                {{ t("distric.ok") }}
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

            <h5 class="mb-2">{{ t("distric.confirmDeleteTitle") }}</h5>
            <p class="text-muted mb-0">{{ t("distric.confirmDeleteMessage") }}</p>

            <div class="d-flex justify-content-center gap-2 mt-4">
              <button
                type="button"
                class="btn btn-outline-secondary"
                data-bs-dismiss="modal"
                :disabled="deleting"
              >
                {{ t("distric.cancel") }}
              </button>

              <button type="button" class="btn btn-danger" @click="confirmDelete" :disabled="deleting">
                <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                {{ t("distric.confirmDeleteButton") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="pageLoading || modalLoading || submitLoading" class="loading-overlay" role="dialog" aria-modal="true">
      <div class="loading-box">
        <div class="spinner"></div>
        <div class="loading-text">{{ t("distric.loading") }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios"
import { Modal } from "bootstrap"
import { useI18n } from "vue-i18n"

export default {
  setup() {
    const { t } = useI18n()
    return { t }
  },

  data() {
    return {
      districts: [],
      provinces: [],
      editMode: false,
      editId: null,
      deleting: false,
      deleteTarget: null,
      districtModal: null,
      alertModal: null,
      confirmDeleteModal: null,

      map: null,
      marker: null,
      defaultCenter: { lat: 11.5564, lng: 104.9282 },

      pageLoading: false,
      modalLoading: false,
      submitLoading: false,

      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
        from: 0,
        to: 0,
      },

      form: {
        province_code: "",
        district_name_en: "",
        district_name_kh: "",
        district_code: "",
        latitude: "",
        longitude: "",
      },

      alertState: {
        title: "",
        message: "",
        icon: "fa-circle-check",
        iconClass: "success",
        btnClass: "btn-success",
      },
    }
  },

  computed: {
    visiblePages() {
      const pages = []
      const current = this.pagination.current_page || 1
      const last = this.pagination.last_page || 1

      let start = Math.max(1, current - 2)
      let end = Math.min(last, current + 2)

      if (current <= 3) {
        end = Math.min(last, 5)
      }

      if (current >= last - 2) {
        start = Math.max(1, last - 4)
      }

      for (let i = start; i <= end; i++) {
        pages.push(i)
      }

      return pages
    },
  },

  async mounted() {
    this.pageLoading = true

    this.districtModal = new Modal(this.$refs.districtModalEl, {
      backdrop: "static",
      keyboard: false,
    })

    this.alertModal = new Modal(this.$refs.alertModalEl, {
      backdrop: "static",
      keyboard: true,
    })

    this.confirmDeleteModal = new Modal(this.$refs.confirmDeleteModalEl, {
      backdrop: "static",
      keyboard: false,
    })

    await this.loadProvinces()
    await this.loadDistricts(1)

    try {
      await this.loadGoogleMapsScript()
    } catch (err) {
      this.showAlert(
        "error",
        this.t("distric.mapError"),
        this.t("distric.mapErrorMessage")
      )
    } finally {
      this.pageLoading = false
    }
  },

  watch: {
    pageLoading: "toggleBodyScroll",
    modalLoading: "toggleBodyScroll",
    submitLoading: "toggleBodyScroll",
  },

  methods: {
    rowNumber(index) {
      return ((this.pagination.current_page - 1) * this.pagination.per_page) + index + 1
    },

    async loadDistricts(page = this.pagination.current_page) {
      this.pageLoading = true

      try {
        const res = await axios.get("/districts", {
          params: {
            page,
            per_page: this.pagination.per_page,
          },
        })

        this.districts = res.data.data || []

        const pg = res.data.pagination || {}
        this.pagination.current_page = Number(pg.current_page || 1)
        this.pagination.last_page = Number(pg.last_page || 1)
        this.pagination.per_page = Number(pg.per_page || this.pagination.per_page)
        this.pagination.total = Number(pg.total || 0)
        this.pagination.from = Number(pg.from || 0)
        this.pagination.to = Number(pg.to || 0)
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.districts = []
        this.showAlert(
          "error",
          this.t("distric.loadFailed"),
          this.t("distric.loadDistrictFailedMessage")
        )
      } finally {
        this.pageLoading = false
      }
    },

    async loadProvinces() {
      try {
        const res = await axios.get("/provinces", {
          params: {
            per_page: 9999,
          },
        })

        this.provinces = res.data.data || []
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("distric.loadFailed"),
          this.t("distric.loadProvinceFailedMessage")
        )
      }
    },

    goToPage(page) {
      if (
        page < 1 ||
        page > this.pagination.last_page ||
        page === this.pagination.current_page
      ) {
        return
      }

      this.loadDistricts(page)
    },

    goToFirstPage() {
      if (this.pagination.current_page > 1) {
        this.loadDistricts(1)
      }
    },

    goToPrevPage() {
      if (this.pagination.current_page > 1) {
        this.loadDistricts(this.pagination.current_page - 1)
      }
    },

    goToNextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.loadDistricts(this.pagination.current_page + 1)
      }
    },

    goToLastPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.loadDistricts(this.pagination.last_page)
      }
    },

    async changePerPage() {
      this.pagination.current_page = 1
      await this.loadDistricts(1)
    },

    getCurrentLocation() {
      if (!navigator.geolocation) {
        this.showAlert(
          "error",
          this.t("distric.geolocationError"),
          this.t("distric.geolocationNotSupported")
        )
        return
      }

      this.modalLoading = true

      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude
          const lng = position.coords.longitude

          this.form.latitude = lat.toFixed(6)
          this.form.longitude = lng.toFixed(6)

          if (this.map && this.marker) {
            this.setMarkerPosition(lat, lng)
            this.map.setZoom(15)
          } else {
            this.initMap(lat, lng)
          }

          this.modalLoading = false
        },
        (error) => {
          this.modalLoading = false

          let message = this.t("distric.unableToGetLocation")
          if (error.code === 1) message = this.t("distric.permissionDenied")
          if (error.code === 2) message = this.t("distric.locationUnavailable")
          if (error.code === 3) message = this.t("distric.locationTimeout")

          this.showAlert("error", this.t("distric.locationError"), message)
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0,
        }
      )
    },

    toggleBodyScroll() {
      const hasLoading = this.pageLoading || this.modalLoading || this.submitLoading
      document.body.style.overflow = hasLoading ? "hidden" : ""
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

    resetForm() {
      this.form = {
        province_code: "",
        district_name_en: "",
        district_name_kh: "",
        district_code: "",
        latitude: "",
        longitude: "",
      }
    },

    async generateDistrictCode() {
      if (!this.form.province_code || this.editMode) return

      try {
        const res = await axios.get(`/districts/next-code/${this.form.province_code}`)
        this.form.district_code = res.data.district_code || ""
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("distric.codeError"),
          this.t("distric.codeErrorMessage")
        )
      }
    },

    loadGoogleMapsScript() {
      return new Promise((resolve, reject) => {
        if (window.google && window.google.maps) {
          resolve()
          return
        }

        const existingScript = document.getElementById("googleMapsScript")
        if (existingScript) {
          existingScript.addEventListener("load", () => resolve())
          existingScript.addEventListener("error", () =>
            reject(new Error("Google Maps failed to load"))
          )
          return
        }

        const script = document.createElement("script")
        script.id = "googleMapsScript"
        script.src =
          "https://maps.googleapis.com/maps/api/js?key=AIzaSyCs8T5do-m_YasDGHU0bcti_e15di4G8Hk"
        script.async = true
        script.defer = true
        script.onload = () => resolve()
        script.onerror = () => reject(new Error("Google Maps script load error"))
        document.head.appendChild(script)
      })
    },

    initMap(lat = null, lng = null) {
      const mapEl = document.getElementById("district-map")
      if (!mapEl || !(window.google && window.google.maps)) return

      const hasLat = lat !== null && lat !== "" && !isNaN(Number(lat))
      const hasLng = lng !== null && lng !== "" && !isNaN(Number(lng))

      const center = {
        lat: hasLat ? Number(lat) : this.defaultCenter.lat,
        lng: hasLng ? Number(lng) : this.defaultCenter.lng,
      }

      this.map = new window.google.maps.Map(mapEl, {
        center,
        zoom: hasLat && hasLng ? 10 : 7,
      })

      this.marker = new window.google.maps.Marker({
        position: center,
        map: this.map,
        draggable: true,
      })

      if (!hasLat || !hasLng) {
        this.form.latitude = center.lat.toFixed(6)
        this.form.longitude = center.lng.toFixed(6)
      }

      this.map.addListener("click", (event) => {
        const clickedLat = event.latLng.lat()
        const clickedLng = event.latLng.lng()
        this.setMarkerPosition(clickedLat, clickedLng)
      })

      this.marker.addListener("dragend", (event) => {
        const draggedLat = event.latLng.lat()
        const draggedLng = event.latLng.lng()
        this.setMarkerPosition(draggedLat, draggedLng)
      })

      setTimeout(() => {
        window.google.maps.event.trigger(this.map, "resize")
        this.map.setCenter(center)
      }, 300)
    },

    setMarkerPosition(lat, lng) {
      const position = {
        lat: Number(lat),
        lng: Number(lng),
      }

      this.form.latitude = position.lat.toFixed(6)
      this.form.longitude = position.lng.toFixed(6)

      if (this.marker) {
        this.marker.setPosition(position)
      }

      if (this.map) {
        this.map.panTo(position)
      }
    },

    updateMarkerFromInputs() {
      const lat = parseFloat(this.form.latitude)
      const lng = parseFloat(this.form.longitude)

      if (isNaN(lat) || isNaN(lng) || !this.map || !this.marker) return

      const position = { lat, lng }
      this.marker.setPosition(position)
      this.map.panTo(position)
    },

    openCreateModal() {
      this.modalLoading = true
      this.editMode = false
      this.editId = null
      this.resetForm()
      this.districtModal.show()

      setTimeout(() => {
        this.initMap()
        this.modalLoading = false
      }, 500)
    },

    openEditModal(district) {
      this.modalLoading = true
      this.editMode = true
      this.editId = district.id
      this.form.province_code = district.province_code || ""
      this.form.district_name_en = district.district_name_en || ""
      this.form.district_name_kh = district.district_name_kh || ""
      this.form.district_code = district.district_code || ""
      this.form.latitude = district.latitude || ""
      this.form.longitude = district.longitude || ""
      this.districtModal.show()

      setTimeout(() => {
        this.initMap(this.form.latitude, this.form.longitude)
        this.modalLoading = false
      }, 500)
    },

    async createDistrict() {
      if (
        !this.form.province_code ||
        !this.form.district_name_en ||
        !this.form.district_name_kh ||
        !this.form.district_code
      ) {
        this.showAlert(
          "warning",
          this.t("distric.validationError"),
          this.t("distric.fillRequired")
        )
        return
      }

      this.submitLoading = true
      try {
        await axios.post("/districts", {
          province_code: this.form.province_code,
          district_name_en: this.form.district_name_en,
          district_name_kh: this.form.district_name_kh,
          district_code: this.form.district_code,
          latitude: this.form.latitude || null,
          longitude: this.form.longitude || null,
        })

        await this.loadDistricts(1)
        this.districtModal.hide()
        this.resetForm()

        this.showAlert(
          "success",
          this.t("distric.success"),
          this.t("distric.createdSuccess")
        )
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg =
            Object.values(errors || {})?.[0]?.[0] ||
            err.response?.data?.message ||
            this.t("distric.validationMessage")

          this.showAlert("warning", this.t("distric.validationError"), firstMsg)
          return
        }

        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("distric.createFailed"),
          err.response?.data?.message || err.message || this.t("distric.createFailedMessage")
        )
      } finally {
        this.submitLoading = false
      }
    },

    async updateDistrict() {
      if (!this.editId) return

      if (
        !this.form.province_code ||
        !this.form.district_name_en ||
        !this.form.district_name_kh ||
        !this.form.district_code
      ) {
        this.showAlert(
          "warning",
          this.t("distric.validationError"),
          this.t("distric.fillRequired")
        )
        return
      }

      this.submitLoading = true
      try {
        await axios.put(`/districts/${this.editId}`, {
          province_code: this.form.province_code,
          district_name_en: this.form.district_name_en,
          district_name_kh: this.form.district_name_kh,
          district_code: this.form.district_code,
          latitude: this.form.latitude || null,
          longitude: this.form.longitude || null,
        })

        await this.loadDistricts(this.pagination.current_page)
        this.districtModal.hide()
        this.resetForm()

        this.showAlert(
          "success",
          this.t("distric.success"),
          this.t("distric.updatedSuccess")
        )
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg =
            Object.values(errors || {})?.[0]?.[0] ||
            err.response?.data?.message ||
            this.t("distric.validationMessage")

          this.showAlert("warning", this.t("distric.validationError"), firstMsg)
          return
        }

        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("distric.updateFailed"),
          err.response?.data?.message || err.message || this.t("distric.updateFailedMessage")
        )
      } finally {
        this.submitLoading = false
      }
    },

    askDelete(district) {
      this.deleteTarget = district
      this.confirmDeleteModal?.show()
    },

    async confirmDelete() {
      if (!this.deleteTarget?.id) return

      this.deleting = true
      try {
        await axios.delete(`/districts/${this.deleteTarget.id}`)

        this.confirmDeleteModal?.hide()

        let targetPage = this.pagination.current_page
        const isLastItemOnPage = this.districts.length === 1 && targetPage > 1

        if (isLastItemOnPage) {
          targetPage -= 1
        }

        await this.loadDistricts(targetPage)

        this.showAlert(
          "success",
          this.t("distric.deletedTitle"),
          this.t("distric.deletedSuccess")
        )
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("distric.deleteFailed"),
          err.response?.data?.message || err.message || this.t("distric.deleteFailedMessage")
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
  border: none;
  font-weight: 600;
  color: #5f5a2f;
}

.container {
  font-family: "Khmer OS Battambang", sans-serif;
}

.per-page-select {
  width: 90px;
}

.modal-gradient {
  background: linear-gradient(180deg, #fffdf2 0%, #fffefb 100%);
}

.modal-top {
  background: linear-gradient(180deg, #fff9d9 0%, transparent 100%);
  border-top-left-radius: 1rem;
  border-top-right-radius: 1rem;
}

.logo {
  width: 62px;
  margin: 0 auto 14px auto;
  display: block;
}

.title-border {
  display: inline-block;
  border-bottom: 3px solid #d8c96d;
  padding-bottom: 6px;
  color: #5c5528;
  font-weight: 700;
  letter-spacing: 0.3px;
}

.form-panel,
.map-panel {
  background: #ffffff;
  border: 1px solid #f0ead2;
}

.custom-label {
  font-size: 14px;
  font-weight: 600;
  color: #5f5a2f;
  margin-bottom: 8px;
}

.section-title {
  font-size: 15px;
  font-weight: 700;
  color: #6c5d13;
  margin-bottom: 16px;
  padding-bottom: 8px;
  border-bottom: 1px dashed #d8cb84;
}

.custom-input {
  min-height: 48px;
  border-radius: 14px;
  border: 1px solid #ddd8b8;
  background-color: #fffef9;
  padding: 10px 14px;
  font-size: 14px;
  transition: all 0.2s ease;
  box-shadow: none;
}

.custom-input:focus {
  border-color: #d6c76d;
  background-color: #ffffff;
  box-shadow: 0 0 0 0.2rem rgba(214, 199, 109, 0.2);
}

.form-select.custom-input {
  cursor: pointer;
}

.btn-action {
  min-width: 110px;
  height: 44px;
  border-radius: 12px;
  font-weight: 600;
  border: 1px solid gainsboro;
}

.modal-header {
  padding-top: 1rem;
  padding-bottom: 0.5rem;
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

.map-box {
  width: 100%;
  height: 400px;
  background: #f8f9fa;
  border: 1px solid #e6dfbf;
  overflow: hidden;
}

.map-note {
  font-size: 13px;
  line-height: 1.5;
}

.loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.35);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 99999;
}

.loading-box {
  background: #fff;
  padding: 22px 26px;
  border-radius: 14px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  min-width: 240px;
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
  text-align: center;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 991px) {
  .map-box {
    height: 300px;
  }

  .form-panel,
  .map-panel {
    padding: 1rem !important;
  }
}
</style>
