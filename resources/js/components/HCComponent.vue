<template>
  <div class="container-fluid mt-4">
    <div class="card shadow-sm rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <h4>{{ t('hc.title') }}</h4>
          <button class="btn btn-success" @click="openCreateModal">
            <i class="fa-solid fa-circle-plus"></i> {{ t('hc.createButton') }}
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-bg">
              <tr>
                <th width="80">{{ t('hc.tableId') }}</th>
                <th>{{ t('hc.province') }}</th>
                <th>{{ t('hc.district') }}</th>
                <th>{{ t('hc.commune') }}</th>
                <th>{{ t('hc.od') }}</th>
                <th>{{ t('hc.hcNameEn') }}</th>
                <th>{{ t('hc.hcNameKh') }}</th>
                <th>{{ t('hc.hcCode') }}</th>
                <th>{{ t('hc.directorName') }}</th>
                <th>{{ t('hc.phone') }}</th>
                <th>{{ t('hc.latitude') }}</th>
                <th>{{ t('hc.longitude') }}</th>
                <th>{{ t('hc.image') }}</th>
                <th width="180" class="text-end">{{ t('hc.action') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(h, index) in healthCenters" :key="h.id || index">
                <td>{{ index + 1 }}</td>
                <td>{{ h.province?.province_name_en || "-" }}</td>
                <td>{{ h.district?.district_name_en || "-" }}</td>
                <td>{{ h.commune?.commune_name_en || "-" }}</td>
                <td>{{ h.od?.od_name_en || "-" }}</td>
                <td>{{ h.hc_name_en }}</td>
                <td>{{ h.hc_name_kh }}</td>
                <td>{{ h.hc_code }}</td>
                <td>{{ h.name_director || "-" }}</td>
                <td>{{ h.phone || "-" }}</td>
                <td>{{ h.latitude || "-" }}</td>
                <td>{{ h.longitude || "-" }}</td>
                <td>
                  <img v-if="h.image" :src="`/storage/${h.image}`" :alt="t('hc.imageAlt')" class="table-image" />
                  <span v-else>-</span>
                </td>
                <td class="text-end">
                  <button class="btn btn-sm btn-primary me-2" @click="openEditModal(h)">
                    <i class="fa-solid fa-pen-to-square"></i> {{ t('hc.edit') }}
                  </button>
                  <button class="btn btn-sm btn-danger" @click="askDelete(h)">
                    <i class="fa-solid fa-trash"></i> {{ t('hc.delete') }}
                  </button>
                </td>
              </tr>

              <tr v-if="healthCenters.length === 0">
                <td colspan="14" class="text-center text-muted py-4">{{ t('hc.noData') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create / Edit Modal -->
    <div class="modal fade" id="hcModal" tabindex="-1" aria-hidden="true" ref="hcModalEl">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow modal-gradient">
          <div class="modal-header border-0 flex-column text-center position-relative pb-2">
            <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>

            <div v-if="!editMode" class="w-100">
              <img src="@/assets/logo_moh.png" alt="Logo MOH" class="logo mt-3" />
              <h5 class="modal-title title-border pb-2">{{ t('hc.createTitle') }}</h5>
              <p class="text-muted small mb-0">{{ t('hc.createSubtitle') }}</p>
            </div>

            <div v-else class="w-100">
              <h5 class="modal-title title-border pb-2 mt-3">{{ t('hc.editTitle') }}</h5>
              <p class="text-muted small mb-0">{{ t('hc.editSubtitle') }}</p>
            </div>
          </div>

          <div class="modal-body pt-3 px-4 px-md-5">
            <form @submit.prevent="editMode ? updateHC() : createHC()">
              <div class="form-section">
                <div class="section-title">
                  <i class="fa-solid fa-hospital me-2"></i>
                  {{ t('hc.sectionInfo') }}
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.selectProvince') }}</label>
                    <select class="form-select custom-input" v-model="form.province_code" @change="onProvinceChange"
                      required>
                      <option value="">{{ t('hc.selectProvinceOption') }}</option>
                      <option v-for="p in provinces" :key="p.id" :value="p.province_code">
                        {{ p.province_name_en }} - {{ p.province_name_kh }}
                      </option>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.selectDistrict') }}</label>
                    <select class="form-select custom-input" v-model="form.district_code" @change="onDistrictChange"
                      required>
                      <option value="">{{ t('hc.selectDistrictOption') }}</option>
                      <option v-for="d in filteredDistricts" :key="d.id" :value="d.district_code">
                        {{ d.district_name_en }} - {{ d.district_name_kh }}
                      </option>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.selectCommune') }}</label>
                    <select class="form-select custom-input" v-model="form.commune_code" @change="onCommuneChange"
                      required>
                      <option value="">{{ t('hc.selectCommuneOption') }}</option>
                      <option v-for="c in filteredCommunes" :key="c.id" :value="c.commune_code">
                        {{ c.commune_name_en }} - {{ c.commune_name_kh }}
                      </option>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.selectOD') }}</label>
                    <select class="form-select custom-input" v-model="form.od_code" @change="generateHCCode" required>
                      <option value="">{{ t('hc.selectODOption') }}</option>
                      <option v-for="o in filteredODs" :key="o.id" :value="o.od_code">
                        {{ o.od_name_en }} - {{ o.od_name_kh }}
                      </option>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.hcNameEnLabel') }}</label>
                    <input type="text" class="form-control custom-input" v-model.trim="form.hc_name_en" required />
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.hcNameKhLabel') }}</label>
                    <input type="text" class="form-control custom-input" v-model.trim="form.hc_name_kh" required />
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.hcCodeLabel') }}</label>
                    <input type="text" class="form-control custom-input bg-body-secondary" v-model.trim="form.hc_code"
                      :placeholder="t('hc.hcCodePlaceholder')" readonly />
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.directorNameLabel') }}</label>
                    <input type="text" class="form-control custom-input" v-model.trim="form.name_director"
                      :placeholder="t('hc.directorNamePlaceholder')" />
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.phoneLabel') }}</label>
                    <input type="text" class="form-control custom-input" v-model.trim="form.phone"
                      :placeholder="t('hc.phonePlaceholder')" />
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">{{ t('hc.imageLabel') }}</label>
                    <input type="file" class="form-control custom-input-file" accept="image/*"
                      @change="handleImageUpload" />
                  </div>

                  <div class="col-md-3">
                    <label class="form-label">{{ t('hc.latitudeLabel') }}</label>
                    <input type="number" step="any" class="form-control custom-input" v-model="form.latitude"
                      @input="updateMarkerFromInputs" />
                  </div>

                  <div class="col-md-3">
                    <label class="form-label">{{ t('hc.longitudeLabel') }}</label>
                    <input type="number" step="any" class="form-control custom-input" v-model="form.longitude"
                      @input="updateMarkerFromInputs" />
                  </div>

                  <div class="col-md-6" v-if="imagePreview">
                    <label class="form-label">{{ t('hc.previewLabel') }}</label>
                    <div v-if="imagePreview"></div>
                    <div class="preview-wrapper">
                      <img :src="imagePreview" :alt="t('hc.previewAlt')" class="img-preview" />

                      <button type="button" class="remove-preview-btn" @click="removePreviewImage"
                        :title="t('hc.removeImage')">
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <button type="button" class="btn btn-outline-secondary rounded-4" @click="getCurrentLocation">
                      <i class="fa-solid fa-location-crosshairs me-1"></i>
                      {{ t('hc.getCurrentLocation') }}
                    </button>
                  </div>
                </div>
              </div>

              <div class="form-section mt-4">
                <div class="section-title">
                  <i class="fa-solid fa-location-dot me-2"></i>
                  {{ t('hc.sectionMap') }}
                </div>

                <div id="hc-map" class="map-box rounded-4 shadow-sm"></div>
                <small class="text-muted d-block mt-2">
                  {{ t('hc.mapNote') }}
                </small>
              </div>

              <div class="modal-footer border-0 px-0 mt-4">
                <button type="button" class="btn btn-light btn-action" data-bs-dismiss="modal">
                  {{ t('hc.cancel') }}
                </button>

                <button v-if="!editMode" type="submit" class="btn btn-success btn-action">
                  <i class="fa-solid fa-check me-1"></i> {{ t('hc.save') }}
                </button>

                <button v-else type="submit" class="btn btn-primary btn-action">
                  <i class="fa-solid fa-check me-1"></i> {{ t('hc.update') }}
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
                {{ t('hc.ok') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" ref="confirmDeleteModalEl">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-body p-4 text-center">
            <div class="mb-3 text-danger" style="font-size: 40px">
              <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <h5 class="mb-2">{{ t('hc.confirmDeleteTitle') }}</h5>
            <p class="text-muted mb-0">{{ t('hc.confirmDeleteMessage') }}</p>

            <div class="d-flex justify-content-center gap-2 mt-4">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" :disabled="deleting">
                {{ t('hc.cancel') }}
              </button>

              <button type="button" class="btn btn-danger" @click="confirmDelete" :disabled="deleting">
                <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                {{ t('hc.confirmDeleteButton') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="pageLoading || modalLoading || submitLoading" class="loading-overlay" role="dialog" aria-modal="true">
      <div class="loading-box">
        <div class="spinner"></div>
        <div class="loading-text">{{ t('hc.loading') }}</div>
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
      healthCenters: [],
      provinces: [],
      districts: [],
      communes: [],
      ods: [],

      filteredDistricts: [],
      filteredCommunes: [],
      filteredODs: [],

      imagePreview: "",

      editMode: false,
      editId: null,
      deleting: false,
      deleteTarget: null,

      hcModal: null,
      alertModal: null,
      confirmDeleteModal: null,

      map: null,
      marker: null,
      defaultCenter: { lat: 11.5564, lng: 104.9282 },

      pageLoading: false,
      modalLoading: false,
      submitLoading: false,

      form: {
        province_code: "",
        district_code: "",
        commune_code: "",
        od_code: "",
        hc_name_en: "",
        hc_name_kh: "",
        hc_code: "",
        name_director: "",
        phone: "",
        image: null,
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

  async mounted() {
    this.pageLoading = true
    this.hcModal = new Modal(this.$refs.hcModalEl, {
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
    await this.loadDistricts()
    await this.loadCommunes()
    await this.loadODs()
    await this.loadHealthCenters()

    try {
      await this.loadGoogleMapsScript()
    } catch (err) {
      this.showAlert("error", this.t("hc.mapError"), this.t("hc.mapErrorMessage"))
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
    removePreviewImage() {
      if (this.imagePreview && this.imagePreview.startsWith("blob:")) {
        URL.revokeObjectURL(this.imagePreview)
      }

      this.form.image = null
      this.imagePreview = ""

      const fileInput = this.$el.querySelector('input[type="file"]')
      if (fileInput) {
        fileInput.value = ""
      }
    },

    handleImageUpload(event) {
      const file = event.target.files[0]

      if (this.imagePreview && this.imagePreview.startsWith("blob:")) {
        URL.revokeObjectURL(this.imagePreview)
      }

      this.form.image = file || null

      if (file) {
        this.imagePreview = URL.createObjectURL(file)
      } else {
        this.imagePreview = ""
      }
    },

    getCurrentLocation() {
      if (!navigator.geolocation) {
        this.showAlert("error", this.t("hc.geolocationError"), this.t("hc.geolocationNotSupported"))
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

          let message = this.t("hc.unableToGetLocation")
          if (error.code === 1) message = this.t("hc.permissionDenied")
          if (error.code === 2) message = this.t("hc.locationUnavailable")
          if (error.code === 3) message = this.t("hc.locationTimeout")

          this.showAlert("error", this.t("hc.locationError"), message)
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
      if (this.imagePreview && this.imagePreview.startsWith("blob:")) {
        URL.revokeObjectURL(this.imagePreview)
      }

      this.form = {
        province_code: "",
        district_code: "",
        commune_code: "",
        od_code: "",
        hc_name_en: "",
        hc_name_kh: "",
        hc_code: "",
        name_director: "",
        phone: "",
        image: null,
        latitude: "",
        longitude: "",
      }

      this.imagePreview = ""
      this.filteredDistricts = []
      this.filteredCommunes = []
      this.filteredODs = []

      const fileInput = this.$el?.querySelector('input[type="file"]')
      if (fileInput) {
        fileInput.value = ""
      }
    },

    async loadProvinces() {
      try {
        const res = await axios.get("/provinces")
        this.provinces = res.data.data || []
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert("error", this.t("hc.loadFailed"), this.t("hc.loadProvinceFailedMessage"))
      }
    },

    async loadDistricts() {
      try {
        const res = await axios.get("/districts")
        this.districts = res.data.data || []
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert("error", this.t("hc.loadFailed"), this.t("hc.loadDistrictFailedMessage"))
      }
    },

    async loadCommunes() {
      try {
        const res = await axios.get("/communes")
        this.communes = res.data.data || []
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert("error", this.t("hc.loadFailed"), this.t("hc.loadCommuneFailedMessage"))
      }
    },

    async loadODs() {
      try {
        const res = await axios.get("/ods")
        this.ods = res.data.data || []
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert("error", this.t("hc.loadFailed"), this.t("hc.loadODFailedMessage"))
      }
    },

    async loadHealthCenters() {
      try {
        const res = await axios.get("/health-centers")
        this.healthCenters = res.data.data || []
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert("error", this.t("hc.loadFailed"), this.t("hc.loadHCFailedMessage"))
      }
    },

    async generateHCCode() {
      this.form.hc_code = ""

      if (!this.form.od_code) return

      try {
        const res = await axios.get(`/health-centers/next-code/${this.form.od_code}`)
        this.form.hc_code = res.data.hc_code || ""
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("hc.codeError"),
          err.response?.data?.message || this.t("hc.codeErrorMessage")
        )
      }
    },

    onProvinceChange() {
      this.form.district_code = ""
      this.form.commune_code = ""
      this.form.od_code = ""
      this.form.hc_code = ""

      this.filteredDistricts = this.districts.filter(
        (d) => String(d.province_code) === String(this.form.province_code)
      )

      this.filteredCommunes = []
      this.filteredODs = []
    },

    onDistrictChange() {
      this.form.commune_code = ""
      this.form.od_code = ""
      this.form.hc_code = ""

      this.filteredCommunes = this.communes.filter(
        (c) => String(c.district_code) === String(this.form.district_code)
      )

      this.filteredODs = []
    },

    onCommuneChange() {
      this.form.od_code = ""
      this.form.hc_code = ""

      this.filteredODs = this.ods.filter(
        (o) => String(o.commune_code) === String(this.form.commune_code)
      )
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
      const mapEl = document.getElementById("hc-map")
      if (!mapEl || !(window.google && window.google.maps)) return

      const hasLat = lat !== null && lat !== "" && !isNaN(Number(lat))
      const hasLng = lng !== null && lng !== "" && !isNaN(Number(lng))

      const center = {
        lat: hasLat ? Number(lat) : this.defaultCenter.lat,
        lng: hasLng ? Number(lng) : this.defaultCenter.lng,
      }

      this.map = new window.google.maps.Map(mapEl, {
        center,
        zoom: hasLat && hasLng ? 12 : 7,
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
      this.hcModal.show()

      setTimeout(() => {
        this.initMap()
        this.modalLoading = false
      }, 500)
    },

    openEditModal(hc) {
      this.modalLoading = true
      this.editMode = true
      this.editId = hc.id

      this.form.province_code = hc.province_code || ""
      this.filteredDistricts = this.districts.filter(
        (d) => String(d.province_code) === String(this.form.province_code)
      )

      this.form.district_code = hc.district_code || ""
      this.filteredCommunes = this.communes.filter(
        (c) => String(c.district_code) === String(this.form.district_code)
      )

      this.form.commune_code = hc.commune_code || ""
      this.filteredODs = this.ods.filter(
        (o) => String(o.commune_code) === String(this.form.commune_code)
      )

      this.form.od_code = hc.od_code || ""
      this.form.hc_name_en = hc.hc_name_en || ""
      this.form.hc_name_kh = hc.hc_name_kh || ""
      this.form.hc_code = hc.hc_code || ""
      this.form.name_director = hc.name_director || ""
      this.form.phone = hc.phone || ""
      this.form.image = null
      this.imagePreview = hc.image ? `/storage/${hc.image}` : ""
      this.form.latitude = hc.latitude || ""
      this.form.longitude = hc.longitude || ""

      this.hcModal.show()

      setTimeout(() => {
        this.initMap(this.form.latitude, this.form.longitude)
        this.modalLoading = false
      }, 500)
    },

    async createHC() {
      if (
        !this.form.province_code ||
        !this.form.district_code ||
        !this.form.commune_code ||
        !this.form.od_code ||
        !this.form.hc_name_en ||
        !this.form.hc_name_kh ||
        !this.form.hc_code
      ) {
        this.showAlert("warning", this.t("hc.validationError"), this.t("hc.fillRequired"))
        return
      }

      this.submitLoading = true

      try {
        const formData = new FormData()
        formData.append("province_code", this.form.province_code)
        formData.append("district_code", this.form.district_code)
        formData.append("commune_code", this.form.commune_code)
        formData.append("od_code", this.form.od_code)
        formData.append("hc_name_en", this.form.hc_name_en)
        formData.append("hc_name_kh", this.form.hc_name_kh)
        formData.append("hc_code", this.form.hc_code)
        formData.append("name_director", this.form.name_director || "")
        formData.append("phone", this.form.phone || "")
        formData.append("latitude", this.form.latitude || "")
        formData.append("longitude", this.form.longitude || "")

        if (this.form.image) {
          formData.append("image", this.form.image)
        }

        await axios.post("/health-centers", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })

        await this.loadHealthCenters()
        this.hcModal.hide()
        this.resetForm()
        this.showAlert("success", this.t("hc.success"), this.t("hc.createdSuccess"))
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg =
            Object.values(errors)?.[0]?.[0] ||
            err.response?.data?.message ||
            this.t("hc.validationMessage")
          this.showAlert("warning", this.t("hc.validationError"), firstMsg)
          return
        }

        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("hc.createFailed"),
          err.response?.data?.message || err.message || this.t("hc.createFailedMessage")
        )
      } finally {
        this.submitLoading = false
      }
    },

    async updateHC() {
      if (!this.editId) return

      if (
        !this.form.province_code ||
        !this.form.district_code ||
        !this.form.commune_code ||
        !this.form.od_code ||
        !this.form.hc_name_en ||
        !this.form.hc_name_kh ||
        !this.form.hc_code
      ) {
        this.showAlert("warning", this.t("hc.validationError"), this.t("hc.fillRequired"))
        return
      }

      this.submitLoading = true

      try {
        const formData = new FormData()
        formData.append("_method", "PUT")
        formData.append("province_code", this.form.province_code)
        formData.append("district_code", this.form.district_code)
        formData.append("commune_code", this.form.commune_code)
        formData.append("od_code", this.form.od_code)
        formData.append("hc_name_en", this.form.hc_name_en)
        formData.append("hc_name_kh", this.form.hc_name_kh)
        formData.append("hc_code", this.form.hc_code)
        formData.append("name_director", this.form.name_director || "")
        formData.append("phone", this.form.phone || "")
        formData.append("latitude", this.form.latitude || "")
        formData.append("longitude", this.form.longitude || "")

        if (this.form.image) {
          formData.append("image", this.form.image)
        }

        await axios.post(`/health-centers/${this.editId}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })

        await this.loadHealthCenters()
        this.hcModal.hide()
        this.resetForm()
        this.showAlert("success", this.t("hc.success"), this.t("hc.updatedSuccess"))
      } catch (err) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors
          const firstMsg =
            Object.values(errors)?.[0]?.[0] ||
            err.response?.data?.message ||
            this.t("hc.validationMessage")
          this.showAlert("warning", this.t("hc.validationError"), firstMsg)
          return
        }

        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("hc.updateFailed"),
          err.response?.data?.message || err.message || this.t("hc.updateFailedMessage")
        )
      } finally {
        this.submitLoading = false
      }
    },

    askDelete(hc) {
      this.deleteTarget = hc
      this.confirmDeleteModal?.show()
    },

    async confirmDelete() {
      if (!this.deleteTarget?.id) return

      this.deleting = true
      try {
        await axios.delete(`/health-centers/${this.deleteTarget.id}`)
        this.healthCenters = this.healthCenters.filter((h) => h.id !== this.deleteTarget.id)

        this.confirmDeleteModal?.hide()
        this.showAlert("success", this.t("hc.deletedTitle"), this.t("hc.deletedSuccess"))
      } catch (err) {
        console.log(err.response?.data || err.message)
        this.showAlert(
          "error",
          this.t("hc.deleteFailed"),
          err.response?.data?.message || err.message || this.t("hc.deleteFailedMessage")
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
  background: linear-gradient(180deg, #fffdf1 0%, #fffef9 100%);
}

.container-fluid {
  font-family: "Khmer OS Battambang", sans-serif;
}

.logo {
  width: 64px;
  margin: 0 auto 14px auto;
  display: block;
}

.title-border {
  display: inline-block;
  border-bottom: 2px solid #d6c76d;
  font-weight: 700;
}

.form-section {
  background: #ffffffcc;
  border: 1px solid #efe7b8;
  border-radius: 18px;
  padding: 20px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
}

.section-title {
  font-size: 15px;
  font-weight: 700;
  color: #6c5d13;
  margin-bottom: 16px;
  padding-bottom: 8px;
  border-bottom: 1px dashed #d8cb84;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 6px;
}

.custom-input {
  border-radius: 12px;
  min-height: 44px;
  border: 1px solid #dcdcdc;
  box-shadow: none;
  transition: all 0.2s ease;
}

.custom-input-file {
  border-radius: 12px;
  min-height: 38px;
  border: 1px solid #dcdcdc;
  box-shadow: none;
  transition: all 0.2s ease;
}

.custom-input:focus {
  border-color: #d6c76d;
  box-shadow: 0 0 0 0.18rem rgba(214, 199, 109, 0.2);
}

.preview-wrapper {
  position: relative;
  display: inline-block;
  width: 240px;
}

.img-preview {
  object-fit: fill;
  border-radius: 14px;
  border: 1px solid #ddd;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
  background: #fff;
}

.table-image {
  width: 80px;
  height: 80px;
  border-radius: 20px;
  object-fit: cover;
}

.remove-preview-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 34px;
  height: 34px;
  border: none;
  border-radius: 50%;
  background: rgba(220, 53, 69, 0.95);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.18);
  transition: all 0.2s ease;
}

.remove-preview-btn:hover {
  background: #bb2d3b;
  transform: scale(1.06);
}

.remove-preview-btn:active {
  transform: scale(0.96);
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
  height: 360px;
  background: #f8f9fa;
  border: 1px solid #dee2e6;
}

.btn-action {
  min-width: 110px;
  border-radius: 12px;
  font-weight: 600;
  padding: 10px 18px;
  border: 1px solid gainsboro;
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
</style>